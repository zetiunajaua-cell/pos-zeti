<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SettingAndActivityLogTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $kasir;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $kasirRole = Role::firstOrCreate(['name' => 'Kasir']);

        $this->admin = User::factory()->create(['role_id' => $adminRole->id]);
        $this->kasir = User::factory()->create(['role_id' => $kasirRole->id]);
    }

    public function test_admin_can_update_settings_and_logs_activity()
    {
        Storage::fake('public');
        $logo = UploadedFile::fake()->image('logo_baru.png');

        $response = $this->actingAs($this->admin)->put(route('settings.update'), [
            'store_name' => 'Toko Barokah Jaya',
            'store_slogan' => 'Pusat Belanja Hemat',
            'store_address' => 'Jl. Merdeka No. 45',
            'store_phone' => '0812-9999-8888',
            'store_email' => 'toko@barokah.com',
            'store_website' => 'barokahjaya.com',
            'store_logo' => $logo,
            'receipt_header_msg' => 'Selamat Berbelanja!',
            'receipt_footer_msg' => 'Semoga Berkah Selalu.',

            'tax_enabled' => '1',
            'tax_name' => 'PPN 11%',
            'tax_type' => 'percentage',
            'tax_rate' => 11,

            'service_charge_enabled' => '1',
            'service_charge_name' => 'Biaya Kemasan',
            'service_charge_type' => 'fixed',
            'service_charge_rate' => 2000,
        ]);

        $response->assertRedirect(route('settings.index'));

        $this->assertEquals('Toko Barokah Jaya', setting('store_name'));
        $this->assertEquals('1', setting('tax_enabled'));
        $this->assertEquals('11', setting('tax_rate'));
        $this->assertEquals('Biaya Kemasan', setting('service_charge_name'));

        // Pastikan activity log tercatat
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'UPDATE_SETTING',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_transaction_calculates_tax_and_service_charge_automatically()
    {
        // Set up tax (10%) and fixed service charge (2000)
        Setting::set('tax_enabled', '1');
        Setting::set('tax_name', 'PPN 10%');
        Setting::set('tax_type', 'percentage');
        Setting::set('tax_rate', '10');

        Setting::set('service_charge_enabled', '1');
        Setting::set('service_charge_name', 'Biaya Layanan');
        Setting::set('service_charge_type', 'fixed');
        Setting::set('service_charge_rate', '2000');

        $category = Category::create(['name' => 'Makanan', 'slug' => 'makanan']);
        $product = Product::create([
            'category_id' => $category->id,
            'sku' => 'BUR-01',
            'name' => 'Burger Spesial',
            'purchase_price' => 15000,
            'sell_price' => 30000,
            'stock' => 20,
            'min_stock' => 2,
            'unit' => 'pcs',
            'is_active' => true,
        ]);

        // Beli 1 Burger = Subtotal 30.000
        // Pajak 10% = 3.000
        // Service Charge = 2.000
        // Final Total = 30.000 + 3.000 + 2.000 = 35.000

        $response = $this->actingAs($this->kasir)->post(route('transactions.store'), [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => 30000,
                ],
            ],
            'paid_amount' => 50000,
            'payment_method' => 'cash',
        ]);

        $this->assertDatabaseHas('transactions', [
            'total_amount' => 35000,
            'tax' => 3000,
            'service_charge' => 2000,
            'paid_amount' => 50000,
            'change_amount' => 15000,
        ]);

        // Cek log aktivitas checkout
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'CHECKOUT',
            'user_id' => $this->kasir->id,
        ]);
    }

    public function test_void_and_refund_record_activity_logs()
    {
        $category = Category::create(['name' => 'Snack', 'slug' => 'snack']);
        $product = Product::create([
            'category_id' => $category->id,
            'sku' => 'SNK-01',
            'name' => 'Keripik Kentang',
            'purchase_price' => 5000,
            'sell_price' => 10000,
            'stock' => 10,
            'min_stock' => 2,
            'unit' => 'bungkus',
            'is_active' => true,
        ]);

        $tx = Transaction::create([
            'invoice_number' => 'INV-TEST-001',
            'user_id' => $this->kasir->id,
            'total_amount' => 10000,
            'discount' => 0,
            'tax' => 0,
            'service_charge' => 0,
            'paid_amount' => 10000,
            'change_amount' => 0,
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);
        $tx->items()->create([
            'product_id' => $product->id,
            'price' => 10000,
            'quantity' => 1,
            'subtotal' => 10000,
        ]);

        // Void transaction
        $voidResponse = $this->actingAs($this->admin)->post(route('transactions.void', $tx), [
            'reason' => 'Salah input oleh kasir',
        ]);
        $voidResponse->assertRedirect(route('transactions.show', $tx));

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'VOID_TRANSACTION',
            'user_id' => $this->admin->id,
        ]);

        // Transaksi kedua untuk refund
        $tx2 = Transaction::create([
            'invoice_number' => 'INV-TEST-002',
            'user_id' => $this->kasir->id,
            'total_amount' => 20000,
            'discount' => 0,
            'tax' => 0,
            'service_charge' => 0,
            'paid_amount' => 20000,
            'change_amount' => 0,
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);
        $tx2->items()->create([
            'product_id' => $product->id,
            'price' => 10000,
            'quantity' => 2,
            'subtotal' => 20000,
        ]);

        $refundResponse = $this->actingAs($this->admin)->post(route('transactions.refund', $tx2), [
            'reason' => 'Pelanggan minta ganti uang',
            'refund_amount' => 20000,
        ]);
        $refundResponse->assertRedirect(route('transactions.show', $tx2));

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'REFUND_TRANSACTION',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_admin_can_view_activity_logs()
    {
        ActivityLog::record('LOGIN', 'User testing masuk sistem.');

        $response = $this->actingAs($this->admin)->get(route('activity-logs.index'));
        $response->assertStatus(200);
        $response->assertSee('User testing masuk sistem.');
        $response->assertSee('LOGIN');
    }

    public function test_kasir_cannot_view_settings_or_activity_logs()
    {
        $settingsResponse = $this->actingAs($this->kasir)->get(route('settings.index'));
        $settingsResponse->assertStatus(403);

        $logsResponse = $this->actingAs($this->kasir)->get(route('activity-logs.index'));
        $logsResponse->assertStatus(403);
    }

    public function test_kasir_opening_terminal_records_access_log()
    {
        $response = $this->actingAs($this->kasir)->get(route('transactions.index'));
        $response->assertStatus(200);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'ACCESS_TERMINAL',
            'user_id' => $this->kasir->id,
        ]);
    }

    public function test_admin_can_poll_activity_logs_realtime_feed()
    {
        ActivityLog::record('ACCESS_TERMINAL', 'Kasir membuka terminal kasir.');

        $response = $this->actingAs($this->admin)->get(route('activity-logs.index', ['feed' => 1]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'logs',
            'latest_id',
            'total_count',
            'synced_at',
        ]);
        $response->assertJsonFragment([
            'action' => 'ACCESS_TERMINAL',
        ]);
    }

    public function test_admin_can_upload_large_logo_and_remove_it()
    {
        Storage::fake('public');
        // Upload logo berukuran 4MB (4096 KB)
        $largeLogo = UploadedFile::fake()->image('logo_besar.jpg')->size(4096);

        $response = $this->actingAs($this->admin)->put(route('settings.update'), [
            'store_name' => 'Toko Logo Besar',
            'store_logo' => $largeLogo,
            'tax_name' => 'PPN 10%',
            'tax_type' => 'percentage',
            'tax_rate' => 10,
            'service_charge_name' => 'Layanan',
            'service_charge_type' => 'percentage',
            'service_charge_rate' => 5,
        ]);

        $response->assertRedirect(route('settings.index'));
        $this->assertNotNull(setting('store_logo'));
        Storage::disk('public')->assertExists(setting('store_logo'));

        // Tes hapus logo
        $removeResponse = $this->actingAs($this->admin)->put(route('settings.update'), [
            'store_name' => 'Toko Logo Besar',
            'remove_logo' => '1',
            'tax_name' => 'PPN 10%',
            'tax_type' => 'percentage',
            'tax_rate' => 10,
            'service_charge_name' => 'Layanan',
            'service_charge_type' => 'percentage',
            'service_charge_rate' => 5,
        ]);

        $removeResponse->assertRedirect(route('settings.index'));
        $this->assertNull(setting('store_logo'));
    }
}
