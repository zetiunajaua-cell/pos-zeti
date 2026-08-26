<?php

use App\Models\Product;
use App\Models\User;

it('mengurangi stok produk setelah transaksi berhasil', function () {
    $kasir = User::factory()->kasir()->create();

    $product = Product::factory()->create([
        'stock' => 10,
        'sell_price' => 20000,
    ]);

    $response = $this->actingAs($kasir)->post('/transactions', [
        'items' => [
            [
                'product_id' => $product->id,
                'quantity' => 3,
            ],
        ],
        'paid_amount' => 100000,
        'payment_method' => 'cash',
    ]);

    $response->assertRedirect();

    expect($product->fresh()->stock)->toBe(7);
});


it('menolak transaksi jika stok produk tidak mencukupi', function () {
    $kasir = User::factory()->kasir()->create();

    $product = Product::factory()->create([
        'stock' => 2,
        'sell_price' => 20000,
    ]);

    $response = $this->actingAs($kasir)->post('/transactions', [
        'items' => [
            [
                'product_id' => $product->id,
                'quantity' => 5,
            ],
        ],
        'paid_amount' => 100000,
        'payment_method' => 'cash',
    ]);

    $response->assertRedirect();

    expect($product->fresh()->stock)->toBe(2);
});