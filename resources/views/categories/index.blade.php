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

    {{-- STATISTIK KATEGORI COMPACT --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Kategori</span>
                <h3 class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mt-1">{{ $totalCategories }}</h3>
                <span class="text-[10px] text-slate-400 font-medium">Jenis kelompok barang</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Produk Terhubung</span>
                <h3 class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-1">
                    {{ $totalProductsConnected }}
                </h3>
                <span class="text-[10px] text-slate-400 font-medium">Item terdaftar dalam kategori</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status Integrasi</span>
                <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">Siap POS</h3>
                <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium">Tersinkron ke kasir</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- TOOLBAR ACTION --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 shadow-xs flex items-center justify-between">
        <div>
            <h2 class="text-sm font-extrabold text-slate-900 dark:text-white leading-tight">Master Kategori Produk</h2>
            <p class="text-xs text-slate-400">Pengelompokan barang untuk memudahkan filtering di terminal kasir.</p>
        </div>

        <a href="{{ route('categories.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition active:scale-98">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Kategori Baru</span>
        </a>
    </div>

    {{-- TABEL DATA KATEGORI --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="py-3.5 px-4 w-16 text-center">No</th>
                        <th class="py-3.5 px-4">Nama Kategori</th>
                        <th class="py-3.5 px-4">Slug URL</th>
                        <th class="py-3.5 px-4 text-center">Total Produk Terhubung</th>
                        <th class="py-3.5 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($categories as $index => $category)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-3 px-4 text-center font-bold text-slate-400">
                                {{ $categories->firstItem() ? $categories->firstItem() + $index : $index + 1 }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-800 dark:text-white text-sm">{{ $category->name }}</span>
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-400 dark:text-slate-400 text-xs">
                                {{ $category->slug }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                                    {{ $category->products_count ?? 0 }} Produk
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('categories.edit', $category) }}"
                                       class="p-1.5 text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-slate-800 rounded-lg transition"
                                       title="Edit Kategori">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <form action="{{ route('categories.destroy', $category) }}"
                                          method="POST"
                                          onsubmit="confirmSubmit(event, 'Kategori {{ addslashes($category->name) }} beserta asosiasinya akan dihapus permanen.', 'Hapus Kategori?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-slate-800 rounded-lg transition"
                                                title="Hapus Kategori">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 mx-auto flex items-center justify-center mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                </div>
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Belum ada kategori terdaftar</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Tambahkan kategori untuk mulai mengelompokkan produk Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

</div>

</x-app-layout>
