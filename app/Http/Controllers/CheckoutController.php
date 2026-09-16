<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
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

            $total +=
                $item['price'] *
                $item['quantity'];

        }


        return view(
            'checkout.index',
            compact('cart', 'total')
        );
    }



            public function store(Request $request)
    {
                $validated = $request->validate([
                    'customer_name' => 'required|string|max:255',
                    'customer_phone' => 'required|string|max:30',
                    'customer_email' => 'nullable|email|max:255',
                    'shipping_address' => 'required|string',

                    'payment_method' => 'required|in:cod,bkash,nagad',

                    'transaction_id' => 'nullable|required_if:payment_method,bkash,nagad|string|max:255',
                    'payment_phone' => 'nullable|required_if:payment_method,bkash,nagad|string|max:30',
                ]);

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
                    $total +=
                        $item['price'] *
                        $item['quantity'];
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
                    $orderCode
                ) {

                    $order = Order::create([
                        'order_code' => $orderCode,
                        'invoice_token' => Str::random(64),
                        'user_id' => Auth::id(),
                        'customer_name' => $validated['customer_name'],
                        'customer_phone' => $validated['customer_phone'],
                        'customer_email' => $validated['customer_email'] ?? null,
                        'shipping_address' => $validated['shipping_address'],
                        'total_amount' => $total,
                        'status' => 'pending',

                        'payment_method' => $validated['payment_method'],

                        'payment_status' =>
                            $validated['payment_method'] === 'cod'
                                ? 'unpaid'
                                : 'pending',

                        'transaction_id' => $validated['transaction_id'] ?? null,
                        'payment_phone' => $validated['payment_phone'] ?? null,
                    ]);

                    foreach ($cart as $item) {

                        $variant = \App\Models\ProductVariant::where(
                            'id',
                            $item['variant_id']
                        )
                        ->lockForUpdate()
                        ->firstOrFail();

                        if ($variant->stock < $item['quantity']) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'quantity' =>
                                    'Some products are no longer available in the requested quantity.',
                            ]);
                        }

                        $order->items()->create([
                            'product_id' => $item['product_id'],
                            'variant_id' => $item['variant_id'],
                            'product_name' => $item['name'],
                            'size' => $item['size'] ?? null,
                            'color' => $item['color'] ?? null,
                            'price' => $item['price'],
                            'quantity' => $item['quantity'],
                            'subtotal' =>
                                $item['price'] * $item['quantity'],
                        ]);

                        $variant->decrement(
                            'stock',
                            $item['quantity']
                        );
                    }

                    return $order;
                });

                session()->forget('cart');

                if ($order->customer_email) {
                    Mail::to($order->customer_email)
                        ->send(new OrderInvoiceMail($order));
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
