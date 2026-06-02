<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profil Cafe A
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.cafe-profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Brand</label>
                        <input type="text" name="brand_name" value="{{ old('brand_name', $profile->brand_name) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Legal Usaha</label>
                        <input type="text" name="legal_name" value="{{ old('legal_name', $profile->legal_name) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Slogan</label>
                        <input type="text" name="slogan" value="{{ old('slogan', $profile->slogan) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi Cafe</label>
                        <textarea name="description" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $profile->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Warna Utama</label>
                            <input type="text" name="primary_color" value="{{ old('primary_color', $profile->primary_color) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Warna Sekunder</label>
                            <input type="text" name="secondary_color" value="{{ old('secondary_color', $profile->secondary_color) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Warna Aksen</label>
                            <input type="text" name="accent_color" value="{{ old('accent_color', $profile->accent_color) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor WhatsApp</label>
                        <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $profile->whatsapp_number) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Instagram URL</label>
                        <input type="text" name="instagram_url" value="{{ old('instagram_url', $profile->instagram_url) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">TikTok URL</label>
                        <input type="text" name="tiktok_url" value="{{ old('tiktok_url', $profile->tiktok_url) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Cafe</label>
                        <input type="email" name="email" value="{{ old('email', $profile->email) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Google Maps URL</label>
                        <textarea name="google_maps_url" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('google_maps_url', $profile->google_maps_url) }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="rounded-md bg-gray-900 px-5 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                            Simpan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>