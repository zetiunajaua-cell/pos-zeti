<x-app-layout>
    
    {{-- SCOPED STYLES (Zero-Shift & Stable Layout on Reload / Navigation) --}}
    <style>
        /* Hover Lift Effect: Micro-lift on cursor hover without affecting page load / reload */
        .hover-lift {
            transition: transform 0.25s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.25s cubic-bezier(0.22, 1, 0.36, 1), border-color 0.25s ease;
            will-change: transform, box-shadow;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 32px -8px rgba(15, 23, 42, 0.12), 0 6px 12px -4px rgba(15, 23, 42, 0.08);
        }

        .dark .hover-lift:hover {
            box-shadow: 0 16px 32px -8px rgba(0, 0, 0, 0.6), 0 6px 16px -4px rgba(99, 102, 241, 0.18);
            border-color: rgba(99, 102, 241, 0.3);
        }

        /* Progress Bar */
        .progress-bar-fill {
            transition: width 0.6s cubic-bezier(0.22, 1, 0.36, 1);
        }

        /* Menjaga format angka rapi dan stabil */
        .count-up {
            font-variant-numeric: tabular-nums;
            display: inline-block;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        {{-- HERO BANNER LEBIH COMPACT & PROPORSIONAL (Task 36) --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 shadow-sm p-4 sm:p-5 text-white hover-lift">
            <div class="absolute right-[-20px] top-[-20px] h-40 w-40 rounded-full bg-white/10 blur-xl pointer-events-none"></div>
            
            <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-2.5 py-0.5 text-[11px] text-white font-semibold backdrop-blur-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Terminal POS Online
                    </div>
                    <h1 class="text-lg sm:text-xl font-black text-white tracking-tight">
                        Selamat Datang, {{ auth()->user()->name }}!
                    </h1>
                    <p class="text-xs text-indigo-100 font-medium">
                        Anda masuk sebagai <strong class="text-white uppercase">{{ optional(auth()->user()->role)->name ?? 'User' }}</strong> di sistem POS.
                    </p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    @if(optional(auth()->user()->role)->name === 'Admin')
                        <a href="{{ route('products.create') }}"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white text-indigo-700 hover:bg-indigo-50 font-bold text-xs shadow-sm transition active:scale-98">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Tambah Produk</span>
                        </a>
                        <a href="{{ route('reports.index') }}"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/20 hover:bg-white/30 text-white font-bold text-xs backdrop-blur-xs transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span>Buka Laporan</span>
                        </a>
                    @else
                        <a href="{{ route('transactions.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-blue-700 hover:bg-blue-50 font-extrabold text-xs shadow-md transition active:scale-98">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span>Buka Terminal Kasir</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- PERINGATAN STOK MENIPIS (TASK 24) --}}
        @if(optional(auth()->user()->role)->name === 'Admin' && isset($lowStockAlertProducts) && $lowStockAlertProducts->count() > 0)
            <div class="hover-lift bg-amber-50/90 dark:bg-amber-950/20 border border-amber-200/90 dark:border-amber-800/50 rounded-3xl p-5 shadow-xs transition cursor-default">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-amber-200/60 dark:border-amber-800/40">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-amber-950 dark:text-amber-200 flex items-center gap-2">
                                Peringatan Stok Menipis & Habis!
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-200 dark:bg-amber-900/60 text-amber-900 dark:text-amber-200 count-up"
                                      data-target="{{ $lowStockAlertProducts->count() }}"
                                      data-suffix="Produk">
                                    {{ $lowStockAlertProducts->count() }} Produk
                                </span>
                            </h3>
                            <p class="text-xs text-amber-800 dark:text-amber-300/80">
                                Stok produk berikut telah mencapai atau berada di bawah batas minimum (min_stock) yang ditentukan.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition shadow-xs">
                        <span>Kelola & Restock</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($lowStockAlertProducts->take(6) as $p)
                        <div class="bg-white/90 dark:bg-slate-800/90 rounded-2xl p-3 border border-amber-200/70 dark:border-amber-800/40 flex items-center justify-between">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center shrink-0 overflow-hidden">
                                    @if($p->image_url)
                                        <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="w-8 h-8 rounded-lg object-cover">
                                    @else
                                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ $p->name }}</h4>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $p->sku }} • Min: {{ $p->min_stock }} {{ $p->unit }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0 pl-2">
                                <span class="px-2 py-0.5 rounded-lg text-xs font-black {{ $p->stock <= 0 ? 'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300' }}">
                                    {{ $p->stock }} {{ $p->unit }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- METRIC CARDS (CLEAN & MODERN DESIGN WITH STAGGERED FADE-UP & HOVER LIFT) -->
        @if (optional(auth()->user()->role)->name === 'Admin')
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                
                <!-- Card 1: Penjualan Hari Ini -->
                <div class="hover-lift bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between transition cursor-default">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 tracking-wider uppercase">Penjualan Hari Ini</span>
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight count-up"
                            data-target="{{ (int)$todaySales }}"
                            data-prefix="Rp ">
                            Rp {{ number_format($todaySales, 0, ',', '.') }}
                        </h3>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1 font-semibold flex items-center gap-1">
                            <span>✦</span> Hari ini ({{ now()->format('d M Y') }})
                        </p>
                    </div>
                </div>

                <!-- Card 2: Omzet Bulan Ini -->
                <div class="hover-lift bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between transition cursor-default">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 tracking-wider uppercase">Omzet Bulan Ini</span>
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight count-up"
                            data-target="{{ (int)$monthlySales }}"
                            data-prefix="Rp ">
                            Rp {{ number_format($monthlySales, 0, ',', '.') }}
                        </h3>
                        <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1 font-semibold flex items-center gap-1">
                            <span>✦</span> Periode {{ now()->format('F Y') }}
                        </p>
                    </div>
                </div>

                <!-- Card 3: Transaksi Hari Ini -->
                <div class="hover-lift bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between transition cursor-default">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 tracking-wider uppercase">Transaksi Hari Ini</span>
                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight count-up"
                            data-target="{{ (int)$todayTransactions }}"
                            data-suffix="Struk">
                            {{ $todayTransactions }} <span class="text-sm font-semibold text-slate-400">Struk</span>
                        </h3>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1 font-semibold flex items-center gap-1">
                            <span>✦</span> Struk selesai diproses
                        </p>
                    </div>
                </div>

                <!-- Card 4: Total Produk -->
                <div class="hover-lift bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between transition cursor-default">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 tracking-wider uppercase">Total Katalog</span>
                        <div class="w-10 h-10 rounded-2xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight count-up"
                            data-target="{{ (int)$totalProducts }}"
                            data-suffix="Item">
                            {{ $totalProducts }} <span class="text-sm font-semibold text-slate-400">Item</span>
                        </h3>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1 font-semibold flex items-center gap-1">
                            <span>✦</span> Master produk aktif
                        </p>
                    </div>
                </div>

            </div>
        @else
            <!-- KASIR VIEW PROFESIONAL WITH HOVER LIFT -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="hover-lift bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between transition cursor-default">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Penjualan Kasir Hari Ini</span>
                        <h3 class="text-3xl font-black text-slate-900 dark:text-white mt-2 tracking-tight count-up"
                            data-target="{{ (int)$todaySales }}"
                            data-prefix="Rp ">
                            Rp {{ number_format($todaySales, 0, ',', '.') }}
                        </h3>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold mt-2 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Sinkronisasi real-time
                        </p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <div class="hover-lift bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between transition cursor-default">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Struk Diselesaikan</span>
                        <h3 class="text-3xl font-black text-slate-900 dark:text-white mt-2 tracking-tight count-up"
                            data-target="{{ (int)$todayTransactions }}"
                            data-suffix="Struk">
                            {{ $todayTransactions }} <span class="text-lg font-normal text-slate-400">Struk</span>
                        </h3>
                        <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold mt-2 flex items-center gap-1.5">
                            <span>✦</span> Selesai diproses
                        </p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
        @endif

        <!-- GRAFIK TREN 7 HARI & TOP 5 PRODUK TERLARIS (2-COLUMN GRID WITH HOVER LIFT) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            
            <!-- GRAFIK TREN PENJUALAN 7 HARI TERAKHIR (lg:col-span-7) -->
            <div class="hover-lift lg:col-span-7 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 flex flex-col justify-between transition">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900 dark:text-white tracking-tight">Tren Penjualan 7 Hari</h3>
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-medium">Grafik omset harian otomatis real-time</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Otomatis
                        </span>
                    </div>
                </div>

                <div class="w-full h-64 sm:h-72 mt-4">
                    <canvas id="dashboardSalesChart"></canvas>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-400 dark:text-slate-400">
                    <span>Data teragregasi otomatis dari setiap transaksi sukses</span>
                    @if(optional(auth()->user()->role)->name === 'Admin')
                        <a href="{{ route('reports.index') }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition flex items-center gap-1">
                            <span>Laporan Detail</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- TOP 5 PRODUK TERLARIS TABLE (lg:col-span-5) -->
            <div class="hover-lift lg:col-span-5 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between overflow-hidden transition">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/40">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white tracking-tight">Top 5 Produk Terlaris</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5 font-medium">Berdasarkan volume unit bulan ini ({{ now()->format('F Y') }})</p>
                    </div>
                    <span class="px-3 py-1 bg-slate-900 text-white dark:bg-slate-700 text-[10px] font-extrabold uppercase tracking-wider rounded-full">
                        Ranking
                    </span>
                </div>

                <div class="p-6 flex-1 flex flex-col justify-center">
                    @if ($topProducts->count() > 0)
                        @php
                            $maxProductQty = $topProducts->max('total_qty') ?? 1;
                        @endphp
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                                        <th class="pb-3 px-2 w-12 text-center">Rank</th>
                                        <th class="pb-3 px-3">Produk</th>
                                        <th class="pb-3 px-2 text-right">Terjual</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                                    @foreach ($topProducts as $index => $item)
                                        @php
                                             $rowPercent = $maxProductQty > 0 ? round(($item->total_qty / $maxProductQty) * 100) : 0;
                                        @endphp
                                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                                            <td class="py-2.5 px-2 text-center">
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg text-xs font-black {{ $index == 0 ? 'bg-amber-400 text-slate-950 shadow-xs' : ($index == 1 ? 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' : ($index == 2 ? 'bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400')) }}">
                                                    #{{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td class="py-2.5 px-3">
                                                <div class="font-bold text-slate-800 dark:text-slate-200 text-xs truncate max-w-[140px] sm:max-w-[180px]">
                                                    {{ $item->product->name ?? 'Produk Dihapus' }}
                                                </div>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="text-[10px] text-slate-400 font-mono">{{ $item->product->sku ?? '-' }}</span>
                                                    {{-- Progress Bar Volume Penjualan --}}
                                                    <div class="flex-1 max-w-[80px] bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                                        <div class="progress-bar-fill bg-gradient-to-r from-blue-500 to-indigo-600 h-1.5 rounded-full"
                                                             style="width: {{ $rowPercent }}%;"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-2.5 px-2 text-right">
                                                <span class="inline-block px-2.5 py-0.5 rounded-lg text-xs font-extrabold bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-900/50 count-up">
                                                    {{ $item->total_qty }} pcs
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-12 text-center text-slate-400 text-xs font-medium">
                            <div class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 mx-auto flex items-center justify-center mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            Belum ada transaksi penjualan pada bulan ini.
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

    <!-- SCRIPT CHART.JS (Clean & Rock Solid on Reload) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // CHART.JS LINE CHART
            const chartCanvas = document.getElementById('dashboardSalesChart');
            if (chartCanvas) {
                const ctxDashboard = chartCanvas.getContext('2d');
                
                // Gradient fill modern
                const gradient = ctxDashboard.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(79, 70, 229, 0.28)');
                gradient.addColorStop(1, 'rgba(79, 70, 229, 0.00)');

                const isDarkMode = document.documentElement.classList.contains('dark');
                const gridColor = isDarkMode ? 'rgba(51, 65, 85, 0.4)' : '#f1f5f9';
                const textColor = isDarkMode ? '#94a3b8' : '#64748b';

                new Chart(ctxDashboard, {
                    type: 'line',
                    data: {
                        labels: @json($salesChartLabels),
                        datasets: [{
                            label: 'Penjualan (Rp)',
                            data: @json($salesChartValues),
                            borderColor: '#4f46e5',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.38,
                            pointRadius: 4,
                            pointHoverRadius: 7,
                            pointBackgroundColor: '#4f46e5',
                            pointBorderColor: isDarkMode ? '#0f172a' : '#ffffff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1200,
                            easing: 'easeOutQuart'
                        },
                        animations: {
                            y: {
                                duration: 1200,
                                easing: 'easeOutQuart'
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: isDarkMode ? '#0f172a' : '#1e293b',
                                titleColor: '#ffffff',
                                bodyColor: '#e2e8f0',
                                borderColor: isDarkMode ? '#334155' : 'transparent',
                                borderWidth: 1,
                                callbacks: {
                                    label: function(context) {
                                        return ' Penjualan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: textColor,
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                                        } else if (value >= 1000) {
                                            return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                                        }
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                },
                                grid: {
                                    color: gridColor
                                }
                            },
                            x: {
                                ticks: {
                                    color: textColor
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

        });
    </script>
</x-app-layout>
