<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Outlet Cafe A
            </h2>

            <a href="{{ route('admin.outlets.create') }}"
                class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                Tambah Outlet
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

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-left text-gray-700">
                        <tr>
                            <th class="px-4 py-3">Nama Outlet</th>
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Kota</th>
                            <th class="px-4 py-3">Jam Operasional</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($outlets as $outlet)
                            <tr>
                                <td class="px-4 py-3 font-medium">
                                    {{ $outlet->outlet_name }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $outlet->outlet_code }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $outlet->city ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $outlet->open_time ?? '-' }} - {{ $outlet->close_time ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($outlet->status === 'active')
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs text-green-700">
                                            Active
                                        </span>
                                    @else
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs text-red-700">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.outlets.edit', $outlet) }}"
                                        class="text-blue-600 hover:underline">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.outlets.destroy', $outlet) }}"
                                        method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Yakin ingin menghapus outlet ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="ml-3 text-red-600 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada outlet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>