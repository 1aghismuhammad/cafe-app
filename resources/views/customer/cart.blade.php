<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Meja {{ $qrCode->restaurantTable->table_number }} - Cafe A</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen">
        <div class="mx-auto max-w-md bg-white min-h-screen shadow-sm">
            <div class="bg-gray-900 px-5 py-6 text-white">
                <h1 class="text-2xl font-bold">
                    Keranjang
                </h1>

                <p class="mt-1 text-sm text-gray-300">
                    Meja {{ $qrCode->restaurantTable->table_number }} - {{ $qrCode->restaurantTable->outlet->outlet_name ?? '-' }}
                </p>
            </div>

            <div class="px-5 py-5 pb-32">
                @if (session('success'))
                    <div class="mb-4 rounded-xl bg-green-100 px-4 py-3 text-sm text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if (count($cart) > 0)
                    <div class="space-y-4">
                        @foreach ($cart as $item)
                            <div class="rounded-2xl border bg-white p-4 shadow-sm">
                                <div class="flex gap-3">
                                    <div class="h-20 w-20 shrink-0 overflow-hidden rounded-xl bg-gray-100">
                                        @if ($item['image_path'])
                                            <img src="{{ asset('storage/' . $item['image_path']) }}"
                                                alt="{{ $item['menu_name'] }}"
                                                class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-center text-xs text-gray-400">
                                                No Image
                                            </div>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <h2 class="font-semibold leading-tight">
                                            {{ $item['menu_name'] }}
                                        </h2>

                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $item['category_name'] }}
                                        </p>

                                        <p class="mt-2 font-bold">
                                            Rp{{ number_format($item['price'], 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <form method="POST" action="{{ route('customer.cart.decrease', [$token, $item['menu_id']]) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                class="flex h-9 w-9 items-center justify-center rounded-lg border text-lg font-bold">
                                                -
                                            </button>
                                        </form>

                                        <div class="flex h-9 min-w-10 items-center justify-center rounded-lg bg-gray-100 px-3 font-semibold">
                                            {{ $item['qty'] }}
                                        </div>

                                        <form method="POST" action="{{ route('customer.cart.increase', [$token, $item['menu_id']]) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                class="flex h-9 w-9 items-center justify-center rounded-lg border text-lg font-bold">
                                                +
                                            </button>
                                        </form>
                                    </div>

                                    <p class="font-bold">
                                        Rp{{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </p>
                                </div>

                                <form method="POST" action="{{ route('customer.cart.note', [$token, $item['menu_id']]) }}" class="mt-4">
                                    @csrf
                                    @method('PATCH')

                                    <label class="block text-xs font-medium text-gray-600">
                                        Catatan
                                    </label>

                                    <div class="mt-1 flex gap-2">
                                        <input type="text" name="note" value="{{ $item['note'] }}"
                                            placeholder="Contoh: less sugar, tanpa es"
                                            class="block w-full rounded-lg border-gray-300 text-sm shadow-sm">

                                        <button type="submit"
                                            class="rounded-lg border px-3 text-sm font-semibold">
                                            Simpan
                                        </button>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('customer.cart.remove', [$token, $item['menu_id']]) }}" class="mt-3">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="text-sm font-semibold text-red-600"
                                        onclick="return confirm('Hapus menu ini dari keranjang?')">
                                        Hapus item
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border bg-white p-6 text-center">
                        <p class="font-semibold text-gray-800">
                            Keranjang masih kosong.
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Silakan pilih menu terlebih dahulu.
                        </p>

                        <a href="{{ route('customer.order.table', $token) }}"
                            class="mt-5 inline-block rounded-lg bg-gray-900 px-5 py-3 text-sm font-semibold text-white">
                            Pilih Menu
                        </a>
                    </div>
                @endif
            </div>

            @if (count($cart) > 0)
                <div class="fixed bottom-0 left-0 right-0 z-20">
                    <div class="mx-auto max-w-md bg-white border-t p-4 shadow-lg">
                        <div class="mb-3 flex items-center justify-between">
                            <span class="text-sm text-gray-600">
                                Total {{ $cartSummary['total_qty'] }} item
                            </span>

                            <span class="text-lg font-bold">
                                Rp{{ number_format($cartSummary['total_price'], 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('customer.order.table', $token) }}"
                                class="rounded-xl border px-4 py-3 text-center text-sm font-semibold">
                                Tambah Menu
                            </a>

                            <button type="button"
                                onclick="alert('Checkout akan dibuat pada checkpoint berikutnya.')"
                                class="rounded-xl bg-gray-900 px-4 py-3 text-sm font-semibold text-white">
                                Lanjut Checkout
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>