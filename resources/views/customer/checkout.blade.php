<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Meja {{ $qrCode->restaurantTable->table_number }} - Cafe A</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen">
        <div class="mx-auto max-w-md bg-white min-h-screen shadow-sm">
            <div class="bg-gray-900 px-5 py-6 text-white">
                <h1 class="text-2xl font-bold">
                    Checkout
                </h1>

                <p class="mt-1 text-sm text-gray-300">
                    Meja {{ $qrCode->restaurantTable->table_number }} - {{ $qrCode->restaurantTable->outlet->outlet_name ?? '-' }}
                </p>
            </div>

            <div class="px-5 py-5">
                @if (session('error'))
                    <div class="mb-4 rounded-xl bg-red-100 px-4 py-3 text-sm text-red-800">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="rounded-2xl border bg-white p-4 shadow-sm">
                    <h2 class="font-bold">
                        Ringkasan Pesanan
                    </h2>

                    <div class="mt-4 space-y-3">
                        @foreach ($cartSummary['items'] as $item)
                            <div class="flex justify-between gap-3 border-b pb-3 last:border-b-0 last:pb-0">
                                <div>
                                    <p class="font-semibold">
                                        {{ $item['menu_name'] }}
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        {{ $item['qty'] }} x Rp{{ number_format($item['price'], 0, ',', '.') }}
                                    </p>

                                    @if (! empty($item['note']))
                                        <p class="mt-1 text-xs text-gray-500">
                                            Catatan: {{ $item['note'] }}
                                        </p>
                                    @endif
                                </div>

                                <p class="shrink-0 font-bold">
                                    Rp{{ number_format($item['subtotal'], 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t pt-4">
                        <span class="text-sm text-gray-600">
                            Total {{ $cartSummary['total_qty'] }} item
                        </span>

                        <span class="text-lg font-bold">
                            Rp{{ number_format($cartSummary['total_price'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('customer.checkout.store', $token) }}" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">
                            Nama Pelanggan
                        </label>

                        <input
                            type="text"
                            name="customer_name"
                            value="{{ old('customer_name') }}"
                            placeholder="Contoh: Aaron"
                            required
                            class="mt-1 block w-full rounded-xl border-gray-300 text-sm shadow-sm"
                        >

                        @error('customer_name')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">
                            Nomor HP
                        </label>

                        <input
                            type="text"
                            name="customer_phone"
                            value="{{ old('customer_phone') }}"
                            placeholder="Opsional"
                            class="mt-1 block w-full rounded-xl border-gray-300 text-sm shadow-sm"
                        >

                        @error('customer_phone')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">
                            Catatan Pesanan
                        </label>

                        <textarea
                            name="customer_note"
                            rows="3"
                            placeholder="Opsional, contoh: tolong antar saat semua menu siap"
                            class="mt-1 block w-full rounded-xl border-gray-300 text-sm shadow-sm"
                        >{{ old('customer_note') }}</textarea>

                        @error('customer_note')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <a href="{{ route('customer.cart.show', $token) }}"
                            class="rounded-xl border px-4 py-3 text-center text-sm font-semibold">
                            Kembali
                        </a>

                        <button type="submit"
                            class="rounded-xl bg-gray-900 px-4 py-3 text-sm font-semibold text-white">
                            Kirim Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>