<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TableQrCode;
use App\Services\MidtransQrisService;

class PaymentController extends Controller
{
    public function showQris(string $token, Order $order)
    {
        $qrCode = $this->getActiveQrCode($token);

        if ((int) $order->table_qr_code_id !== (int) $qrCode->id) {
            abort(404);
        }

        $order->load(['items', 'payment', 'restaurantTable.outlet', 'outlet']);

        if (! $order->payment) {
            abort(404, 'Data pembayaran tidak ditemukan.');
        }

        return view('customer.payment-qris', compact('qrCode', 'order', 'token'));
    }

    public function checkStatus(string $token, Order $order, MidtransQrisService $midtransQrisService)
    {
        $qrCode = $this->getActiveQrCode($token);

        if ((int) $order->table_qr_code_id !== (int) $qrCode->id) {
            abort(404);
        }

        $order->load('payment');

        if (! $order->payment) {
            return redirect()
                ->route('customer.payment.qris.show', [
                    'token' => $token,
                    'order' => $order->id,
                ])
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $midtransQrisService->checkStatus($order->payment);

        $order->refresh();

        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('customer.checkout.success', [
                    'token' => $token,
                    'order' => $order->id,
                ])
                ->with('success', 'Pembayaran berhasil dikonfirmasi.');
        }

        return redirect()
            ->route('customer.payment.qris.show', [
                'token' => $token,
                'order' => $order->id,
            ])
            ->with('status', 'Pembayaran belum terkonfirmasi. Silakan cek kembali beberapa saat lagi.');
    }

    private function getActiveQrCode(string $token): TableQrCode
    {
        $qrCode = TableQrCode::with(['restaurantTable.outlet'])
            ->where('qr_token', $token)
            ->where('is_active', true)
            ->first();

        if (! $qrCode) {
            abort(404, 'QR meja tidak aktif atau tidak valid.');
        }

        return $qrCode;
    }
}