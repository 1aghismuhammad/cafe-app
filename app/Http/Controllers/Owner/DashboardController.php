<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $baseOrdersQuery = Order::query()
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            });

        $totalOrders = (clone $baseOrdersQuery)->count();

        $paidOrders = (clone $baseOrdersQuery)
            ->where('payment_status', 'paid')
            ->count();

        $unpaidOrders = (clone $baseOrdersQuery)
            ->where('payment_status', 'unpaid')
            ->count();

        $cancelledOrders = (clone $baseOrdersQuery)
            ->where(function ($query) {
                $query->where('status', 'cancelled')
                    ->orWhere('payment_status', 'cancelled');
            })
            ->count();

        $totalRevenue = (clone $baseOrdersQuery)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $todayRevenue = Order::query()
            ->whereDate('created_at', now()->toDateString())
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $latestOrders = (clone $baseOrdersQuery)
            ->with(['restaurantTable', 'outlet'])
            ->latest()
            ->limit(8)
            ->get();

        $topMenus = OrderItem::query()
            ->select('menu_name')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(subtotal) as total_sales')
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->where('payment_status', 'paid')
                    ->when($startDate, function ($query) use ($startDate) {
                        $query->whereDate('created_at', '>=', $startDate);
                    })
                    ->when($endDate, function ($query) use ($endDate) {
                        $query->whereDate('created_at', '<=', $endDate);
                    });
            })
            ->groupBy('menu_name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return view('owner.dashboard', compact(
            'startDate',
            'endDate',
            'totalOrders',
            'paidOrders',
            'unpaidOrders',
            'cancelledOrders',
            'totalRevenue',
            'todayRevenue',
            'latestOrders',
            'topMenus'
        ));
    }
}