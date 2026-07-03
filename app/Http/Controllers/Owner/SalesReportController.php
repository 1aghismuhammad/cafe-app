<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class SalesReportController extends Controller
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

        $totalItemsSold = OrderItem::query()
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->where('payment_status', 'paid')
                    ->when($startDate, function ($query) use ($startDate) {
                        $query->whereDate('created_at', '>=', $startDate);
                    })
                    ->when($endDate, function ($query) use ($endDate) {
                        $query->whereDate('created_at', '<=', $endDate);
                    });
            })
            ->sum('quantity');

        $orders = (clone $baseOrdersQuery)
            ->with(['items', 'restaurantTable', 'outlet'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('owner.reports.sales', compact(
            'startDate',
            'endDate',
            'orders',
            'totalOrders',
            'paidOrders',
            'unpaidOrders',
            'cancelledOrders',
            'totalRevenue',
            'totalItemsSold'
        ));
    }
}