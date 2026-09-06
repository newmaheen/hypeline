<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusMail;

class MyOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('orders.my-orders', compact('orders'));
    }

        public function show(string $orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->where('user_id', Auth::id())
            ->with('items.product')
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

        public function cancel(string $orderCode)
        {
            $order = Order::where('order_code', $orderCode)
                ->where('user_id', Auth::id())
                ->firstOrFail();

           if ($order->status !== 'pending') {
                return back()->withErrors([
                    'order' => 'This order cannot be cancelled.',
                ]);
            }

            if ($order->created_at->addHour()->isPast()) {
                return back()->withErrors([
                    'order' => 'Cancellation time has expired. You can only cancel an order within 1 hour.',
                ]);
            }

            DB::transaction(function () use ($order) {

                $order->load('items');

                foreach ($order->items as $item) {

                    $variant = ProductVariant::where('id', $item->variant_id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $variant->increment('stock', $item->quantity);
                }

                $order->update([
                    'status' => 'cancelled',
                ]);
            });

            if ($order->customer_email) {
                Mail::to($order->customer_email)
                    ->send(new OrderStatusMail($order));
            }

            return back()->with(
                'success',
                'Order cancelled successfully and stock has been restored.'
            );
        }


}