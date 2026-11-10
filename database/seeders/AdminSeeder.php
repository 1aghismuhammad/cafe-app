<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminSeeder extends Seeder
{
    /**
     * Bootstrap the first admin user from config values.
     *
     * Required environment variables (mapped via config/admin.php):
     * - ADMIN_NAME
     * - ADMIN_EMAIL
     * - ADMIN_PASSWORD
     *
     * Behavior:
     * - Missing config values → fail with descriptive exception.
     * - Email not yet registered → create admin user.
     * - Email exists with role=admin → idempotent no-op.
     * - Email exists with role!=admin → fail (won't silently skip).
     * - No hardcoded credentials.
     */
    public function run(): void
    {
        $name = config('admin.name');
        $email = config('admin.email');
        $password = config('admin.password');

        $missing = [];

        if (empty($name)) {
            $missing[] = 'ADMIN_NAME';
        }

        if (empty($email)) {
            $missing[] = 'ADMIN_EMAIL';
        }

        if (empty($password)) {
            $missing[] = 'ADMIN_PASSWORD';
        }

        if (! empty($missing)) {
            throw new RuntimeException(
                'Admin seeder gagal: variabel environment berikut belum diatur: '
                . implode(', ', $missing)
                . '. Atur di file .env atau environment server.'
            );
        }

        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            if ($existingUser->role === 'admin') {
                $this->command?->info("Admin user [{$email}] sudah ada. Skip.");

                return;
            }

            throw new RuntimeException(
                "User [{$email}] sudah ada dengan role [{$existingUser->role}]. "
                . 'Tidak dapat dijadikan admin secara otomatis. '
                . 'Ubah role secara manual atau gunakan email lain.'
            );
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        $this->command?->info("Admin user [{$email}] berhasil dibuat.");
    }
}
