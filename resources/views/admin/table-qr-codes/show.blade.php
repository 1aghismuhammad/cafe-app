<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail QR Meja
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <h3 class="text-lg font-bold mb-4">Informasi Meja</h3>

                        <div class="space-y-2 text-sm">
                            <p><strong>Outlet:</strong> {{ $tableQrCode->restaurantTable->outlet->outlet_name ?? '-' }}</p>
                            <p><strong>Nomor Meja:</strong> {{ $tableQrCode->restaurantTable->table_number }}</p>
                            <p><strong>Kode Meja:</strong> {{ $tableQrCode->restaurantTable->table_code }}</p>
                            <p><strong>Status QR:</strong> {{ $tableQrCode->is_active ? 'Active' : 'Inactive' }}</p>
                            <p><strong>Token:</strong> {{ $tableQrCode->qr_token }}</p>
                        </div>

                        <div class="mt-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL QR</label>
                            <input type="text" value="{{ $tableQrCode->qr_url }}" readonly
                                class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ $tableQrCode->qr_url }}" target="_blank"
                                class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                                Buka Link
                            </a>

                            <button type="button" onclick="window.print()"
                                class="rounded-md border px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Cetak QR
                            </button>

                            <form action="{{ route('admin.table-qr-codes.regenerate', $tableQrCode) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin membuat ulang QR? Token lama tidak bisa dipakai lagi.')">
                                @csrf

                                <button type="submit"
                                    class="rounded-md bg-yellow-500 px-4 py-2 text-sm font-semibold text-white hover:bg-yellow-600">
                                    Regenerate
                                </button>
                            </form>

                            <a href="{{ route('admin.table-qr-codes.index') }}"
                                class="rounded-md border px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Kembali
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col items-center justify-center">
                        <div class="rounded-lg border bg-white p-5">
                            {!! QrCode::size(260)->generate($tableQrCode->qr_url) !!}
                        </div>

                        <p class="mt-4 text-center text-sm text-gray-600">
                            Scan QR ini untuk membuka halaman order meja.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>