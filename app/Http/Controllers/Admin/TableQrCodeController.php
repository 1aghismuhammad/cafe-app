<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use App\Models\TableQrCode;
use Illuminate\Support\Str;

class TableQrCodeController extends Controller
{
    public function index()
    {
        $tables = RestaurantTable::with(['outlet', 'tableQrCode'])
            ->latest()
            ->get();

        return view('admin.table-qr-codes.index', compact('tables'));
    }

    public function generate(RestaurantTable $restaurantTable)
    {
        if ($restaurantTable->tableQrCode) {
            return redirect()
                ->route('admin.table-qr-codes.index')
                ->with('success', 'QR meja sudah tersedia.');
        }

        $token = $this->generateUniqueToken();
        $url = url('/order/table/' . $token);

        TableQrCode::create([
            'restaurant_table_id' => $restaurantTable->id,
            'qr_token' => $token,
            'qr_url' => $url,
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.table-qr-codes.index')
            ->with('success', 'QR meja berhasil dibuat.');
    }

    public function show(TableQrCode $tableQrCode)
    {
        $tableQrCode->load(['restaurantTable.outlet']);

        return view('admin.table-qr-codes.show', compact('tableQrCode'));
    }

    public function regenerate(TableQrCode $tableQrCode)
    {
        $token = $this->generateUniqueToken();
        $url = url('/order/table/' . $token);

        $tableQrCode->update([
            'qr_token' => $token,
            'qr_url' => $url,
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.table-qr-codes.show', $tableQrCode)
            ->with('success', 'QR meja berhasil dibuat ulang.');
    }

    public function toggle(TableQrCode $tableQrCode)
    {
        $tableQrCode->update([
            'is_active' => ! $tableQrCode->is_active,
        ]);

        return redirect()
            ->route('admin.table-qr-codes.index')
            ->with('success', 'Status QR meja berhasil diperbarui.');
    }

    private function generateUniqueToken(): string
    {
        do {
            $token = Str::random(32);
        } while (TableQrCode::where('qr_token', $token)->exists());

        return $token;
    }
}