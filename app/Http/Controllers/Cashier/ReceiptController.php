<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\CafeProfile;
use App\Models\Order;

class ReceiptController extends Controller
{
    public function show(Order $order)
    {
        $order->load([
            'items',
            'restaurantTable.outlet',
            'outlet',
        ]);

        $cafeProfile = CafeProfile::first();

        return view('kasir.orders.receipt', compact('order', 'cafeProfile'));
    }
}