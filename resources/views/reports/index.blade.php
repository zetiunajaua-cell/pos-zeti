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

    <div class="py-8 bg-[#f8fafc] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- JUDUL HANYA MUNCUL SAAT DICETAK -->
            <div class="print-only-title">
                <h1 class="text-2xl font-bold">LAPORAN PENJUALAN POS ZETI</h1>
                <p class="text-sm text-gray-600">Periode: {{ Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                <hr class="mt-4 border-gray-300">
            </div>

            <!-- HEADER UTAMA & FILTER & TOMBOL CETAK -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 no-print">
                <div>
                    <div class="flex items-center gap-2 text-slate-800 font-bold text-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Laporan Penjualan
                    </div>
                    <p class="text-xs text-slate-400 font-medium mt-1">Pantau tinjauan omset dan riwayat transaksi toko Anda</p>
                </div>

                <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2 bg-slate-50 px-3.5 py-2 rounded-2xl border border-slate-200">
                        <label for="start_date" class="text-xs font-semibold text-slate-400">Dari:</label>
                        <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="border-0 bg-transparent text-xs font-bold text-slate-700 focus:ring-0 p-0">
                    </div>
                    <div class="flex items-center gap-2 bg-slate-50 px-3.5 py-2 rounded-2xl border border-slate-200">
                        <label for="end_date" class="text-xs font-semibold text-slate-400">Sampai:</label>
                        <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="border-0 bg-transparent text-xs font-bold text-slate-700 focus:ring-0 p-0">
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-2xl shadow-sm transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <!-- TOMBOL CETAK LAPORAN -->
                    <button type="button" onclick="window.print()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-2xl shadow-sm transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak Laporan
                    </button>
                </form>
            </div>

            <!-- 3 CARD SUMMARY (DISAMAKAN DENGAN TAMPILAN DASHBOARD/PRODUK) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- CARD 1: TOTAL PENJUALAN -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex justify-between items-start">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">TOTAL PENJUALAN</span>
                        <h3 class="text-3xl font-black text-slate-800 mt-2 tracking-tight">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
                        <p class="text-[11px] text-emerald-600 font-semibold mt-2 flex items-center gap-1.5">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Terverifikasi Total omset
                        </p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-2xl text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- CARD 2: JUMLAH TRANSAKSI SELESAI -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex justify-between items-start">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">JUMLAH TRANSAKSI SELESAI</span>
                        <h3 class="text-3xl font-black text-slate-800 mt-2 tracking-tight">{{ $totalTransactions }} <span class="text-base font-semibold text-slate-500">Transaksi</span></h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-2">Status Completed</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-2xl text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- CARD 3: RATA-RATA TRANSAKSI -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex justify-between items-start">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">RATA-RATA TRANSAKSI</span>
                        <h3 class="text-3xl font-black text-slate-800 mt-2 tracking-tight">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-2">Average basket size</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-2xl text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 30a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zM5 11a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H6a1 1 0 01-1-1v-2zM11 5a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V5z"/></svg>
                    </div>
                </div>
            </div>

            <!-- GRAFIK TREN PENJUALAN HARIAN -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Tren Penjualan Harian</h3>
                        <p class="text-[11px] text-slate-400">Pergerakan nominal penjualan harian (Rp)</p>
                    </div>
                </div>
                <div class="w-full h-64">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- TABEL DETAIL TRANSAKSI -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-800">Detail Transaksi</h3>
                    <p class="text-[11px] text-slate-400">Rincian seluruh item transaksi pada rentang tanggal terpilih</p>
                </div>
                <div class="p-6">
                    @if ($transactions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-[11px] font-extrabold text-slate-400 uppercase border-b border-slate-100 tracking-wider">
                                        <th class="pb-4 px-4">ID TRANSAKSI</th>
                                        <th class="pb-4 px-4">TANGGAL & WAKTU</th>
                                        <th class="pb-4 px-4 text-right">TOTAL NOMINAL</th>
                                        <th class="pb-4 px-4 text-center">STATUS</th>
                                        <th class="pb-4 px-4 text-center no-print">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs">
                                    @foreach ($transactions as $transaction)
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="py-4 px-4 font-bold text-slate-900">
                                                #TRX-{{ $transaction->created_at->format('Ymd') }}-{{ str_pad($transaction->id, 3, '0', STR_PAD_LEFT) }}
                                            </td>
                                            <td class="py-4 px-4 text-slate-600 font-medium">
                                                {{ $transaction->created_at->format('d M Y, H:i') }}
                                            </td>
                                            <td class="py-4 px-4 text-right font-black text-slate-900">
                                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="py-4 px-4 text-center">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                    Selesai
                                                </span>
                                            </td>
                                            <td class="py-4 px-4 text-center no-print">
                                                <a href="{{ route('transactions.show', $transaction->id) }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 hover:text-blue-800 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: JSON.parse('{!! json_encode($chartLabels) !!}'),
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: JSON.parse('{!! json_encode($chartValues) !!}'),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#2563eb'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
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