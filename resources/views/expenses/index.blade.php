<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Laporan Pengeluaran Operasional (Task 27)
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Pencatatan pengeluaran operasional toko seperti pembelian stok, utilitas, gaji, dan biaya lainnya.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('reports.profit_loss') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 transition">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Laba & Rugi (P&L) &rarr;
                </a>
                <a href="{{ route('expenses.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-extrabold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/25 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Catat Pengeluaran Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm font-semibold flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/60 shadow-xs">
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400">Pengeluaran Hari Ini</p>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white mt-1">
                        Rp {{ number_format($todayExpenses, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/60 shadow-xs">
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400">Pengeluaran Bulan Ini</p>
                    <h3 class="text-xl font-black text-rose-600 dark:text-rose-400 mt-1">
                        Rp {{ number_format($thisMonthExpenses, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/60 shadow-xs">
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400">Total Keseluruhan</p>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white mt-1">
                        Rp {{ number_format($totalExpenses, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-sm border border-slate-100 dark:border-slate-700/60">
                <form method="GET" action="{{ route('expenses.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1">Cari Keterangan / No.</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau nomor..."
                               class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1">Kategori Pengeluaran</label>
                        <select name="category" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                   class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md transition self-end">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'category', 'start_date', 'end_date']))
                            <a href="{{ route('expenses.index') }}" class="px-3 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold hover:bg-slate-200 transition self-end">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table of Expenses -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/60 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50/80 dark:bg-slate-900/60 text-[11px] font-black uppercase text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                            <tr>
                                <th class="px-5 py-3.5">No. Pengeluaran</th>
                                <th class="px-5 py-3.5">Tanggal</th>
                                <th class="px-5 py-3.5">Kategori</th>
                                <th class="px-5 py-3.5">Keterangan</th>
                                <th class="px-5 py-3.5 text-right">Nominal (Rp)</th>
                                <th class="px-5 py-3.5">Dicatat Oleh</th>
                                <th class="px-5 py-3.5 text-center">Bukti / Struk</th>
                                <th class="px-5 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($expenses as $exp)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/40 transition">
                                    <td class="px-5 py-4 font-mono font-bold text-slate-800 dark:text-white">
                                        {{ $exp->expense_number }}
                                    </td>
                                    <td class="px-5 py-4 font-medium text-slate-600 dark:text-slate-300">
                                        {{ $exp->expense_date->format('d M Y') }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300">
                                            {{ $exp->category }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 font-semibold text-slate-800 dark:text-white max-w-xs truncate">
                                        {{ $exp->title }}
                                        @if($exp->note)
                                            <p class="text-[10px] font-normal text-slate-400 dark:text-slate-400 truncate">{{ $exp->note }}</p>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right font-black text-rose-600 dark:text-rose-400">
                                        Rp {{ number_format($exp->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-4 text-slate-600 dark:text-slate-400 font-medium">
                                        {{ $exp->user?->name ?? 'Sistem' }}
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        @if($exp->receipt_image_url)
                                            <a href="{{ $exp->receipt_image_url }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-slate-400 text-[10px]">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('expenses.edit', $exp) }}" class="p-1.5 rounded-lg text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/30" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form action="{{ route('expenses.destroy', $exp) }}" method="POST" onsubmit="confirmSubmit(event, 'Catatan pengeluaran senilai Rp {{ number_format($exp->amount, 0, ',', '.') }} akan dihapus.', 'Hapus Pengeluaran?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-lg text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/30" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-10 h-10 mb-2 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400">Belum ada data pengeluaran yang dicatat.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($expenses->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                        {{ $expenses->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
