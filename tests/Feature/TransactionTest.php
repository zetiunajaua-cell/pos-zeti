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

it('menolak transaksi jika uang pembayaran kurang dari total tagihan', function () {
    $kasir = User::factory()->kasir()->create();

    $product = Product::factory()->create([
        'stock' => 10,
        'sell_price' => 50000,
    ]);

    $response = $this->actingAs($kasir)->from('/transactions')->post('/transactions', [
        'items' => [
            [
                'product_id' => $product->id,
                'quantity' => 1,
            ],
        ],
        'paid_amount' => 30000, // Kurang dari 50.000
        'payment_method' => 'cash',
    ]);

    $response->assertRedirect('/transactions');
    $response->assertSessionHas('error');
    expect($product->fresh()->stock)->toBe(10);
});

it('dapat menampilkan kembali struk transaksi dan mengunduh format pdf', function () {
    $kasir = User::factory()->kasir()->create();

    $product = Product::factory()->create([
        'stock' => 10,
        'sell_price' => 25000,
    ]);

    $this->actingAs($kasir)->post('/transactions', [
        'items' => [
            [
                'product_id' => $product->id,
                'quantity' => 2,
            ],
        ],
        'paid_amount' => 50000,
        'payment_method' => 'cash',
    ]);

    $transaction = \App\Models\Transaction::latest()->first();

    // Test buka kembali struk (Task 6 & Task 10)
    $viewResponse = $this->actingAs($kasir)->get(route('transactions.show', $transaction));
    $viewResponse->assertOk();
    $viewResponse->assertSee('POS ZETI');
    $viewResponse->assertSee('Selamat Datang di POS ZETI');
    $viewResponse->assertSee('Terima Kasih Atas Kunjungan Anda!');
    $viewResponse->assertSee('Silakan berkunjung kembali');
    $viewResponse->assertSee($transaction->invoice_number);

    // Test stream PDF struk (Task 5 & Task 10)
    $pdfResponse = $this->actingAs($kasir)->get(route('transactions.pdf', $transaction));
    $pdfResponse->assertOk();
    expect($pdfResponse->headers->get('content-type'))->toBe('application/pdf');
});

it('dapat memproses transaksi dengan tipe pesanan, catatan, metode bayar dinamis, dan varian produk', function () {
    $kasir = User::factory()->kasir()->create();

    $paymentMethod = \App\Models\PaymentMethod::create([
        'name' => 'QRIS BCA',
        'code' => 'qris_bca',
        'type' => 'qris',
        'is_active' => true,
    ]);

    $product = Product::factory()->create([
        'stock' => 20,
        'sell_price' => 15000,
    ]);

    $variant = $product->variants()->create([
        'name' => 'Ukuran Large',
        'additional_price' => 5000,
        'is_active' => true,
    ]);

    // Checkout dengan Take Away, Catatan Khusus, QRIS, dan Varian (Tasks 18, 19, 20, 21)
    $response = $this->actingAs($kasir)->post('/transactions', [
        'items' => [
            [
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'note' => 'Gula sedikit, tanpa es batu',
                'quantity' => 2,
            ],
        ],
        'paid_amount' => 40000, // (15.000 + 5.000) * 2 = 40.000
        'payment_method_id' => $paymentMethod->id,
        'order_type' => 'take_away',
        'note' => 'Pesanan Bungkus Cepat',
    ]);

    $response->assertRedirect();

    $transaction = \App\Models\Transaction::latest()->first();
    expect($transaction->total_amount)->toEqual(40000)
        ->and($transaction->order_type)->toBe('take_away')
        ->and($transaction->note)->toBe('Pesanan Bungkus Cepat')
        ->and($transaction->payment_method)->toBe('QRIS BCA')
        ->and($transaction->payment_method_id)->toBe($paymentMethod->id);

    $item = $transaction->items()->first();
    expect($item->variant_name)->toBe('Ukuran Large')
        ->and($item->note)->toBe('Gula sedikit, tanpa es batu')
        ->and((float)$item->price)->toEqual(20000)
        ->and($product->fresh()->stock)->toBe(18);
});

it('dapat melakukan void (pembatalan transaksi) dan mengembalikan stok produk', function () {
    $kasir = User::factory()->kasir()->create();

    $product = Product::factory()->create([
        'stock' => 10,
        'sell_price' => 20000,
    ]);

    $this->actingAs($kasir)->post('/transactions', [
        'items' => [
            [
                'product_id' => $product->id,
                'quantity' => 3,
            ],
        ],
        'paid_amount' => 60000,
        'payment_method' => 'cash',
    ]);

    expect($product->fresh()->stock)->toBe(7);

    $transaction = \App\Models\Transaction::latest()->first();

    // Lakukan VOID (Task 22)
    $response = $this->actingAs($kasir)->post(route('transactions.void', $transaction), [
        'reason' => 'Pelanggan membatalkan pesanan karena buru-buru',
    ]);

    $response->assertRedirect(route('transactions.show', $transaction));
    $response->assertSessionHas('success');

    $freshTx = $transaction->fresh();
    expect($freshTx->status)->toBe('voided')
        ->and($freshTx->void_reason)->toBe('Pelanggan membatalkan pesanan karena buru-buru')
        ->and($freshTx->voided_by)->toBe($kasir->id)
        ->and($product->fresh()->stock)->toBe(10); // Stok kembali!
});

it('dapat melakukan refund (pengembalian dana) dan mencatat riwayat transaksi', function () {
    $kasir = User::factory()->kasir()->create();

    $product = Product::factory()->create([
        'stock' => 15,
        'sell_price' => 25000,
    ]);

    $this->actingAs($kasir)->post('/transactions', [
        'items' => [
            [
                'product_id' => $product->id,
                'quantity' => 2,
            ],
        ],
        'paid_amount' => 50000,
        'payment_method' => 'cash',
    ]);

    expect($product->fresh()->stock)->toBe(13);

    $transaction = \App\Models\Transaction::latest()->first();

    // Lakukan REFUND (Task 17)
    $response = $this->actingAs($kasir)->post(route('transactions.refund', $transaction), [
        'reason' => 'Barang kemasan cacat saat dibuka pelanggan',
        'refund_amount' => 50000,
    ]);

    $response->assertRedirect(route('transactions.show', $transaction));
    $response->assertSessionHas('success');

    $freshTx = $transaction->fresh();
    expect($freshTx->status)->toBe('refunded')
        ->and($freshTx->refund_reason)->toBe('Barang kemasan cacat saat dibuka pelanggan')
        ->and((float)$freshTx->refund_amount)->toEqual(50000)
        ->and($freshTx->refunded_by)->toBe($kasir->id)
        ->and($product->fresh()->stock)->toBe(15); // Stok dikembalikan!
});