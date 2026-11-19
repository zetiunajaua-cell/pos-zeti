<x-app-layout>

    <!-- Style Khusus Cetak Thermal / Kertas -->
    <style>
        @media print {
            nav, header, .no-print, button, a {
                display: none !important;
            }
            body, .min-h-screen {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .receipt-container {
                max-width: 100% !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 auto !important;
            }
            .print-border {
                border-top: 1px dashed #475569 !important;
                border-bottom: 1px dashed #475569 !important;
            }
        }
    </style>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
         x-data="{ showVoidModal: false, showRefundModal: false }">

        {{-- Bar Aksi Bagian Atas (No Print) --}}
        <div class="no-print mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('transactions.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-xs">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Transaksi Baru</span>
                </a>
                <span class="text-xs font-semibold text-slate-400">|</span>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Invoice: <strong class="text-slate-800 dark:text-white">{{ $transaction->invoice_number }}</strong></span>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                {{-- Tombol Void / Batalkan Transaksi (Task 22) --}}
                @if($transaction->status === 'completed')
                    <button type="button"
                            @click="showVoidModal = true"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-2xl bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-900/60 text-xs font-bold transition shadow-xs">
                        <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        <span>Void (Batalkan)</span>
                    </button>

                    {{-- Tombol Refund / Pengembalian Dana (Task 17) --}}
                    <button type="button"
                            @click="showRefundModal = true"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-2xl bg-amber-50 dark:bg-amber-950/40 hover:bg-amber-100 dark:hover:bg-amber-900/60 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-900/60 text-xs font-bold transition shadow-xs">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H4m0 0l3-3m-3 3l3 3m5 4v1a4 4 0 004 4h8m0 0l-3-3m3 3l-3 3"/></svg>
                        <span>Refund</span>
                    </button>
                @endif

                {{-- Tombol Cetak Slip Barista / Dapur (KOT) --}}
                <a href="{{ route('transactions.kitchen', $transaction->id) }}"
                   target="_blank"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-2xl bg-amber-50 dark:bg-amber-950/40 hover:bg-amber-100 dark:hover:bg-amber-900/60 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800 text-xs font-bold transition shadow-xs">
                    <span>☕</span>
                    <span>Slip Barista (KOT)</span>
                </a>

                {{-- Tombol Cetak / Simpan PDF (Task 5) --}}
                <a href="{{ route('transactions.pdf', $transaction->id) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold transition shadow-md shadow-blue-500/20 active:scale-98">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Cetak PDF</span>
                </a>

                <button type="button"
                        onclick="window.print()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-2xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print View</span>
                </button>
            </div>
        </div>

        {{-- Notifikasi Server Sukses / Error --}}
        @if(session('success'))
            <div class="no-print mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-sm shrink-0">✓</div>
                    <div>
                        <h4 class="text-sm font-bold text-emerald-900 leading-tight">Berhasil</h4>
                        <p class="text-xs text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="no-print mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center font-bold text-sm shrink-0">✕</div>
                    <div>
                        <h4 class="text-sm font-bold text-rose-900 leading-tight">Perhatian</h4>
                        <p class="text-xs text-rose-700 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- STATUS BANNER: VOIDED (Task 22) --}}
        @if($transaction->status === 'voided')
            <div class="mb-6 rounded-2xl border-2 border-rose-300 bg-rose-50 p-5 text-rose-900 shadow-sm">
                <div class="flex items-center gap-2.5">
                    <span class="px-2.5 py-1 rounded-xl bg-rose-600 text-white text-xs font-black uppercase tracking-wider">STATUS: VOID (DIBATALKAN)</span>
                    <span class="text-xs text-rose-600 font-semibold">{{ $transaction->voided_at?->format('d M Y, H:i') }}</span>
                </div>
                <div class="mt-2.5 text-xs space-y-1">
                    <p><strong>Alasan Pembatalan:</strong> {{ $transaction->void_reason }}</p>
                    <p class="text-rose-700">Dibatalkan oleh: <strong>{{ $transaction->voidedBy->name ?? 'Kasir / Admin' }}</strong>. Stok produk telah otomatis dikembalikan ke inventaris.</p>
                </div>
            </div>
        @endif

        {{-- STATUS BANNER: REFUNDED (Task 17) --}}
        @if($transaction->status === 'refunded')
            <div class="mb-6 rounded-2xl border-2 border-amber-300 bg-amber-50 p-5 text-amber-900 shadow-sm">
                <div class="flex items-center gap-2.5">
                    <span class="px-2.5 py-1 rounded-xl bg-amber-600 text-white text-xs font-black uppercase tracking-wider">STATUS: REFUND (PENGEMBALIAN DANA)</span>
                    <span class="text-xs text-amber-700 font-semibold">{{ $transaction->refunded_at?->format('d M Y, H:i') }}</span>
                </div>
                <div class="mt-2.5 text-xs space-y-1">
                    <p><strong>Nominal Dikembalikan:</strong> <span class="text-sm font-black text-amber-800">Rp {{ number_format($transaction->refund_amount ?? $transaction->total_amount, 0, ',', '.') }}</span></p>
                    <p><strong>Alasan Refund:</strong> {{ $transaction->refund_reason }}</p>
                    <p class="text-amber-700">Diproses oleh: <strong>{{ $transaction->refundedBy->name ?? 'Kasir / Admin' }}</strong>. Stok barang telah dikembalikan ke inventaris.</p>
                </div>
            </div>
        @endif

        {{-- Kartu Struk Fisik (Thermal Style Modern) --}}
        <div class="receipt-container bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/90 dark:border-slate-800 shadow-md p-6 sm:p-8 font-sans relative overflow-hidden">
            
            @if($transaction->status === 'voided')
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-10">
                    <span class="text-7xl font-black text-rose-600 rotate-[-25deg] uppercase">VOIDED</span>
                </div>
            @elseif($transaction->status === 'refunded')
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-10">
                    <span class="text-7xl font-black text-amber-600 rotate-[-25deg] uppercase">REFUNDED</span>
                </div>
            @endif

            <!-- Struk Header (Task 10 & 31) -->
            <div class="text-center pb-6 border-b border-dashed border-slate-300 dark:border-slate-700">
                @if(setting('store_logo'))
                    <img src="{{ asset('storage/' . setting('store_logo')) }}" alt="Logo" class="w-12 h-12 rounded-2xl object-cover mx-auto shadow-sm mb-3">
                @else
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white font-black text-xl flex items-center justify-center mx-auto shadow-sm mb-3">
                        {{ strtoupper(substr(setting('store_name', 'POS ZETI'), 0, 1)) }}
                    </div>
                @endif
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">{{ setting('store_name', 'POS ZETI') }}</h2>
                @if(setting('store_slogan'))
                    <p class="text-xs text-slate-600 dark:text-slate-300 font-semibold mt-0.5">{{ setting('store_slogan') }}</p>
                @endif
                @if(setting('store_address'))
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ setting('store_address') }}</p>
                @endif
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                    @if(setting('store_phone')) Telp: {{ setting('store_phone') }} @endif
                    @if(setting('store_website')) | {{ setting('store_website') }} @endif
                </p>
                
                {{-- Salam Pembuka (Task 10 & 31) --}}
                <div class="mt-3 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 inline-block text-[11px] font-bold text-slate-700 dark:text-slate-300 italic">
                    {{ setting('receipt_header_msg', 'Selamat Datang di POS ZETI! Selamat Berbelanja.') }}
                </div>

                {{-- Tipe Pesanan Badge (Task 19) --}}
                <div class="mt-2.5">
                    @if(($transaction->order_type ?? 'dine_in') === 'take_away')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-black bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                            🛍️ TAKE AWAY (BAWA PULANG)
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-black bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                            🍽️ DINE IN (MAKAN DI TEMPAT)
                        </span>
                    @endif
                </div>

                {{-- Catatan Pesanan / Meja (Task 20) --}}
                @if($transaction->note)
                    <div class="mt-2 text-xs text-slate-600 dark:text-slate-300 font-medium bg-slate-50/70 dark:bg-slate-800/70 p-2 rounded-xl inline-block border border-slate-200/60 dark:border-slate-700">
                        <strong>Catatan Transaksi:</strong> {{ $transaction->note }}
                    </div>
                @endif

                {{-- Nomor Antrean Pesanan Kafe / F&B --}}
                <div class="mt-3.5 p-3 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border-2 border-dashed border-amber-300 dark:border-amber-800 text-center">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-700 dark:text-amber-400 block">Nomor Antrean Pesanan</span>
                    <span class="text-3xl font-black text-amber-950 dark:text-amber-100 font-mono tracking-wider block py-0.5">
                        {{ $transaction->formatted_queue_number }}
                    </span>
                    <span class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold">Harap tunggu nomor dipanggil barista</span>
                </div>
            </div>

            <!-- Meta Transaksi Grid -->
            <div class="py-4 border-b border-dashed border-slate-300 dark:border-slate-700 text-xs grid grid-cols-2 gap-y-2">
                <div>
                    <span class="text-slate-400 block text-[11px]">No. Struk</span>
                    <span class="font-bold text-slate-800 dark:text-white font-mono">{{ $transaction->invoice_number }}</span>
                </div>
                <div class="text-right">
                    <span class="text-slate-400 block text-[11px]">Waktu</span>
                    <span class="font-bold text-slate-800 dark:text-white">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">Kasir</span>
                    <span class="font-bold text-slate-800 dark:text-white">{{ $transaction->user->name ?? 'Kasir Utama' }}</span>
                </div>
                <div class="text-right">
                    <span class="text-slate-400 block text-[11px]">Metode Bayar</span>
                    <span class="font-extrabold text-blue-600 dark:text-blue-400 uppercase">{{ $transaction->paymentMethod->name ?? ($transaction->payment_method ?? 'CASH') }}</span>
                </div>
                @if($transaction->customer)
                    <div class="col-span-2 pt-2 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <span class="text-slate-400 text-[11px]">Member Pelanggan</span>
                        <span class="font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded-lg border border-emerald-200 dark:border-emerald-800">
                            {{ $transaction->customer->name }} ({{ $transaction->customer->member_code }} &bull; {{ $transaction->customer->membership_tier }})
                        </span>
                    </div>
                @endif
            </div>

            <!-- Rincian Produk / Items Table (Task 20 & 21) -->
            <div class="py-5 border-b border-dashed border-slate-300 dark:border-slate-700">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-slate-400 text-[11px] uppercase border-b border-slate-100 dark:border-slate-800 pb-2">
                            <th class="text-left font-bold pb-2">Produk & Varian</th>
                            <th class="text-center font-bold pb-2 w-14">Qty</th>
                            <th class="text-right font-bold pb-2 w-24">Harga</th>
                            <th class="text-right font-bold pb-2 w-28">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($transaction->items as $item)
                            <tr>
                                <td class="py-2.5">
                                    <div class="font-bold text-slate-800 dark:text-white text-xs">{{ $item->product->name ?? 'Item' }}</div>
                                    
                                    {{-- Varian Produk (Task 21) --}}
                                    @if($item->variant_name || $item->variant)
                                        <div class="text-[11px] font-bold text-blue-600 dark:text-blue-400">
                                            Varian: {{ $item->variant_name ?? $item->variant->name }}
                                        </div>
                                    @endif

                                    {{-- Catatan Khusus Item (Task 20) --}}
                                    @if($item->note)
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400 italic">
                                            &bull; Catatan: {{ $item->note }}
                                        </div>
                                    @endif

                                    <div class="text-[10px] text-slate-400 font-mono">{{ $item->product->sku ?? '-' }}</div>
                                </td>
                                <td class="py-2.5 text-center font-bold text-slate-700 dark:text-slate-200">
                                    {{ $item->quantity }}
                                </td>
                                <td class="py-2.5 text-right text-slate-600 dark:text-slate-300">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="py-2.5 text-right font-bold text-slate-900 dark:text-white">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totalan & Ringkasan Pembayaran -->
            <div class="pt-5 space-y-2 text-xs">
                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                    <span>Subtotal Barang ({{ $transaction->items->sum('quantity') }} unit)</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-200">Rp {{ number_format($transaction->items->sum('subtotal'), 0, ',', '.') }}</span>
                </div>

                @if($transaction->discount > 0)
                    <div class="flex justify-between text-slate-500 dark:text-slate-400">
                        <span>
                            Diskon
                            @if($transaction->promotion)
                                <strong class="text-indigo-600 dark:text-indigo-400 font-mono">({{ $transaction->promotion->code }})</strong>
                            @endif
                            @if($transaction->customer && $transaction->customer->discount_percent > 0)
                                <strong class="text-emerald-600 dark:text-emerald-400 font-mono">(Member {{ (float)$transaction->customer->discount_percent }}%)</strong>
                            @endif
                        </span>
                        <span class="text-rose-600 dark:text-rose-400 font-semibold">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                    </div>
                @endif

                @if($transaction->tax > 0)
                    <div class="flex justify-between text-slate-500 dark:text-slate-400">
                        <span>{{ setting('tax_name', 'Pajak (PPN)') }}</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200">Rp {{ number_format($transaction->tax, 0, ',', '.') }}</span>
                    </div>
                @endif

                @if($transaction->service_charge > 0)
                    <div class="flex justify-between text-slate-500 dark:text-slate-400">
                        <span>{{ setting('service_charge_name', 'Biaya Layanan') }}</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200">Rp {{ number_format($transaction->service_charge, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="flex justify-between text-slate-900 dark:text-white text-sm font-black pt-2 border-t border-slate-200 dark:border-slate-800">
                    <span class="uppercase tracking-wider">Total Tagihan</span>
                    <span class="text-base text-blue-600 dark:text-blue-400">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>

                @if($transaction->points_earned > 0)
                    <div class="flex justify-between text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/50 px-2.5 py-1.5 rounded-xl border border-emerald-200 dark:border-emerald-800">
                        <span class="font-bold">✦ Poin Loyalitas Diperoleh</span>
                        <span class="font-black">+{{ number_format($transaction->points_earned, 0, ',', '.') }} Poin</span>
                    </div>
                @endif

                <div class="flex justify-between text-slate-600 dark:text-slate-300 pt-1">
                    <span>Jumlah Dibayar</span>
                    <span class="font-bold text-slate-800 dark:text-white">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between text-slate-600 dark:text-slate-300">
                    <span>Kembalian</span>
                    <span class="font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Footer Struk: Salam Terima Kasih & Ajakan Kembali (Task 10 & 31) -->
            <div class="text-center pt-8 mt-6 border-t border-dashed border-slate-300 dark:border-slate-700">
                <p class="text-sm font-extrabold text-slate-800 dark:text-white">Terima Kasih Atas Kunjungan Anda!</p>
                <p class="text-xs font-bold text-blue-600 dark:text-blue-400 mt-1">{{ setting('receipt_footer_msg', 'Semoga hari Anda menyenangkan. Silakan berkunjung kembali!') }}</p>
                <p class="text-[10px] text-slate-400 mt-2">Barang yang sudah dibeli tidak dapat ditukar/dikembalikan tanpa struk asli.</p>
                <p class="text-[9px] text-slate-400 mt-2 font-mono">{{ setting('store_name', 'POS Zeti') }} &bull; Layanan Pelanggan: {{ setting('store_email', 'cs@poszeti.com') }}</p>
            </div>

        </div>

        {{-- MODAL VOID TRANSAKSI (Task 22) --}}
        <div x-show="showVoidModal"
             x-transition
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4"
             style="display: none;">
            <div @click.away="showVoidModal = false"
                 class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-md w-full p-6 space-y-4">
                <div class="flex items-center gap-3 text-rose-600 dark:text-rose-400">
                    <div class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-950/50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Batalkan Transaksi (Void)</h3>
                        <p class="text-xs text-slate-400">{{ $transaction->invoice_number }}</p>
                    </div>
                </div>

                <p class="text-xs text-slate-600 dark:text-slate-300">
                    Pembatalan transaksi ini akan mengembalikan seluruh stok produk ke gudang/toko dan menandai status transaksi sebagai <strong>VOID</strong>.
                </p>

                <form action="{{ route('transactions.void', $transaction) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="void_reason" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Alasan Pembatalan Transaksi <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="void_reason"
                                  name="reason"
                                  rows="3"
                                  required
                                  placeholder="Contoh: Salah input pesanan kasir / Pelanggan batalkan pesanan..."
                                  class="w-full text-xs font-medium rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:border-rose-500 focus:ring-rose-500 text-slate-800 dark:text-white p-2.5"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button"
                                @click="showVoidModal = false"
                                class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                            Tutup
                        </button>
                        <button type="submit"
                                class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                            Konfirmasi Void Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL REFUND TRANSAKSI (Task 17) --}}
        <div x-show="showRefundModal"
             x-transition
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4"
             style="display: none;">
            <div @click.away="showRefundModal = false"
                 class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-md w-full p-6 space-y-4">
                <div class="flex items-center gap-3 text-amber-600 dark:text-amber-400">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H4m0 0l3-3m-3 3l3 3m5 4v1a4 4 0 004 4h8m0 0l-3-3m3 3l-3 3"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Pengembalian Dana (Refund)</h3>
                        <p class="text-xs text-slate-400">{{ $transaction->invoice_number }}</p>
                    </div>
                </div>

                <form action="{{ route('transactions.refund', $transaction) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="refund_amount" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Nominal Dana Dikembalikan (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number"
                               id="refund_amount"
                               name="refund_amount"
                               value="{{ (int)$transaction->total_amount }}"
                               max="{{ (int)$transaction->total_amount }}"
                               min="0"
                               step="500"
                               required
                               class="w-full text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:border-amber-500 focus:ring-amber-500 text-slate-800 dark:text-white p-2.5">
                    </div>

                    <div>
                        <label for="refund_reason" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Alasan Pengembalian Dana <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="refund_reason"
                                  name="reason"
                                  rows="3"
                                  required
                                  placeholder="Contoh: Barang cacat / Pesanan salah saji / Pelanggan minta refund..."
                                  class="w-full text-xs font-medium rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:border-amber-500 focus:ring-amber-500 text-slate-800 dark:text-white p-2.5"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button"
                                @click="showRefundModal = false"
                                class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                            Tutup
                        </button>
                        <button type="submit"
                                class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                            Proses Pengembalian Dana
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-app-layout>
