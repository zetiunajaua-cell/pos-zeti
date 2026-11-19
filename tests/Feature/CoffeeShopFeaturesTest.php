<?php

namespace Tests\Feature;

use App\Models\CashierShift;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoffeeShopFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected $adminRole;
    protected $kasirRole;
    protected $admin;
    protected $kasir;
    protected $category;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::create(['name' => 'Admin']);
        $this->kasirRole = Role::create(['name' => 'Kasir']);

        $this->admin = User::factory()->create([
            'role_id' => $this->adminRole->id,
            'is_active' => true,
        ]);

        $this->kasir = User::factory()->create([
            'role_id' => $this->kasirRole->id,
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Coffee & Beverages',
            'slug' => 'coffee-beverages',
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Iced Americano',
            'sku' => 'CF-AME-01',
            'buy_price' => 12000,
            'sell_price' => 25000,
            'stock' => 50,
            'unit' => 'Cup',
            'is_active' => true,
        ]);

        PaymentMethod::create([
            'name' => 'Tunai (Cash)',
            'code' => 'cash',
            'type' => 'cash',
            'is_active' => true,
        ]);

        PaymentMethod::create([
            'name' => 'QRIS Static',
            'code' => 'qris',
            'type' => 'qris',
            'is_active' => true,
        ]);
    }

    public function test_transaksi_kasir_menghasilkan_nomor_antrean_harian_otomatis()
    {
        $this->actingAs($this->kasir);

        // Transaksi 1
        $res1 = $this->post(route('transactions.store'), [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 2,
                    'note' => 'Less Ice, No Sugar',
                ],
            ],
            'paid_amount' => 50000,
            'order_type' => 'dine_in',
            'note' => 'Meja 03',
        ]);

        $res1->assertSessionHasNoErrors();
        $trx1 = Transaction::latest('id')->first();
        $this->assertEquals(1, $trx1->queue_number);
        $this->assertEquals('#01', $trx1->formatted_queue_number);

        // Transaksi 2
        $res2 = $this->post(route('transactions.store'), [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 1,
                    'note' => 'Oatmilk',
                ],
            ],
            'paid_amount' => 50000,
            'order_type' => 'take_away',
        ]);

        $res2->assertSessionHasNoErrors();
        $trx2 = Transaction::latest('id')->first();
        $this->assertEquals(2, $trx2->queue_number);
        $this->assertEquals('#02', $trx2->formatted_queue_number);
    }

    public function test_slip_barista_dapur_dapat_diakses_oleh_kasir_dan_admin()
    {
        $trx = Transaction::create([
            'invoice_number' => 'INV-20261117-0001',
            'queue_number' => 5,
            'user_id' => $this->kasir->id,
            'payment_method' => 'cash',
            'total_amount' => 25000,
            'paid_amount' => 50000,
            'change_amount' => 25000,
            'order_type' => 'dine_in',
            'note' => 'Meja 07',
            'status' => 'completed',
        ]);

        $trx->items()->create([
            'product_id' => $this->product->id,
            'price' => 25000,
            'quantity' => 1,
            'subtotal' => 25000,
            'note' => 'Less Ice, Oatmilk',
        ]);

        // Kasir akses tiket barista
        $this->actingAs($this->kasir);
        $responseKasir = $this->get(route('transactions.kitchen', $trx));
        $responseKasir->assertStatus(200);
        $responseKasir->assertSee('#05');
        $responseKasir->assertSee('SLIP BARISTA / DAPUR');
        $responseKasir->assertSee('Iced Americano');
        $responseKasir->assertSee('Less Ice, Oatmilk');

        // Admin akses tiket barista
        $this->actingAs($this->admin);
        $responseAdmin = $this->get(route('transactions.kitchen', $trx));
        $responseAdmin->assertStatus(200);
    }

    public function test_kasir_dapat_buka_shift_dan_tutup_shift_dengan_rekonsiliasi_laci_kas()
    {
        $this->actingAs($this->kasir);

        // 1. Buka shift dengan modal awal Rp 200.000
        $openResponse = $this->post(route('cashier-shifts.open'), [
            'starting_cash' => 200000,
            'notes' => 'Shift Pagi Barista',
        ]);

        $openResponse->assertRedirect();
        $openResponse->assertSessionHas('success');

        $shift = CashierShift::where('user_id', $this->kasir->id)->active()->first();
        $this->assertNotNull($shift);
        $this->assertEquals(200000, $shift->starting_cash);
        $this->assertEquals('open', $shift->status);

        // 2. Buat transaksi tunai sebesar Rp 25.000
        $this->post(route('transactions.store'), [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 1,
                ],
            ],
            'paid_amount' => 30000,
            'payment_method' => 'Tunai (Cash)',
        ]);

        // 3. Tutup shift kasir dengan uang aktual di laci Rp 225.000 (Pas)
        $closeResponse = $this->post(route('cashier-shifts.close', $shift), [
            'actual_cash' => 225000,
            'notes' => 'Tutup shift lancar, serah terima ke kasir siang.',
        ]);

        $closeResponse->assertRedirect(route('cashier-shifts.print', $shift));

        $shift->refresh();
        $this->assertEquals('closed', $shift->status);
        $this->assertEquals(225000, $shift->expected_cash);
        $this->assertEquals(225000, $shift->actual_cash);
        $this->assertEquals(0, $shift->difference);
        $this->assertEquals(25000, $shift->total_cash_sales);
        $this->assertEquals(1, $shift->total_transactions);

        // 4. Akses cetak laporan tutup shift (Z-Report)
        $printResponse = $this->get(route('cashier-shifts.print', $shift));
        $printResponse->assertStatus(200);
        $printResponse->assertSee('LAPORAN TUTUP SHIFT KASIR');
        $printResponse->assertSee('PAS (SEIMBANG)');
    }

    public function test_kasir_dapat_menginput_nama_customer_pada_transaksi(): void
    {
        $this->actingAs($this->kasir);

        // Kasir checkout dengan input nama customer "Kak Sarah"
        $response = $this->post(route('transactions.store'), [
            'paid_amount' => 50000,
            'customer_name' => 'Kak Sarah',
            'order_type' => 'dine_in',
            'note' => 'Meja 03',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 1,
                    'price' => 25000,
                    'note' => 'Less Sugar',
                ]
            ]
        ]);

        $response->assertRedirect();
        
        $transaction = Transaction::latest('id')->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('Kak Sarah', $transaction->customer_name);
        $this->assertEquals('Meja 03', $transaction->note);

        // Periksa tampilan show / receipt
        $showResponse = $this->get(route('transactions.show', $transaction));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Kak Sarah');

        // Periksa tampilan KOT Barista
        $kitchenResponse = $this->get(route('transactions.kitchen', $transaction));
        $kitchenResponse->assertStatus(200);
        $kitchenResponse->assertSee('Kak Sarah');
    }
}
