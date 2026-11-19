<nav x-data="{ 
        open: false, 
        openProfile: false, 
        openNotifications: false,
        isDark: document.documentElement.classList.contains('dark'),
        toggleDark() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('pos_theme', 'light');
                this.isDark = false;
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('pos_theme', 'dark');
                this.isDark = true;
            }
        }
    }" 
    class="bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 sticky top-0 z-40 backdrop-blur-md bg-white/95 dark:bg-slate-900/95 transition-colors duration-150">
    
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Left: Brand & Nav Links -->
            <div class="flex items-center gap-6 lg:gap-8">
                <!-- Brand Logo (Task 31: Dynamic Store Identity) -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                    @if(setting('store_logo'))
                        <img src="{{ asset('storage/' . setting('store_logo')) }}" alt="Logo" 
                             onerror="this.onerror=null; this.classList.add('hidden'); if(this.nextElementSibling) this.nextElementSibling.classList.remove('hidden');"
                             class="w-9 h-9 rounded-xl object-cover shadow-sm group-hover:scale-105 transition-transform duration-200">
                        <div class="hidden w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    @else
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="flex flex-col">
                        <span class="text-base font-extrabold text-slate-900 dark:text-white tracking-tight leading-none group-hover:text-blue-600 transition-colors">
                            {{ setting('store_name', 'POS Zeti') }}
                        </span>
                        <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-400 tracking-wider uppercase mt-0.5 truncate max-w-[140px]">
                            {{ setting('store_slogan', 'Terminal System') }}
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex md:items-center md:gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-2 rounded-xl text-xs font-bold transition-all duration-150 flex items-center gap-1.5 {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>

                    {{-- MENU KHUSUS ADMIN --}}
                    @if(optional(auth()->user()->role)->name === 'Admin')
                        <a href="{{ route('products.index') }}"
                           class="px-3 py-2 rounded-xl text-xs font-bold transition-all duration-150 flex items-center gap-1.5 {{ request()->routeIs('products.*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Produk
                        </a>

                        <a href="{{ route('categories.index') }}"
                           class="px-3 py-2 rounded-xl text-xs font-bold transition-all duration-150 flex items-center gap-1.5 {{ request()->routeIs('categories.*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            Kategori
                        </a>

                        {{-- Task 27: Pengeluaran --}}
                        <a href="{{ route('expenses.index') }}"
                           class="px-3 py-2 rounded-xl text-xs font-bold transition-all duration-150 flex items-center gap-1.5 {{ request()->routeIs('expenses.*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Pengeluaran
                        </a>

                        <a href="{{ route('promotions.index') }}"
                           class="px-3 py-2 rounded-xl text-xs font-bold transition-all duration-150 flex items-center gap-1.5 {{ request()->routeIs('promotions.*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/><circle cx="12" cy="12" r="3"/></svg>
                            Promo
                        </a>

                        <a href="{{ route('customers.index') }}"
                           class="px-3 py-2 rounded-xl text-xs font-bold transition-all duration-150 flex items-center gap-1.5 {{ request()->routeIs('customers.*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Member
                        </a>

                    {{-- MENU KHUSUS KASIR --}}
                    @elseif(optional(auth()->user()->role)->name === 'Kasir')
                        <a href="{{ route('transactions.index') }}"
                           class="px-3 py-2 rounded-xl text-xs font-bold transition-all duration-150 flex items-center gap-1.5 {{ request()->routeIs('transactions.*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Transaksi Kasir
                        </a>
                    @endif

                    {{-- MENU LAPORAN (BISA DIAKSES ADMIN & KASIR) --}}
                    <a href="{{ route('reports.index') }}"
                       class="px-3 py-2 rounded-xl text-xs font-bold transition-all duration-150 flex items-center gap-1.5 {{ request()->routeIs('reports.*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Laporan
                    </a>
                </div>
            </div>

            <!-- Right: Dark Mode Toggle, Notifications & Profile -->
            <div class="hidden sm:flex sm:items-center sm:gap-2 lg:gap-3">
                
                <!-- Dark Mode Toggle Button (Task 30) -->
                <button type="button" 
                        @click="toggleDark()"
                        class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-amber-500 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none"
                        :title="isDark ? 'Beralih ke Mode Terang (Light Mode)' : 'Beralih ke Mode Gelap (Dark Mode)'">
                    <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="isDark" class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                {{-- NOTIFIKASI STOK MENIPIS (TASK 24) --}}
                @if(optional(auth()->user()->role)->name === 'Admin')
                    <div class="relative" @click.away="openNotifications = false">
                        <button @click="openNotifications = !openNotifications"
                                class="relative p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none"
                                title="Notifikasi Stok Menipis">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if(isset($navLowStockCount) && $navLowStockCount > 0)
                                <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[10px] font-black text-white shadow-sm animate-pulse">
                                    {{ $navLowStockCount > 9 ? '9+' : $navLowStockCount }}
                                </span>
                            @endif
                        </button>

                        <!-- Dropdown Notifikasi -->
                        <div x-show="openNotifications"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 z-50 overflow-hidden"
                             style="display: none;">
                            <div class="px-4 py-3 bg-slate-50/80 dark:bg-slate-700/50 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ ($navLowStockCount ?? 0) > 0 ? 'bg-amber-500 animate-ping' : 'bg-slate-300' }}"></span>
                                    <h4 class="text-xs font-extrabold text-slate-800 dark:text-white">Peringatan Stok Produk</h4>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ ($navLowStockCount ?? 0) > 0 ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}">
                                    {{ $navLowStockCount ?? 0 }} Perlu Restock
                                </span>
                            </div>

                            <div class="max-h-64 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700">
                                @if(isset($navLowStockProducts) && $navLowStockProducts->count() > 0)
                                    @foreach($navLowStockProducts as $lowItem)
                                        <a href="{{ route('products.edit', $lowItem) }}" class="p-3 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/50 transition block">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/></svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ $lowItem->name }}</p>
                                                    <p class="text-[10px] text-slate-400 font-mono">{{ $lowItem->sku }} • Min: {{ $lowItem->min_stock }}</p>
                                                </div>
                                            </div>
                                            <span class="ml-2 px-2 py-0.5 rounded-md text-[10px] font-extrabold {{ $lowItem->stock <= 0 ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' }} shrink-0">
                                                {{ $lowItem->stock }} {{ $lowItem->unit }}
                                            </span>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="p-6 text-center text-xs text-slate-400 dark:text-slate-500">
                                        Semua stok produk dalam kondisi aman.
                                    </div>
                                @endif
                            </div>
                            <div class="p-2.5 bg-slate-50 dark:bg-slate-700/40 text-center border-t border-slate-100 dark:border-slate-700">
                                <a href="{{ route('products.index') }}" class="text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                    Buka Master Produk &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Profile Dropdown -->
                <div class="relative" @click.away="openProfile = false">
                    <button @click="openProfile = !openProfile"
                            class="flex items-center gap-3 p-1.5 pr-3 rounded-2xl hover:bg-slate-100/80 dark:hover:bg-slate-800 transition-colors focus:outline-none">
                        
                        <!-- Avatar Inisial / Foto Profil -->
                        @if(Auth::user()->avatar_url)
                            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                                 class="w-8 h-8 rounded-xl object-cover ring-2 ring-slate-200 dark:ring-slate-700 shadow-sm">
                        @else
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr {{ optional(auth()->user()->role)->name === 'Admin' ? 'from-purple-600 to-indigo-600' : 'from-blue-600 to-teal-600' }} text-white font-bold text-xs flex items-center justify-center shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                        @endif

                        <!-- Info Nama & Role -->
                        <div class="text-left hidden lg:block">
                            <div class="text-xs font-bold text-slate-800 dark:text-white leading-tight">
                                {{ Auth::user()->name }}
                            </div>
                            <span class="inline-block text-[10px] font-extrabold uppercase px-1.5 py-0.5 rounded-md mt-0.5 {{ optional(auth()->user()->role)->name === 'Admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' }}">
                                {{ optional(auth()->user()->role)->name ?? 'User' }}
                            </span>
                        </div>

                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': openProfile }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Profile Dropdown Menu -->
                    <div x-show="openProfile"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-800 rounded-2xl shadow-xl py-1.5 border border-slate-100 dark:border-slate-700 z-50 divide-y divide-slate-100 dark:divide-slate-700"
                         style="display: none;">
                        
                        <div class="px-4 py-2.5">
                            <p class="text-xs text-slate-400 dark:text-slate-400 font-medium">Masuk sebagai</p>
                            <p class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ Auth::user()->email }}</p>
                        </div>

                        {{-- Menu Ekstra Admin --}}
                        @if(optional(auth()->user()->role)->name === 'Admin')
                            <div class="py-1">
                                <a href="{{ route('settings.index') }}"
                                   class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Pengaturan Toko & Pajak
                                </a>

                                <a href="{{ route('reports.profit_loss') }}"
                                   class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Laporan Laba & Rugi
                                </a>

                                <a href="{{ route('payment-methods.index') }}"
                                   class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    Metode Pembayaran
                                </a>

                                <a href="{{ route('activity-logs.index') }}"
                                   class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Riwayat Aktivitas (Log)
                                </a>
                            </div>
                        @endif

                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil Saya
                            </a>
                        </div>

                        <div class="py-1">
                            <form method="POST" action="{{ route('logout') }}" onsubmit="confirmSubmit(event, 'Apakah Anda yakin ingin keluar dari sesi POS saat ini?', 'Konfirmasi Keluar', false)">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors text-left">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Keluar (Log Out)
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Hamburger & Mobile Dark Mode Toggle -->
            <div class="flex items-center gap-2 sm:hidden">
                <button type="button" 
                        @click="toggleDark()"
                        class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none">
                    <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="isDark" class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                <button @click="open = !open"
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile Drawer) -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 pt-3 pb-4 space-y-1.5 shadow-lg">
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
            Dashboard
        </a>

        @if(optional(auth()->user()->role)->name === 'Admin')
            <a href="{{ route('products.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('products.*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                Produk
            </a>
            <a href="{{ route('categories.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('categories.*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                Kategori
            </a>
            <a href="{{ route('expenses.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('expenses.*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                Pengeluaran
            </a>
            <a href="{{ route('promotions.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('promotions.*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                Promo & Diskon
            </a>
            <a href="{{ route('customers.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('customers.*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                Pelanggan & Member
            </a>
            <a href="{{ route('reports.profit_loss') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('reports.profit_loss') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                Laba & Rugi
            </a>
            <a href="{{ route('settings.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('settings.*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                Pengaturan Toko & Pajak
            </a>
            <a href="{{ route('activity-logs.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('activity-logs.*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                Riwayat Aktivitas
            </a>
        @elseif(optional(auth()->user()->role)->name === 'Kasir')
            <a href="{{ route('transactions.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('transactions.*') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                Transaksi Kasir
            </a>
        @endif

        <a href="{{ route('reports.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('reports.index') ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
            Laporan Penjualan
        </a>

        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                @if(Auth::user()->avatar_url)
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                         class="w-8 h-8 rounded-xl object-cover ring-2 ring-slate-200 dark:ring-slate-700 shadow-sm">
                @else
                    <div class="w-8 h-8 rounded-xl bg-blue-600 text-white font-bold text-xs flex items-center justify-center">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                @endif
                <div>
                    <p class="text-xs font-bold text-slate-800 dark:text-white">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-slate-400">{{ optional(auth()->user()->role)->name ?? 'User' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" onsubmit="confirmSubmit(event, 'Apakah Anda yakin ingin keluar dari sesi POS saat ini?', 'Konfirmasi Keluar', false)">
                @csrf
                <button type="submit" class="text-xs font-bold text-red-600 dark:text-red-400 hover:underline">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</nav>