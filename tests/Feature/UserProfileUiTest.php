<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserProfileUiTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $kasir;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $kasirRole = Role::firstOrCreate(['name' => 'Kasir']);

        $this->admin = User::factory()->create([
            'name' => 'Aditya Pratama',
            'email' => 'admin@poszeti.com',
            'role_id' => $adminRole->id,
            'password' => Hash::make('password123'),
        ]);

        $this->kasir = User::factory()->create([
            'name' => 'Rina Kasir',
            'email' => 'kasir@poszeti.com',
            'role_id' => $kasirRole->id,
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_admin_sees_admin_specific_profile_ui()
    {
        $response = $this->actingAs($this->admin)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Aditya Pratama');
        $response->assertSee('admin@poszeti.com');
        $response->assertSee('Super Administrator');
        $response->assertSee('Katalog Produk');
        $response->assertSee('Log Aktivitas');
        $response->assertSee('Riwayat Aktivitas Log');
    }

    public function test_kasir_sees_kasir_specific_profile_ui_and_transactions()
    {
        $category = Category::create(['name' => 'Makanan', 'slug' => 'makanan']);
        $product = Product::create([
            'category_id' => $category->id,
            'sku' => 'MAK-01',
            'name' => 'Nasi Goreng Spesial',
            'purchase_price' => 10000,
            'sell_price' => 20000,
            'stock' => 20,
            'min_stock' => 2,
            'unit' => 'porsi',
            'is_active' => true,
        ]);

        $tx = Transaction::create([
            'invoice_number' => 'INV-20261114-9999',
            'user_id' => $this->kasir->id,
            'total_amount' => 40000,
            'discount' => 0,
            'tax' => 0,
            'service_charge' => 0,
            'paid_amount' => 50000,
            'change_amount' => 10000,
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->kasir)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Rina Kasir');
        $response->assertSee('kasir@poszeti.com');
        $response->assertSee('Kasir Terminal POS');
        $response->assertSee('Total Transaksi');
        $response->assertSee('Omset Tercapai');
        $response->assertSee('40.000');
        $response->assertSee('INV-20261114-9999');
    }

    public function test_user_can_update_password_and_it_records_activity()
    {
        $response = $this->actingAs($this->kasir)->put(route('password.update'), [
            'current_password' => 'password123',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'password-updated');

        $this->assertTrue(Hash::check('newpassword456', $this->kasir->fresh()->password));

        // Memastikan log aktivitas tercatat
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'UPDATE_PASSWORD',
            'user_id' => $this->kasir->id,
        ]);
    }
}
