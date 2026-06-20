<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Kasir Cafe A
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-bold mb-2">Selamat datang, Kasir.</h3>

                <p class="text-gray-600">
                    Di halaman ini nanti kasir dapat melihat pesanan masuk, menandai pembayaran lunas, mencetak nota pelanggan, dan mencetak nota dapur.
                </p>

                <div class="mt-6">
                    <a href="{{ route('kasir.orders.index') }}"
                        class="inline-flex rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                        Lihat Order Masuk
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>