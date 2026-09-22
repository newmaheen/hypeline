<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Mail\OrderInvoiceMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (count($cart) === 0) {
            return redirect()
                ->route('cart.index')
                ->withErrors([
                    'cart' => 'Your cart is empty.',
                ]);
        }

        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view(
            'checkout.index',
            compact('cart', 'total')
        );
    }

    public function store(Request $request)
    {
        // ১. ভ্যালিডেশন রুলস এবং বাংলাদেশি ফোন নম্বর রেজেক্স
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => [
                'required',
                'string',
                'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/',
            ],
            'customer_email'   => 'nullable|email|max:255',
            'shipping_address' => 'required|string',
            'payment_method'   => 'required|in:cod,bkash,nagad',
            'transaction_id'   => 'nullable|required_if:payment_method,bkash,nagad|string|max:255',
            'payment_phone'    => [
                'nullable',
                'required_if:payment_method,bkash,nagad',
                'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/',
            ],
        ], [
            'customer_phone.required' => 'গ্রাহকের মোবাইল নম্বর দেওয়া আবশ্যক।',
            'customer_phone.regex'    => 'সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন (যেমন: 017XXXXXXXX)।',
            'payment_phone.required_if' => 'বিকাশ বা নগদ নম্বর প্রদান করা আবশ্যক।',
            'payment_phone.regex'       => 'সঠিক ১১ ডিজিটের পেমেন্ট নম্বর দিন (যেমন: 017XXXXXXXX)।',
        ]);

        $cart = session()->get('cart', []);

        if (count($cart) === 0) {
            return redirect()
                ->route('cart.index')
                ->withErrors([
                    'cart' => 'Your cart is empty.',
                ]);
        }

        // ২. মোবাইল নম্বর থেকে +88 বা হাইফেন মুছে ক্লিন ১১ ডিজিট তৈরি
        $customerPhone = preg_replace('/[^0-9]/', '', $validated['customer_phone']);
        if (strlen($customerPhone) > 11) {
            $customerPhone = substr($customerPhone, -11);
        }

        $paymentPhone = null;
        if (!empty($validated['payment_phone'])) {
            $paymentPhone = preg_replace('/[^0-9]/', '', $validated['payment_phone']);
            if (strlen($paymentPhone) > 11) {
                $paymentPhone = substr($paymentPhone, -11);
            }
        }

        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        do {
            $orderCode = (string) random_int(100000, 999999);
        } while (
            Order::where('order_code', $orderCode)->exists()
        );

        $order = DB::transaction(function () use (
            $validated,
            $cart,
            $total,
            $orderCode,
            $customerPhone,
            $paymentPhone
        ) {
            $order = Order::create([
                'order_code'       => $orderCode,
                'invoice_token'    => Str::random(64),
                'user_id'          => Auth::id(),
                'customer_name'    => $validated['customer_name'],
                'customer_phone'   => $customerPhone, // ক্লিন ১১ ডিজিট
                'customer_email'   => $validated['customer_email'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'total_amount'     => $total,
                'status'           => 'pending',
                'payment_method'   => $validated['payment_method'],
                'payment_status'   => $validated['payment_method'] === 'cod' ? 'unpaid' : 'pending',
                'transaction_id'   => $validated['transaction_id'] ?? null,
                'payment_phone'    => $paymentPhone, // ক্লিন ১১ ডিজিট
            ]);

            foreach ($cart as $item) {
                $variant = ProductVariant::where('id', $item['variant_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($variant->stock < $item['quantity']) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'quantity' => 'Some products are no longer available in the requested quantity.',
                    ]);
                }

                $order->items()->create([
                    'product_id'   => $item['product_id'],
                    'variant_id'   => $item['variant_id'],
                    'product_name' => $item['name'],
                    'size'         => $item['size'] ?? null,
                    'color'        => $item['color'] ?? null,
                    'price'        => $item['price'],
                    'quantity'     => $item['quantity'],
                    'subtotal'     => $item['price'] * $item['quantity'],
                ]);

                // ইনভেন্টরি থেকে স্টক কমানো
                $variant->decrement('stock', $item['quantity']);

                // স্টক মুভমেন্ট হিস্ট্রিতে রেকর্ড সংরক্ষণ
                StockMovement::create([
                    'product_variant_id' => $variant->id,
                    'type'               => 'out',
                    'quantity'           => $item['quantity'],
                    'remarks'            => 'Online Order #' . $order->order_code,
                ]);
            }

            return $order;
        });

        session()->forget('cart');

        if ($order->customer_email) {
            try {
                Mail::to($order->customer_email)
                    ->send(new OrderInvoiceMail($order));
            } catch (\Exception $e) {
                // ইমেইল ড্রাইভার সমস্যা হলেও অর্ডার যাতে বন্ধ না হয়
            }
        }

        return redirect()->route(
            'order.confirmation',
            $order->order_code
        );
    }

   public function invoice($orderCode, $invoiceToken)
    {
        $order = Order::where('order_code', $orderCode)
            ->where('invoice_token', $invoiceToken)
            ->with('items')
            ->firstOrFail();

        // যদি অর্ডারটি কোনো রেজিস্টার্ড ইউজারের হয়ে থাকে,
        // তবে বর্তমান লগইন থাকা ইউজার ছাড়া অন্য কেউ এটি দেখতে পারবে না
        if ($order->user_id !== null) {
            if (!Auth::check() || Auth::id() !== $order->user_id) {
                abort(403, 'Unauthorized access to this invoice.');
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'invoices.invoice',
            compact('order')
        );

        return $pdf->download(
            'invoice-' . $order->order_code . '.pdf'
        );
    }

    public function confirmation($orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->with('items')
            ->firstOrFail();

        return view(
            'orders.confirmation',
            compact('order')
        );
    }
}