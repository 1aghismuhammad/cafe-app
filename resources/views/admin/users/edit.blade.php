<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Nama')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        
                        <!-- Role -->
                        <div class="mt-4">
                            <x-input-label for="role" :value="__('Role')" />
                            <select id="role" name="role" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required onchange="toggleOutlets()">
                                <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                <option value="owner" {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>Owner</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Outlets (for Kasir) -->
                        <div class="mt-4" id="outlets-container" style="display: {{ old('role', $user->role) == 'kasir' ? 'block' : 'none' }};">
                            <x-input-label :value="__('Pilih Outlet (Wajib untuk Kasir)')" />
                            <div class="mt-2 space-y-2">
                                @php
                                    $userOutletIds = old('outlets', $user->outlets->pluck('id')->toArray());
                                @endphp
                                @foreach($outlets as $outlet)
                                    <div class="flex items-center">
                                        <input id="outlet_{{ $outlet->id }}" name="outlets[]" type="checkbox" value="{{ $outlet->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ in_array($outlet->id, $userOutletIds) ? 'checked' : '' }}>
                                        <label for="outlet_{{ $outlet->id }}" class="ml-2 text-sm text-gray-600">{{ $outlet->outlet_name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('outlets')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4 border-t pt-4">
                            <p class="text-sm text-gray-600 mb-2">Kosongkan jika tidak ingin mengubah password.</p>
                            <x-input-label for="password" :value="__('Password Baru (Opsional)')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4" href="{{ route('admin.users.index') }}">
                                {{ __('Batal') }}
                            </a>

                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function toggleOutlets() {
            var roleSelect = document.getElementById('role');
            var outletsContainer = document.getElementById('outlets-container');
            if (roleSelect.value === 'kasir') {
                outletsContainer.style.display = 'block';
            } else {
                outletsContainer.style.display = 'none';
            }
        }
    </script>
</x-app-layout>
