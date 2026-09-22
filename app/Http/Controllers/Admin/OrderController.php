<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StockMovement;
use App\Mail\OrderStatusMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show($orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->orWhere('id', $orderCode)
            ->firstOrFail();

        $order->load(['items.product', 'items.variant']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $orderCode)
    {
        // order_code বা id যেকোনোটি দিয়ে অর্ডার খুঁজে নেওয়া
        $order = Order::where('order_code', $orderCode)
            ->orWhere('id', $orderCode)
            ->firstOrFail();

        // ১. ভ্যালিডেশন
        $isManualPayment = in_array(strtolower($order->payment_method), ['bkash', 'nagad', 'rocket']);

        $request->validate([
            'status' => 'required|string|in:pending,confirmed,processing,shipped,delivered,cancelled',
            
            // শুধু বিকাশ/নগদ হলে payment_status বাধ্যতামূলক, COD হলে নয়
            'payment_status' => $isManualPayment 
                ? 'required|string|in:pending,paid,failed' 
                : 'nullable|string|in:pending,paid,failed',
        ]);

        // ২. ডাটা প্রস্তুত করা
        $updateData = [
            'status' => $request->status,
        ];

        // ৩. পেমেন্ট স্ট্যাটাস হ্যান্ডলিং
        if ($isManualPayment) {
            $updateData['payment_status'] = $request->payment_status;
        } else {
            // COD (ক্যাশ অন ডেলিভারি) এর ক্ষেত্রে লজিক
            if ($request->status === 'delivered') {
                $updateData['payment_status'] = 'paid';
            } else {
                $updateData['payment_status'] = $request->payment_status ?? $order->payment_status ?? 'pending';
            }
        }

        // ৪. যদি অর্ডার ক্যানসেল করা হয়, তবে স্টক ফেরত দেওয়া
        if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
            DB::transaction(function () use ($order, $updateData) {
                foreach ($order->items as $item) {
                    if ($item->variant) {
                        $item->variant->increment('stock', $item->quantity);

                        StockMovement::create([
                            'product_variant_id' => $item->variant_id,
                            'type'               => 'in',
                            'quantity'           => $item->quantity,
                            'remarks'            => 'Restocked: Order #' . $order->order_code . ' cancelled via status update',
                        ]);
                    }
                }
                $order->update($updateData);
            });
        } else {
            $order->update($updateData);
        }

        // ৫. কাস্টমারকে ইমেইল নোটিফিকেশন পাঠানো
        if ($order->customer_email) {
            try {
                Mail::to($order->customer_email)->send(new OrderStatusMail($order));
            } catch (\Exception $e) {
                // ইমেইল ফেইল করলে ক্র্যাশ ঠেকানো
            }
        }

        return back()->with('success', 'Order status updated successfully!');
    }
    /**
     * পেমেন্ট সরাসরি ডিক্লাইন/রিজেক্ট করার মেথড
     */
    public function declinePayment(Request $request, $orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->orWhere('id', $orderCode)
            ->firstOrFail();

        $request->validate([
            'decline_reason' => 'nullable|string|max:255',
        ]);

        if ($order->status === 'delivered') {
            return back()->withErrors(['error' => 'ইতিমধ্যে ডেলিভারি হওয়া অর্ডারের পেমেন্ট ডিক্লাইন করা যাবে না!']);
        }

        DB::transaction(function () use ($request, $order) {
            if ($order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->variant) {
                        $item->variant->increment('stock', $item->quantity);

                        StockMovement::create([
                            'product_variant_id' => $item->variant_id,
                            'type' => 'in',
                            'quantity' => $item->quantity,
                            'remarks' => 'Restocked: Payment Declined for Order #' . $order->order_code,
                        ]);
                    }
                }
            }

            $order->update([
                'payment_status' => 'failed',
                'status' => 'cancelled',
            ]);
        });

        if ($order->customer_email) {
            try {
                Mail::to($order->customer_email)->send(new OrderStatusMail($order));
            } catch (\Exception $e) {}
        }

        return back()->with('success', 'পেমেন্ট সফলভাবে Decline করা হয়েছে এবং প্রোডাক্টগুলো স্বয়ংক্রিয়ভাবে স্টকে ফিরিয়ে দেওয়া হয়েছে।');
    }


    /**
     * পেমেন্ট ম্যানুয়ালি অনুমোদন (Approve) করার মেথড
     */
    public function approvePayment(Request $request, $orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->orWhere('id', $orderCode)
            ->firstOrFail();

        if ($order->payment_status === 'paid') {
            return back()->with('info', 'পেমেন্টটি আগেই অনুমোদিত (Paid) রয়েছে।');
        }

        $order->update([
            'payment_status' => 'paid',
            // পেমেন্ট পেইড হলে পেন্ডিং অর্ডার প্রসেসিংয়ে নিয়ে যাওয়া
            'status' => $order->status === 'pending' ? 'processing' : $order->status,
        ]);

        if ($order->customer_email) {
            try {
                Mail::to($order->customer_email)->send(new OrderStatusMail($order));
            } catch (\Exception $e) {}
        }

        return back()->with('success', 'পেমেন্ট সফলভাবে Approve করা হয়েছে!');
    }
}