<x-app-layout>
    
    <div class="w-full px-8 py-8">

        {{-- HERO BANNER TETAP ELEGAN --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 shadow-xl mb-8">
            <div class="absolute right-[-80px] top-[-80px] h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between px-8 py-8 md:px-10 md:py-10 gap-4">
                <div>
                    <span class="inline-flex items-center rounded-full bg-white/20 px-5 py-2 text-sm text-white font-semibold">
                        📊 Dashboard Overview
                    </span>
                    <h1 class="mt-4 text-3xl md:text-5xl font-black text-white">
                        Platform POS Zeti
                    </h1>
                    <p class="mt-2 text-lg text-indigo-100 font-medium">
                        Mode Akses: <span class="capitalize font-bold text-white">{{ auth()->user()->email === 'admin@gmail.com' ? 'Administrator' : 'Kasir System' }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center gap-2 shadow-sm">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-400"></span>
                        </span>
                        <span class="text-sm font-bold text-white">Real-time Terminal</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto space-y-8">

            <!-- METRIC CARDS GAYA PROFESIONAL (CLEAN & MODERN) -->
            @if (auth()->user()->email === 'admin@gmail.com')
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Card 1: Penjualan Hari Ini (Aksen Indigo) -->
                    <div class="bg-white p-7 rounded-3xl border border-indigo-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-600"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 tracking-wider uppercase">Penjualan Hari Ini</span>
                            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition duration-200">
                                💳
                            </div>
                        </div>
                        <div class="mt-6">
                            <h3 class="text-3xl font-black text-slate-900 tracking-tight">Rp {{ number_format($todaySales, 0, ',', '.') }}</h3>
                            <p class="text-xs text-indigo-600 mt-1 font-semibold flex items-center gap-1">
                                <span>✦</span> Performa transaksi aktif
                            </p>
                        </div>
                    </div>

                    <!-- Card 2: Omzet Bulan Ini (Aksen Violet/Purple) -->
                    <div class="bg-white p-7 rounded-3xl border border-purple-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-purple-600"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 tracking-wider uppercase">Omzet Bulan Ini</span>
                            <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition duration-200">
                                📈
                            </div>
                        </div>
                        <div class="mt-6">
                            <h3 class="text-3xl font-black text-slate-900 tracking-tight">Rp {{ number_format($monthlySales, 0, ',', '.') }}</h3>
                            <p class="text-xs text-purple-600 mt-1 font-semibold flex items-center gap-1">
                                <span>✦</span> Akumulasi berjalan
                            </p>
                        </div>
                    </div>

                    <!-- Card 3: Transaksi Hari Ini (Aksen Biru/Blue) -->
                    <div class="bg-white p-7 rounded-3xl border border-blue-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-blue-600"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 tracking-wider uppercase">Transaksi Hari Ini</span>
                            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition duration-200">
                                ⚡
                            </div>
                        </div>
                        <div class="mt-6">
                            <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $todayTransactions }} <span class="text-base font-semibold text-gray-400">Struk</span></h3>
                            <p class="text-xs text-blue-600 mt-1 font-semibold flex items-center gap-1">
                                <span>✦</span> Pesanan diproses
                            </p>
                        </div>
                    </div>

                    <!-- Card 4: Total Katalog (Aksen Emerald/Green) -->
                    <div class="bg-white p-7 rounded-3xl border border-emerald-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-600"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 tracking-wider uppercase">Total Katalog</span>
                            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition duration-200">
                                📦
                            </div>
                        </div>
                        <div class="mt-6">
                            <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $totalProducts }} <span class="text-base font-semibold text-gray-400">Item</span></h3>
                            <p class="text-xs text-emerald-600 mt-1 font-semibold flex items-center gap-1">
                                <span>✦</span> Produk aktif
                            </p>
                        </div>
                    </div>

                </div>
            @else
                <!-- KASIR VIEW PROFESIONAL -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-8 rounded-3xl border border-indigo-100 shadow-sm relative overflow-hidden flex items-center justify-between group">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-600"></div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Penjualan Hari Ini</span>
                            <h3 class="text-3xl font-black text-slate-900 mt-2 tracking-tight">Rp {{ number_format($todaySales, 0, ',', '.') }}</h3>
                            <p class="text-xs text-emerald-600 font-semibold mt-3 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Sinkronisasi otomatis
                            </p>
                        </div>
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl font-bold">
                            💳
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-3xl border border-blue-100 shadow-sm relative overflow-hidden flex items-center justify-between group">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-blue-600"></div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Transaksi Selesai</span>
                            <h3 class="text-3xl font-black text-slate-900 mt-2 tracking-tight">{{ $todayTransactions }} <span class="text-lg font-normal text-gray-400">Struk</span></h3>
                            <p class="text-xs text-blue-600 font-semibold mt-3 flex items-center gap-1.5">
                                <span>✦</span> Total checkout kasir
                            </p>
                        </div>
                        <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl font-bold">
                            ✅
                        </div>
                    </div>
                </div>
            @endif

            <!-- TABLE DESIGN (GLASS & MINIMALIST) -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Top 5 Produk Terlaris</h3>
                        <p class="text-xs text-gray-500 mt-0.5 font-medium">Berdasarkan volume unit penjualan bulan ini</p>
                    </div>
                    <span class="px-4 py-1.5 bg-slate-900 text-white text-xs font-bold rounded-full">
                        Ranking
                    </span>
                </div>

                <div class="p-6">
                    @if ($topProducts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                        <th class="pb-4 px-4 w-20 text-center">No</th>
                                        <th class="pb-4 px-4">Detail Produk</th>
                                        <th class="pb-4 px-4 text-right">Unit Terjual</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @foreach ($topProducts as $index => $item)
                                        <tr class="hover:bg-slate-50/60 transition duration-150">
                                            <td class="py-4 px-4 text-center">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-xs font-extrabold {{ $index == 0 ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'bg-gray-100 text-gray-600' }}">
                                                    0{{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="font-bold text-slate-900 text-base">
                                                    {{ $item->product->name ?? 'Produk Dihapus' }}
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <span class="inline-block px-4 py-1.5 rounded-xl text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                    {{ $item->total_qty }} pcs
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-12 text-center text-gray-400 text-sm font-medium">
                            Belum ada data transaksi untuk bulan ini.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>