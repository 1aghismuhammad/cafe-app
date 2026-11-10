<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use RuntimeException;
use Tests\TestCase;

class AdminSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_seeder_creates_admin_from_config(): void
    {
        Config::set('admin.name', 'Super Admin');
        Config::set('admin.email', 'super@example.com');
        Config::set('admin.password', 'secret123');

        $this->artisan('db:seed', ['--class' => 'AdminSeeder'])
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => 'super@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_admin_seeder_is_idempotent(): void
    {
        Config::set('admin.name', 'Super Admin');
        Config::set('admin.email', 'super@example.com');
        Config::set('admin.password', 'secret123');

        User::factory()->create([
            'email' => 'super@example.com',
            'role' => 'admin',
        ]);

        $this->artisan('db:seed', ['--class' => 'AdminSeeder'])
            ->expectsOutputToContain('sudah ada. Skip.')
            ->assertExitCode(0);
            
        $this->assertEquals(1, User::where('email', 'super@example.com')->count());
    }

    public function test_admin_seeder_fails_on_missing_config(): void
    {
        Config::set('admin.name', ''); // missing
        Config::set('admin.email', 'super@example.com');
        Config::set('admin.password', 'secret123');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Admin seeder gagal');

        $this->artisan('db:seed', ['--class' => 'AdminSeeder']);
    }

    public function test_admin_seeder_fails_on_email_collision_with_non_admin(): void
    {
        Config::set('admin.name', 'Super Admin');
        Config::set('admin.email', 'super@example.com');
        Config::set('admin.password', 'secret123');

        // Existing user with kasir role
        User::factory()->create([
            'email' => 'super@example.com',
            'role' => 'kasir',
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Tidak dapat dijadikan admin secara otomatis');

        $this->artisan('db:seed', ['--class' => 'AdminSeeder']);
    }
}
