<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->get();

        return view('admin.orders.index', compact('orders'));
    }

            public function show(string $orderCode)
        {
            $order = Order::where('order_code', $orderCode)
                ->with('items')
                ->firstOrFail();

            return view(
                'admin.orders.show',
                compact('order')
            );
        }

        public function approvePayment(string $orderCode)
        {
            $order = Order::where('order_code', $orderCode)
                ->firstOrFail();

            $order->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
            ]);

            return redirect()
                ->route('admin.orders.show', $order->order_code)
                ->with('success', 'Payment approved successfully.');
            }

        
           public function updateStatus(Request $request, string $orderCode)
                {
                    $validated = $request->validate([
                        'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
                    ]);

                    $order = Order::where('order_code', $orderCode)
                        ->firstOrFail();

                    $newStatus = $validated['status'];
                    $allowedTransitions = 
                    [
                        'pending' => ['confirmed', 'cancelled'],
                        'confirmed' => ['processing', 'cancelled'],
                        'processing' => ['shipped', 'cancelled'],
                        'shipped' => ['delivered', 'cancelled'],
                        'delivered' => [],
                        'cancelled' => [],
                    ];

if (!in_array($newStatus, $allowedTransitions[$order->status])) {
    return back()->withErrors([
        'status' => 'This status change is not allowed.',
    ]);
}

                    if ($order->status === 'cancelled') {
                        return back()->withErrors([
                            'status' => 'A cancelled order cannot be changed.',
                        ]);
                    }

                    if ($newStatus === 'cancelled') {

                        DB::transaction(function () use ($order) {

                            $order->load('items');

                            foreach ($order->items as $item) {

                                $variant = ProductVariant::where(
                                    'id',
                                    $item->variant_id
                                )
                                ->lockForUpdate()
                                ->firstOrFail();

                                $variant->increment(
                                    'stock',
                                    $item->quantity
                                );
                            }

                            $order->update([
                                'status' => 'cancelled',
                            ]);
                        });

                    } else {

                        $order->update([
                            'status' => $newStatus,
                        ]);
                    }

                    if ($order->customer_email) {
                        Mail::to($order->customer_email)
                            ->send(new \App\Mail\OrderStatusMail($order));
                    }

                    return redirect()
                        ->route('admin.orders.show', $order->order_code)
                        ->with('success', 'Order status updated successfully.');
                }


}