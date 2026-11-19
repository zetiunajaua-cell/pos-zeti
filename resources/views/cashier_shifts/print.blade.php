<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Shift Kasir - {{ $shift->user->name ?? 'Kasir' }} ({{ $shift->opened_at->format('d/m/Y') }})</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
                color: #000 !important;
                font-size: 12px !important;
            }
            .z-report-box {
                border: none !important;
                box-shadow: none !important;
                width: 100% !important;
                max-width: 80mm !important;
                margin: 0 auto !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 dark:bg-slate-950 font-sans antialiased text-slate-800 dark:text-slate-100 min-h-screen p-4 sm:p-8 flex flex-col items-center">

    <!-- Top Action Bar (No Print) -->
    <div class="no-print w-full max-w-md mb-6 flex items-center justify-between gap-3">
        <a href="{{ route('transactions.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-xs">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Kasir</span>
        </a>

        <button type="button"
                onclick="window.print()"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-black shadow-md shadow-blue-500/20 active:scale-95 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak Struk Rekap (Z-Report)</span>
        </button>
    </div>

    <!-- Container Struk Rekap Shift (Thermal Standard) -->
    <div class="z-report-box w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-6 sm:p-8 space-y-4 font-sans text-xs">
        
        <!-- Header -->
        <div class="text-center pb-4 border-b-2 border-dashed border-slate-300 dark:border-slate-700">
            <h1 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ setting('store_name', 'POS ZETI COFFEE') }}</h1>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold">{{ setting('store_address', 'Outlet / Cabang Utama') }}</p>
            <div class="mt-2 inline-block px-3 py-1 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[11px] font-black uppercase tracking-wider">
                LAPORAN TUTUP SHIFT KASIR (Z-REPORT)
            </div>
        </div>

        <!-- Meta Shift -->
        <div class="py-2 border-b border-dashed border-slate-300 dark:border-slate-700 space-y-1.5 text-xs">
            <div class="flex justify-between">
                <span class="text-slate-400">Kasir / Barista:</span>
                <span class="font-bold text-slate-800 dark:text-white">{{ $shift->user->name ?? 'Kasir' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Cabang / Outlet:</span>
                <span class="font-bold text-slate-800 dark:text-white">{{ $shift->branch ?? 'Pusat' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Waktu Buka Shift:</span>
                <span class="font-bold text-slate-800 dark:text-white">{{ $shift->opened_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Waktu Tutup Shift:</span>
                <span class="font-bold text-slate-800 dark:text-white">{{ $shift->closed_at ? $shift->closed_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Total Transaksi Selesai:</span>
                <span class="font-black text-blue-600 dark:text-blue-400">{{ $shift->total_transactions }} Transaksi</span>
            </div>
        </div>

        <!-- Rincian Penjualan & Uang Laci -->
        <div class="py-2 border-b border-dashed border-slate-300 dark:border-slate-700 space-y-2 text-xs">
            <div class="flex justify-between">
                <span class="text-slate-600 dark:text-slate-400">Modal Kas Awal (Kembalian):</span>
                <span class="font-bold text-slate-800 dark:text-white">Rp {{ number_format($shift->starting_cash, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600 dark:text-slate-400">(+) Penjualan Kas (Tunai):</span>
                <span class="font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($shift->total_cash_sales, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600 dark:text-slate-400">(+) Penjualan Non-Tunai (QRIS/Transfer):</span>
                <span class="font-bold text-blue-600 dark:text-blue-400">Rp {{ number_format($shift->total_non_cash_sales, 0, ',', '.') }}</span>
            </div>
            <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex justify-between font-black text-sm">
                <span>Total Omset Shift:</span>
                <span class="text-slate-900 dark:text-white">Rp {{ number_format($shift->total_cash_sales + $shift->total_non_cash_sales, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Rekonsiliasi Kas Fisik Laci -->
        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-2">
            <div class="flex justify-between font-bold">
                <span class="text-slate-600 dark:text-slate-400">Uang Kas Sistem (Expected):</span>
                <span class="text-slate-900 dark:text-white">Rp {{ number_format($shift->expected_cash, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-black">
                <span class="text-slate-700 dark:text-slate-300">Uang Kas Fisik Laci (Actual):</span>
                <span class="text-slate-900 dark:text-white">Rp {{ number_format($shift->actual_cash ?? 0, 0, ',', '.') }}</span>
            </div>

            <div class="pt-2 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <span class="font-bold text-xs">Status Selisih Kas:</span>
                @if(($shift->difference ?? 0) == 0)
                    <span class="px-2.5 py-1 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-black text-xs">
                        ✓ PAS (SEIMBANG)
                    </span>
                @elseif(($shift->difference ?? 0) > 0)
                    <span class="px-2.5 py-1 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 font-black text-xs">
                        + LEBIH Rp {{ number_format($shift->difference, 0, ',', '.') }}
                    </span>
                @else
                    <span class="px-2.5 py-1 rounded-xl bg-rose-100 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 font-black text-xs">
                        - KURANG Rp {{ number_format(abs($shift->difference), 0, ',', '.') }}
                    </span>
                @endif
            </div>
        </div>

        @if($shift->notes)
            <div class="text-[11px] bg-slate-50 dark:bg-slate-800/40 p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-700">
                <strong>Catatan Kasir:</strong> {{ $shift->notes }}
            </div>
        @endif

        <!-- Area Tanda Tangan -->
        <div class="pt-6 grid grid-cols-2 gap-4 text-center text-[10px] text-slate-500 dark:text-slate-400">
            <div>
                <p>Kasir yang Bertugas</p>
                <div class="h-12"></div>
                <p class="font-bold border-t border-dashed border-slate-400 pt-1 text-slate-800 dark:text-white">
                    ( {{ $shift->user->name ?? 'Kasir' }} )
                </p>
            </div>
            <div>
                <p>Supervisor / Owner</p>
                <div class="h-12"></div>
                <p class="font-bold border-t border-dashed border-slate-400 pt-1 text-slate-800 dark:text-white">
                    ( ............................ )
                </p>
            </div>
        </div>

    </div>

</body>
</html>
