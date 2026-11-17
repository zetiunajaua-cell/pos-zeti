<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $categoryId = request('category_id');

        $products = Product::with(['category', 'variants'])
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%")
                      ->orWhereHas('category', function ($catQuery) use ($search) {
                          $catQuery->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories    = Category::orderBy('name')->get();
        $totalProduct  = Product::count();
        $totalActive   = Product::where('is_active', true)->count();
        $totalCategory = Category::count();
        $totalStock    = Product::sum('stock');
        $totalLowStock = Product::where('is_active', true)->whereColumn('stock', '<=', 'min_stock')->count();

        return view('products.index', compact(
            'products',
            'search',
            'categoryId',
            'categories',
            'totalProduct',
            'totalActive',
            'totalCategory',
            'totalStock',
            'totalLowStock'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->safe()->except(['variants', 'image']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['min_stock'] = $request->input('min_stock', 5);

        $product = Product::create($data);

        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (!empty(trim($variantData['name'] ?? ''))) {
                    $product->variants()->create([
                        'name' => trim($variantData['name']),
                        'additional_price' => $variantData['additional_price'] ?? 0,
                        'is_active' => true,
                    ]);
                }
            }
        }

        ActivityLog::record(
            'CREATE_PRODUCT',
            "Menambahkan produk baru '{$product->name}' (SKU: {$product->sku})",
            $product,
            ['price' => $product->price, 'stock' => $product->stock]
        );

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('variants');
        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->safe()->except(['variants', 'image']);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['min_stock'] = $request->input('min_stock', 5);

        $product->update($data);

        $product->variants()->delete();
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (!empty(trim($variantData['name'] ?? ''))) {
                    $product->variants()->create([
                        'name' => trim($variantData['name']),
                        'additional_price' => $variantData['additional_price'] ?? 0,
                        'is_active' => true,
                    ]);
                }
            }
        }

        ActivityLog::record(
            'UPDATE_PRODUCT',
            "Memperbarui data produk '{$product->name}' (SKU: {$product->sku})",
            $product,
            ['price' => $product->price, 'stock' => $product->stock]
        );

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->transactionItems()->exists()) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Produk tidak dapat dihapus karena sudah memiliki riwayat transaksi.');
        }

        $prodName = $product->name;
        $prodSku = $product->sku;

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        ActivityLog::record(
            'DELETE_PRODUCT',
            "Menghapus produk '{$prodName}' (SKU: {$prodSku})",
            null,
            ['sku' => $prodSku]
        );

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}