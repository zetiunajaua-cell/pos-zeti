<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExpenseAndProfitLossTest extends TestCase
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

    public function test_admin_can_view_expense_list()
    {
        Expense::create([
            'expense_number' => 'EXP-20261113-0001',
            'user_id' => $this->admin->id,
            'category' => 'Pembelian Stok',
            'title' => 'Beli Biji Kopi Arabika 5kg',
            'amount' => 450000,
            'expense_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->admin)->get(route('expenses.index'));

        $response->assertStatus(200);
        $response->assertSee('Beli Biji Kopi Arabika 5kg');
        $response->assertSee('450.000');
    }

    public function test_admin_can_create_expense_with_receipt()
    {
        Storage::fake('public');

        $receipt = UploadedFile::fake()->image('nota.jpg');

        $response = $this->actingAs($this->admin)->post(route('expenses.store'), [
            'category' => 'Utilitas',
            'title' => 'Tagihan Listrik Bulan Ini',
            'amount' => 350000,
            'expense_date' => now()->toDateString(),
            'note' => 'PLN Token 500k',
            'receipt_image' => $receipt,
        ]);

        $response->assertRedirect(route('expenses.index'));

        $this->assertDatabaseHas('expenses', [
            'category' => 'Utilitas',
            'title' => 'Tagihan Listrik Bulan Ini',
            'amount' => 350000,
        ]);

        $expense = Expense::where('title', 'Tagihan Listrik Bulan Ini')->first();
        $this->assertNotNull($expense->receipt_image);
        Storage::disk('public')->assertExists($expense->receipt_image);
    }

    public function test_admin_can_update_and_delete_expense()
    {
        $expense = Expense::create([
            'expense_number' => 'EXP-20261113-0002',
            'user_id' => $this->admin->id,
            'category' => 'Operasional Toko',
            'title' => 'Sapu dan Pel Toko',
            'amount' => 75000,
            'expense_date' => now()->toDateString(),
        ]);

        $updateResponse = $this->actingAs($this->admin)->put(route('expenses.update', $expense), [
            'category' => 'Operasional Toko',
            'title' => 'Sapu dan Pel Toko (Super)',
            'amount' => 95000,
            'expense_date' => now()->toDateString(),
        ]);

        $updateResponse->assertRedirect(route('expenses.index'));
        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'title' => 'Sapu dan Pel Toko (Super)',
            'amount' => 95000,
        ]);

        $deleteResponse = $this->actingAs($this->admin)->delete(route('expenses.destroy', $expense));
        $deleteResponse->assertRedirect(route('expenses.index'));
        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }

    public function test_kasir_cannot_manage_expenses()
    {
        $response = $this->actingAs($this->kasir)->get(route('expenses.index'));
        $response->assertStatus(403);
    }

    public function test_profit_and_loss_report_calculation()
    {
        $category = Category::create(['name' => 'Minuman', 'slug' => 'minuman']);

        $product = Product::create([
            'category_id' => $category->id,
            'sku' => 'KOP-001',
            'name' => 'Kopi Latte',
            'purchase_price' => 10000, // COGS per unit
            'sell_price' => 25000,     // Jual per unit
            'stock' => 50,
            'min_stock' => 5,
            'unit' => 'cup',
            'is_active' => true,
        ]);

        // Buat transaksi completed (Penjualan 2 unit: Subtotal 50.000, COGS 20.000)
        $tx = Transaction::create([
            'invoice_number' => 'INV-20261113-0001',
            'user_id' => $this->kasir->id,
            'total_amount' => 50000,
            'discount' => 0,
            'tax' => 0,
            'service_charge' => 0,
            'paid_amount' => 50000,
            'change_amount' => 0,
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);

        $tx->items()->create([
            'product_id' => $product->id,
            'price' => 25000,
            'quantity' => 2,
            'subtotal' => 50000,
        ]);

        // Buat beban pengeluaran (Expense: 15.000)
        Expense::create([
            'expense_number' => 'EXP-20261113-0003',
            'user_id' => $this->admin->id,
            'category' => 'Operasional Toko',
            'title' => 'Beli Es Batu Kristal',
            'amount' => 15000,
            'expense_date' => now()->toDateString(),
        ]);

        // Net Sales = 50.000
        // COGS = 2 * 10.000 = 20.000
        // Gross Profit = 50.000 - 20.000 = 30.000
        // Expenses = 15.000
        // Net Profit = 30.000 - 15.000 = 15.000

        $response = $this->actingAs($this->admin)->get(route('reports.profit_loss', ['period' => 'today']));

        $response->assertStatus(200);
        $response->assertSee('50.000'); // Net Sales
        $response->assertSee('20.000'); // COGS
        $response->assertSee('30.000'); // Gross Profit
        $response->assertSee('15.000'); // Net Profit & Expense
        $response->assertSee('SURPLUS');
    }
}
