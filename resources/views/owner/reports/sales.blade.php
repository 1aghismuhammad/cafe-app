<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Laporan Detail Penjualan
            </h2>

            <a href="{{ route('owner.dashboard') }}"
                class="inline-flex rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200">
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Filter Laporan</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Laporan ini menampilkan transaksi berdasarkan periode tanggal. Omzet hanya dihitung dari order dengan status pembayaran paid.
                        </p>
                    </div>

                    <form method="GET" action="{{ route('owner.reports.sales') }}"
                        class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">
                                Tanggal Mulai
                            </label>
                            <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">
                                Tanggal Selesai
                            </label>
                            <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                                Filter
                            </button>

                            <a href="{{ route('owner.reports.sales') }}"
                                class="rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6">
                <div class="bg-white p-5 shadow-sm sm:rounded-lg xl:col-span-2">
                    <div class="text-sm font-medium text-gray-500">Omzet Paid</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">
                        Rp{{ number_format($totalRevenue, 0, ',', '.') }}
                    </div>
                </div>

                <div class="bg-white p-5 shadow-sm sm:rounded-lg">
                    <div class="text-sm font-medium text-gray-500">Total Order</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalOrders }}
                    </div>
                </div>

                <div class="bg-white p-5 shadow-sm sm:rounded-lg">
                    <div class="text-sm font-medium text-gray-500">Order Paid</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $paidOrders }}
                    </div>
                </div>

                <div class="bg-white p-5 shadow-sm sm:rounded-lg">
                    <div class="text-sm font-medium text-gray-500">Belum Dibayar</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $unpaidOrders }}
                    </div>
                </div>

                <div class="bg-white p-5 shadow-sm sm:rounded-lg">
                    <div class="text-sm font-medium text-gray-500">Dibatalkan</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $cancelledOrders }}
                    </div>
                </div>

                <div class="bg-white p-5 shadow-sm sm:rounded-lg">
                    <div class="text-sm font-medium text-gray-500">Item Terjual</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalItemsSold }}
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Daftar Transaksi</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Menampilkan daftar order beserta detail item pesanan.
                        </p>
                    </div>

                    <div class="text-sm text-gray-500">
                        Total data: {{ $orders->total() }}
                    </div>
                </div>

                <div class="mt-4 space-y-4">
                    @forelse ($orders as $order)
                        <div class="rounded-lg border border-gray-200">
                            <div class="grid grid-cols-1 gap-4 border-b border-gray-200 bg-gray-50 p-4 lg:grid-cols-8">
                                <div class="lg:col-span-2">
                                    <div class="text-xs font-medium text-gray-500">Kode Order</div>
                                    <div class="mt-1 font-semibold text-gray-900">
                                        {{ $order->order_code }}
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-xs font-medium text-gray-500">Pelanggan</div>
                                    <div class="mt-1 text-sm text-gray-900">
                                        {{ $order->customer_name }}
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ $order->customer_phone ?? '-' }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-xs font-medium text-gray-500">Outlet</div>
                                    <div class="mt-1 text-sm text-gray-900">
                                        {{ $order->outlet->outlet_name ?? '-' }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-xs font-medium text-gray-500">Meja</div>
                                    <div class="mt-1 text-sm text-gray-900">
                                        {{ $order->restaurantTable->table_number ?? '-' }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-xs font-medium text-gray-500">Status</div>
                                    <div class="mt-1 text-sm text-gray-900">
                                        {{ ucfirst($order->status) }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-xs font-medium text-gray-500">Pembayaran</div>
                                    <div class="mt-1 text-sm text-gray-900">
                                        {{ ucfirst($order->payment_status) }}
                                    </div>
                                </div>

                                <div class="text-left lg:text-right">
                                    <div class="text-xs font-medium text-gray-500">Total</div>
                                    <div class="mt-1 font-semibold text-gray-900">
                                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <div class="overflow-x-auto p-4">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead>
                                        <tr>
                                            <th class="py-2 text-left font-semibold text-gray-700">Menu</th>
                                            <th class="py-2 text-right font-semibold text-gray-700">Harga</th>
                                            <th class="py-2 text-right font-semibold text-gray-700">Qty</th>
                                            <th class="py-2 text-right font-semibold text-gray-700">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($order->items as $item)
                                            <tr>
                                                <td class="py-2 text-gray-900">
                                                    <div class="font-medium">{{ $item->menu_name }}</div>

                                                    @if ($item->item_note)
                                                        <div class="mt-1 text-xs text-gray-500">
                                                            Catatan: {{ $item->item_note }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="py-2 text-right text-gray-700">
                                                    Rp{{ number_format($item->menu_price, 0, ',', '.') }}
                                                </td>
                                                <td class="py-2 text-right text-gray-700">
                                                    {{ $item->quantity }}
                                                </td>
                                                <td class="py-2 text-right font-medium text-gray-900">
                                                    Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-md bg-gray-50 p-6 text-center text-sm text-gray-500">
                            Belum ada transaksi pada periode ini.
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>