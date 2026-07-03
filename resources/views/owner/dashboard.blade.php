<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Owner Cafe A
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Ringkasan Laporan</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Data dihitung dari order yang sudah masuk ke sistem. Omzet hanya menghitung order dengan status pembayaran paid.
                        </p>

                        <a href="{{ route('owner.reports.sales') }}"
                            class="inline-flex rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                            Lihat Laporan Detail
                        </a>
                    </div>

                    <form method="GET" action="{{ route('owner.dashboard') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                                Filter
                            </button>

                            <a href="{{ route('owner.dashboard') }}"
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

                <div class="bg-white p-5 shadow-sm sm:rounded-lg xl:col-span-2">
                    <div class="text-sm font-medium text-gray-500">Omzet Hari Ini</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">
                        Rp{{ number_format($todayRevenue, 0, ',', '.') }}
                    </div>
                </div>

                <div class="bg-white p-5 shadow-sm sm:rounded-lg">
                    <div class="text-sm font-medium text-gray-500">Total Order</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">{{ $totalOrders }}</div>
                </div>

                <div class="bg-white p-5 shadow-sm sm:rounded-lg">
                    <div class="text-sm font-medium text-gray-500">Order Paid</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">{{ $paidOrders }}</div>
                </div>

                <div class="bg-white p-5 shadow-sm sm:rounded-lg">
                    <div class="text-sm font-medium text-gray-500">Belum Dibayar</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">{{ $unpaidOrders }}</div>
                </div>

                <div class="bg-white p-5 shadow-sm sm:rounded-lg">
                    <div class="text-sm font-medium text-gray-500">Dibatalkan</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">{{ $cancelledOrders }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg xl:col-span-2">
                    <h3 class="text-lg font-bold text-gray-900">Transaksi Terbaru</h3>

                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Kode</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Pelanggan</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Outlet</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Total</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Pembayaran</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($latestOrders as $order)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $order->order_code }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $order->customer_name }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $order->outlet->outlet_name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-700">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ ucfirst($order->status) }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ ucfirst($order->payment_status) }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                            Belum ada transaksi pada periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="text-lg font-bold text-gray-900">Menu Terlaris</h3>
                    <p class="mt-1 text-sm text-gray-600">Dihitung dari order paid.</p>

                    <div class="mt-4 space-y-4">
                        @forelse ($topMenus as $menu)
                            <div class="border-b border-gray-100 pb-3 last:border-b-0 last:pb-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $menu->menu_name }}</div>
                                        <div class="text-sm text-gray-500">Terjual {{ $menu->total_quantity }} item</div>
                                    </div>
                                    <div class="text-right text-sm font-semibold text-gray-900">
                                        Rp{{ number_format($menu->total_sales, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-md bg-gray-50 p-4 text-sm text-gray-500">
                                Belum ada menu terlaris karena belum ada order paid.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>