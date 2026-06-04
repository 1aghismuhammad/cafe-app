<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CafeProfile;
use App\Models\Category;
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

        $profile = CafeProfile::first();

        $categories = Category::where('status', 'active')
            ->whereHas('menus', function ($query) {
                $query->where('is_active', true)
                    ->where('stock_status', 'available');
            })
            ->with(['menus' => function ($query) {
                $query->where('is_active', true)
                    ->where('stock_status', 'available')
                    ->orderBy('menu_name');
            }])
            ->orderBy('display_order')
            ->orderBy('category_name')
            ->get();

        return view('customer.order-table-preview', compact(
            'qrCode',
            'profile',
            'categories'
        ));
    }
}