<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Meja
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.restaurant-tables.update', $restaurantTable) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Outlet</label>
                        <select name="outlet_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Pilih Outlet</option>
                            @foreach ($outlets as $outlet)
                                <option value="{{ $outlet->id }}" @selected(old('outlet_id', $restaurantTable->outlet_id) == $outlet->id)>
                                    {{ $outlet->outlet_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('outlet_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Meja</label>
                            <input type="text" name="table_number" value="{{ old('table_number', $restaurantTable->table_number) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('table_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kode Meja</label>
                            <input type="text" name="table_code" value="{{ old('table_code', $restaurantTable->table_code) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('table_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kapasitas</label>
                            <input type="number" name="capacity" value="{{ old('capacity', $restaurantTable->capacity) }}" min="1"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="active" @selected(old('status', $restaurantTable->status) === 'active')>Active</option>
                                <option value="inactive" @selected(old('status', $restaurantTable->status) === 'inactive')>Inactive</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.restaurant-tables.index') }}"
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