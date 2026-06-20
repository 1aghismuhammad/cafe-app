<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    private array $statuses = [
        'pending',
        'confirmed',
        'preparing',
        'ready',
        'served',
        'cancelled',
    ];

    public function index(Request $request)
    {
        $status = $request->query('status');

        $orders = Order::with(['restaurantTable.outlet', 'outlet'])
            ->when(in_array($status, $this->statuses, true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->get();

        $statusOptions = $this->statusOptions();
        $paymentStatusOptions = $this->paymentStatusOptions();

        return view('kasir.orders.index', compact(
            'orders',
            'status',
            'statusOptions',
            'paymentStatusOptions'
        ));
    }

    public function show(Order $order)
    {
        $order->load(['items', 'restaurantTable.outlet', 'outlet']);

        $statusOptions = $this->statusOptions();
        $paymentStatusOptions = $this->paymentStatusOptions();

        return view('kasir.orders.show', compact(
            'order',
            'statusOptions',
            'paymentStatusOptions'
        ));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in($this->statuses)],
        ]);

        if ($order->status === 'served' && $validated['status'] === 'cancelled') {
            return redirect()
                ->back()
                ->with('error', 'Order yang sudah served tidak dapat dibatalkan.');
        }

        $order->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('kasir.orders.show', $order)
            ->with('success', 'Status order berhasil diperbarui.');
    }

    private function statusOptions(): array
    {
        return [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'preparing' => 'Preparing',
            'ready' => 'Ready',
            'served' => 'Served',
            'cancelled' => 'Cancelled',
        ];
    }

    private function paymentStatusOptions(): array
    {
        return [
            'unpaid' => 'Unpaid',
            'paid' => 'Paid',
            'cancelled' => 'Cancelled',
        ];
    }
}