<?php

namespace Tests\Feature\Cashier;

use App\Models\Order;
use App\Models\Outlet;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OutletAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private $kasirA;
    private $outletA;
    private $orderA;

    private $kasirB;
    private $outletB;
    private $orderB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->outletA = Outlet::create([
            'outlet_name' => 'Outlet A', 'outlet_code' => 'A1', 'address' => '1', 'city' => '1', 'province' => '1', 'phone_number' => '1', 'email' => 'a@out.com', 'open_time' => '08:00', 'close_time' => '22:00', 'status' => 'active'
        ]);
        $tableA = RestaurantTable::create([
            'outlet_id' => $this->outletA->id, 'table_number' => '1', 'table_code' => 'T1', 'capacity' => 2, 'status' => 'active'
        ]);
        $qrCodeA = \App\Models\TableQrCode::create([
            'restaurant_table_id' => $tableA->id, 'qr_token' => 'QR1', 'qr_url' => 'http://test.com/QR1', 'status' => 'active'
        ]);
        $this->orderA = Order::create([
            'outlet_id' => $this->outletA->id, 'restaurant_table_id' => $tableA->id, 'table_qr_code_id' => $qrCodeA->id, 'order_code' => 'ORD-1', 'customer_name' => 'Test', 'total_amount' => 100, 'status' => 'pending', 'payment_status' => 'paid'
        ]);
        $this->kasirA = User::factory()->kasir()->create();
        $this->kasirA->outlets()->attach($this->outletA->id);

        $this->outletB = Outlet::create([
            'outlet_name' => 'Outlet B', 'outlet_code' => 'B1', 'address' => '2', 'city' => '2', 'province' => '2', 'phone_number' => '2', 'email' => 'b@out.com', 'open_time' => '08:00', 'close_time' => '22:00', 'status' => 'active'
        ]);
        $tableB = RestaurantTable::create([
            'outlet_id' => $this->outletB->id, 'table_number' => '2', 'table_code' => 'T2', 'capacity' => 2, 'status' => 'active'
        ]);
        $qrCodeB = \App\Models\TableQrCode::create([
            'restaurant_table_id' => $tableB->id, 'qr_token' => 'QR2', 'qr_url' => 'http://test.com/QR2', 'status' => 'active'
        ]);
        $this->orderB = Order::create([
            'outlet_id' => $this->outletB->id, 'restaurant_table_id' => $tableB->id, 'table_qr_code_id' => $qrCodeB->id, 'order_code' => 'ORD-2', 'customer_name' => 'Test2', 'total_amount' => 200, 'status' => 'pending', 'payment_status' => 'paid'
        ]);
        $this->kasirB = User::factory()->kasir()->create();
        $this->kasirB->outlets()->attach($this->outletB->id);
    }

    public function test_kasir_index_only_shows_assigned_outlet_orders(): void
    {
        $response = $this->actingAs($this->kasirA)->get('/kasir/orders');
        
        $response->assertStatus(200);
        $response->assertSee($this->orderA->order_code);
        $response->assertDontSee($this->orderB->order_code);
    }

    public function test_kasir_can_view_own_outlet_order(): void
    {
        $response = $this->actingAs($this->kasirA)->get("/kasir/orders/{$this->orderA->id}");
        $response->assertStatus(200);
    }

    public function test_kasir_cannot_view_other_outlet_order(): void
    {
        $response = $this->actingAs($this->kasirA)->get("/kasir/orders/{$this->orderB->id}");
        $response->assertStatus(403);
    }

    public function test_kasir_cannot_update_status_other_outlet_order(): void
    {
        $response = $this->actingAs($this->kasirA)->patch("/kasir/orders/{$this->orderB->id}/status", [
            'status' => 'confirmed'
        ]);
        $response->assertStatus(403);
    }

    public function test_kasir_cannot_update_payment_status_other_outlet_order(): void
    {
        $response = $this->actingAs($this->kasirA)->patch("/kasir/orders/{$this->orderB->id}/payment-status", [
            'payment_status' => 'paid'
        ]);
        $response->assertStatus(403);
    }

    public function test_kasir_cannot_view_receipt_other_outlet_order(): void
    {
        $response = $this->actingAs($this->kasirA)->get("/kasir/orders/{$this->orderB->id}/receipt");
        $response->assertStatus(403);
    }

    public function test_kasir_cannot_check_midtrans_other_outlet_order(): void
    {
        // Must return 403 before making any external Midtrans call
        $response = $this->actingAs($this->kasirA)->post("/kasir/orders/{$this->orderB->id}/payment/check-midtrans");
        $response->assertStatus(403);
    }
}
