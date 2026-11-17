<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('pengguna yang tidak aktif (is_active = false) ditolak saat login', function () {
    $kasirRole = Role::firstOrCreate(['name' => 'Kasir']);

    $user = User::factory()->create([
        'email' => 'kasir_nonaktif@gmail.com',
        'password' => Hash::make('password123'),
        'role_id' => $kasirRole->id,
        'is_active' => false,
    ]);

    $response = $this->post('/login', [
        'email' => 'kasir_nonaktif@gmail.com',
        'password' => 'password123',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('produk tidak dapat dihapus jika sudah memiliki riwayat transaksi', function () {
    $admin = User::factory()->create([
        'role_id' => Role::firstOrCreate(['name' => 'Admin'])->id,
    ]);

    $product = Product::factory()->create(['name' => 'Kopi Robusta']);

    // Buat transaksi dummy yang menggunakan produk ini
    $transaction = Transaction::create([
        'invoice_number' => 'INV-TEST-0001',
        'user_id' => $admin->id,
        'total_amount' => 50000,
        'paid_amount' => 50000,
        'change_amount' => 0,
        'payment_method' => 'cash',
        'status' => 'completed',
    ]);

    $transaction->items()->create([
        'product_id' => $product->id,
        'price' => 50000,
        'quantity' => 1,
        'subtotal' => 50000,
    ]);

    $response = $this->actingAs($admin)->delete(route('products.destroy', $product));

    $response->assertRedirect(route('products.index'));
    $response->assertSessionHas('error');

    // Pastikan produk tetap ada di database
    expect(Product::find($product->id))->not->toBeNull();
});

test('produk non-aktif ditolak saat transaksi checkout', function () {
    $kasir = User::factory()->kasir()->create();

    $inactiveProduct = Product::factory()->create([
        'stock' => 10,
        'sell_price' => 25000,
        'is_active' => false,
    ]);

    $response = $this->actingAs($kasir)->post('/transactions', [
        'items' => [
            [
                'product_id' => $inactiveProduct->id,
                'quantity' => 1,
            ],
        ],
        'paid_amount' => 50000,
        'payment_method' => 'cash',
    ]);

    $response->assertSessionHas('error');
    expect($inactiveProduct->fresh()->stock)->toBe(10);
});

test('kasir diblokir dengan 403 saat mengakses route khusus admin', function () {
    $kasir = User::factory()->kasir()->create();

    $adminRoutes = [
        '/products',
        '/categories',
        '/payment-methods',
        '/promotions',
        '/customers',
        '/expenses',
        '/settings',
        '/activity-logs',
    ];

    foreach ($adminRoutes as $route) {
        $response = $this->actingAs($kasir)->get($route);
        $response->assertStatus(403);
    }
});

test('tamu tanpa autentikasi diarahkan ke halaman login', function () {
    $protectedRoutes = [
        '/dashboard',
        '/transactions',
        '/reports',
        '/profile',
        '/settings',
        '/products',
    ];

    foreach ($protectedRoutes as $route) {
        $response = $this->get($route);
        $response->assertRedirect('/login');
    }
});

test('sinkronisasi stok dan status real-time antara kasir dan admin saat checkout, void, dan refund', function () {
    $admin = User::factory()->create([
        'role_id' => Role::firstOrCreate(['name' => 'Admin'])->id,
    ]);
    $kasir = User::factory()->kasir()->create();

    $category = Category::create(['name' => 'Minuman', 'slug' => 'minuman']);
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Matcha Latte',
        'sell_price' => 20000,
        'purchase_price' => 12000,
        'stock' => 50,
        'min_stock' => 10,
        'is_active' => true,
    ]);

    // 1. Kasir melakukan transaksi checkout 5 unit
    $checkoutResponse = $this->actingAs($kasir)->post('/transactions', [
        'items' => [
            [
                'product_id' => $product->id,
                'quantity' => 5,
            ],
        ],
        'paid_amount' => 100000,
        'payment_method' => 'cash',
        'order_type' => 'dine_in',
    ]);

    $checkoutResponse->assertSessionHas('success');
    expect($product->fresh()->stock)->toBe(45);

    // Ambil transaksi yang baru dibuat
    $tx = Transaction::latest('id')->first();
    expect($tx->status)->toBe('completed');
    expect((float)$tx->total_amount)->toBe(100000.0);

    // 2. Admin dapat melihat transaksi kasir di Dashboard dan Laporan secara real-time
    $dashboardResponse = $this->actingAs($admin)->get('/dashboard');
    $dashboardResponse->assertStatus(200);
    $dashboardResponse->assertSee('Matcha Latte');

    $reportResponse = $this->actingAs($admin)->get('/reports');
    $reportResponse->assertStatus(200);
    $reportResponse->assertSee($tx->invoice_number);

    // 3. Kasir atau Admin melakukan Refund
    $refundResponse = $this->actingAs($admin)->post(route('transactions.refund', $tx), [
        'reason' => 'Pelanggan membatalkan pesanan',
        'refund_amount' => 100000,
    ]);

    $refundResponse->assertSessionHas('success');

    // Stok tersinkronisasi kembali secara otomatis (45 + 5 = 50)
    expect($product->fresh()->stock)->toBe(50);
    expect($tx->fresh()->status)->toBe('refunded');
});

test('admin diblokir dengan 403 saat mengakses terminal transaksi kasir', function () {
    $admin = User::factory()->create([
        'role_id' => Role::firstOrCreate(['name' => 'Admin'])->id,
    ]);

    $response = $this->actingAs($admin)->get('/transactions');
    $response->assertStatus(403);
});

test('admin tidak melihat menu transaksi kasir pada sidebar', function () {
    $admin = User::factory()->create([
        'role_id' => Role::firstOrCreate(['name' => 'Admin'])->id,
    ]);

    $response = $this->actingAs($admin)->get('/dashboard');
    $response->assertStatus(200);
    $response->assertDontSee('Transaksi Kasir');
});

test('kasir melihat menu transaksi kasir pada sidebar', function () {
    $kasir = User::factory()->kasir()->create();

    $response = $this->actingAs($kasir)->get('/dashboard');
    $response->assertStatus(200);
    $response->assertSee('Transaksi Kasir');
});



