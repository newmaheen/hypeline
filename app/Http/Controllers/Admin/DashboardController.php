<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;

class DashboardController extends Controller
{
   public function index()
{
    $todayOrders = Order::whereDate('created_at', today())
        ->count();

    $todaySales = Order::whereDate('created_at', today())
    ->where('status', '!=', 'cancelled')
    ->sum('total_amount');

    $totalOrders = Order::count();

    $totalSales = Order::where('status', '!=', 'cancelled')
    ->sum('total_amount');

    $pendingOrders = Order::where('status', 'pending')->count();

    $confirmedOrders = Order::where('status', 'confirmed')->count();

    $processingOrders = Order::where('status', 'processing')->count();

    $shippedOrders = Order::where('status', 'shipped')->count();

    $deliveredOrders = Order::where('status', 'delivered')->count();
    $totalProducts = Product::count();

    $lowStockProducts = ProductVariant::where('stock', '>', 0)
        ->where('stock', '<=', 5)
        ->count();

    $outOfStockProducts = ProductVariant::where('stock', 0)
        ->count();

    return view('admin.dashboard', compact(
        'todayOrders',
        'todaySales',
        'pendingOrders',
        'confirmedOrders',
        'processingOrders',
        'shippedOrders',
        'deliveredOrders',
        'totalProducts',
        'lowStockProducts',
        'outOfStockProducts',
        'totalOrders',
        'totalSales',
    ));
}
}