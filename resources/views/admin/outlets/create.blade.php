<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Outlet
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.outlets.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Outlet</label>
                        <input type="text" name="outlet_name" value="{{ old('outlet_name') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('outlet_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kode Outlet</label>
                        <input type="text" name="outlet_code" value="{{ old('outlet_code', 'CFA-001') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('outlet_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="address" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('address') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kota</label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Provinsi</label>
                            <input type="text" name="province" value="{{ old('province') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email Outlet</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jam Buka</label>
                            <input type="time" name="open_time" value="{{ old('open_time', '10:00') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jam Tutup</label>
                            <input type="time" name="close_time" value="{{ old('close_time', '22:00') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="active" @selected(old('status') === 'active')>Active</option>
                                <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.outlets.index') }}"
                            class="rounded-md border px-5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Batal
                        </a>

                        <button type="submit"
                            class="rounded-md bg-gray-900 px-5 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                            Simpan Outlet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>