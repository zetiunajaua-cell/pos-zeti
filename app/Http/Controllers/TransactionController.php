<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Promotion;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Halaman transaksi kasir
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $categoryId = $request->category_id;

        $products = Product::with(['category', 'variants'])
            ->where('is_active', true)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->get();

        $categories = Category::all();

        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('id')->get();
        if ($paymentMethods->isEmpty()) {
            $paymentMethods = collect([
                (object) ['id' => null, 'name' => 'Tunai (Cash)', 'code' => 'cash', 'type' => 'cash', 'account_number' => null, 'account_name' => null, 'instructions' => null]
            ]);
        }

        $promotions = Promotion::where('is_active', true)->orderBy('name')->get();
        $customers = Customer::where('is_active', true)->orderBy('name')->get();

        $latestTransaction = Transaction::where('status', 'completed')
            ->when(optional(auth()->user()->role)->name !== 'Admin', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest('id')
            ->first();

        $taxSettings = [
            'tax_enabled' => setting('tax_enabled', '0') === '1',
            'tax_name' => setting('tax_name', 'PPN 10%'),
            'tax_type' => setting('tax_type', 'percentage'),
            'tax_rate' => (float) setting('tax_rate', 10),
            'service_charge_enabled' => setting('service_charge_enabled', '0') === '1',
            'service_charge_name' => setting('service_charge_name', 'Biaya Layanan'),
            'service_charge_type' => setting('service_charge_type', 'percentage'),
            'service_charge_rate' => (float) setting('service_charge_rate', 5),
        ];

        // Catat aktivitas pembukaan Terminal Kasir (POS)
        if (auth()->check() && !$request->ajax() && empty($search) && empty($categoryId)) {
            $sessionKey = 'last_terminal_access_log_' . auth()->id();
            $lastAccess = session($sessionKey);
            if (!$lastAccess || now()->diffInSeconds($lastAccess) >= 180) {
                session([$sessionKey => now()]);
                ActivityLog::record(
                    'ACCESS_TERMINAL',
                    auth()->user()->name . ' membuka dan aktif di Terminal Kasir (POS).',
                    null,
                    [
                        'role' => optional(auth()->user()->role)->name ?? 'Kasir',
                        'ip' => $request->ip(),
                    ]
                );
            }
        }

        return view('transactions.index', compact(
            'products',
            'categories',
            'paymentMethods',
            'promotions',
            'customers',
            'search',
            'categoryId',
            'latestTransaction',
            'taxSettings'
        ));
    }

    /**
     * Halaman create diarahkan ke transaksi
     */
    public function create()
    {
        return redirect()->route('transactions.index');
    }

    /**
     * Proses checkout
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => [
                'required',
                'integer',
                'exists:products,id'
            ],
            'items.*.product_variant_id' => [
                'nullable',
                'integer',
                'exists:product_variants,id'
            ],
            'items.*.note' => [
                'nullable',
                'string',
                'max:255'
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1'
            ],
            'paid_amount' => [
                'required',
                'numeric',
                'min:0'
            ],
            'customer_id' => [
                'nullable',
                'exists:customers,id',
            ],
            'promotion_id' => [
                'nullable',
                'exists:promotions,id',
            ],
            'payment_method_id' => [
                'nullable',
                'exists:payment_methods,id'
            ],
            'payment_method' => [
                'nullable',
                'string',
                'max:100'
            ],
            'order_type' => [
                'nullable',
                'string',
                'in:dine_in,take_away'
            ],
            'note' => [
                'nullable',
                'string',
                'max:500'
            ],
        ], [
            'items.required' => 'Keranjang masih kosong.',
            'items.min' => 'Keranjang masih kosong.',
            'items.*.product_id.required' => 'Produk wajib dipilih.',
            'items.*.quantity.required' => 'Jumlah produk wajib diisi.',
            'items.*.quantity.min' => 'Jumlah produk minimal 1.',
            'paid_amount.required' => 'Jumlah pembayaran wajib diisi.',
            'paid_amount.min' => 'Jumlah pembayaran tidak boleh kurang dari 0.',
        ]);

        DB::beginTransaction();

        try {

            $totalAmount = 0;
            $cartItems = [];

            /*
            |--------------------------------------------------------------------------
            | Ambil produk dan cek stok & varian
            |--------------------------------------------------------------------------
            */

            foreach ($request->items as $item) {

                // Mengunci baris produk selama transaksi berlangsung
                $product = Product::lockForUpdate()
                    ->findOrFail($item['product_id']);

                if (!$product->is_active) {
                    throw new \Exception("Produk {$product->name} saat ini sedang tidak aktif.");
                }

                $quantity = (int) $item['quantity'];

                // Cek stok
                if ($product->stock < $quantity) {
                    throw new \Exception(
                        "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}."
                    );
                }

                // Cek varian produk jika ada
                $variant = null;
                $variantAdditionalPrice = 0;
                $variantName = null;

                if (!empty($item['product_variant_id'])) {
                    $variant = ProductVariant::where('product_id', $product->id)->find($item['product_variant_id']);
                    if ($variant) {
                        $variantAdditionalPrice = (float) $variant->additional_price;
                        $variantName = $variant->name;
                    }
                }

                $unitPrice = (float)$product->sell_price + $variantAdditionalPrice;
                $subtotal = $unitPrice * $quantity;

                $totalAmount += $subtotal;

                $cartItems[] = [
                    'product_id' => $product->id,
                    'product' => $product,
                    'product_variant_id' => $variant?->id,
                    'variant_name' => $variantName,
                    'note' => !empty($item['note']) ? trim($item['note']) : null,
                    'quantity' => $quantity,
                    'price' => $unitPrice,
                    'subtotal' => $subtotal,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Hitung Diskon Promo & Membership (Task 25 & 26)
            |--------------------------------------------------------------------------
            */
            $subtotal = $totalAmount;
            $promoDiscount = 0;
            $memberDiscount = 0;
            $customer = null;
            $promotion = null;

            // Diskon Kupon Promo
            if (!empty($request->promotion_id)) {
                $promotion = Promotion::find($request->promotion_id);
                if ($promotion && $promotion->isValidFor($subtotal)) {
                    $promoDiscount = $promotion->calculateDiscount($subtotal);
                }
            }

            // Diskon Membership Pelanggan
            if (!empty($request->customer_id)) {
                $customer = Customer::find($request->customer_id);
                if ($customer && $customer->is_active && $customer->discount_percent > 0) {
                    $memberDiscount = round($subtotal * ((float)$customer->discount_percent / 100));
                }
            }

            $totalDiscount = min($subtotal, round($promoDiscount + $memberDiscount));
            $taxableSubtotal = max(0, $subtotal - $totalDiscount);

            // Hitung Pajak & Biaya Tambahan (Task 29)
            $taxAmount = 0;
            if (setting('tax_enabled', '0') === '1') {
                $taxRate = (float) setting('tax_rate', 0);
                if (setting('tax_type', 'percentage') === 'percentage') {
                    $taxAmount = round($taxableSubtotal * ($taxRate / 100));
                } else {
                    $taxAmount = round($taxRate);
                }
            }

            $serviceChargeAmount = 0;
            if (setting('service_charge_enabled', '0') === '1') {
                $serviceRate = (float) setting('service_charge_rate', 0);
                if (setting('service_charge_type', 'percentage') === 'percentage') {
                    $serviceChargeAmount = round($taxableSubtotal * ($serviceRate / 100));
                } else {
                    $serviceChargeAmount = round($serviceRate);
                }
            }

            $finalTotal = max(0, $taxableSubtotal + $taxAmount + $serviceChargeAmount);

            /*
            |--------------------------------------------------------------------------
            | Cek pembayaran
            |--------------------------------------------------------------------------
            */

            $paidAmount = (float) $request->paid_amount;

            if ($paidAmount < $finalTotal) {
                throw new \Exception(
                    'Uang pembayaran kurang dari total transaksi.'
                );
            }

            $changeAmount = $paidAmount - $finalTotal;

            // Hitung poin loyalitas member
            $pointsEarned = 0;
            if ($customer) {
                $pointsEarned = $customer->calculatePointsEarned($finalTotal);
                if ($pointsEarned > 0) {
                    $customer->increment('points', $pointsEarned);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Generate nomor invoice
            |--------------------------------------------------------------------------
            */

            $prefix = 'INV-' . now()->format('Ymd') . '-';

            $latestTransaction = Transaction::where('invoice_number', 'like', $prefix . '%')
                ->lockForUpdate()
                ->orderByDesc('invoice_number')
                ->first();

            $nextSequence = 1;
            if ($latestTransaction && preg_match('/-(\d+)$/', $latestTransaction->invoice_number, $matches)) {
                $nextSequence = ((int) $matches[1]) + 1;
            }

            $invoiceNumber = $prefix . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);

            // Garansi mutlak tidak akan terjadi duplicate entry
            while (Transaction::where('invoice_number', $invoiceNumber)->exists()) {
                $nextSequence++;
                $invoiceNumber = $prefix . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
            }

            /*
            |--------------------------------------------------------------------------
            | Tentukan nama metode pembayaran dinamis
            |--------------------------------------------------------------------------
            */
            $paymentMethodId = $request->payment_method_id;
            $paymentMethodName = 'cash';

            if (!empty($paymentMethodId)) {
                $pm = PaymentMethod::find($paymentMethodId);
                if ($pm) {
                    $paymentMethodName = $pm->name;
                }
            } elseif (!empty($request->payment_method)) {
                $paymentMethodName = $request->payment_method;
            }

            // Batasi panjang string maksimal 100 karakter sesuai kolom database
            $paymentMethodName = mb_substr($paymentMethodName, 0, 100);

            /*
            |--------------------------------------------------------------------------
            | Simpan transaksi
            |--------------------------------------------------------------------------
            */

            $transaction = new Transaction();

            $transaction->invoice_number = $invoiceNumber;
            $transaction->user_id = auth()->id();
            $transaction->customer_id = $customer?->id;
            $transaction->promotion_id = $promotion?->id;
            $transaction->total_amount = $finalTotal;
            $transaction->discount = $totalDiscount;
            $transaction->tax = $taxAmount;
            $transaction->service_charge = $serviceChargeAmount;
            $transaction->paid_amount = $paidAmount;
            $transaction->change_amount = $changeAmount;
            $transaction->payment_method = $paymentMethodName;
            $transaction->payment_method_id = $paymentMethodId;
            $transaction->order_type = $request->order_type ?? 'dine_in';
            $transaction->note = $request->note;
            $transaction->points_earned = $pointsEarned;
            $transaction->status = 'completed';

            $transaction->save();

            /*
            |--------------------------------------------------------------------------
            | Simpan detail transaksi + stok
            |--------------------------------------------------------------------------
            */

            foreach ($cartItems as $item) {

                $product = $item['product'];

                /*
                | Simpan transaction item dengan varian dan catatan
                */
                $transaction->items()->create([
                    'product_id' => $product->id,
                    'product_variant_id' => $item['product_variant_id'],
                    'variant_name' => $item['variant_name'],
                    'note' => $item['note'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                /*
                | Catat stock movement
                */
                $product->stockMovements()->create([
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'reference_type' => Transaction::class,
                    'reference_id' => $transaction->id,
                    'note' => 'Penjualan ' . $invoiceNumber . ($item['variant_name'] ? ' (' . $item['variant_name'] . ')' : ''),
                ]);

                /*
                | Kurangi stok produk
                */
                $product->decrement('stock', $item['quantity']);
            }

            /*
            |--------------------------------------------------------------------------
            | Semua proses berhasil → simpan permanen
            |--------------------------------------------------------------------------
            */

            DB::commit();

            ActivityLog::record(
                'CHECKOUT',
                "Transaksi {$invoiceNumber} berhasil diproses sebesar Rp " . number_format($finalTotal, 0, ',', '.') . " via {$paymentMethodName}.",
                $transaction,
                ['invoice' => $invoiceNumber, 'total' => $finalTotal, 'items_count' => count($cartItems)]
            );

            return redirect()
                ->route('transactions.show', $transaction)
                ->with(
                    'success',
                    'Transaksi berhasil disimpan.'
                );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Jika terjadi error → batalkan semua perubahan
            |--------------------------------------------------------------------------
            */

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Detail transaksi / struk
     */
    public function show(Transaction $transaction)
    {
        $transaction->load([
            'user',
            'customer',
            'promotion',
            'paymentMethod',
            'voidedBy',
            'refundedBy',
            'items.product',
            'items.variant',
        ]);

        return view(
            'transactions.show',
            compact('transaction')
        );
    }

    /**
     * Cetak Struk Format PDF (Task 5 & 10)
     */
    public function pdf(Transaction $transaction)
    {
        $transaction->load([
            'user',
            'customer',
            'promotion',
            'paymentMethod',
            'voidedBy',
            'refundedBy',
            'items.product',
            'items.variant',
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'transactions.pdf',
            compact('transaction')
        );

        // Kertas thermal roll standar 80mm lebar (~226.77pt)
        $pdf->setPaper([0, 0, 226.77, 650], 'portrait');

        return $pdf->stream('struk-' . $transaction->invoice_number . '.pdf');
    }

    /**
     * Pembatalan Transaksi (Void Transaction - Task 22)
     */
    public function void(Request $request, Transaction $transaction)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ], [
            'reason.required' => 'Alasan pembatalan transaksi (void) wajib diisi.',
        ]);

        if ($transaction->status !== 'completed') {
            return back()->with('error', "Transaksi tidak dapat dibatalkan karena status saat ini adalah {$transaction->status}.");
        }

        DB::beginTransaction();

        try {
            // Kembalikan stok untuk setiap produk
            foreach ($transaction->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('stock', $item->quantity);
                    $product->stockMovements()->create([
                        'type' => 'in',
                        'quantity' => $item->quantity,
                        'reference_type' => Transaction::class,
                        'reference_id' => $transaction->id,
                        'note' => 'Pembatalan (Void) ' . $transaction->invoice_number . ': ' . $request->reason,
                    ]);
                }
            }

            // Tarik kembali poin loyalitas jika transaksi menggunakan member
            if ($transaction->customer_id && $transaction->points_earned > 0) {
                $cust = Customer::find($transaction->customer_id);
                if ($cust) {
                    $deductPoints = min($cust->points, (int)$transaction->points_earned);
                    if ($deductPoints > 0) {
                        $cust->decrement('points', $deductPoints);
                    }
                }
            }

            $transaction->update([
                'status' => 'voided',
                'void_reason' => $request->reason,
                'voided_by' => auth()->id(),
                'voided_at' => now(),
            ]);

            DB::commit();

            ActivityLog::record(
                'VOID_TRANSACTION',
                "Membatalkan (VOID) transaksi {$transaction->invoice_number}. Alasan: {$request->reason}",
                $transaction,
                ['invoice' => $transaction->invoice_number, 'reason' => $request->reason]
            );

            return redirect()
                ->route('transactions.show', $transaction)
                ->with('success', "Transaksi {$transaction->invoice_number} berhasil dibatalkan (VOID) dan stok produk telah dikembalikan.");

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembatalan: ' . $e->getMessage());
        }
    }

    /**
     * Pengembalian Dana (Refund - Task 17)
     */
    public function refund(Request $request, Transaction $transaction)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
            'refund_amount' => ['nullable', 'numeric', 'min:0', 'max:' . $transaction->total_amount],
        ], [
            'reason.required' => 'Alasan pengembalian dana (refund) wajib diisi.',
        ]);

        if ($transaction->status !== 'completed') {
            return back()->with('error', "Transaksi tidak dapat di-refund karena status saat ini adalah {$transaction->status}.");
        }

        DB::beginTransaction();

        try {
            // Kembalikan stok produk ke inventaris
            foreach ($transaction->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('stock', $item->quantity);
                    $product->stockMovements()->create([
                        'type' => 'in',
                        'quantity' => $item->quantity,
                        'reference_type' => Transaction::class,
                        'reference_id' => $transaction->id,
                        'note' => 'Pengembalian Dana (Refund) ' . $transaction->invoice_number . ': ' . $request->reason,
                    ]);
                }
            }

            $refundAmount = $request->filled('refund_amount') ? (float) $request->refund_amount : (float) $transaction->total_amount;

            // Tarik kembali poin loyalitas jika refund
            if ($transaction->customer_id && $transaction->points_earned > 0) {
                $cust = Customer::find($transaction->customer_id);
                if ($cust) {
                    $deductPoints = min($cust->points, (int)$transaction->points_earned);
                    if ($deductPoints > 0) {
                        $cust->decrement('points', $deductPoints);
                    }
                }
            }

            $transaction->update([
                'status' => 'refunded',
                'refund_reason' => $request->reason,
                'refund_amount' => $refundAmount,
                'refunded_by' => auth()->id(),
                'refunded_at' => now(),
            ]);

            DB::commit();

            ActivityLog::record(
                'REFUND_TRANSACTION',
                "Melakukan REFUND transaksi {$transaction->invoice_number} sebesar Rp " . number_format($refundAmount, 0, ',', '.') . ". Alasan: {$request->reason}",
                $transaction,
                ['invoice' => $transaction->invoice_number, 'refund_amount' => $refundAmount, 'reason' => $request->reason]
            );

            return redirect()
                ->route('transactions.show', $transaction)
                ->with('success', "Pengembalian dana (REFUND) sebesar Rp " . number_format($refundAmount, 0, ',', '.') . " berhasil dicatat.");

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses refund: ' . $e->getMessage());
        }
    }

    /**
     * Edit
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update
     */
    public function update(
        Request $request,
        Transaction $transaction
    ) {
        //
    }

    /**
     * Hapus
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}