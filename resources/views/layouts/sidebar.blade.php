<!-- SIDEBAR NAVIGATION (Task 34, 35, 41, 42 & Buka Tutup Sidebar) -->
<aside id="main-sidebar"
       class="fixed inset-y-0 left-0 z-50 bg-white dark:bg-slate-900 border-r border-slate-200/80 dark:border-slate-800 flex flex-col justify-between lg:translate-x-0 shadow-sm"
       :class="{
           'translate-x-0': sidebarOpen,
           '-translate-x-full lg:translate-x-0': !sidebarOpen
       }">

    <!-- Top Sidebar: Brand & Identity (Task 34 & 35) -->
    <div>
        <div class="h-16 flex items-center border-b border-slate-100 dark:border-slate-800 {{ ($isSidebarCollapsed ?? false) ? 'justify-center px-2' : 'justify-between px-5' }}"
             :class="sidebarCollapsed ? 'justify-center px-2' : 'justify-between px-5'">
            
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group shrink-0" :title="sidebarCollapsed ? 'POS - Dashboard' : ''">
                <!-- Modern POS Vector Logo (Task 34) -->
                @if(setting('store_logo'))
                    <img src="{{ asset('storage/' . setting('store_logo')) }}" alt="Logo" 
                         onerror="this.onerror=null; this.classList.add('hidden'); if(this.nextElementSibling) this.nextElementSibling.classList.remove('hidden');"
                         class="w-10 h-10 rounded-xl object-cover shadow-sm group-hover:scale-105 transition-transform duration-200 shrink-0">
                    <div class="hidden w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-600 flex items-center justify-center text-white shadow-md shadow-blue-500/25 group-hover:scale-105 transition-transform duration-200 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="16" rx="3" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h5m-5 4h8"/>
                            <circle cx="16.5" cy="12.5" r="1.5" fill="currentColor"/>
                        </svg>
                    </div>
                @else
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-600 flex items-center justify-center text-white shadow-md shadow-blue-500/25 group-hover:scale-105 transition-transform duration-200 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="16" rx="3" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h5m-5 4h8"/>
                            <circle cx="16.5" cy="12.5" r="1.5" fill="currentColor"/>
                        </svg>
                    </div>
                @endif

                <!-- Dynamic POS Title with Modern Colorful Gradient (Task 35) -->
                <div x-show="!sidebarCollapsed" class="sidebar-text-label flex flex-col whitespace-nowrap overflow-hidden" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>
                    <div class="flex items-center gap-1.5">
                        <span class="text-xl font-black bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent tracking-tight leading-none">
                            POS
                        </span>
                        <span class="px-1.5 py-0.2 rounded-md text-[9px] font-black uppercase tracking-wider bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300">
                            v2.0
                        </span>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-400 tracking-wider uppercase mt-1 truncate max-w-[130px]">
                        {{ setting('store_name', 'Terminal System') }}
                    </span>
                </div>
            </a>

            <!-- Toggle Button for Desktop (Buka/Tutup Sidebar) -->
            <button type="button" @click="toggleSidebarCollapse()"
                    x-show="!sidebarCollapsed"
                    @if($isSidebarCollapsed ?? false) style="display: none;" @endif
                    class="hidden lg:flex p-1.5 rounded-xl text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                    title="Tutup / Ciutkan Sidebar (Hanya Ikon)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>

            <!-- Close Button for Mobile -->
            <button type="button" @click="sidebarOpen = false" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-white lg:hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Tombol Buka Sidebar jika Collapsed (Desktop) -->
        <div x-show="sidebarCollapsed" @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif class="hidden lg:flex justify-center pt-2.5 pb-1">
            <button type="button" @click="toggleSidebarCollapse()"
                    class="p-2 rounded-xl text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition shadow-2xs"
                    title="Buka / Lebarkan Menu Sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <!-- Navigation Menus (Task 41) -->
        <nav class="overflow-y-auto max-h-[calc(100vh-140px)] {{ ($isSidebarCollapsed ?? false) ? 'p-2 space-y-4' : 'p-3.5 space-y-6' }}"
             :class="sidebarCollapsed ? 'p-2 space-y-4' : 'p-3.5 space-y-6'">

            <!-- KELOMPOK: MENU UTAMA -->
            <div class="space-y-1">
                <span x-show="!sidebarCollapsed" class="sidebar-group-heading px-3 text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-400 block truncate" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>
                    Menu Utama
                </span>
                <div x-show="sidebarCollapsed" @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif class="h-px bg-slate-100 dark:bg-slate-800 my-1 mx-2"></div>

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 rounded-xl text-xs font-bold transition-colors relative group {{ ($isSidebarCollapsed ?? false) ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5' }} {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                   :class="sidebarCollapsed ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5'"
                   :title="sidebarCollapsed ? 'Dashboard' : ''">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span x-show="!sidebarCollapsed" class="sidebar-text-label truncate whitespace-nowrap" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>Dashboard</span>

                    <!-- Floating Tooltip on Hover when Collapsed -->
                    <div x-show="sidebarCollapsed"
                         @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif
                         class="hidden lg:group-hover:flex absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none items-center border border-slate-700">
                        Dashboard
                    </div>
                </a>

                {{-- TRANSAKSI KASIR (KHUSUS ROLE KASIR) --}}
                @if(optional(auth()->user()->role)->name === 'Kasir')
                    <a href="{{ route('transactions.index') }}"
                       class="flex items-center gap-3 rounded-xl text-xs font-bold transition-colors relative group {{ ($isSidebarCollapsed ?? false) ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5' }} {{ request()->routeIs('transactions.index') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5'"
                       :title="sidebarCollapsed ? 'Transaksi Kasir' : ''">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <span x-show="!sidebarCollapsed" class="sidebar-text-label truncate whitespace-nowrap" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>Transaksi Kasir</span>
                        <span x-show="!sidebarCollapsed" class="sidebar-text-label ml-auto px-1.5 py-0.5 rounded text-[9px] font-black uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>POS</span>

                        <!-- Floating Tooltip on Hover when Collapsed -->
                        <div x-show="sidebarCollapsed"
                             @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif
                             class="hidden lg:group-hover:flex absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none items-center border border-slate-700">
                            Transaksi Kasir (POS)
                        </div>
                    </a>
                @endif
            </div>

            {{-- MENU KHUSUS ADMINISTRATOR --}}
            @if(optional(auth()->user()->role)->name === 'Admin')
                <!-- KELOMPOK: MASTER PRODUK & INVENTARIS -->
                <div class="space-y-1">
                    <span x-show="!sidebarCollapsed" class="sidebar-group-heading px-3 text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-400 block truncate" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>
                        Katalog & Inventaris
                    </span>
                    <div x-show="sidebarCollapsed" @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif class="h-px bg-slate-100 dark:bg-slate-800 my-1 mx-2"></div>

                    <a href="{{ route('products.index') }}"
                       class="flex items-center gap-3 rounded-xl text-xs font-bold transition-colors relative group {{ ($isSidebarCollapsed ?? false) ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5' }} {{ request()->routeIs('products.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5'"
                       :title="sidebarCollapsed ? 'Master Produk' : ''">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span x-show="!sidebarCollapsed" class="sidebar-text-label truncate whitespace-nowrap" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>Master Produk</span>

                        <!-- Floating Tooltip on Hover when Collapsed -->
                        <div x-show="sidebarCollapsed"
                             @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif
                             class="hidden lg:group-hover:flex absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none items-center border border-slate-700">
                            Master Produk
                        </div>
                    </a>

                    <a href="{{ route('categories.index') }}"
                       class="flex items-center gap-3 rounded-xl text-xs font-bold transition-colors relative group {{ ($isSidebarCollapsed ?? false) ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5' }} {{ request()->routeIs('categories.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5'"
                       :title="sidebarCollapsed ? 'Kategori Produk' : ''">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <span x-show="!sidebarCollapsed" class="sidebar-text-label truncate whitespace-nowrap" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>Kategori Produk</span>

                        <!-- Floating Tooltip on Hover when Collapsed -->
                        <div x-show="sidebarCollapsed"
                             @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif
                             class="hidden lg:group-hover:flex absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none items-center border border-slate-700">
                            Kategori Produk
                        </div>
                    </a>
                </div>

                <!-- KELOMPOK: OPERASIONAL & CRM -->
                <div class="space-y-1">
                    <span x-show="!sidebarCollapsed" class="sidebar-group-heading px-3 text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-400 block truncate" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>
                        Operasional & CRM
                    </span>
                    <div x-show="sidebarCollapsed" @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif class="h-px bg-slate-100 dark:bg-slate-800 my-1 mx-2"></div>

                    <a href="{{ route('expenses.index') }}"
                       class="flex items-center gap-3 rounded-xl text-xs font-bold transition-colors relative group {{ ($isSidebarCollapsed ?? false) ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5' }} {{ request()->routeIs('expenses.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5'"
                       :title="sidebarCollapsed ? 'Pengeluaran Toko' : ''">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span x-show="!sidebarCollapsed" class="sidebar-text-label truncate whitespace-nowrap" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>Pengeluaran Toko</span>

                        <!-- Floating Tooltip on Hover when Collapsed -->
                        <div x-show="sidebarCollapsed"
                             @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif
                             class="hidden lg:group-hover:flex absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none items-center border border-slate-700">
                            Pengeluaran Toko
                        </div>
                    </a>

                    <a href="{{ route('promotions.index') }}"
                       class="flex items-center gap-3 rounded-xl text-xs font-bold transition-colors relative group {{ ($isSidebarCollapsed ?? false) ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5' }} {{ request()->routeIs('promotions.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5'"
                       :title="sidebarCollapsed ? 'Promo & Diskon' : ''">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/><circle cx="12" cy="12" r="3"/></svg>
                        <span x-show="!sidebarCollapsed" class="sidebar-text-label truncate whitespace-nowrap" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>Promo & Diskon</span>

                        <!-- Floating Tooltip on Hover when Collapsed -->
                        <div x-show="sidebarCollapsed"
                             @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif
                             class="hidden lg:group-hover:flex absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none items-center border border-slate-700">
                            Promo & Diskon
                        </div>
                    </a>

                    <a href="{{ route('customers.index') }}"
                       class="flex items-center gap-3 rounded-xl text-xs font-bold transition-colors relative group {{ ($isSidebarCollapsed ?? false) ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5' }} {{ request()->routeIs('customers.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5'"
                       :title="sidebarCollapsed ? 'Pelanggan & Member' : ''">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span x-show="!sidebarCollapsed" class="sidebar-text-label truncate whitespace-nowrap" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>Pelanggan & Member</span>

                        <!-- Floating Tooltip on Hover when Collapsed -->
                        <div x-show="sidebarCollapsed"
                             @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif
                             class="hidden lg:group-hover:flex absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none items-center border border-slate-700">
                            Pelanggan & Member
                        </div>
                    </a>

                    <a href="{{ route('payment-methods.index') }}"
                       class="flex items-center gap-3 rounded-xl text-xs font-bold transition-colors relative group {{ ($isSidebarCollapsed ?? false) ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5' }} {{ request()->routeIs('payment-methods.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5'"
                       :title="sidebarCollapsed ? 'Metode Pembayaran' : ''">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <span x-show="!sidebarCollapsed" class="sidebar-text-label truncate whitespace-nowrap" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>Metode Pembayaran</span>

                        <!-- Floating Tooltip on Hover when Collapsed -->
                        <div x-show="sidebarCollapsed"
                             @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif
                             class="hidden lg:group-hover:flex absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none items-center border border-slate-700">
                            Metode Pembayaran
                        </div>
                    </a>
                </div>
            @endif

            <!-- KELOMPOK: LAPORAN (BISA DIAKSES KASIR & ADMIN) -->
            <div class="space-y-1">
                <span x-show="!sidebarCollapsed" class="sidebar-group-heading px-3 text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-400 block truncate" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>
                    Laporan Keuangan
                </span>
                <div x-show="sidebarCollapsed" @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif class="h-px bg-slate-100 dark:bg-slate-800 my-1 mx-2"></div>

                <a href="{{ route('reports.index') }}"
                   class="flex items-center gap-3 rounded-xl text-xs font-bold transition-colors relative group {{ ($isSidebarCollapsed ?? false) ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5' }} {{ request()->routeIs('reports.index') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                   :class="sidebarCollapsed ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5'"
                   :title="sidebarCollapsed ? 'Laporan Penjualan' : ''">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span x-show="!sidebarCollapsed" class="sidebar-text-label truncate whitespace-nowrap" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>Laporan Penjualan</span>

                    <!-- Floating Tooltip on Hover when Collapsed -->
                    <div x-show="sidebarCollapsed"
                         @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif
                         class="hidden lg:group-hover:flex absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none items-center border border-slate-700">
                        Laporan Penjualan
                    </div>
                </a>

                <a href="{{ route('reports.profit_loss') }}"
                   class="flex items-center gap-3 rounded-xl text-xs font-bold transition-colors relative group {{ ($isSidebarCollapsed ?? false) ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5' }} {{ request()->routeIs('reports.profit_loss') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                   :class="sidebarCollapsed ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5'"
                   :title="sidebarCollapsed ? 'Laba & Rugi (P&L)' : ''">
                    <svg class="w-5 h-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-show="!sidebarCollapsed" class="sidebar-text-label truncate whitespace-nowrap" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>Laba & Rugi (P&L)</span>

                    <!-- Floating Tooltip on Hover when Collapsed -->
                    <div x-show="sidebarCollapsed"
                         @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif
                         class="hidden lg:group-hover:flex absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none items-center border border-slate-700">
                        Laba & Rugi (P&L)
                    </div>
                </a>
            </div>

            <!-- KELOMPOK: SISTEM & AKUN -->
            <div class="space-y-1">
                <span x-show="!sidebarCollapsed" class="sidebar-group-heading px-3 text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-400 block truncate" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>
                    Sistem & Akun
                </span>
                <div x-show="sidebarCollapsed" @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif class="h-px bg-slate-100 dark:bg-slate-800 my-1 mx-2"></div>

                @if(optional(auth()->user()->role)->name === 'Admin')
                    <a href="{{ route('settings.index') }}"
                       class="flex items-center gap-3 rounded-xl text-xs font-bold transition-colors relative group {{ ($isSidebarCollapsed ?? false) ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5' }} {{ request()->routeIs('settings.*') && request('tab') !== 'cashiers' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5'"
                       :title="sidebarCollapsed ? 'Pengaturan Toko' : ''">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                        <span x-show="!sidebarCollapsed" class="sidebar-text-label truncate whitespace-nowrap" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>Pengaturan Toko</span>

                        <!-- Floating Tooltip on Hover when Collapsed -->
                        <div x-show="sidebarCollapsed"
                             @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif
                             class="hidden lg:group-hover:flex absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none items-center border border-slate-700">
                            Pengaturan Toko
                        </div>
                    </a>



                    <a href="{{ route('activity-logs.index') }}"
                       class="flex items-center gap-3 rounded-xl text-xs font-bold transition-colors relative group {{ ($isSidebarCollapsed ?? false) ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5' }} {{ request()->routeIs('activity-logs.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5'"
                       :title="sidebarCollapsed ? 'Riwayat Aktivitas' : ''">
                        <svg class="w-5 h-5 shrink-0 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-show="!sidebarCollapsed" class="sidebar-text-label truncate whitespace-nowrap" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>Riwayat Aktivitas</span>

                        <!-- Floating Tooltip on Hover when Collapsed -->
                        <div x-show="sidebarCollapsed"
                             @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif
                             class="hidden lg:group-hover:flex absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none items-center border border-slate-700">
                            Riwayat Aktivitas
                        </div>
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 rounded-xl text-xs font-bold transition-colors relative group {{ ($isSidebarCollapsed ?? false) ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5' }} {{ request()->routeIs('profile.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                   :class="sidebarCollapsed ? 'justify-center px-0 py-3' : 'justify-start px-3 py-2.5'"
                   :title="sidebarCollapsed ? 'Profil Saya' : ''">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span x-show="!sidebarCollapsed" class="sidebar-text-label truncate whitespace-nowrap" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>Profil Saya</span>

                    <!-- Floating Tooltip on Hover when Collapsed -->
                    <div x-show="sidebarCollapsed"
                         @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif
                         class="hidden lg:group-hover:flex absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none items-center border border-slate-700">
                        Profil Saya
                    </div>
                </a>
            </div>

        </nav>
    </div>

    <!-- Bottom Sidebar: Tombol Logout Terpisah (Task 42) -->
    <div class="border-t border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 {{ ($isSidebarCollapsed ?? false) ? 'p-2' : 'p-3.5' }}"
         :class="sidebarCollapsed ? 'p-2' : 'p-3.5'">
        <form method="POST" action="{{ route('logout') }}" onsubmit="confirmSubmit(event, 'Apakah Anda yakin ingin keluar dari sesi POS saat ini?', 'Konfirmasi Keluar', false)">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-2.5 rounded-xl text-xs font-black text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/50 border border-rose-200/80 dark:border-rose-900/50 transition shadow-xs group relative {{ ($isSidebarCollapsed ?? false) ? 'justify-center px-0 py-3' : 'justify-center px-4 py-2.5' }}"
                    :class="sidebarCollapsed ? 'justify-center px-0 py-3' : 'justify-center px-4 py-2.5'"
                    :title="sidebarCollapsed ? 'Keluar (Log Out)' : ''">
                <svg class="w-5 h-5 text-rose-500 group-hover:scale-110 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span x-show="!sidebarCollapsed" class="sidebar-text-label whitespace-nowrap" @if($isSidebarCollapsed ?? false) style="display: none;" @endif>Keluar (Log Out)</span>

                <!-- Floating Tooltip on Hover when Collapsed -->
                <div x-show="sidebarCollapsed"
                     @if(!($isSidebarCollapsed ?? false)) style="display: none;" @endif
                     class="hidden lg:group-hover:flex absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-rose-900 text-white text-[11px] font-bold rounded-lg shadow-xl z-50 whitespace-nowrap pointer-events-none items-center border border-rose-700">
                    Keluar (Log Out)
                </div>
            </button>
        </form>
    </div>

</aside>

<!-- Mobile Overlay Backdrop -->
<div x-show="sidebarOpen"
     @click="sidebarOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-xs lg:hidden"
     style="display: none;">
</div>
