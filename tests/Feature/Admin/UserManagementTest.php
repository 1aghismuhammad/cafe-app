<?php

namespace Tests\Feature\Admin;

use App\Models\Outlet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_user_management(): void
    {
        $kasir = User::factory()->kasir()->create();
        
        $response = $this->actingAs($kasir)->get('/admin/users');
        $response->assertStatus(403);
    }

    public function test_admin_can_create_kasir_with_outlet(): void
    {
        $admin = User::factory()->admin()->create();
        $outlet = Outlet::create([
            'outlet_name' => 'Test Outlet',
            'outlet_code' => 'OUT-01',
            'address' => 'Test',
            'city' => 'Test',
            'province' => 'Test',
            'phone_number' => '123',
            'email' => 'test@outlet.com',
            'open_time' => '08:00',
            'close_time' => '22:00',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Kasir 1',
            'email' => 'kasir1@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'kasir',
            'outlets' => [$outlet->id],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'kasir1@example.com', 'role' => 'kasir']);
        
        $user = User::where('email', 'kasir1@example.com')->first();
        $this->assertTrue($user->outlets->contains($outlet->id));
    }

    public function test_admin_can_create_owner_without_outlet(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Owner 1',
            'email' => 'owner1@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'owner',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'owner1@example.com', 'role' => 'owner']);
    }

    public function test_kasir_creation_fails_without_outlet(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Kasir 2',
            'email' => 'kasir2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'kasir',
        ]);

        $response->assertSessionHasErrors('outlets');
        $this->assertDatabaseMissing('users', ['email' => 'kasir2@example.com']);
    }

    public function test_admin_cannot_create_another_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Admin 2',
            'email' => 'admin2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseMissing('users', ['email' => 'admin2@example.com']);
    }

    public function test_admin_can_update_kasir_outlet_assignment(): void
    {
        $admin = User::factory()->admin()->create();
        $kasir = User::factory()->kasir()->create();
        $outlet1 = Outlet::create(['outlet_name' => 'O1', 'outlet_code' => 'O1', 'address' => 'A', 'city' => 'C', 'province' => 'P', 'phone_number' => '1', 'email' => '1@e.com', 'open_time' => '08:00', 'close_time' => '22:00', 'status' => 'active']);
        $outlet2 = Outlet::create(['outlet_name' => 'O2', 'outlet_code' => 'O2', 'address' => 'B', 'city' => 'D', 'province' => 'Q', 'phone_number' => '2', 'email' => '2@e.com', 'open_time' => '08:00', 'close_time' => '22:00', 'status' => 'active']);
        
        $kasir->outlets()->attach($outlet1->id);

        $response = $this->actingAs($admin)->put("/admin/users/{$kasir->id}", [
            'name' => 'Kasir Updated',
            'email' => $kasir->email,
            'role' => 'kasir',
            'outlets' => [$outlet2->id],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertTrue($kasir->fresh()->outlets->contains($outlet2->id));
        $this->assertFalse($kasir->fresh()->outlets->contains($outlet1->id));
    }

    public function test_changing_kasir_to_owner_clears_user_outlet(): void
    {
        $admin = User::factory()->admin()->create();
        $kasir = User::factory()->kasir()->create();
        $outlet = Outlet::create(['outlet_name' => 'O1', 'outlet_code' => 'O1', 'address' => 'A', 'city' => 'C', 'province' => 'P', 'phone_number' => '1', 'email' => '1@e.com', 'open_time' => '08:00', 'close_time' => '22:00', 'status' => 'active']);
        
        $kasir->outlets()->attach($outlet->id);

        $response = $this->actingAs($admin)->put("/admin/users/{$kasir->id}", [
            'name' => 'Kasir Jadi Owner',
            'email' => $kasir->email,
            'role' => 'owner',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertEquals('owner', $kasir->fresh()->role);
        $this->assertCount(0, $kasir->fresh()->outlets);
    }

    public function test_admin_can_delete_kasir_or_owner(): void
    {
        $admin = User::factory()->admin()->create();
        $kasir = User::factory()->kasir()->create();
        $owner = User::factory()->owner()->create();

        $response1 = $this->actingAs($admin)->delete("/admin/users/{$kasir->id}");
        $response1->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $kasir->id]);

        $response2 = $this->actingAs($admin)->delete("/admin/users/{$owner->id}");
        $response2->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $owner->id]);
    }

    public function test_crafted_request_cannot_edit_or_delete_admin(): void
    {
        $admin1 = User::factory()->admin()->create();
        $admin2 = User::factory()->admin()->create(['email' => 'target@admin.com']);

        // Try to edit
        $responseEdit = $this->actingAs($admin1)->get("/admin/users/{$admin2->id}/edit");
        $responseEdit->assertStatus(403);

        $responseUpdate = $this->actingAs($admin1)->put("/admin/users/{$admin2->id}", [
            'name' => 'Hacked',
            'email' => 'target@admin.com',
            'role' => 'owner'
        ]);
        $responseUpdate->assertStatus(403);

        // Try to delete
        $responseDelete = $this->actingAs($admin1)->delete("/admin/users/{$admin2->id}");
        $responseDelete->assertStatus(403);

        $this->assertDatabaseHas('users', ['email' => 'target@admin.com']);
    }

    public function test_crafted_owner_creation_does_not_attach_outlets(): void
    {
        $admin = User::factory()->admin()->create();
        $outlet = Outlet::create(['outlet_name' => 'O1', 'outlet_code' => 'O1', 'address' => 'A', 'city' => 'C', 'province' => 'P', 'phone_number' => '1', 'email' => '1@e.com', 'open_time' => '08:00', 'close_time' => '22:00', 'status' => 'active']);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Owner Hacked',
            'email' => 'hackedowner@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'owner',
            'outlets' => [$outlet->id],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $owner = User::where('email', 'hackedowner@example.com')->first();
        $this->assertEquals('owner', $owner->role);
        $this->assertCount(0, $owner->outlets);
    }

    public function test_crafted_owner_update_clears_outlets(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->owner()->create();
        $outlet = Outlet::create(['outlet_name' => 'O1', 'outlet_code' => 'O1', 'address' => 'A', 'city' => 'C', 'province' => 'P', 'phone_number' => '1', 'email' => '1@e.com', 'open_time' => '08:00', 'close_time' => '22:00', 'status' => 'active']);

        $response = $this->actingAs($admin)->put("/admin/users/{$owner->id}", [
            'name' => 'Owner Hacked Update',
            'email' => $owner->email,
            'role' => 'owner',
            'outlets' => [$outlet->id],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertEquals('owner', $owner->fresh()->role);
        $this->assertCount(0, $owner->fresh()->outlets);
    }

    public function test_kasir_cannot_be_assigned_to_inactive_outlet(): void
    {
        $admin = User::factory()->admin()->create();
        $inactiveOutlet = Outlet::create(['outlet_name' => 'Inactive', 'outlet_code' => 'INA', 'address' => 'A', 'city' => 'C', 'province' => 'P', 'phone_number' => '1', 'email' => 'ina@e.com', 'open_time' => '08:00', 'close_time' => '22:00', 'status' => 'inactive']);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Kasir Inactive',
            'email' => 'kasirinactive@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'kasir',
            'outlets' => [$inactiveOutlet->id],
        ]);

        $response->assertSessionHasErrors('outlets.0');
        $this->assertDatabaseMissing('users', ['email' => 'kasirinactive@example.com']);
    }

    public function test_admin_can_update_kasir_password(): void
    {
        $admin = User::factory()->admin()->create();
        $kasir = User::factory()->kasir()->create(['password' => \Illuminate\Support\Facades\Hash::make('oldpassword')]);
        $outlet = Outlet::create(['outlet_name' => 'O1', 'outlet_code' => 'O1', 'address' => 'A', 'city' => 'C', 'province' => 'P', 'phone_number' => '1', 'email' => '1@e.com', 'open_time' => '08:00', 'close_time' => '22:00', 'status' => 'active']);

        $response = $this->actingAs($admin)->put("/admin/users/{$kasir->id}", [
            'name' => $kasir->name,
            'email' => $kasir->email,
            'role' => 'kasir',
            'outlets' => [$outlet->id],
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword123', $kasir->fresh()->password));
    }

    public function test_admin_update_user_without_password_keeps_old_password(): void
    {
        $admin = User::factory()->admin()->create();
        $kasir = User::factory()->kasir()->create(['password' => \Illuminate\Support\Facades\Hash::make('oldpassword')]);
        $outlet = Outlet::create(['outlet_name' => 'O1', 'outlet_code' => 'O1', 'address' => 'A', 'city' => 'C', 'province' => 'P', 'phone_number' => '1', 'email' => '1@e.com', 'open_time' => '08:00', 'close_time' => '22:00', 'status' => 'active']);

        $response = $this->actingAs($admin)->put("/admin/users/{$kasir->id}", [
            'name' => 'Name Updated',
            'email' => $kasir->email,
            'role' => 'kasir',
            'outlets' => [$outlet->id],
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('oldpassword', $kasir->fresh()->password));
    }
}
