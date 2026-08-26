<div class="mb-4">
    <label>SKU</label>
    <input
        type="text"
        name="sku"
        class="border rounded w-full p-2"
        value="{{ old('sku', $product->sku ?? '') }}">

    @error('sku')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label>Nama Produk</label>
    <input
        type="text"
        name="name"
        class="border rounded w-full p-2"
        value="{{ old('name', $product->name ?? '') }}">

    @error('name')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label>Kategori</label>
    <select
        name="category_id"
        class="border rounded w-full p-2">

        <option value="">-- Pilih Kategori --</option>

        @foreach($categories as $category)
            <option
                value="{{ $category->id }}"
                {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach

    </select>

    @error('category_id')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label>Harga Beli</label>
    <input
        type="number"
        name="purchase_price"
        class="border rounded w-full p-2"
        value="{{ old('purchase_price', $product->purchase_price ?? '') }}">

    @error('purchase_price')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label>Harga Jual</label>
    <input
        type="number"
        name="sell_price"
        class="border rounded w-full p-2"
        value="{{ old('sell_price', $product->sell_price ?? '') }}">

    @error('sell_price')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label>Stok</label>
    <input
        type="number"
        name="stock"
        class="border rounded w-full p-2"
        value="{{ old('stock', $product->stock ?? '') }}">

    @error('stock')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label>Satuan</label>
    <input
        type="text"
        name="unit"
        class="border rounded w-full p-2"
        value="{{ old('unit', $product->unit ?? 'pcs') }}">

    @error('unit')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-6">
    <label>
        <input
            type="checkbox"
            name="is_active"
            value="1"
            {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
        Produk Aktif
    </label>
</div>