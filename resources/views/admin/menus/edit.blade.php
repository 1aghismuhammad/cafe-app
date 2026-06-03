<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Menu
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.menus.update', $menu) }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $menu->category_id) == $category->id)>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kode Menu</label>
                            <input type="text" name="menu_code" value="{{ old('menu_code', $menu->menu_code) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('menu_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Menu</label>
                            <input type="text" name="menu_name" value="{{ old('menu_name', $menu->menu_name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('menu_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $menu->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga</label>
                            <input type="number" name="price" value="{{ old('price', $menu->price) }}" min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estimasi Proses Menit</label>
                            <input type="number" name="preparation_time" value="{{ old('preparation_time', $menu->preparation_time) }}" min="1"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('preparation_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Foto Menu</label>

                        @if ($menu->image_path)
                            <img src="{{ asset('storage/' . $menu->image_path) }}"
                                alt="{{ $menu->menu_name }}"
                                class="mb-3 h-24 w-24 rounded-md object-cover">
                        @endif

                        <input type="file" name="image" accept="image/*"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <p class="mt-1 text-xs text-gray-500">
                            Kosongkan jika tidak ingin mengganti foto.
                        </p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status Stok</label>
                            <select name="stock_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="available" @selected(old('stock_status', $menu->stock_status) === 'available')>Available</option>
                                <option value="sold_out" @selected(old('stock_status', $menu->stock_status) === 'sold_out')>Sold Out</option>
                            </select>
                            @error('stock_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status Tampil</label>
                            <select name="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="1" @selected(old('is_active', (string) (int) $menu->is_active) === '1')>Active</option>
                                <option value="0" @selected(old('is_active', (string) (int) $menu->is_active) === '0')>Inactive</option>
                            </select>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.menus.index') }}"
                            class="rounded-md border px-5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Batal
                        </a>

                        <button type="submit"
                            class="rounded-md bg-gray-900 px-5 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>