<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromotionAndMembershipTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $kasir;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        $roleAdmin = Role::create(['name' => 'Admin']);
        $roleKasir = Role::create(['name' => 'Kasir']);

        $this->admin = User::factory()->create([
            'role_id' => $roleAdmin->id,
            'is_active' => true,
        ]);

        $this->kasir = User::factory()->create([
            'role_id' => $roleKasir->id,
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Biji Kopi',
            'slug' => 'biji-kopi',
        ]);
    }

    /**
     * Task 25: Admin CRUD Promo & Diskon
     */
    public function test_admin_dapat_mengelola_promo_dan_toggle_status(): void
    {
        // 1. Tambah promo baru
        $response = $this->actingAs($this->admin)->post(route('promotions.store'), [
            'name' => 'Diskon Kilat 20%',
            'code' => 'KILAT20',
            'type' => 'percentage',
            'value' => 20,
            'min_purchase' => 50000,
            'max_discount' => 20000,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('promotions.index'));

        $promo = Promotion::where('code', 'KILAT20')->first();
        $this->assertNotNull($promo);
        $this->assertEquals(20, $promo->value);
        $this->assertTrue($promo->is_active);

        // 2. Toggle status promo menjadi nonaktif
        $this->actingAs($this->admin)->patch(route('promotions.toggle', $promo));
        $this->assertFalse($promo->fresh()->is_active);
    }

    /**
     * Task 26: Admin CRUD Pelanggan & Membership
     */
    public function test_admin_dapat_mendaftarkan_member_pelanggan_dan_menentukan_tier(): void
    {
        $response = $this->actingAs($this->admin)->post(route('customers.store'), [
            'name' => 'Siti Rahma',
            'phone' => '081298765432',
            'email' => 'siti@example.com',
            'membership_tier' => 'Gold',
            'discount_percent' => 10,
            'points' => 50,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('customers.index'));

        $customer = Customer::where('name', 'Siti Rahma')->first();
        $this->assertNotNull($customer);
        $this->assertStringStartsWith('MBR-', $customer->member_code);
        $this->assertEquals('Gold', $customer->membership_tier);
        $this->assertEquals(10, (float)$customer->discount_percent);
        $this->assertEquals(50, $customer->points);
    }

    /**
     * Task 25 & 26: Kasir memproses transaksi dengan diskon kupon promo dan diskon member serta perolehan poin loyalitas.
     */
    public function test_transaksi_kasir_dapat_menerapkan_diskon_promo_dan_member_serta_menambah_poin(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Kopi Arabika Premium',
            'sku' => 'ARB-001',
            'purchase_price' => 50000,
            'sell_price' => 100000,
            'stock' => 20,
            'min_stock' => 5,
            'unit' => 'Pack',
            'is_active' => true,
        ]);

        $member = Customer::create([
            'member_code' => 'MBR-9999',
            'name' => 'Ahmad VIP',
            'membership_tier' => 'Platinum',
            'discount_percent' => 10, // Diskon 10%
            'points' => 0,
            'is_active' => true,
        ]);

        $promo = Promotion::create([
            'name' => 'Diskon Rp 10.000',
            'code' => 'HEMAT10K',
            'type' => 'fixed',
            'value' => 10000,
            'min_purchase' => 50000,
            'is_active' => true,
        ]);

        // Belanja 1 x 100.000 = Subtotal 100.000
        // Member Disc 10% = 10.000
        // Promo Disc = 10.000
        // Total Disc = 20.000 -> Total Tagihan = 80.000
        // Poin: 80.000 / 10.000 = 8 poin

        $response = $this->actingAs($this->kasir)->post(route('transactions.store'), [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]
            ],
            'customer_id' => $member->id,
            'promotion_id' => $promo->id,
            'paid_amount' => 100000,
            'order_type' => 'dine_in',
        ]);

        $transaction = Transaction::latest('id')->first();
        $this->assertNotNull($transaction);
        $response->assertRedirect(route('transactions.show', $transaction));

        $this->assertEquals(80000, (float)$transaction->total_amount);
        $this->assertEquals(20000, (float)$transaction->discount);
        $this->assertEquals(20000, (float)$transaction->change_amount);
        $this->assertEquals(8, $transaction->points_earned);

        // Member poin harus bertambah 8
        $this->assertEquals(8, $member->fresh()->points);

        // Void transaksi: Poin harus ditarik kembali
        $this->actingAs($this->kasir)->post(route('transactions.void', $transaction), [
            'reason' => 'Pelanggan salah pesan',
        ]);

        $this->assertEquals(0, $member->fresh()->points);
        $this->assertEquals(20, $product->fresh()->stock); // Stok kembali
    }
}
