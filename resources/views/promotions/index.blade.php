<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">
                    Kelola Diskon & Promo
                </h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">
                    Atur kode kupon diskon nominal maupun persentase untuk kasir POS.
                </p>
            </div>
            <a href="{{ route('promotions.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition active:scale-98">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Promo Baru</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 font-black">✕</button>
            </div>
        @endif

        {{-- METRIC CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Promo</span>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-1">{{ $totalPromos }}</h3>
                    <span class="text-[10px] text-slate-400 font-medium">Semua kupon promosi</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Promo Aktif</span>
                    <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ $activePromos }}</h3>
                    <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium font-bold">Bisa digunakan di kasir</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Digunakan</span>
                    <h3 class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mt-1">{{ $totalUsage }} <span class="text-sm font-semibold text-slate-400">kali</span></h3>
                    <span class="text-[10px] text-slate-400 font-medium">Dalam transaksi selesai</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
        </div>

        {{-- FILTER & PENCARIAN --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 shadow-xs">
            <form action="{{ route('promotions.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[220px]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari kode atau nama promo..."
                           class="w-full pl-9 pr-4 py-2 text-xs font-medium rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                </div>

                <select name="type"
                        onchange="this.form.submit()"
                        class="py-2 px-3 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                    <option value="" class="dark:bg-slate-800 dark:text-white">Semua Tipe Diskon</option>
                    <option value="percentage" class="dark:bg-slate-800 dark:text-white" {{ request('type') === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                    <option value="fixed" class="dark:bg-slate-800 dark:text-white" {{ request('type') === 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                </select>

                <select name="status"
                        onchange="this.form.submit()"
                        class="py-2 px-3 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                    <option value="" class="dark:bg-slate-800 dark:text-white">Semua Status</option>
                    <option value="active" class="dark:bg-slate-800 dark:text-white" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" class="dark:bg-slate-800 dark:text-white" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
                    Filter
                </button>

                @if(request('search') || request('type') || request('status'))
                    <a href="{{ route('promotions.index') }}"
                       class="px-3 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- TABEL PROMO --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                            <th class="py-3.5 px-4">Kode Promo</th>
                            <th class="py-3.5 px-4">Nama Promo</th>
                            <th class="py-3.5 px-4">Potongan Diskon</th>
                            <th class="py-3.5 px-4">Min. Belanja</th>
                            <th class="py-3.5 px-4">Periode Berlaku</th>
                            <th class="py-3.5 px-4 text-center">Dipakai</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($promotions as $promo)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-950/50 border border-blue-200/60 dark:border-blue-800 text-blue-700 dark:text-blue-300 font-mono font-black text-xs">
                                        <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                        <span>{{ $promo->code }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-white">
                                    {{ $promo->name }}
                                </td>
                                <td class="py-3.5 px-4 font-bold">
                                    @if($promo->type === 'percentage')
                                        <span class="text-indigo-700 dark:text-indigo-400 font-black">{{ (float)$promo->value }}%</span>
                                        @if($promo->max_discount)
                                            <span class="text-[10px] text-slate-400 font-normal block">Maks Rp {{ number_format($promo->max_discount, 0, ',', '.') }}</span>
                                        @endif
                                    @else
                                        <span class="text-emerald-700 dark:text-emerald-400 font-black">Rp {{ number_format($promo->value, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300">
                                    @if($promo->min_purchase > 0)
                                        Rp {{ number_format($promo->min_purchase, 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-400">Tanpa min.</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400 text-[11px]">
                                    @if($promo->start_date && $promo->end_date)
                                        {{ $promo->start_date->format('d M Y') }} s/d {{ $promo->end_date->format('d M Y') }}
                                    @elseif($promo->start_date)
                                        Mulai {{ $promo->start_date->format('d M Y') }}
                                    @elseif($promo->end_date)
                                        Hingga {{ $promo->end_date->format('d M Y') }}
                                    @else
                                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold">Berlaku Selamanya</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ $promo->transactions_count ?? 0 }}x
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <form action="{{ route('promotions.toggle', $promo) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold transition {{ $promo->is_active ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-200' }}"
                                                title="Klik untuk ubah status">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $promo->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                            {{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('promotions.edit', $promo) }}"
                                           class="p-1.5 text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-slate-800 rounded-lg transition"
                                           title="Edit Promo">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        <form action="{{ route('promotions.destroy', $promo) }}"
                                              method="POST"
                                              onsubmit="confirmSubmit(event, 'Promo diskon dengan kode {{ addslashes($promo->code) }} akan dihapus.', 'Hapus Promo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-1.5 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-slate-800 rounded-lg transition"
                                                    title="Hapus Promo">
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
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    </div>
                                    <p class="font-bold text-slate-600 dark:text-slate-300">Belum ada data promo</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Tambahkan promo untuk memberikan potongan harga kepada pelanggan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($promotions->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                    {{ $promotions->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>

