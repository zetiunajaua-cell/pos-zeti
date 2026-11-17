<?php

use App\Models\PaymentMethod;
use App\Models\User;

it('admin dapat mengakses daftar metode pembayaran dan menambah metode baru', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('payment-methods.index'));
    $response->assertOk();
    $response->assertSee('Pengaturan Metode Pembayaran');

    // Tambah metode pembayaran baru
    $postResponse = $this->actingAs($admin)->post(route('payment-methods.store'), [
        'name' => 'GoPay Merchant',
        'type' => 'e_wallet',
        'account_number' => '0899887766',
        'account_name' => 'ZETI POS STORE',
        'instructions' => 'Pindai barcode GoPay di kasir.',
        'is_active' => '1',
    ]);

    $postResponse->assertRedirect(route('payment-methods.index'));
    $postResponse->assertSessionHas('success');

    $this->assertDatabaseHas('payment_methods', [
        'name' => 'GoPay Merchant',
        'type' => 'e_wallet',
        'account_number' => '0899887766',
    ]);
});

it('admin dapat mengubah dan menonaktifkan metode pembayaran', function () {
    $admin = User::factory()->admin()->create();

    $pm = PaymentMethod::create([
        'name' => 'Debit BRI',
        'code' => 'debit_bri',
        'type' => 'bank_transfer',
        'account_number' => '12345678',
        'is_active' => true,
    ]);

    // Update
    $updateResponse = $this->actingAs($admin)->put(route('payment-methods.update', $pm), [
        'name' => 'Debit BRI Updated',
        'type' => 'bank_transfer',
        'account_number' => '87654321',
        'is_active' => '1',
    ]);

    $updateResponse->assertRedirect(route('payment-methods.index'));
    expect($pm->fresh()->name)->toBe('Debit BRI Updated')
        ->and($pm->fresh()->account_number)->toBe('87654321');

    // Toggle status
    $toggleResponse = $this->actingAs($admin)->patch(route('payment-methods.toggle', $pm));
    $toggleResponse->assertRedirect();
    expect($pm->fresh()->is_active)->toBeFalse();
});

it('transaksi dengan nama metode pembayaran panjang berhasil disimpan', function () {
    $kasir = User::factory()->kasir()->create();
    $product = \App\Models\Product::factory()->create(['stock' => 10, 'sell_price' => 20000]);

    $pm = PaymentMethod::create([
        'name' => 'E-Wallet GoPay / ShopeePay',
        'code' => 'gopay_shopee',
        'type' => 'e_wallet',
        'is_active' => true,
    ]);

    $response = $this->actingAs($kasir)->post(route('transactions.store'), [
        'items' => [
            [
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => 20000,
            ]
        ],
        'paid_amount' => 50000,
        'payment_method_id' => $pm->id,
        'order_type' => 'dine_in',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('transactions', [
        'payment_method' => 'E-Wallet GoPay / ShopeePay',
        'payment_method_id' => $pm->id,
    ]);
});


