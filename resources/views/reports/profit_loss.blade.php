<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 print:hidden">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-800">
                        📊 Modul Laporan
                    </span>
                    <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                        Laporan
                    </h2>
                </div>
                <div class="mt-2.5 flex items-center gap-2">
                    <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-blue-700 dark:hover:text-blue-400 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 transition">
                        &larr; Laporan Penjualan
                    </a>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                        Laba & Rugi (P&L)
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                    Laporan perhitungan keuntungan dan kerugian usaha berdasarkan pendapatan, HPP modal produk, dan beban operasional.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="window.print()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-extrabold text-white bg-slate-900 dark:bg-blue-600 hover:bg-slate-800 shadow-md transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Laporan</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Periode (Task 28: Harian, Mingguan, Bulanan, Kustom) -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-sm border border-slate-100 dark:border-slate-700/60 print:hidden">
                <form method="GET" action="{{ route('reports.profit_loss') }}" class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('reports.profit_loss', ['period' => 'today']) }}"
                           class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $period === 'today' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200' }}">
                            Hari Ini
                        </a>
                        <a href="{{ route('reports.profit_loss', ['period' => 'this_week']) }}"
                           class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $period === 'this_week' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200' }}">
                            Minggu Ini
                        </a>
                        <a href="{{ route('reports.profit_loss', ['period' => 'this_month']) }}"
                           class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $period === 'this_month' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200' }}">
                            Bulan Ini
                        </a>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="hidden" name="period" value="custom">
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-bold text-slate-500">Dari:</span>
                            <input type="date" name="start_date" value="{{ $startDate }}"
                                   class="rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-xs font-semibold text-slate-800 dark:text-white py-1.5 px-3">
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-bold text-slate-500">Sampai:</span>
                            <input type="date" name="end_date" value="{{ $endDate }}"
                                   class="rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-xs font-semibold text-slate-800 dark:text-white py-1.5 px-3">
                        </div>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md transition">
                            Terapkan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Print Header -->
            <div class="hidden print:block text-center border-b pb-4 mb-6">
                <h1 class="text-xl font-bold text-slate-900">{{ setting('store_name', 'POS ZETI') }}</h1>
                <p class="text-xs text-slate-600">{{ setting('store_address', '') }} | Telp: {{ setting('store_phone', '') }}</p>
                <h2 class="text-base font-bold text-slate-900 mt-2">LAPORAN LABA DAN RUGI (PROFIT & LOSS)</h2>
                <p class="text-xs text-slate-500">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
            </div>

            <!-- KPI Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Net Sales -->
                <div class="p-5 rounded-3xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/60 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Penjualan Bersih</p>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white mt-1">
                        Rp {{ number_format($netSales, 0, ',', '.') }}
                    </h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">{{ $totalCompletedCount }} Transaksi Berhasil</p>
                </div>

                <!-- Total HPP -->
                <div class="p-5 rounded-3xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/60 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Modal Produk (HPP)</p>
                    <h3 class="text-xl font-black text-amber-600 dark:text-amber-400 mt-1">
                        Rp {{ number_format($totalCogs, 0, ',', '.') }}
                    </h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">Biaya Pokok Pengadaan Barang</p>
                </div>

                <!-- Beban Operasional -->
                <div class="p-5 rounded-3xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/60 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Beban Operasional</p>
                    <h3 class="text-xl font-black text-rose-600 dark:text-rose-400 mt-1">
                        Rp {{ number_format($totalExpenses, 0, ',', '.') }}
                    </h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">{{ $expenses->count() }} Catatan Pengeluaran</p>
                </div>

                <!-- Net Profit -->
                <div class="p-5 rounded-3xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/60 shadow-xs {{ $netProfit >= 0 ? 'bg-gradient-to-br from-emerald-50/50 to-teal-50/20 dark:from-emerald-950/20' : 'bg-gradient-to-br from-rose-50/50 to-orange-50/20 dark:from-rose-950/20' }}">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-black uppercase tracking-wider {{ $netProfit >= 0 ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400' }}">
                            {{ $netProfit >= 0 ? 'Laba Bersih (Untung)' : 'Rugi Bersih' }}
                        </p>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $netProfit >= 0 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300' }}">
                            {{ $netProfit >= 0 ? 'SURPLUS' : 'DEFISIT' }}
                        </span>
                    </div>
                    <h3 class="text-2xl font-black {{ $netProfit >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} mt-1">
                        Rp {{ number_format(abs($netProfit), 0, ',', '.') }}
                    </h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">Setelah dikurangi HPP & Beban</p>
                </div>
            </div>

            <!-- Financial Statement Table (Standard Laporan Laba Rugi) -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/60 p-6 sm:p-8 space-y-6">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Laporan Keuangan Komprehensif</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }} &mdash; {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</p>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-700 text-xs">
                    
                    <!-- 1. PENDAPATAN USAHA -->
                    <div class="py-4 space-y-2">
                        <div class="flex justify-between items-center font-extrabold text-slate-900 dark:text-white text-sm">
                            <span>1. PENDAPATAN PENJUALAN</span>
                            <span></span>
                        </div>
                        <div class="flex justify-between items-center pl-4 text-slate-600 dark:text-slate-300">
                            <span>Penjualan Kotor Produk</span>
                            <span class="font-semibold">Rp {{ number_format($grossProductSales, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pl-4 text-rose-600 dark:text-rose-400">
                            <span>Potongan Diskon & Promo Kupon</span>
                            <span class="font-semibold">- Rp {{ number_format($totalDiscounts, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pl-4 text-rose-600 dark:text-rose-400">
                            <span>Pengembalian Dana Pelanggan (Refund)</span>
                            <span class="font-semibold">- Rp {{ number_format($totalRefunds, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 font-bold text-slate-900 dark:text-white border-t border-dashed border-slate-200 dark:border-slate-700">
                            <span>Total Penjualan Bersih (Net Sales)</span>
                            <span class="font-black text-sm text-blue-600 dark:text-blue-400">Rp {{ number_format($netSales, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- 2. HARGA POKOK PENJUALAN (HPP) -->
                    <div class="py-4 space-y-2">
                        <div class="flex justify-between items-center font-extrabold text-slate-900 dark:text-white text-sm">
                            <span>2. HARGA POKOK PENJUALAN (HPP / MODAL)</span>
                            <span></span>
                        </div>
                        <div class="flex justify-between items-center pl-4 text-slate-600 dark:text-slate-300">
                            <span>Total Harga Beli / Modal Produk Terjual</span>
                            <span class="font-semibold text-rose-600 dark:text-rose-400">- Rp {{ number_format($totalCogs, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 font-bold text-slate-900 dark:text-white border-t border-dashed border-slate-200 dark:border-slate-700">
                            <span>LABA KOTOR (GROSS PROFIT)</span>
                            <span class="font-black text-sm {{ $grossProfit >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600' }}">
                                Rp {{ number_format($grossProfit, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- 3. BEBAN OPERASIONAL -->
                    <div class="py-4 space-y-2">
                        <div class="flex justify-between items-center font-extrabold text-slate-900 dark:text-white text-sm">
                            <span>3. BEBAN OPERASIONAL TOKO (EXPENSES)</span>
                            <span></span>
                        </div>
                        @if($expensesByCategory->isNotEmpty())
                            @foreach($expensesByCategory as $catName => $catAmount)
                                <div class="flex justify-between items-center pl-4 text-slate-600 dark:text-slate-300">
                                    <span>Biaya {{ $catName }}</span>
                                    <span class="font-semibold text-rose-600 dark:text-rose-400">- Rp {{ number_format($catAmount, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="pl-4 text-slate-400 italic">Tidak ada pengeluaran operasional pada periode ini.</div>
                        @endif
                        <div class="flex justify-between items-center pt-2 font-bold text-slate-900 dark:text-white border-t border-dashed border-slate-200 dark:border-slate-700">
                            <span>Total Beban Operasional</span>
                            <span class="font-black text-sm text-rose-600 dark:text-rose-400">- Rp {{ number_format($totalExpenses, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- 4. PAJAK & BIAYA TAMBAHAN -->
                    <div class="py-4 space-y-2">
                        <div class="flex justify-between items-center font-extrabold text-slate-900 dark:text-white text-sm">
                            <span>4. PAJAK & BIAYA LAYANAN DITERIMA</span>
                            <span></span>
                        </div>
                        <div class="flex justify-between items-center pl-4 text-slate-600 dark:text-slate-300">
                            <span>Total Pajak Terkumpul (PPN)</span>
                            <span class="font-semibold">Rp {{ number_format($totalTax, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pl-4 text-slate-600 dark:text-slate-300">
                            <span>Total Biaya Layanan Terkumpul (Service Charge)</span>
                            <span class="font-semibold">Rp {{ number_format($totalServiceCharge, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- 5. LABA / RUGI BERSIH AKHIR -->
                    <div class="py-5 bg-slate-50 dark:bg-slate-900/60 -mx-6 sm:-mx-8 px-6 sm:px-8 flex justify-between items-center">
                        <div>
                            <h4 class="text-base font-black text-slate-900 dark:text-white">LABA / RUGI BERSIH AKHIR (NET PROFIT)</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Laba Kotor dikurangi Total Beban Operasional Toko</p>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-black {{ $netProfit >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                Rp {{ number_format($netProfit, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
