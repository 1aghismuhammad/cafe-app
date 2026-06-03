<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\TableQrCode;

class OrderTableController extends Controller
{
    public function show(string $token)
    {
        $qrCode = TableQrCode::with(['restaurantTable.outlet'])
            ->where('qr_token', $token)
            ->where('is_active', true)
            ->first();

        if (! $qrCode) {
            abort(404, 'QR meja tidak aktif atau tidak valid.');
        }

        return view('customer.order-table-preview', compact('qrCode'));
    }
}