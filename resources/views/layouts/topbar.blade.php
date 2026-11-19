<!-- TOPBAR & SEARCH BAR (Task 43 & 44) -->
<header x-data="{
            openNotifications: false,
            openProfileMenu: false,
            quickSearchQuery: '',
            currentTime: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }),
            currentDate: new Intl.DateTimeFormat('id-ID', { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' }).format(new Date()),
            init() {
                setInterval(() => {
                    this.currentTime = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                }, 1000);
            },
            searchRoutes: [
                { title: 'Dashboard', url: '{{ route('dashboard') }}', group: 'Menu Utama' },
                @if(optional(auth()->user()->role)->name === 'Kasir')
                    { title: 'Transaksi Kasir POS', url: '{{ route('transactions.index') }}', group: 'Transaksi' },
                @endif
                @if(optional(auth()->user()->role)->name === 'Admin')
                    { title: 'Master Produk', url: '{{ route('products.index') }}', group: 'Katalog' },
                    { title: 'Kategori Produk', url: '{{ route('categories.index') }}', group: 'Katalog' },
                    { title: 'Pengeluaran Toko (Expenses)', url: '{{ route('expenses.index') }}', group: 'Keuangan' },
                    { title: 'Kupon Promo & Diskon', url: '{{ route('promotions.index') }}', group: 'CRM' },
                    { title: 'Data Pelanggan & Member', url: '{{ route('customers.index') }}', group: 'CRM' },
                    { title: 'Metode Pembayaran', url: '{{ route('payment-methods.index') }}', group: 'Konfigurasi' },
                    { title: 'Pengaturan Toko & Pajak', url: '{{ route('settings.index') }}', group: 'Sistem' },
                    { title: 'Riwayat Aktivitas Log', url: '{{ route('activity-logs.index') }}', group: 'Sistem' },
                @endif
                { title: 'Laporan Penjualan', url: '{{ route('reports.index') }}', group: 'Laporan' },
                { title: 'Laporan Laba & Rugi (P&L)', url: '{{ route('reports.profit_loss') }}', group: 'Laporan' },
                { title: 'Profil Saya', url: '{{ route('profile.edit') }}', group: 'Akun' }
            ],
            get searchResults() {
                if (!this.quickSearchQuery.trim()) return [];
                const q = this.quickSearchQuery.toLowerCase();
                return this.searchRoutes.filter(r => r.title.toLowerCase().includes(q) || r.group.toLowerCase().includes(q));
            },
            handleKeydown(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    $refs.quickSearchInput.focus();
                }
            }
        }"
        @keydown.window="handleKeydown($event)"
        class="sticky top-0 z-30 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 transition-colors duration-150">

    <!-- ROW 1: ELEMEN BAGIAN ATAS UTAMA (Task 43) -->
    <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
        
        <!-- Left: Hamburger (Mobile & Desktop) & Real-time Info -->
        <div class="flex items-center gap-3 sm:gap-4">
            <!-- Hamburger Button for Mobile Drawer (Task 41) -->
            <button type="button" @click="sidebarOpen = true"
                    class="p-2 rounded-xl text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition lg:hidden"
                    title="Buka Navigasi Mobile">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Desktop Sidebar Toggle Button (Buka/Tutup Sidebar) -->
            <button type="button" @click="toggleSidebarCollapse()"
                    class="hidden lg:flex p-2 rounded-xl text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none"
                    :title="sidebarCollapsed ? 'Buka Menu Sidebar' : 'Tutup / Ciutkan Menu Sidebar'">
                <!-- When open: show collapse icon -->
                <svg x-show="!sidebarCollapsed" @if($isSidebarCollapsed ?? false) style="display: none;" @endif class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                </svg>
                <!-- When collapsed: show expand icon -->
                <svg x-show="sidebarCollapsed" @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h16"/>
                </svg>
            </button>

            <!-- Status Terminal POS Online Pill -->
            <div class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200/80 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>POS Online</span>
                <span class="text-slate-300 dark:text-slate-600">|</span>
                <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">{{ setting('store_name', 'Toko Aktif') }}</span>
            </div>

            <!-- Live Real-Time Indonesian Clock & Date (Task 43) -->
            <div class="hidden md:flex items-center gap-2 text-xs font-medium text-slate-500 dark:text-slate-400 pl-2">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-bold text-slate-700 dark:text-slate-200" x-text="currentDate"></span>
                <span>&bull;</span>
                <span class="font-mono font-bold text-blue-600 dark:text-blue-400 text-xs" x-text="currentTime + ' WIB'"></span>
            </div>
        </div>

        <!-- Right: Actions, Dark Mode, Notifications, User Chip -->
        <div class="flex items-center gap-2 sm:gap-3">
            
            <!-- Dark Mode Toggle Button (Task 30) -->
            <button type="button" @click="toggleDark()"
                    class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-amber-500 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none"
                    :title="isDark ? 'Beralih ke Mode Terang (Light)' : 'Beralih ke Mode Gelap (Dark)'">
                <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg x-show="isDark" class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>

            <!-- Lonceng Notifikasi Stok Menipis (Task 24) -->
            @if(optional(auth()->user()->role)->name === 'Admin')
                <div class="relative" @click.away="openNotifications = false">
                    <button type="button" @click="openNotifications = !openNotifications"
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
                         x-transition
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
                                                <p class="text-[10px] text-slate-400 font-mono">{{ $lowItem->sku }} &bull; Min: {{ $lowItem->min_stock }}</p>
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

            <!-- User Profile Chip -->
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-2.5 p-1.5 pr-3 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                @if(auth()->user()->avatar_url)
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                         class="w-8 h-8 rounded-xl object-cover ring-2 ring-slate-200 dark:ring-slate-700 shadow-xs">
                @else
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr {{ optional(auth()->user()->role)->name === 'Admin' ? 'from-purple-600 to-indigo-600' : 'from-blue-600 to-teal-600' }} text-white font-bold text-xs flex items-center justify-center shadow-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                @endif
                <div class="text-left hidden sm:block">
                    <span class="text-xs font-bold text-slate-800 dark:text-white leading-tight block truncate max-w-[120px]">
                        {{ auth()->user()->name }}
                    </span>
                    <span class="inline-block text-[9px] font-black uppercase px-1.5 py-0.2 rounded mt-0.5 {{ optional(auth()->user()->role)->name === 'Admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' }}">
                        {{ optional(auth()->user()->role)->name ?? 'User' }}
                    </span>
                </div>
            </a>

        </div>

    </div>

    <!-- ROW 2: SEARCH BAR DI BAWAH HEADER (Task 44) -->
    <div class="px-4 sm:px-6 lg:px-8 py-2.5 bg-slate-50/80 dark:bg-slate-900/60 border-t border-slate-100 dark:border-slate-800">
        <div class="relative max-w-full">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            
            <input type="text"
                   x-ref="quickSearchInput"
                   x-model="quickSearchQuery"
                   placeholder="Cari menu, transaksi kasir, produk, atau fitur sistem... (Tekan Ctrl + K untuk mencari cepat)"
                   class="w-full pl-10 pr-24 py-2 rounded-xl text-xs font-semibold border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900 transition shadow-2xs">

            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <kbd class="hidden sm:inline-block px-1.5 py-0.5 text-[10px] font-mono font-bold text-slate-400 bg-slate-100 dark:bg-slate-700 dark:text-slate-300 rounded border border-slate-200 dark:border-slate-600 shadow-2xs">
                    Ctrl + K
                </kbd>
            </div>

            <!-- Live Search Suggestions Dropdown (Task 44) -->
            <div x-show="quickSearchQuery.trim().length > 0"
                 @click.away="quickSearchQuery = ''"
                 x-transition
                 class="absolute left-0 right-0 mt-2 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200/80 dark:border-slate-700 z-50 overflow-hidden"
                 style="display: none;">
                
                <div class="p-2 border-b border-slate-100 dark:border-slate-700 text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center justify-between">
                    <span>Hasil Pencarian Cepat</span>
                    <button type="button" @click="quickSearchQuery = ''" class="hover:text-slate-600 dark:hover:text-white">&times; Tutup</button>
                </div>

                <div class="max-h-60 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700">
                    <template x-for="item in searchResults" :key="item.url">
                        <a :href="item.url" class="p-3 flex items-center justify-between hover:bg-blue-50/60 dark:hover:bg-slate-700/60 transition group">
                            <div class="flex items-center gap-2.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300" x-text="item.group"></span>
                                <span class="text-xs font-bold text-slate-800 dark:text-white group-hover:text-blue-600 transition" x-text="item.title"></span>
                            </div>
                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400">&rarr;</span>
                        </a>
                    </template>

                    <div x-show="searchResults.length === 0" class="p-4 text-center text-xs text-slate-400 dark:text-slate-500">
                        Tidak ada menu yang sesuai dengan kata kunci pencarian.
                    </div>
                </div>
            </div>
        </div>
    </div>

</header>
