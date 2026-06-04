<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Meja {{ $qrCode->restaurantTable->table_number }} - {{ $profile->brand_name ?? 'Cafe A' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen">
        <div class="mx-auto max-w-md bg-white min-h-screen shadow-sm">
            <div class="bg-gray-900 px-5 py-6 text-white">
                <div class="text-center">
                    <h1 class="text-2xl font-bold">
                        {{ $profile->brand_name ?? 'Cafe A' }}
                    </h1>

                    <p class="mt-1 text-sm text-gray-300">
                        {{ $profile->slogan ?? 'Scan, Order, Enjoy' }}
                    </p>
                </div>

                <div class="mt-5 rounded-2xl bg-white/10 p-4">
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-gray-300">Outlet</p>
                            <p class="font-semibold">
                                {{ $qrCode->restaurantTable->outlet->outlet_name ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-300">Meja</p>
                            <p class="font-semibold">
                                {{ $qrCode->restaurantTable->table_number }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-300">Kode Meja</p>
                            <p class="font-semibold">
                                {{ $qrCode->restaurantTable->table_code }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-300">Status</p>
                            <p class="font-semibold">
                                Siap Order
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-5 py-5 pb-28">
                @if (session('success'))
                    <div class="mb-4 rounded-xl bg-green-100 px-4 py-3 text-sm text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 rounded-xl bg-red-100 px-4 py-3 text-sm text-red-800">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-4">
                    <h2 class="text-lg font-bold">
                        Pilih Menu
                    </h2>
                    <p class="text-sm text-gray-500">
                        Silakan pilih makanan dan minuman yang tersedia.
                    </p>
                </div>

                @if ($categories->count() > 0)
                    <div class="sticky top-0 z-10 -mx-5 bg-white px-5 py-3 border-b">
                        <div class="flex gap-2 overflow-x-auto pb-1">
                            @foreach ($categories as $category)
                                <a href="#category-{{ $category->id }}"
                                    class="shrink-0 rounded-full border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-900 hover:text-white">
                                    {{ $category->category_name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-5 space-y-8">
                        @foreach ($categories as $category)
                            <section id="category-{{ $category->id }}">
                                <div class="mb-3">
                                    <h3 class="text-base font-bold">
                                        {{ $category->category_name }}
                                    </h3>

                                    @if ($category->description)
                                        <p class="text-sm text-gray-500">
                                            {{ $category->description }}
                                        </p>
                                    @endif
                                </div>

                                <div class="space-y-3">
                                    @foreach ($category->menus as $menu)
                                        <div class="rounded-2xl border bg-white p-3 shadow-sm">
                                            <div class="flex gap-3">
                                                <div class="h-24 w-24 shrink-0 overflow-hidden rounded-xl bg-gray-100">
                                                    @if ($menu->image_path)
                                                        <img src="{{ asset('storage/' . $menu->image_path) }}"
                                                            alt="{{ $menu->menu_name }}"
                                                            class="h-full w-full object-cover">
                                                    @else
                                                        <div class="flex h-full w-full items-center justify-center text-center text-xs text-gray-400">
                                                            No Image
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="min-w-0 flex-1">
                                                    <h4 class="font-semibold leading-tight">
                                                        {{ $menu->menu_name }}
                                                    </h4>

                                                    <p class="mt-1 text-xs text-gray-500">
                                                        {{ $menu->preparation_time }} menit
                                                    </p>

                                                    @if ($menu->description)
                                                        <p class="mt-2 text-sm text-gray-500">
                                                            {{ $menu->description }}
                                                        </p>
                                                    @endif

                                                    <div class="mt-3 flex items-center justify-between">
                                                        <p class="font-bold text-gray-900">
                                                            Rp{{ number_format($menu->price, 0, ',', '.') }}
                                                        </p>

                                                        <form method="POST" action="{{ route('customer.cart.add', [$token, $menu]) }}">
                                                            @csrf

                                                            <button type="submit"
                                                                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                                                                Tambah
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border bg-white p-6 text-center">
                        <p class="font-semibold text-gray-800">
                            Menu belum tersedia.
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Silakan hubungi kasir untuk informasi menu.
                        </p>
                    </div>
                @endif
            </div>

            @if ($cartSummary['total_qty'] > 0)
                <div class="fixed bottom-0 left-0 right-0 z-20">
                    <div class="mx-auto max-w-md bg-white border-t p-4 shadow-lg">
                        <a href="{{ route('customer.cart.show', $token) }}"
                            class="flex items-center justify-between rounded-2xl bg-gray-900 px-5 py-4 text-white">
                            <div>
                                <p class="text-sm font-semibold">
                                    Lihat Keranjang
                                </p>
                                <p class="text-xs text-gray-300">
                                    {{ $cartSummary['total_qty'] }} item
                                </p>
                            </div>

                            <div class="font-bold">
                                Rp{{ number_format($cartSummary['total_price'], 0, ',', '.') }}
                            </div>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>