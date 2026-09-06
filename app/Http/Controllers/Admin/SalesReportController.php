<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->period ?? 'today';

        $startDate = null;
        $endDate = null;

        switch ($period) {

            case 'yesterday':
                $startDate = Carbon::yesterday()->startOfDay();
                $endDate = Carbon::yesterday()->endOfDay();
                break;

            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;

            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;

            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;

            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;

            case 'custom':
                $request->validate([
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                ]);

                $startDate = Carbon::parse($request->start_date)
                    ->startOfDay();

                $endDate = Carbon::parse($request->end_date)
                    ->endOfDay();
                break;

            default:
                $startDate = Carbon::today()->startOfDay();
                $endDate = Carbon::today()->endOfDay();
        }

        $orders = Order::whereBetween(
                'created_at',
                [$startDate, $endDate]
            )
            ->where('status', '!=', 'cancelled')
            ->latest()
            ->get();

        $totalOrders = $orders->count();

        $totalSales = $orders->sum('total_amount');

                    $cancelledOrders = Order::whereBetween(
                    'created_at',
                    [$startDate, $endDate]
                )
                ->where('status', 'cancelled')
                ->count();

        return view('admin.sales-report.index', compact(
            'orders',
            'totalOrders',
            'totalSales',
            'period',
            'startDate',
            'cancelledOrders',
            'endDate'
        ));
    }
}