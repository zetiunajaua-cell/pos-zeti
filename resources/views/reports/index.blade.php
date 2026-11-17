<x-app-layout>
    <!-- CSS Khusus Mode Cetak (Print Stylesheet) -->
    <style>
        @media print {
            body {
                background-color: #ffffff !important;
                color: #000000 !important;
            }
            nav, header, .no-print, button, form {
                display: none !important;
            }
            .print-only-title {
                display: block !important;
                margin-bottom: 20px;
                text-align: center;
            }
            .shadow-sm, .shadow-xl, .shadow-2xl {
                box-shadow: none !important;
            }
            .border {
                border-color: #e2e8f0 !important;
            }
        }
        .print-only-title {
            display: none;
        }
    </style>

    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- JUDUL HANYA MUNCUL SAAT DICETAK -->
            <div class="print-only-title">
                <h1 class="text-2xl font-bold">LAPORAN PENJUALAN POS</h1>
                <p class="text-sm text-gray-600">Periode: {{ Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                <hr class="mt-4 border-gray-300">
            </div>

            <!-- HEADER UTAMA & PENANDA BAGIAN LAPORAN (Task 39) -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 no-print">
                <div>
                    <div class="flex items-center gap-2.5">
                        <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 border border-blue-200/80 dark:border-blue-800">
                            📊 Modul Laporan
                        </span>
                        <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                            Laporan
                        </h1>
                    </div>
                    <p class="text-xs text-slate-400 dark:text-slate-400 font-medium mt-1">Pantau tinjauan omset dan riwayat transaksi toko Anda</p>
                    <div class="mt-3 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/40 border border-blue-200 dark:border-blue-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            Laporan Penjualan
                        </span>
                        <a href="{{ route('reports.profit_loss') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-emerald-700 dark:hover:text-emerald-400 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Laba & Rugi (P&L) &rarr;
                        </a>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Preset Cepat:</span>
                        <a href="{{ route('reports.index', ['start_date' => now()->toDateString(), 'end_date' => now()->toDateString()]) }}"
                           class="px-2.5 py-1 rounded-lg text-[11px] font-bold {{ $startDate === now()->toDateString() && $endDate === now()->toDateString() ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                            Hari Ini
                        </a>
                        <a href="{{ route('reports.index', ['start_date' => now()->subDays(6)->toDateString(), 'end_date' => now()->toDateString()]) }}"
                           class="px-2.5 py-1 rounded-lg text-[11px] font-bold {{ $startDate === now()->subDays(6)->toDateString() && $endDate === now()->toDateString() ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                            7 Hari Terakhir
                        </a>
                        <a href="{{ route('reports.index', ['start_date' => now()->startOfMonth()->toDateString(), 'end_date' => now()->toDateString()]) }}"
                           class="px-2.5 py-1 rounded-lg text-[11px] font-bold {{ $startDate === now()->startOfMonth()->toDateString() && $endDate === now()->toDateString() ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                            Bulan Ini
                        </a>
                    </div>

                    <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-center gap-2.5">
                        <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-800 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700">
                            <label for="start_date" class="text-[11px] font-semibold text-slate-400">Dari:</label>
                            <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="border-0 bg-transparent text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-0 p-0">
                        </div>
                        <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-800 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700">
                            <label for="end_date" class="text-[11px] font-semibold text-slate-400">Sampai:</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="border-0 bg-transparent text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-0 p-0">
                        </div>

                        {{-- Dropdown Filter Status Transaksi (Task 17 & 22) --}}
                        <div class="flex items-center bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 px-2.5 py-1">
                            <select name="status" class="border-0 bg-transparent text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-0 py-0.5 pl-0 pr-6">
                                <option value="all" class="dark:bg-slate-800 dark:text-white" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="completed" class="dark:bg-slate-800 dark:text-white" {{ ($status ?? '') === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                                <option value="refunded" class="dark:bg-slate-800 dark:text-white" {{ ($status ?? '') === 'refunded' ? 'selected' : '' }}>Refund (Pengembalian)</option>
                                <option value="voided" class="dark:bg-slate-800 dark:text-white" {{ ($status ?? '') === 'voided' ? 'selected' : '' }}>Void (Dibatalkan)</option>
                            </select>
                        </div>

                        {{-- Dropdown Filter Kasir (Task 7 & 16) --}}
                        <div class="flex items-center bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 px-2.5 py-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <select name="user_id" class="border-0 bg-transparent text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-0 py-0.5 pl-0 pr-6">
                                <option value="" class="dark:bg-slate-800 dark:text-white">Semua Kasir</option>
                                @foreach($cashiers as $c)
                                    <option value="{{ $c->id }}" class="dark:bg-slate-800 dark:text-white" {{ (string)$userId === (string)$c->id ? 'selected' : '' }}>
                                        {{ $c->name }} ({{ $c->role->name ?? 'User' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Input Pencarian Keyword (Task 16) --}}
                        <div class="relative">
                            <input type="text"
                                   name="search"
                                   value="{{ $search ?? '' }}"
                                   placeholder="Cari No. Struk..."
                                   class="text-xs font-medium pl-8 pr-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-blue-500 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500">
                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>

                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5 active:scale-98">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            <span>Filter</span>
                        </button>

                        @if(!empty($userId) || !empty($search) || request()->has('start_date'))
                            <a href="{{ route('reports.index') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl transition flex items-center gap-1" title="Reset Filter">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Reset</span>
                            </a>
                        @endif

                        <!-- TOMBOL CETAK LAPORAN -->
                        <button type="button" onclick="window.print()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5 active:scale-98">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Cetak</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- 3 CARD SUMMARY (DISAMAKAN DENGAN TAMPILAN DASHBOARD/PRODUK) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- CARD 1: TOTAL PENJUALAN BERSIH -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex justify-between items-start">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">TOTAL PENJUALAN BERSIH</span>
                        <h3 class="text-3xl font-black text-slate-800 dark:text-white mt-2 tracking-tight">Rp {{ number_format($netSales ?? $totalSales, 0, ',', '.') }}</h3>
                        <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold mt-2 flex items-center gap-1.5">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            @if(($totalRefund ?? 0) > 0)
                                Omset Kotor: Rp {{ number_format($grossSales, 0, ',', '.') }} &bull; Refund: -Rp {{ number_format($totalRefund, 0, ',', '.') }}
                            @else
                                Total omset penjualan terverifikasi
                            @endif
                        </p>
                    </div>
                    <div class="p-3 bg-blue-50 dark:bg-blue-950/50 rounded-2xl text-blue-600 dark:text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- CARD 2: JUMLAH TRANSAKSI SELESAI -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex justify-between items-start">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">TRANSAKSI SELESAI</span>
                        <h3 class="text-3xl font-black text-slate-800 dark:text-white mt-2 tracking-tight">{{ $totalCompleted ?? $totalTransactions }} <span class="text-base font-semibold text-slate-500 dark:text-slate-400">Transaksi</span></h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-2">
                            {{ $totalRefundCount ?? 0 }} Refund &bull; {{ $totalVoidCount ?? 0 }} Void
                        </p>
                    </div>
                    <div class="p-3 bg-blue-50 dark:bg-blue-950/50 rounded-2xl text-blue-600 dark:text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- CARD 3: RATA-RATA TRANSAKSI -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex justify-between items-start">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">RATA-RATA TRANSAKSI</span>
                        <h3 class="text-3xl font-black text-slate-800 dark:text-white mt-2 tracking-tight">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-2">Average basket size</p>
                    </div>
                    <div class="p-3 bg-blue-50 dark:bg-blue-950/50 rounded-2xl text-blue-600 dark:text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 30a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zM5 11a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H6a1 1 0 01-1-1v-2zM11 5a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V5z"/></svg>
                    </div>
                </div>
            </div>

            <!-- GRAFIK TREN PENJUALAN HARIAN -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white">Tren Penjualan Harian</h3>
                        <p class="text-[11px] text-slate-400">Pergerakan nominal penjualan harian (Rp)</p>
                    </div>
                </div>
                <div class="w-full h-64">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- TABEL DETAIL TRANSAKSI -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Detail Transaksi</h3>
                    <p class="text-[11px] text-slate-400">Rincian seluruh item transaksi pada rentang tanggal terpilih</p>
                </div>
                <div class="p-6">
                    @if ($transactions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-[11px] font-extrabold text-slate-400 uppercase border-b border-slate-100 dark:border-slate-800 tracking-wider">
                                        <th class="pb-4 px-4">NO. STRUK / INVOICE</th>
                                        <th class="pb-4 px-4">TANGGAL & WAKTU</th>
                                        <th class="pb-4 px-4">TIPE & KASIR</th>
                                        <th class="pb-4 px-4">METODE BAYAR</th>
                                        <th class="pb-4 px-4 text-right">TOTAL NOMINAL</th>
                                        <th class="pb-4 px-4 text-center">STATUS</th>
                                        <th class="pb-4 px-4 text-center no-print">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                                    @foreach ($transactions as $transaction)
                                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/60 transition">
                                            <td class="py-4 px-4 font-bold text-slate-900 dark:text-white">
                                                <span class="font-mono text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 px-2 py-0.5 rounded-lg text-xs">{{ $transaction->invoice_number }}</span>
                                                @if($transaction->note)
                                                    <span class="block text-[10px] text-slate-400 mt-0.5 italic truncate max-w-[160px]">{{ $transaction->note }}</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4 text-slate-600 dark:text-slate-300 font-medium">
                                                {{ $transaction->created_at->format('d M Y, H:i') }}
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="flex flex-col gap-1 items-start">
                                                    @if(($transaction->order_type ?? 'dine_in') === 'take_away')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                                            🛍️ Take Away
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                                            🍽️ Dine In
                                                        </span>
                                                    @endif
                                                    <span class="text-slate-700 dark:text-slate-200 font-medium text-xs">{{ $transaction->user->name ?? 'Kasir' }}</span>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <span class="inline-block px-2.5 py-1 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold uppercase">
                                                    {{ $transaction->paymentMethod->name ?? ($transaction->payment_method ?? 'CASH') }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <span class="font-black text-slate-900 dark:text-white text-sm block">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                                                @if($transaction->status === 'refunded' && $transaction->refund_amount)
                                                    <span class="text-[10px] font-extrabold text-amber-600 dark:text-amber-400 block">Refund: -Rp {{ number_format($transaction->refund_amount, 0, ',', '.') }}</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4 text-center">
                                                @if($transaction->status === 'completed')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                        Selesai
                                                    </span>
                                                @elseif($transaction->status === 'voided')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800"
                                                          title="Alasan: {{ $transaction->void_reason }}">
                                                        ✕ Void (Batal)
                                                    </span>
                                                @elseif($transaction->status === 'refunded')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800"
                                                          title="Alasan: {{ $transaction->refund_reason }}">
                                                        ↺ Refund
                                                    </span>
                                                @else
                                                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                                                        {{ ucfirst($transaction->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4 text-center no-print">
                                                <a href="{{ route('transactions.show', $transaction->id) }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($transactions->hasPages())
                            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 no-print">
                                {{ $transactions->links() }}
                            </div>
                        @endif
                    @else
                        <div class="py-12 text-center text-slate-400 text-xs font-medium">
                            Tidak ada data transaksi ditemukan pada rentang tanggal ini.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- SCRIPT CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const isDark = document.documentElement.classList.contains('dark');
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: @json($chartValues),
                    borderColor: '#3b82f6',
                    backgroundColor: isDark ? 'rgba(59, 130, 246, 0.15)' : 'rgba(37, 99, 235, 0.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#3b82f6'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: {
                            color: isDark ? 'rgba(51, 65, 85, 0.4)' : '#f1f5f9'
                        },
                        ticks: {
                            color: isDark ? '#94a3b8' : '#64748b'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: isDark ? 'rgba(51, 65, 85, 0.4)' : '#f1f5f9'
                        },
                        ticks: {
                            color: isDark ? '#94a3b8' : '#64748b',
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>