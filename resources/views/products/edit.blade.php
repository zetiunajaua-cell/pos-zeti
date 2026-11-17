<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- HEADER BANNER COMPACT --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 shadow-md p-6 sm:p-8 text-white">
            <div class="absolute right-[-40px] top-[-40px] h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
            <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-xs font-semibold backdrop-blur-xs">
                        <span>✏️</span> Edit Produk
                    </div>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Perbarui Data: {{ $product->name }}
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm text-indigo-100 font-medium">
                        Ubah harga, stok, atau kategori produk sesuai perubahan terbaru di toko.
                    </p>
                </div>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white/20 hover:bg-white/30 text-white font-bold text-xs backdrop-blur-xs transition self-start sm:self-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Katalog</span>
                </a>
            </div>
        </div>

        {{-- ERROR ALERT --}}
        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50/90 p-5 text-rose-800 text-xs font-semibold shadow-xs">
                <div class="flex items-center gap-2 text-sm font-bold mb-2">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Periksa Kembali Data Input:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM CARD FULL WIDTH COMPACT --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    {{-- SKU / BARCODE --}}
                    <div>
                        <label for="sku" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Kode SKU / Barcode <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="sku"
                               id="sku"
                               value="{{ old('sku', $product->sku) }}"
                               required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition {{ $errors->has('sku') ? 'border-rose-400 bg-rose-50/30' : '' }}">
                        @error('sku')
                            <p class="text-[11px] font-semibold text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NAMA PRODUK --}}
                    <div class="lg:col-span-2">
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nama Produk <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $product->name) }}"
                               required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/30' : '' }}">
                        @error('name')
                            <p class="text-[11px] font-semibold text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- KATEGORI --}}
                    <div>
                        <label for="category_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Kategori Produk <span class="text-rose-500">*</span>
                        </label>
                        <select name="category_id"
                                id="category_id"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition {{ $errors->has('category_id') ? 'border-rose-400 bg-rose-50/30' : '' }}">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-[11px] font-semibold text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- SATUAN --}}
                    <div>
                        <label for="unit" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Satuan Unit <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="unit"
                               id="unit"
                               value="{{ old('unit', $product->unit) }}"
                               required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition {{ $errors->has('unit') ? 'border-rose-400 bg-rose-50/30' : '' }}">
                        @error('unit')
                            <p class="text-[11px] font-semibold text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- STOK --}}
                    <div>
                        <label for="stock" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Jumlah Stok <span class="text-rose-500">*</span>
                        </label>
                        <input type="number"
                               name="stock"
                               id="stock"
                               value="{{ old('stock', $product->stock) }}"
                               min="0"
                               required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition {{ $errors->has('stock') ? 'border-rose-400 bg-rose-50/30' : '' }}">
                        @error('stock')
                            <p class="text-[11px] font-semibold text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- BATAS STOK MINIMUM (Task 24) --}}
                    <div>
                        <label for="min_stock" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Batas Stok Minimum <span class="text-amber-500">*</span>
                        </label>
                        <input type="number"
                               name="min_stock"
                               id="min_stock"
                               value="{{ old('min_stock', $product->min_stock ?? 5) }}"
                               min="0"
                               required
                               placeholder="5"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition {{ $errors->has('min_stock') ? 'border-rose-400 bg-rose-50/30' : '' }}">
                        <p class="text-[10px] text-slate-400 mt-1">Peringatan otomatis muncul bila stok mencapai angka ini.</p>
                        @error('min_stock')
                            <p class="text-[11px] font-semibold text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- FOTO PRODUK (Task 23) --}}
                    <div class="md:col-span-2 lg:col-span-3" x-data="{
                        imagePreview: '{{ $product->image_url }}',
                        previewFile(event) {
                            const file = event.target.files[0];
                            if (file) {
                                this.imagePreview = URL.createObjectURL(file);
                            }
                        }
                    }">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Foto Produk (Format JPG, PNG, WEBP, Maks. 2MB)
                        </label>
                        <div class="flex items-center gap-4 p-4 rounded-2xl border border-dashed border-slate-300 bg-slate-50/50 hover:bg-slate-50 transition">
                            <div class="w-16 h-16 rounded-xl border border-slate-200 bg-white flex items-center justify-center overflow-hidden shrink-0 shadow-xs">
                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!imagePreview">
                                    <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </template>
                            </div>
                            <div class="flex-1">
                                <input type="file"
                                       name="image"
                                       id="image"
                                       accept="image/png, image/jpeg, image/jpg, image/webp"
                                       @change="previewFile($event)"
                                       class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                <p class="text-[10px] text-slate-400 mt-1">Pilih berkas gambar baru jika ingin mengganti foto produk.</p>
                            </div>
                        </div>
                        @error('image')
                            <p class="text-[11px] font-semibold text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- HARGA BELI --}}
                    <div>
                        <label for="purchase_price" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Harga Beli (Modal) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-xs font-bold text-slate-400">
                                Rp
                            </span>
                            <input type="number"
                                   name="purchase_price"
                                   id="purchase_price"
                                   value="{{ old('purchase_price', $product->purchase_price) }}"
                                   min="0"
                                   step="100"
                                   required
                                   class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition {{ $errors->has('purchase_price') ? 'border-rose-400 bg-rose-50/30' : '' }}">
                        </div>
                        @error('purchase_price')
                            <p class="text-[11px] font-semibold text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- HARGA JUAL --}}
                    <div>
                        <label for="sell_price" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Harga Jual <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-xs font-bold text-slate-400">
                                Rp
                            </span>
                            <input type="number"
                                   name="sell_price"
                                   id="sell_price"
                                   value="{{ old('sell_price', $product->sell_price) }}"
                                   min="0"
                                   step="100"
                                   required
                                   class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition {{ $errors->has('sell_price') ? 'border-rose-400 bg-rose-50/30' : '' }}">
                        </div>
                        @error('sell_price')
                            <p class="text-[11px] font-semibold text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- STATUS AKTIF --}}
                    <div class="flex items-center pt-6">
                        <label class="inline-flex items-center cursor-pointer select-none">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded-md border-slate-300 text-blue-600 shadow-xs focus:ring-blue-500 transition cursor-pointer">
                            <span class="ms-2.5 text-xs font-bold text-slate-700">
                                Produk Aktif & Dapat Dijual di Kasir
                            </span>
                        </label>
                    </div>

                </div>

                {{-- SECTION VARIAN PRODUK (Task 21) --}}
                <div x-data="{
                    variants: {{ json_encode(old('variants', $product->variants->map(fn($v) => ['name' => $v->name, 'additional_price' => (float)$v->additional_price])->toArray())) }} || [],
                    addVariant() {
                        this.variants.push({ name: '', additional_price: 0 });
                    },
                    removeVariant(index) {
                        this.variants.splice(index, 1);
                    }
                }" class="pt-6 border-t border-slate-100 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Varian Produk (Opsional)</h3>
                            <p class="text-[11px] text-slate-400">Tambahkan pilihan rasa, ukuran, atau jenis (contoh: Cokelat, Stroberi, Vanila, Matcha, Regular, Large).</p>
                        </div>
                        <button type="button"
                                @click="addVariant()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-bold transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Tambah Varian</span>
                        </button>
                    </div>

                    <template x-if="variants.length === 0">
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 p-4 text-center">
                            <p class="text-xs text-slate-400">Belum ada varian untuk produk ini. Klik tombol di atas jika produk memiliki varian rasa/ukuran.</p>
                        </div>
                    </template>

                    <div class="space-y-2.5">
                        <template x-for="(variant, idx) in variants" :key="idx">
                            <div class="flex items-center gap-3 bg-slate-50/80 p-3 rounded-2xl border border-slate-200/80">
                                <div class="flex-1">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nama Varian</label>
                                    <input type="text"
                                           :name="'variants[' + idx + '][name]'"
                                           x-model="variant.name"
                                           placeholder="Misal: Cokelat / Stroberi / Large"
                                           required
                                           class="w-full px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="w-44">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Tambahan Harga (+Rp)</label>
                                    <input type="number"
                                           :name="'variants[' + idx + '][additional_price]'"
                                           x-model="variant.additional_price"
                                           placeholder="0"
                                           min="0"
                                           class="w-full px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="pt-5">
                                    <button type="button"
                                            @click="removeVariant(idx)"
                                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition"
                                            title="Hapus Varian">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- BUTTONS --}}
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('products.index') }}"
                       class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 active:scale-98 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Perbarui Produk</span>
                    </button>
                </div>

            </form>
        </div>

    </div>
</x-app-layout>
