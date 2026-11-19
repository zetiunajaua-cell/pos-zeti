<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>POS Zeti - Smart Cashier System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-[#F8FAFC] dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col justify-between antialiased transition-colors duration-200">

    <!-- ==================== HEADER / NAVBAR ==================== -->
    <header class="w-full bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800/80 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            
            <!-- Brand Logo & Subtitle -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-black flex items-center justify-center shadow-md shadow-blue-500/25 shrink-0 transition group-hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 11h8M8 15h5"/>
                    </svg>
                </div>
                <div>
                    <span class="text-base sm:text-lg font-black text-slate-900 dark:text-white leading-tight block tracking-tight">
                        {{ setting('store_name', 'POS Zeti') }}
                    </span>
                    <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-400 block -mt-0.5">
                        {{ setting('store_slogan', 'Smart Cashier System') }}
                    </span>
                </div>
            </a>

            <!-- Right Actions (Theme Toggle & Dashboard/Login Button) -->
            <div class="flex items-center gap-3">
                
                {{-- Theme Toggle --}}
                <button type="button"
                        @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 bg-slate-100/80 dark:bg-slate-800 transition"
                        title="Ganti Tema">
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="darkMode" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9h-1m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 active:scale-95 transition">
                        <span>Dashboard</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 active:scale-95 transition">
                        <span>Dashboard</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endauth
            </div>

        </div>
    </header>

    <!-- ==================== MAIN HERO CONTENT ==================== -->
    <main class="flex-1 flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16 w-full">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- KOLOM KIRI: HEADLINE & CALL TO ACTION (7 COLS) -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- Pill Tagline Versi Sistem -->
                    <div>
                        <span class="inline-flex items-center gap-2 text-xs font-extrabold text-blue-600 dark:text-blue-400 tracking-wider uppercase">
                            <span class="w-2 h-2 rounded-full bg-blue-600 dark:bg-blue-400 animate-pulse"></span>
                            <span>SISTEM KASIR & MANAJEMEN TOKO V1.3</span>
                        </span>
                    </div>

                    <!-- Big Headline -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white tracking-tight leading-[1.12]">
                        Kelola Transaksi<br>
                        Penjualan Lebih <span class="text-blue-600 dark:text-blue-500">Cepat &</span><br>
                        <span class="text-blue-600 dark:text-blue-500">Akurat</span>
                    </h1>

                    <!-- Paragraph Description -->
                    <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-relaxed max-w-xl font-medium">
                        Aplikasi Point of Sale (POS) modern berbasis web untuk mengoptimalkan pencatatan produk, laporan transaksi, dan manajemen stok inventaris toko Anda secara real-time.
                    </p>

                    <!-- CTA Button -->
                    <div class="pt-2">
                        <a href="{{ auth()->check() ? route('transactions.index') : route('login') }}"
                           class="inline-flex items-center justify-center px-7 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black text-sm shadow-xl shadow-blue-600/30 hover:shadow-blue-600/40 transition active:scale-98">
                            <span>Buka Panel Kasir</span>
                        </a>
                    </div>

                </div>

                <!-- KOLOM KANAN: BLUE HERO CARD (5 COLS) -->
                <div class="lg:col-span-5">
                    
                    <div class="relative rounded-3xl p-7 sm:p-8 bg-gradient-to-br from-blue-600 via-blue-600 to-indigo-700 text-white shadow-2xl shadow-blue-600/25 overflow-hidden border border-blue-400/20">
                        
                        <!-- Glowing Background Effects -->
                        <div class="absolute -right-10 -top-10 w-44 h-44 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
                        <div class="absolute -left-10 -bottom-10 w-44 h-44 rounded-full bg-indigo-900/40 blur-2xl pointer-events-none"></div>

                        <!-- Card Top: Tag & Cube Icon -->
                        <div class="flex items-center justify-between relative z-10">
                            <span class="px-3.5 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-xs font-semibold text-white tracking-wide">
                                Product Management
                            </span>

                            <div class="w-10 h-10 rounded-2xl bg-white/15 backdrop-blur-md flex items-center justify-center text-white shadow-inner">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Card Main Text -->
                        <div class="relative z-10 mt-7 mb-8">
                            <h3 class="text-2xl font-black text-white tracking-tight leading-snug">
                                Data Produk Aktif
                            </h3>
                            <p class="text-xs sm:text-sm text-blue-100 font-medium leading-relaxed mt-2 max-w-sm">
                                Kelola seluruh data produk dengan cepat, aman, dan profesional.
                            </p>
                        </div>

                        <!-- Card Metrics Box -->
                        <div class="relative z-10 p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase font-bold tracking-wider text-blue-200 block">Total SKU Terdaftar</span>
                                <span class="text-xl font-black text-white tracking-tight mt-0.5 block font-mono">
                                    {{ $totalProducts ?? 21 }} Produk
                                </span>
                            </div>

                            <span class="px-3 py-1 rounded-full text-xs font-bold text-emerald-300 bg-emerald-500/25 border border-emerald-400/30">
                                Ready Stock
                            </span>
                        </div>

                    </div>

                </div>

            </div>

            <!-- ==================== STATS ROW BOTTOM ==================== -->
            <div class="border-t border-slate-200/80 dark:border-slate-800/80 mt-12 pt-8">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-left">
                    <div class="space-y-0.5">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 block">Total Produk</span>
                        <p class="text-xl font-black text-slate-800 dark:text-white font-mono">{{ $totalProducts ?? 0 }}</p>
                    </div>

                    <div class="space-y-0.5">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 block">Produk Aktif</span>
                        <p class="text-xl font-black text-emerald-600 dark:text-emerald-400 font-mono">{{ $activeProducts ?? 0 }}</p>
                    </div>

                    <div class="space-y-0.5">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 block">Total Kategori</span>
                        <p class="text-xl font-black text-slate-800 dark:text-white font-mono">{{ $totalCategories ?? 0 }}</p>
                    </div>

                    <div class="space-y-0.5">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 block">Total Stok</span>
                        <p class="text-xl font-black text-blue-600 dark:text-blue-400 font-mono">{{ number_format($totalStock ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- ==================== FOOTER ==================== -->
    <footer class="py-4 text-center text-xs text-slate-400 dark:text-slate-500">
        &copy; {{ date('Y') }} {{ setting('store_name', 'POS Zeti') }}. All rights reserved.
    </footer>

</body>
</html>
