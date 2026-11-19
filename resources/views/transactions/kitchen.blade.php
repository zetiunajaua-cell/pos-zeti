<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Barista - {{ $transaction->formatted_queue_number }} ({{ $transaction->invoice_number }})</title>
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
                font-size: 13px !important;
                color: #000 !important;
            }
            .ticket-box {
                border: none !important;
                box-shadow: none !important;
                width: 100% !important;
                max-width: 80mm !important;
                margin: 0 auto !important;
                padding: 4px !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 dark:bg-slate-950 font-sans antialiased text-slate-800 dark:text-slate-100 min-h-screen p-4 sm:p-8 flex flex-col items-center">

    <!-- Top Action Bar (No Print) -->
    <div class="no-print w-full max-w-md mb-6 flex items-center justify-between gap-3">
        <a href="{{ route('transactions.show', $transaction) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-xs">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Struk Kasir</span>
        </a>

        <div class="flex items-center gap-2">
            <button type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white text-xs font-black shadow-md shadow-amber-500/20 active:scale-95 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Tiket Barista</span>
            </button>
        </div>
    </div>

    <!-- Thermal Ticket Container (Kitchen Order Ticket - KOT) -->
    <div class="ticket-box w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-6 sm:p-8 space-y-5">
        
        <!-- Header Kafe & Barista -->
        <div class="text-center pb-4 border-b-2 border-dashed border-slate-300 dark:border-slate-700">
            <div class="inline-block px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[11px] font-black uppercase tracking-wider mb-2">
                ☕ SLIP BARISTA / DAPUR (KOT)
            </div>
            <h1 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ setting('store_name', 'POS ZETI COFFEE') }}</h1>
            <p class="text-[11px] text-slate-500 font-semibold">{{ $transaction->created_at->format('d M Y &bull; H:i:s') }} WIB</p>
        </div>

        <!-- Highlight Nomor Antrean & Tipe Pesanan (Besar & Jelas) -->
        <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border-2 border-amber-300 dark:border-amber-800 text-center space-y-1">
            <div class="text-xs font-extrabold uppercase tracking-widest text-amber-700 dark:text-amber-400">
                NOMOR ANTREAN PESANAN
            </div>
            <div class="text-5xl font-black text-amber-950 dark:text-amber-200 tracking-wider font-mono py-1">
                {{ $transaction->formatted_queue_number }}
            </div>
            <div class="pt-1">
                @if(($transaction->order_type ?? 'dine_in') === 'take_away')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-rose-100 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 text-xs font-black uppercase border border-rose-300 dark:border-rose-800">
                        🛍️ TAKE AWAY (BAWA PULANG)
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 text-xs font-black uppercase border border-blue-300 dark:border-blue-800">
                        🍽️ DINE IN (MINUM DI TEMPAT)
                    </span>
                @endif
            </div>

            @if($transaction->customer_name || $transaction->customer)
                <div class="mt-2 pt-2 border-t border-amber-200/80 dark:border-amber-800/80 text-xs font-bold text-slate-800 dark:text-amber-100 flex items-center justify-center gap-1.5">
                    <span>👤 Pelanggan:</span>
                    <span class="font-black text-amber-950 dark:text-amber-200 text-sm">{{ $transaction->customer_name ?: $transaction->customer->name }}</span>
                </div>
            @endif

            @if($transaction->note)
                <div class="mt-1 pt-1 border-t border-amber-200/40 dark:border-amber-800/40 text-xs font-bold text-slate-800 dark:text-amber-100">
                    📍 Meja / Catatan: <span class="underline decoration-amber-400 decoration-2 font-black">{{ $transaction->note }}</span>
                </div>
            @endif
        </div>

        <!-- Meta Barista -->
        <div class="text-xs flex items-center justify-between text-slate-600 dark:text-slate-400 py-1 border-b border-dashed border-slate-300 dark:border-slate-700">
            <span>Invoice: <strong class="font-mono text-slate-800 dark:text-slate-200">{{ $transaction->invoice_number }}</strong></span>
            <span>Kasir: <strong class="text-slate-800 dark:text-slate-200">{{ $transaction->user->name ?? 'Kasir' }}</strong></span>
        </div>

        <!-- Daftar Minuman & Racikan (Barista Checklist) -->
        <div class="space-y-3 py-2">
            <div class="text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">
                Daftar Racikan Pesanan:
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800 space-y-2.5">
                @foreach($transaction->items as $index => $item)
                    <div class="pt-2.5 first:pt-0 flex items-start gap-3">
                        <!-- Kotak Checklist Barista -->
                        <div class="w-5 h-5 mt-0.5 rounded-lg border-2 border-slate-400 dark:border-slate-600 flex items-center justify-center shrink-0">
                            <!-- Checkbox space untuk barista mencoret setelah selesai diracik -->
                        </div>

                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-md bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-black">
                                    {{ $item->quantity }}x
                                </span>
                                <span class="text-sm font-black text-slate-900 dark:text-white leading-tight">
                                    {{ $item->product->name ?? 'Menu Minuman' }}
                                </span>
                            </div>

                            @if($item->variant_name || $item->variant)
                                <div class="mt-1 text-xs font-bold text-amber-700 dark:text-amber-400 pl-8">
                                    &bull; Varian: {{ $item->variant_name ?? $item->variant->name }}
                                </div>
                            @endif

                            @if($item->note)
                                <div class="mt-1 text-xs font-extrabold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 px-2.5 py-1 rounded-lg border border-rose-200 dark:border-rose-900/60 ml-8 inline-block">
                                    ⚡ Custom: {{ $item->note }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Footer Barista -->
        <div class="pt-4 border-t-2 border-dashed border-slate-300 dark:border-slate-700 text-center space-y-1">
            <p class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300">
                Total Minuman: {{ $transaction->items->sum('quantity') }} Cup / Porsi
            </p>
            <p class="text-[10px] text-slate-400 italic">
                Cek kelengkapan pesanan & panggil nomor antrean saat pesanan siap saji.
            </p>
        </div>

    </div>

</body>
</html>
