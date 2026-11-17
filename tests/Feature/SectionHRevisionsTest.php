<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SectionHRevisionsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $kasir;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'Admin']);
        $kasirRole = Role::create(['name' => 'Kasir']);

        $this->admin = User::factory()->create([
            'role_id' => $adminRole->id,
            'email' => 'admin@pos.test',
            'name' => 'Admin POS',
            'is_active' => true,
        ]);

        $this->kasir = User::factory()->create([
            'role_id' => $kasirRole->id,
            'email' => 'kasir@pos.test',
            'name' => 'Kasir POS',
            'is_active' => true,
        ]);
    }

    /**
     * Task 33 & 34 & 40: Login page has POS name, vector logo, and Forgot Password link.
     */
    public function test_login_page_reflects_section_h_branding_and_forgot_password()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        // Task 33: Cukup POS
        $response->assertSee('POS');
        $response->assertSee('Terminal');
        // Task 40: Forgot Password link exists
        $response->assertSee('Lupa Kata Sandi?');
        $response->assertSee(route('password.request'));

        // Forgot password view also renders cleanly
        $forgotResponse = $this->get(route('password.request'));
        $forgotResponse->assertStatus(200);
        $forgotResponse->assertSee('Pemulihan Kata Sandi');
        $forgotResponse->assertSee('Kirim Tautan Reset Sandi');
    }

    /**
     * Task 35 & 41 & 42: Layout includes sidebar with modern POS title, separated logout button, and grouped navigation.
     */
    public function test_authenticated_layout_renders_sidebar_and_separate_logout()
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);
        // Sidebar branding (Task 34 & 35)
        $response->assertSee('v2.0');
        // Grouped menus (Task 41)
        $response->assertSee('Menu Utama');
        $response->assertSee('Katalog & Inventaris', false);
        $response->assertSee('Operasional & CRM', false);
        $response->assertSee('Laporan Keuangan');
        // Dedicated Logout Button (Task 42)
        $response->assertSee('Keluar (Log Out)');
    }

    /**
     * Task 43 & 44: Topbar contains real-time elements, POS online pill, and Quick Search.
     */
    public function test_topbar_renders_online_badge_and_quick_search_bar()
    {
        $response = $this->actingAs($this->kasir)->get('/transactions');

        $response->assertStatus(200);
        // Topbar elements (Task 43)
        $response->assertSee('POS Online');
        $response->assertSee('WIB');
        // Quick Search Bar below header (Task 44)
        $response->assertSee('Cari menu, transaksi kasir, produk, atau fitur sistem...');
        $response->assertSee('Ctrl + K');
    }

    /**
     * Task 36: Dashboard banner is streamlined and compact.
     */
    public function test_dashboard_renders_compact_hero_banner()
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang, Admin POS!');
        $response->assertSee('Terminal POS Online');
    }

    /**
     * Task 39: Reports page has the section indicator badge and title "Laporan".
     */
    public function test_reports_page_displays_section_indicator_and_title()
    {
        $response = $this->actingAs($this->admin)->get('/reports');

        $response->assertStatus(200);
        $response->assertSee('Modul Laporan');
        $response->assertSee('Laporan Penjualan');
        $response->assertSee('Laba & Rugi (P&L)', false);
    }
}
