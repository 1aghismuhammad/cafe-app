<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Order {{ $order->order_code }}
            </h2>

            <div class="flex items-center gap-2">
                <a href="{{ route('kasir.orders.receipt', $order) }}" target="_blank"
                    class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                    Cetak Nota
                </a>

                <a href="{{ route('kasir.orders.index') }}"
                    class="rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200">
                    Kembali
                </a>
            </div>
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

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                        <h3 class="mb-4 text-lg font-bold text-gray-900">
                            Item Pesanan
                        </h3>

                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-100 text-left text-gray-700">
                                <tr>
                                    <th class="px-4 py-3">Menu</th>
                                    <th class="px-4 py-3">Harga</th>
                                    <th class="px-4 py-3">Qty</th>
                                    <th class="px-4 py-3">Subtotal</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900">
                                                {{ $item->menu_name }}
                                            </div>

                                            @if ($item->item_note)
                                                <div class="mt-1 text-xs text-gray-500">
                                                    Catatan: {{ $item->item_note }}
                                                </div>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3">
                                            Rp{{ number_format($item->menu_price, 0, ',', '.') }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $item->quantity }}
                                        </td>

                                        <td class="px-4 py-3">
                                            Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right font-semibold">
                                        Total
                                    </td>
                                    <td class="px-4 py-3 font-semibold">
                                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                        <h3 class="mb-4 text-lg font-bold text-gray-900">
                            Informasi Order
                        </h3>

                        <div class="space-y-3 text-sm">
                            <div>
                                <div class="text-gray-500">Kode Order</div>
                                <div class="font-medium text-gray-900">{{ $order->order_code }}</div>
                            </div>

                            <div>
                                <div class="text-gray-500">Nama Pelanggan</div>
                                <div class="font-medium text-gray-900">{{ $order->customer_name }}</div>
                            </div>

                            <div>
                                <div class="text-gray-500">Nomor HP</div>
                                <div class="font-medium text-gray-900">{{ $order->customer_phone ?? '-' }}</div>
                            </div>

                            <div>
                                <div class="text-gray-500">Outlet</div>
                                <div class="font-medium text-gray-900">
                                    {{ $order->outlet->outlet_name ?? $order->restaurantTable->outlet->outlet_name ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-gray-500">Meja</div>
                                <div class="font-medium text-gray-900">
                                    {{ $order->restaurantTable->table_number ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-gray-500">Catatan Pelanggan</div>
                                <div class="font-medium text-gray-900">{{ $order->customer_note ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                        <h3 class="mb-4 text-lg font-bold text-gray-900">
                            Status Order
                        </h3>

                        <form action="{{ route('kasir.orders.update-status', $order) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    Ubah Status Order
                                </label>

                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}" @selected($order->status === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit"
                                class="w-full rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                                Simpan Status
                            </button>
                        </form>
                    </div>

                    <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                        <h3 class="mb-4 text-lg font-bold text-gray-900">
                            Status Pembayaran
                        </h3>

                        <div class="mb-4 text-sm">
                            <div class="text-gray-500">Status Saat Ini</div>
                            <div class="font-medium text-gray-900">
                                {{ ucfirst($order->payment_status) }}
                            </div>
                        </div>

                        <form action="{{ route('kasir.orders.update-payment-status', $order) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="payment_status" class="block text-sm font-medium text-gray-700">
                                    Ubah Status Pembayaran
                                </label>

                                <select name="payment_status" id="payment_status"
                                    class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="unpaid" @selected($order->payment_status === 'unpaid')>
                                        Unpaid
                                    </option>
                                    <option value="paid" @selected($order->payment_status === 'paid')>
                                        Paid
                                    </option>
                                    <option value="cancelled" @selected($order->payment_status === 'cancelled')>
                                        Cancelled
                                    </option>
                                </select>
                            </div>

                            <button type="submit"
                                class="w-full rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                                Simpan Pembayaran
                            </button>
                        </form>

                        @if ($order->payment)
                            <div class="mt-5 rounded-lg border border-gray-200 bg-gray-50 p-4">
                                <h4 class="mb-3 text-sm font-semibold text-gray-900">
                                    Informasi Pembayaran QRIS
                                </h4>

                                <div class="space-y-3 text-sm">
                                    <div>
                                        <div class="text-gray-500">Metode</div>
                                        <div class="font-medium text-gray-900">
                                            {{ strtoupper($order->payment->payment_type) }}
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-gray-500">Status Midtrans</div>
                                        <div class="font-medium text-gray-900">
                                            {{ ucfirst($order->payment->transaction_status) }}
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-gray-500">Midtrans Order ID</div>
                                        <div class="font-medium text-gray-900">
                                            {{ $order->payment->midtrans_order_id }}
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-gray-500">Transaction ID</div>
                                        <div class="font-medium text-gray-900">
                                            {{ $order->payment->transaction_id ?? '-' }}
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-gray-500">Waktu Lunas</div>
                                        <div class="font-medium text-gray-900">
                                            {{ $order->payment->paid_at ? $order->payment->paid_at->format('d/m/Y H:i') : '-' }}
                                        </div>
                                    </div>
                                </div>

                                <form
                                    action="{{ route('kasir.orders.check-midtrans-payment', $order) }}"
                                    method="POST"
                                    class="mt-4"
                                >
                                    @csrf

                                    <button type="submit"
                                        class="w-full rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                                        Cek Status Midtrans
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>