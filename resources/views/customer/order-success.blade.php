<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - Cafe A</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen">
        <div class="mx-auto max-w-md bg-white min-h-screen shadow-sm">
            <div class="bg-gray-900 px-5 py-6 text-white">
                <h1 class="text-2xl font-bold">
                    Pesanan Berhasil
                </h1>

                <p class="mt-1 text-sm text-gray-300">
                    Meja {{ $qrCode->restaurantTable->table_number }} - {{ $qrCode->restaurantTable->outlet->outlet_name ?? '-' }}
                </p>
            </div>

            <div class="px-5 py-5">
                <div class="rounded-2xl border bg-white p-6 text-center shadow-sm">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-100 text-2xl">
                        ✓
                    </div>

                    <h2 class="mt-4 text-xl font-bold">
                        Pesanan kamu sudah dikirim
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Silakan tunggu konfirmasi dari kasir.
                    </p>
                </div>

                <div class="mt-5 rounded-2xl border bg-white p-4 shadow-sm">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between gap-3">
                            <span class="text-gray-500">Nomor Order</span>
                            <span class="font-semibold text-right">{{ $order->order_code }}</span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-gray-500">Nama</span>
                            <span class="font-semibold text-right">{{ $order->customer_name }}</span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-gray-500">Meja</span>
                            <span class="font-semibold text-right">
                                {{ $qrCode->restaurantTable->table_number }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-gray-500">Total</span>
                            <span class="font-semibold text-right">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-gray-500">Status Pesanan</span>
                            <span class="font-semibold text-right capitalize">
                                {{ $order->status }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-gray-500">Status Bayar</span>
                            <span class="font-semibold text-right capitalize">
                                {{ $order->payment_status }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-5 rounded-2xl border bg-white p-4 shadow-sm">
                    <h3 class="font-bold">
                        Detail Item
                    </h3>

                    <div class="mt-3 space-y-3">
                        @foreach ($order->items as $item)
                            <div class="flex justify-between gap-3 border-b pb-3 last:border-b-0 last:pb-0">
                                <div>
                                    <p class="font-semibold">
                                        {{ $item->menu_name }}
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        {{ $item->quantity }} x Rp{{ number_format($item->menu_price, 0, ',', '.') }}
                                    </p>

                                    @if ($item->item_note)
                                        <p class="mt-1 text-xs text-gray-500">
                                            Catatan: {{ $item->item_note }}
                                        </p>
                                    @endif
                                </div>

                                <p class="shrink-0 font-bold">
                                    Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('customer.order.table', $token) }}"
                    class="mt-5 block rounded-xl bg-gray-900 px-4 py-3 text-center text-sm font-semibold text-white">
                    Kembali ke Menu
                </a>
            </div>
        </div>
    </div>
</body>
</html>