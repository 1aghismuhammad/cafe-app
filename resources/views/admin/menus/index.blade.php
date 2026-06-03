<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Menu Cafe A
            </h2>

            <a href="{{ route('admin.menus.create') }}"
                class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                Tambah Menu
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
                            <th class="px-4 py-3">Foto</th>
                            <th class="px-4 py-3">Nama Menu</th>
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Harga</th>
                            <th class="px-4 py-3">Stok</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($menus as $menu)
                            <tr>
                                <td class="px-4 py-3">
                                    @if ($menu->image_path)
                                        <img src="{{ asset('storage/' . $menu->image_path) }}"
                                            alt="{{ $menu->menu_name }}"
                                            class="h-12 w-12 rounded-md object-cover">
                                    @else
                                        <div class="flex h-12 w-12 items-center justify-center rounded-md bg-gray-100 text-xs text-gray-500">
                                            No Image
                                        </div>
                                    @endif
                                </td>

                                <td class="px-4 py-3 font-medium">
                                    {{ $menu->menu_name }}
                                    <div class="text-xs text-gray-500">
                                        {{ $menu->preparation_time }} menit
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    {{ $menu->menu_code }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $menu->category->category_name ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    Rp{{ number_format($menu->price, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3">
                                    @if ($menu->stock_status === 'available')
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs text-green-700">
                                            Available
                                        </span>
                                    @else
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs text-red-700">
                                            Sold Out
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    @if ($menu->is_active)
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs text-green-700">
                                            Active
                                        </span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.menus.edit', $menu) }}"
                                        class="text-blue-600 hover:underline">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.menus.destroy', $menu) }}"
                                        method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
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
                                <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada menu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>