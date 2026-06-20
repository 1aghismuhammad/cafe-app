<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    private array $paymentStatuses = [
        'unpaid',
        'paid',
        'cancelled',
    ];

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => ['required', Rule::in($this->paymentStatuses)],
        ]);

        $order->update([
            'payment_status' => $validated['payment_status'],
        ]);

        return redirect()
            ->route('kasir.orders.show', $order)
            ->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}