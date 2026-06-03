<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Meja {{ $qrCode->restaurantTable->table_number }} - Cafe A</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-sm">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-900">
                    Cafe A
                </h1>

                <p class="mt-2 text-gray-600">
                    Selamat datang. Sistem berhasil mengenali meja kamu.
                </p>
            </div>

            <div class="mt-6 rounded-xl bg-gray-50 p-4">
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Outlet</span>
                        <span class="font-semibold text-gray-900">
                            {{ $qrCode->restaurantTable->outlet->outlet_name ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Nomor Meja</span>
                        <span class="font-semibold text-gray-900">
                            {{ $qrCode->restaurantTable->table_number }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Kode Meja</span>
                        <span class="font-semibold text-gray-900">
                            {{ $qrCode->restaurantTable->table_code }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="button"
                    class="w-full rounded-lg bg-gray-900 px-4 py-3 text-sm font-semibold text-white">
                    Lanjut Pilih Menu
                </button>
            </div>

            <p class="mt-4 text-center text-xs text-gray-500">
                Halaman menu akan dibuat pada checkpoint berikutnya.
            </p>
        </div>
    </div>
</body>
</html>