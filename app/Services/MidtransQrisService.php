<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MidtransQrisService
{
    public function createPayment(Order $order): Payment
    {
        if ($order->payment) {
            return $order->payment;
        }

        $serverKey = config('midtrans.server_key');

        if (! $serverKey) {
            throw new RuntimeException('Midtrans server key belum diatur.');
        }

        $order->loadMissing('items');

        $midtransOrderId = $order->order_code;

        $payload = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'phone' => $order->customer_phone,
            ],
            'item_details' => $order->items
                ->map(function ($item) {
                    return [
                        'id' => (string) $item->id,
                        'price' => (int) $item->menu_price,
                        'quantity' => (int) $item->quantity,
                        'name' => $item->menu_name,
                    ];
                })
                ->values()
                ->toArray(),
        ];

        $response = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->asJson()
            ->post(config('midtrans.base_url') . '/v2/charge', $payload);

        if (! $response->successful()) {
            throw new RuntimeException('Gagal membuat transaksi QRIS Midtrans.');
        }

        $responseData = $response->json();

        return Payment::create([
            'order_id' => $order->id,
            'midtrans_order_id' => $midtransOrderId,
            'transaction_id' => $responseData['transaction_id'] ?? null,
            'payment_type' => $responseData['payment_type'] ?? 'qris',
            'gross_amount' => (int) $order->total_amount,
            'currency' => $responseData['currency'] ?? 'IDR',
            'transaction_status' => $responseData['transaction_status'] ?? 'pending',
            'fraud_status' => $responseData['fraud_status'] ?? null,
            'qr_url' => $this->extractQrUrl($responseData),
            'raw_response' => $responseData,
        ]);
    }

    public function checkStatus(Payment $payment): Payment
    {
        $serverKey = config('midtrans.server_key');

        if (! $serverKey) {
            throw new RuntimeException('Midtrans server key belum diatur.');
        }

        $response = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->get(config('midtrans.base_url') . '/v2/' . $payment->midtrans_order_id . '/status');

        if (! $response->successful()) {
            throw new RuntimeException('Gagal mengecek status transaksi Midtrans.');
        }

        return $this->applyStatus($payment, $response->json());
    }

    public function handleNotification(array $payload): Payment
    {
        if (! $this->isValidSignature($payload)) {
            abort(403, 'Invalid Midtrans signature.');
        }

        $payment = Payment::where('midtrans_order_id', $payload['order_id'] ?? null)
            ->firstOrFail();

        return $this->applyStatus($payment, $payload);
    }

    private function applyStatus(Payment $payment, array $payload): Payment
    {
        $transactionStatus = $payload['transaction_status'] ?? 'pending';
        $fraudStatus = $payload['fraud_status'] ?? null;

        $payment->update([
            'transaction_id' => $payload['transaction_id'] ?? $payment->transaction_id,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'raw_notification' => $payload,
            'paid_at' => $this->isPaid($transactionStatus, $fraudStatus)
                ? now()
                : $payment->paid_at,
        ]);

        $order = $payment->order;

        if ($this->isPaid($transactionStatus, $fraudStatus)) {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'pending',
            ]);
        }

        if (in_array($transactionStatus, ['expire', 'cancel', 'deny'], true)) {
            $order->update([
                'payment_status' => 'cancelled',
                'status' => 'cancelled',
            ]);
        }

        if ($transactionStatus === 'failure') {
            $order->update([
                'payment_status' => 'unpaid',
                'status' => 'awaiting_payment',
            ]);
        }

        return $payment->fresh(['order']);
    }

    private function isPaid(string $transactionStatus, ?string $fraudStatus): bool
    {
        if ($transactionStatus === 'settlement') {
            return true;
        }

        if ($transactionStatus === 'capture' && in_array($fraudStatus, [null, 'accept'], true)) {
            return true;
        }

        return false;
    }

    private function isValidSignature(array $payload): bool
    {
        $serverKey = config('midtrans.server_key');

        if (! $serverKey) {
            return false;
        }

        if (
            empty($payload['order_id']) ||
            empty($payload['status_code']) ||
            empty($payload['gross_amount']) ||
            empty($payload['signature_key'])
        ) {
            return false;
        }

        $signature = hash(
            'sha512',
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            $serverKey
        );

        return hash_equals($signature, $payload['signature_key']);
    }

    private function extractQrUrl(array $responseData): ?string
    {
        foreach ($responseData['actions'] ?? [] as $action) {
            if (($action['name'] ?? null) === 'generate-qr-code') {
                return $action['url'] ?? null;
            }
        }

        return null;
    }
}