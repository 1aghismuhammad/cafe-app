<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\CafeProfile;
use App\Models\Order;
use Illuminate\Support\Facades\Gate;

class ReceiptController extends Controller
{
    public function show(Order $order)
    {
        Gate::authorize('view', $order);

        $order->load([
            'items',
            'restaurantTable.outlet',
            'outlet',
        ]);

        $cafeProfile = CafeProfile::first();

        return view('kasir.orders.receipt', compact('order', 'cafeProfile'));
    }
}