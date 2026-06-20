<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order Masuk Kasir
            </h2>

            <a href="{{ route('kasir.dashboard') }}"
                class="rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200">
                Dashboard Kasir
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-4 bg-white p-4 shadow-sm sm:rounded-lg">
                <form action="{{ route('kasir.orders.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            Filter Status
                        </label>

                        <select name="status" id="status"
                            class="mt-1 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Order</option>
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected($status === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                            Terapkan
                        </button>

                        <a href="{{ route('kasir.orders.index') }}"
                            class="rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-left text-gray-700">
                        <tr>
                            <th class="px-4 py-3">Kode Order</th>
                            <th class="px-4 py-3">Pelanggan</th>
                            <th class="px-4 py-3">Meja</th>
                            <th class="px-4 py-3">Outlet</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Status Order</th>
                            <th class="px-4 py-3">Pembayaran</th>
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($orders as $order)
                            <tr>
                                <td class="px-4 py-3 font-medium">
                                    {{ $order->order_code }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $order->customer_name }}
                                    @if ($order->customer_phone)
                                        <div class="text-xs text-gray-500">
                                            {{ $order->customer_phone }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    {{ $order->restaurantTable->table_number ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $order->outlet->outlet_name ?? $order->restaurantTable->outlet->outlet_name ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ ucfirst($order->status) }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ ucfirst($order->payment_status) }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $order->created_at->format('d M Y H:i') }}
                                </td>

                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('kasir.orders.show', $order) }}"
                                        class="text-blue-600 hover:underline">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada order masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>