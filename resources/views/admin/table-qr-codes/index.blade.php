<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            QR Meja Cafe A
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-left text-gray-700">
                        <tr>
                            <th class="px-4 py-3">Nomor Meja</th>
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Outlet</th>
                            <th class="px-4 py-3">Status QR</th>
                            <th class="px-4 py-3">URL</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($tables as $table)
                            <tr>
                                <td class="px-4 py-3 font-medium">
                                    {{ $table->table_number }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $table->table_code }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $table->outlet->outlet_name ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    @if ($table->tableQrCode && $table->tableQrCode->is_active)
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs text-green-700">
                                            Active
                                        </span>
                                    @elseif ($table->tableQrCode && ! $table->tableQrCode->is_active)
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs text-red-700">
                                            Inactive
                                        </span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">
                                            Belum dibuat
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 max-w-xs truncate">
                                    @if ($table->tableQrCode)
                                        <a href="{{ $table->tableQrCode->qr_url }}" target="_blank" class="text-blue-600 hover:underline">
                                            {{ $table->tableQrCode->qr_url }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-right">
                                    @if (! $table->tableQrCode)
                                        <form action="{{ route('admin.table-qr-codes.generate', $table) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:underline">
                                                Generate
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.table-qr-codes.show', $table->tableQrCode) }}"
                                            class="text-blue-600 hover:underline">
                                            Lihat
                                        </a>

                                        <form action="{{ route('admin.table-qr-codes.toggle', $table->tableQrCode) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="ml-3 text-yellow-600 hover:underline">
                                                {{ $table->tableQrCode->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada meja. Buat meja terlebih dahulu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>