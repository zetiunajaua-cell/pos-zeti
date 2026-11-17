<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionReadinessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Kasir']);
    }

    public function test_route_admin_redirects_to_dashboard_for_authenticated_admin(): void
    {
        $admin = User::factory()->create([
            'role_id' => Role::where('name', 'Admin')->first()->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertRedirect(route('dashboard'));
    }

    public function test_custom_404_page_is_rendered_for_non_existent_route(): void
    {
        $response = $this->get('/non-existent-random-route-999');
        $response->assertStatus(404);
        $response->assertSee('Halaman Tidak Ditemukan');
    }

    public function test_custom_403_page_is_rendered_when_kasir_accesses_admin_route(): void
    {
        $kasir = User::factory()->create([
            'role_id' => Role::where('name', 'Kasir')->first()->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($kasir)->get(route('products.index'));
        $response->assertStatus(403);
        $response->assertSee('Akses Ditolak');
    }

    public function test_deactivated_user_is_logged_out_by_active_middleware(): void
    {
        $kasir = User::factory()->create([
            'role_id' => Role::where('name', 'Kasir')->first()->id,
            'is_active' => true,
        ]);

        $this->actingAs($kasir);
        $this->assertAuthenticated();

        // Admin menonaktifkan kasir
        $kasir->update(['is_active' => false]);

        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_database_seeder_runs_cleanly(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->assertDatabaseHas('roles', ['name' => 'Admin']);
        $this->assertDatabaseHas('roles', ['name' => 'Kasir']);
        $this->assertDatabaseHas('payment_methods', ['code' => 'cash']);
    }
}
