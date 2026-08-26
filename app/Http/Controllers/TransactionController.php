<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
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

        $products = Product::with('category')
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

        return view('transactions.index', compact(
            'products',
            'categories',
            'search',
            'categoryId'
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
            | Ambil produk dan cek stok
            |--------------------------------------------------------------------------
            */

            foreach ($request->items as $item) {

                // Mengunci baris produk selama transaksi berlangsung
                $product = Product::lockForUpdate()
                    ->findOrFail($item['product_id']);

                $quantity = (int) $item['quantity'];

                // Cek stok
                if ($product->stock < $quantity) {
                    throw new \Exception(
                        "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}."
                    );
                }

                $subtotal = $product->sell_price * $quantity;

                $totalAmount += $subtotal;

                $cartItems[] = [
                    'product_id' => $product->id,
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $product->sell_price,
                    'subtotal' => $subtotal,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Cek pembayaran
            |--------------------------------------------------------------------------
            */

            $paidAmount = (float) $request->paid_amount;

            if ($paidAmount < $totalAmount) {
                throw new \Exception(
                    'Uang pembayaran kurang dari total transaksi.'
                );
            }

            $changeAmount = $paidAmount - $totalAmount;

            /*
            |--------------------------------------------------------------------------
            | Generate nomor invoice
            |--------------------------------------------------------------------------
            */

            $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' .
                str_pad(
                    Transaction::whereDate('created_at', today())->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );

            /*
            |--------------------------------------------------------------------------
            | Simpan transaksi
            |--------------------------------------------------------------------------
            */

            $transaction = new Transaction();

            $transaction->invoice_number = $invoiceNumber;
            $transaction->user_id = auth()->id();
            $transaction->customer_id = null;
            $transaction->total_amount = $totalAmount;
            $transaction->discount = 0;
            $transaction->tax = 0;
            $transaction->paid_amount = $paidAmount;
            $transaction->change_amount = $changeAmount;
            $transaction->payment_method = 'cash';
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
                | Simpan transaction item
                */
                $transaction->items()->create([
                    'product_id' => $product->id,
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
                    'note' => 'Penjualan ' . $invoiceNumber,
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
            'items.product',
        ]);

        return view(
            'transactions.show',
            compact('transaction')
        );
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