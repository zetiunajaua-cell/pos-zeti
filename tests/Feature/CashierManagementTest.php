<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierManagementTest extends TestCase
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
            'role_id' => $adminRole->id,
            'store_branch' => 'Toko Pusat',
        ]);

        $this->kasir = User::factory()->create([
            'role_id' => $kasirRole->id,
            'name' => 'Kasir Utama',
            'store_branch' => 'Toko Pusat',
        ]);
    }

    public function test_admin_can_view_cashier_tab_in_settings()
    {
        $response = $this->actingAs($this->admin)->get(route('settings.index', ['tab' => 'cashiers']));
        $response->assertStatus(200);
        $response->assertSee('Akun Kasir (Kelola Kasir)');
        $response->assertSee('Manajemen Akun Kasir dan Penugasan Toko');
        $response->assertSee('Kasir Utama');
        $response->assertSee('Toko Pusat');
    }

    public function test_cashiers_index_route_redirects_to_settings_cashiers_tab()
    {
        $response = $this->actingAs($this->admin)->get(route('cashiers.index'));
        $response->assertRedirect(route('settings.index', ['tab' => 'cashiers']));
    }

    public function test_admin_can_create_new_cashier_for_branch_and_records_activity_log()
    {
        $response = $this->actingAs($this->admin)->post(route('cashiers.store'), [
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.kasir@gmail.com',
            'store_branch' => 'Cabang 2 - Mall Central',
            'phone' => '0812-8888-9999',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('settings.index', ['tab' => 'cashiers']));

        $this->assertDatabaseHas('users', [
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.kasir@gmail.com',
            'store_branch' => 'Cabang 2 - Mall Central',
            'phone' => '0812-8888-9999',
            'is_active' => true,
        ]);

        // Cek log aktivitas
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'CREATE_CASHIER',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_admin_can_update_cashier_details_and_branch()
    {
        $response = $this->actingAs($this->admin)->put(route('cashiers.update', $this->kasir), [
            'name' => 'Kasir Utama Updated',
            'email' => $this->kasir->email,
            'store_branch' => 'Cabang Barat Express',
            'phone' => '0899-7777-6666',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('settings.index', ['tab' => 'cashiers']));

        $this->assertDatabaseHas('users', [
            'id' => $this->kasir->id,
            'name' => 'Kasir Utama Updated',
            'store_branch' => 'Cabang Barat Express',
            'phone' => '0899-7777-6666',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'UPDATE_CASHIER',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_admin_can_toggle_cashier_status()
    {
        $this->assertTrue($this->kasir->is_active);

        $response = $this->actingAs($this->admin)->patch(route('cashiers.toggle', $this->kasir));
        $response->assertRedirect(route('settings.index', ['tab' => 'cashiers']));

        $this->assertFalse($this->kasir->fresh()->is_active);
    }

    public function test_admin_can_delete_cashier_without_transactions()
    {
        $response = $this->actingAs($this->admin)->delete(route('cashiers.destroy', $this->kasir));
        $response->assertRedirect(route('settings.index', ['tab' => 'cashiers']));

        $this->assertDatabaseMissing('users', [
            'id' => $this->kasir->id,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'DELETE_CASHIER',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_kasir_cannot_manage_other_cashiers()
    {
        $response = $this->actingAs($this->kasir)->get(route('cashiers.index'));
        $response->assertStatus(403);

        $postResponse = $this->actingAs($this->kasir)->post(route('cashiers.store'), [
            'name' => 'Hacker Kasir',
            'email' => 'hacker@kasir.com',
            'store_branch' => 'Cabang Gelap',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $postResponse->assertStatus(403);
    }
}
