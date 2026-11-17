<x-app-layout>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- Notifikasi Sukses / Gagal --}}
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 text-emerald-800 text-xs font-bold flex items-center gap-2.5 shadow-xs">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3.5 text-rose-800 text-xs font-bold flex items-center gap-2.5 shadow-xs">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- HEADER & STATISTIK COMPACT (DENGAN INDIKATOR STOK MENIPIS - Task 24) --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Produk</span>
                <h3 class="text-xl font-black text-slate-900 dark:text-white mt-1">{{ $totalProduct }}</h3>
                <span class="text-[9px] text-slate-400 font-medium">Dalam katalog</span>
            </div>
            <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Produk Aktif</span>
                <h3 class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ $totalActive }}</h3>
                <span class="text-[9px] text-emerald-600 dark:text-emerald-400 font-medium">Siap ditransaksikan</span>
            </div>
            <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kategori</span>
                <h3 class="text-xl font-black text-indigo-600 dark:text-indigo-400 mt-1">{{ $totalCategory }}</h3>
                <span class="text-[9px] text-slate-400 font-medium">Kelompok barang</span>
            </div>
            <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Fisik Stok</span>
                <h3 class="text-xl font-black text-slate-700 dark:text-slate-200 mt-1">{{ number_format($totalStock, 0, ',', '.') }}</h3>
                <span class="text-[9px] text-slate-400 font-medium">Unit gudang</span>
            </div>
            <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
        </div>

        {{-- KARTU ALERT STOK MENIPIS (Task 24) --}}
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border {{ ($totalLowStock ?? 0) > 0 ? 'border-amber-300 dark:border-amber-800 bg-amber-50/20 dark:bg-amber-950/20' : 'border-slate-200/80 dark:border-slate-800' }} shadow-xs flex items-center justify-between col-span-2 sm:col-span-1">
            <div>
                <span class="text-[10px] font-bold {{ ($totalLowStock ?? 0) > 0 ? 'text-amber-700 dark:text-amber-300' : 'text-slate-400' }} uppercase tracking-wider">Stok Menipis</span>
                <h3 class="text-xl font-black {{ ($totalLowStock ?? 0) > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-700 dark:text-slate-200' }} mt-1">{{ $totalLowStock ?? 0 }}</h3>
                <span class="text-[9px] {{ ($totalLowStock ?? 0) > 0 ? 'text-amber-600 dark:text-amber-400 font-bold' : 'text-slate-400' }} font-medium">Perlu restock</span>
            </div>
            <div class="w-9 h-9 rounded-xl {{ ($totalLowStock ?? 0) > 0 ? 'bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }} flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>
    </div>

    {{-- TOOLBAR PENCARIAN & ACTION --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <form action="{{ route('products.index') }}" method="GET" class="flex flex-wrap items-center gap-2 flex-1 max-w-2xl">
            <div class="relative flex-1 min-w-[200px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari SKU atau Nama Produk..."
                       class="w-full pl-9 pr-8 py-2 text-xs font-medium rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                @if(request('search'))
                    <a href="{{ route('products.index', ['category_id' => request('category_id')]) }}"
                       class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs font-bold text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                        ✕
                    </a>
                @endif
            </div>

            <!-- Dropdown Filter Kategori (Task 7) -->
            <select name="category_id"
                    onchange="this.form.submit()"
                    class="py-2 px-3 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                <option value="" class="dark:bg-slate-800 dark:text-white">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" class="dark:bg-slate-800 dark:text-white" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
                Filter
            </button>
            @if(request('search') || request('category_id'))
                <a href="{{ route('products.index') }}"
                   class="px-3 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl transition">
                    Reset
                </a>
            @endif
        </form>

        <a href="{{ route('products.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition active:scale-98">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Produk Baru</span>
        </a>
    </div>

    {{-- TABEL DATA PRODUK --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="py-3.5 px-4">SKU</th>
                        <th class="py-3.5 px-4">Nama Produk</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4 text-right">Harga Modal</th>
                        <th class="py-3.5 px-4 text-right">Harga Jual</th>
                        <th class="py-3.5 px-4 text-center">Stok</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-3 px-4 font-mono font-bold text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                {{ $product->sku }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shrink-0 flex items-center justify-center">
                                        @if($product->image_url)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-5 h-5 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-slate-800 dark:text-white text-xs block truncate">{{ $product->name }}</span>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-[10px] text-slate-400 font-mono">{{ $product->unit }}</span>
                                            @if($product->variants->count() > 0)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-extrabold bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800" title="{{ $product->variants->pluck('name')->implode(', ') }}">
                                                    {{ $product->variants->count() }} Varian
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-block px-2.5 py-1 rounded-lg text-[11px] font-bold bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800">
                                    {{ $product->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-medium text-slate-500 dark:text-slate-400">
                                Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right font-black text-slate-900 dark:text-white text-xs">
                                Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($product->isOutOfStock())
                                    <div class="inline-flex flex-col items-center">
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                            0 {{ $product->unit }}
                                        </span>
                                        <span class="text-[9px] font-bold text-rose-600 dark:text-rose-400 mt-0.5">Stok Habis</span>
                                    </div>
                                @elseif($product->isLowStock())
                                    <div class="inline-flex flex-col items-center">
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                            {{ $product->stock }} {{ $product->unit }}
                                        </span>
                                        <span class="text-[9px] font-extrabold text-amber-700 dark:text-amber-400 mt-0.5 flex items-center gap-0.5">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/></svg>
                                            Menipis (Min: {{ $product->min_stock }})
                                        </span>
                                    </div>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ $product->stock }} {{ $product->unit }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                @if($product->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="p-1.5 text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-slate-800 rounded-lg transition"
                                       title="Edit Produk">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <form action="{{ route('products.destroy', $product) }}"
                                          method="POST"
                                          onsubmit="confirmSubmit(event, 'Produk {{ addslashes($product->name) }} (SKU: {{ $product->sku }}) akan dihapus permanen.', 'Hapus Produk?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-slate-800 rounded-lg transition"
                                                title="Hapus Produk">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 mx-auto flex items-center justify-center mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Tidak ada produk ditemukan</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Silakan tambahkan produk baru atau ubah kata kunci pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINASI BAWAH --}}
        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>

</x-app-layout>
