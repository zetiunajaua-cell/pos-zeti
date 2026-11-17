<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;

it('menampilkan halaman kategori dengan pagination dan statistik yang sesuai', function () {
    $admin = User::factory()->admin()->create();

    for ($i = 1; $i <= 15; $i++) {
        Category::create([
            'name' => 'Kategori Test ' . $i,
            'slug' => 'kategori-test-' . $i,
        ]);
    }

    $response = $this->actingAs($admin)->get(route('categories.index'));

    $response->assertOk();
    $response->assertSee('Master Kategori Produk');
    $response->assertViewHas('categories');
    $response->assertViewHas('totalCategories');
});

it('menampilkan halaman laporan penjualan dengan filter dan pagination', function () {
    $admin = User::factory()->admin()->create();
    $kasir = User::factory()->kasir()->create();

    $product = Product::factory()->create(['stock' => 50, 'sell_price' => 10000]);

    // Buat 12 transaksi agar memicu pagination
    for ($i = 0; $i < 12; $i++) {
        Transaction::create([
            'invoice_number' => 'INV-TEST-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
            'user_id' => $kasir->id,
            'total_amount' => 10000,
            'paid_amount' => 10000,
            'change_amount' => 0,
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);
    }

    // Test default report
    $response = $this->actingAs($admin)->get(route('reports.index'));
    $response->assertOk();
    $response->assertSee('Laporan Penjualan');
    $response->assertViewHas('transactions');
    $response->assertViewHas('totalSales');
    $response->assertViewHas('totalTransactions');

    // Test filter kasir
    $filterKasirResponse = $this->actingAs($admin)->get(route('reports.index', ['user_id' => $kasir->id]));
    $filterKasirResponse->assertOk();
    expect($filterKasirResponse->viewData('totalTransactions'))->toBe(12);

    // Test filter search keyword
    $filterSearchResponse = $this->actingAs($admin)->get(route('reports.index', ['search' => 'INV-TEST-0001']));
    $filterSearchResponse->assertOk();
    expect($filterSearchResponse->viewData('totalTransactions'))->toBe(1);
});
