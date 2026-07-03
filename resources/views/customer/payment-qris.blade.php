<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100">
    <main class="mx-auto min-h-screen max-w-md bg-gray-100 px-4 py-6">
        <div class="mb-5">
            <p class="text-sm text-gray-500">
                {{ $qrCode->restaurantTable->outlet->outlet_name ?? 'Outlet' }}
            </p>
            <h1 class="text-2xl font-bold text-gray-900">
                Pembayaran QRIS
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                Meja {{ $qrCode->restaurantTable->table_number ?? '-' }}
            </p>
        </div>

        @if (session('error'))
            <div class="mb-4 rounded-xl bg-red-100 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if (session('status'))
            <div class="mb-4 rounded-xl bg-yellow-100 px-4 py-3 text-sm text-yellow-800">
                {{ session('status') }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 rounded-xl bg-green-100 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="mb-4 border-b border-gray-100 pb-4">
                <p class="text-xs uppercase tracking-wide text-gray-500">
                    Kode Order
                </p>
                <p class="mt-1 text-lg font-semibold text-gray-900">
                    {{ $order->order_code }}
                </p>
            </div>

            <div class="mb-4 border-b border-gray-100 pb-4">
                <p class="text-xs uppercase tracking-wide text-gray-500">
                    Total Pembayaran
                </p>
                <p class="mt-1 text-2xl font-bold text-gray-900">
                    Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                </p>
            </div>

            <div class="mb-5">
                <p class="text-xs uppercase tracking-wide text-gray-500">
                    Status Pembayaran
                </p>

                @if ($order->payment_status === 'paid')
                    <span class="mt-2 inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                        Lunas
                    </span>
                @else
                    <span class="mt-2 inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                        Menunggu Pembayaran
                    </span>
                @endif
            </div>

            @if ($order->payment && $order->payment->qr_url)
                <div class="mb-5 rounded-2xl border border-gray-200 bg-gray-50 p-4 text-center">
                    <img
                        src="{{ $order->payment->qr_url }}"
                        alt="QRIS Pembayaran"
                        class="mx-auto h-64 w-64 rounded-xl bg-white object-contain p-3"
                    >

                    <p class="mt-3 text-xs text-gray-500">
                        Scan QRIS menggunakan aplikasi e-wallet atau mobile banking yang mendukung QRIS.
                    </p>
                </div>
            @else
                <div class="mb-5 rounded-xl bg-red-100 px-4 py-3 text-sm text-red-700">
                    QRIS belum tersedia. Silakan hubungi kasir.
                </div>
            @endif

            @if ($order->payment_status === 'paid')
                <a
                    href="{{ route('customer.checkout.success', ['token' => $token, 'order' => $order]) }}"
                    class="block w-full rounded-xl bg-gray-900 px-4 py-3 text-center text-sm font-semibold text-white"
                >
                    Lihat Detail Pesanan
                </a>
            @else
                <form method="POST" action="{{ route('customer.payment.qris.check', ['token' => $token, 'order' => $order]) }}">
                    @csrf

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-gray-900 px-4 py-3 text-sm font-semibold text-white"
                    >
                        Cek Status Pembayaran
                    </button>
                </form>
            @endif
        </section>

        <section class="mt-4 rounded-2xl bg-white p-5 shadow-sm">
            <h2 class="mb-3 text-sm font-semibold text-gray-900">
                Ringkasan Pesanan
            </h2>

            <div class="space-y-3">
                @foreach ($order->items as $item)
                    <div class="flex justify-between gap-3 text-sm">
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $item->menu_name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $item->quantity }} x Rp{{ number_format($item->menu_price, 0, ',', '.') }}
                            </p>
                        </div>

                        <p class="font-semibold text-gray-900">
                            Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </section>
    </main>
</body>
</html>