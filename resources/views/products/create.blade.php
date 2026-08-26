<x-app-layout>

    <div class="w-full px-8 py-8 flex justify-center">

        <div class="w-full max-w-5xl">

            {{-- KOTAK PERINGATAN MERAH DI ATAS --}}
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-6 text-red-700 shadow-sm">
                    <div class="flex items-center gap-2 text-xl font-black mb-2">
                        ⚠️ Terjadi Kesalahan Validasi!
                    </div>

                    <ul class="list-disc list-inside text-base font-semibold space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- HEADER CARD --}}
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 shadow-xl mb-8 p-8 md:p-10 text-white">

                <div class="absolute right-[-40px] top-[-40px] h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>

                <div class="relative flex items-center justify-between">

                    <div>

                        <span class="inline-flex items-center rounded-full bg-white/20 px-4 py-1.5 text-xs font-semibold tracking-wide">
                            📦 Product Management
                        </span>

                        <h1 class="mt-3 text-3xl md:text-5xl font-black">
                            Tambah Produk
                        </h1>

                        <p class="mt-2 text-lg text-indigo-100 font-medium">
                            Tambahkan produk baru ke dalam sistem toko.
                        </p>

                    </div>

                    <div class="hidden sm:flex h-24 w-24 items-center justify-center rounded-3xl bg-white/10 text-5xl">
                        ➕
                    </div>

                </div>

            </div>

            {{-- FORM CARD --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

                <form action="{{ route('products.store') }}" method="POST" novalidate class="p-8 md:p-12 space-y-8">

                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        {{-- KODE SKU --}}
                        <div class="space-y-3">

                            <label for="sku" class="block text-base font-extrabold text-gray-800 uppercase tracking-wider">
                                KODE SKU <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="sku"
                                id="sku"
                                value="{{ old('sku') }}"
                                placeholder="Contoh: SKU98233"
                                class="block w-full rounded-2xl border-2 px-5 py-4 text-lg font-semibold text-gray-800 placeholder-gray-400 focus:outline-none transition duration-150 {{ $errors->has('sku') ? 'border-red-500 bg-red-50/30 focus:border-red-500 focus:ring-4 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100' }}"
                            >

                            @error('sku')
                                <p class="text-base font-bold text-red-600 mt-2 flex items-center gap-1.5">
                                    🚨 {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- NAMA PRODUK --}}
                        <div class="space-y-3">

                            <label for="name" class="block text-base font-extrabold text-gray-800 uppercase tracking-wider">
                                NAMA PRODUK <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                placeholder="Contoh: Nescafe Ice Coffee"
                                class="block w-full rounded-2xl border-2 px-5 py-4 text-lg font-semibold text-gray-800 placeholder-gray-400 focus:outline-none transition duration-150 {{ $errors->has('name') ? 'border-red-500 bg-red-50/30 focus:border-red-500 focus:ring-4 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100' }}"
                            >

                            @error('name')
                                <p class="text-base font-bold text-red-600 mt-2 flex items-center gap-1.5">
                                    🚨 {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- KATEGORI PRODUK --}}
                        <div class="space-y-3">

                            <label for="category_id" class="block text-base font-extrabold text-gray-800 uppercase tracking-wider">
                                KATEGORI PRODUK <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="category_id"
                                id="category_id"
                                class="block w-full rounded-2xl border-2 px-5 py-4 text-lg font-semibold text-gray-800 focus:outline-none transition duration-150 {{ $errors->has('category_id') ? 'border-red-500 bg-red-50/30 focus:border-red-500 focus:ring-4 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100' }}"
                            >

                                <option value="">-- Pilih Kategori --</option>

                                @foreach($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}
                                    >
                                        {{ $category->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('category_id')
                                <p class="text-base font-bold text-red-600 mt-2 flex items-center gap-1.5">
                                    🚨 {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- SATUAN --}}
                        <div class="space-y-3">

                            <label for="unit" class="block text-base font-extrabold text-gray-800 uppercase tracking-wider">
                                SATUAN <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="unit"
                                id="unit"
                                value="{{ old('unit', 'pcs') }}"
                                placeholder="pcs / botol / porsi / pack"
                                class="block w-full rounded-2xl border-2 px-5 py-4 text-lg font-semibold text-gray-800 placeholder-gray-400 focus:outline-none transition duration-150 {{ $errors->has('unit') ? 'border-red-500 bg-red-50/30 focus:border-red-500 focus:ring-4 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100' }}"
                            >

                            @error('unit')
                                <p class="text-base font-bold text-red-600 mt-2 flex items-center gap-1.5">
                                    🚨 {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- HARGA BELI --}}
                        <div class="space-y-3">

                            <label for="purchase_price" class="block text-base font-extrabold text-gray-800 uppercase tracking-wider">
                                HARGA BELI (MODAL) <span class="text-red-500">*</span>
                            </label>

                            <div class="relative rounded-2xl shadow-sm">

                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-5 text-lg font-black text-gray-400">
                                    Rp
                                </div>

                                <input
                                    type="number"
                                    name="purchase_price"
                                    id="purchase_price"
                                    value="{{ old('purchase_price') }}"
                                    placeholder="0"
                                    class="block w-full rounded-2xl border-2 pl-14 pr-5 py-4 text-lg font-semibold text-gray-800 placeholder-gray-400 focus:outline-none transition duration-150 {{ $errors->has('purchase_price') ? 'border-red-500 bg-red-50/30 focus:border-red-500 focus:ring-4 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100' }}"
                                >

                            </div>

                            @error('purchase_price')
                                <p class="text-base font-bold text-red-600 mt-2 flex items-center gap-1.5">
                                    🚨 {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- HARGA JUAL --}}
                        <div class="space-y-3">

                            <label for="sell_price" class="block text-base font-extrabold text-gray-800 uppercase tracking-wider">
                                HARGA JUAL <span class="text-red-500">*</span>
                            </label>

                            <div class="relative rounded-2xl shadow-sm">

                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-5 text-lg font-black text-gray-400">
                                    Rp
                                </div>

                                <input
                                    type="number"
                                    name="sell_price"
                                    id="sell_price"
                                    value="{{ old('sell_price') }}"
                                    placeholder="0"
                                    class="block w-full rounded-2xl border-2 pl-14 pr-5 py-4 text-lg font-semibold text-gray-800 placeholder-gray-400 focus:outline-none transition duration-150 {{ $errors->has('sell_price') ? 'border-red-500 bg-red-50/30 focus:border-red-500 focus:ring-4 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100' }}"
                                >

                            </div>

                            @error('sell_price')
                                <p class="text-base font-bold text-red-600 mt-2 flex items-center gap-1.5">
                                    🚨 {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- STOK AWAL --}}
                        <div class="space-y-3 md:col-span-2">

                            <label for="stock" class="block text-base font-extrabold text-gray-800 uppercase tracking-wider">
                                JUMLAH STOK <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="number"
                                name="stock"
                                id="stock"
                                value="{{ old('stock') }}"
                                placeholder="0"
                                min="0"
                                class="block w-full rounded-2xl border-2 px-5 py-4 text-lg font-semibold text-gray-800 placeholder-gray-400 focus:outline-none transition duration-150 {{ $errors->has('stock') ? 'border-red-500 bg-red-50/30 focus:border-red-500 focus:ring-4 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100' }}"
                            >

                            @error('stock')
                                <p class="text-base font-bold text-red-600 mt-2 flex items-center gap-1.5">
                                    🚨 {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="pt-8 border-t border-gray-100 flex items-center justify-end gap-4">

                        <a
                            href="{{ route('products.index') }}"
                            class="px-8 py-4 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-lg font-extrabold transition duration-150 flex items-center gap-2"
                        >
                            ⬅️ Kembali
                        </a>

                        <button
                            type="submit"
                            class="px-10 py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-extrabold shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transition duration-150 flex items-center gap-2"
                        >
                            💾 Simpan Produk
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>