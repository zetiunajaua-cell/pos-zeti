@php
    $isSidebarCollapsed = request()->cookie('pos_sidebar_collapsed') === 'true';
    $isDarkTheme = request()->cookie('pos_theme') === 'dark';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $isSidebarCollapsed ? 'sidebar-collapsed' : '' }} {{ $isDarkTheme ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'POS') }} - Sistem Kasir</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Sync Client State with Cookies on first paint -->
        <script>
            (function() {
                var lsTheme = localStorage.getItem('pos_theme');
                if (lsTheme === 'dark' || (!lsTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                    document.cookie = "pos_theme=dark; path=/; max-age=31536000; SameSite=Lax";
                } else if (lsTheme === 'light') {
                    document.documentElement.classList.remove('dark');
                    document.cookie = "pos_theme=light; path=/; max-age=31536000; SameSite=Lax";
                }

                var lsCollapsed = localStorage.getItem('pos_sidebar_collapsed');
                if (lsCollapsed === 'true') {
                    document.documentElement.classList.add('sidebar-collapsed');
                    document.cookie = "pos_sidebar_collapsed=true; path=/; max-age=31536000; SameSite=Lax";
                } else if (lsCollapsed === 'false') {
                    document.documentElement.classList.remove('sidebar-collapsed');
                    document.cookie = "pos_sidebar_collapsed=false; path=/; max-age=31536000; SameSite=Lax";
                }
            })();
        </script>

        <style>
            [x-cloak] { display: none !important; }

            /* Static Zero Transition on Page Navigation (Mencegah Layar Menciut/Mengecil Saat Berpindah Halaman) */
            aside#main-sidebar,
            #main-content-wrapper {
                transition: none;
            }

            /* Responsive Sidebar Width & Content Area Padding */
            @media (min-width: 1024px) {
                /* Sidebar Buka (Lebar 16rem / 256px) */
                aside#main-sidebar {
                    width: 16rem;
                }
                #main-content-wrapper {
                    padding-left: 16rem;
                }

                /* Sidebar Tutup / Collapsed (Sidebar 5rem / 80px, Konten Langsung Otomatis Membesar) */
                html.sidebar-collapsed aside#main-sidebar {
                    width: 5rem !important;
                }
                html.sidebar-collapsed #main-content-wrapper {
                    padding-left: 5rem !important;
                }
            }

            /* Transisi Halus HANYA aktif saat tombol buka/tutup sidebar diklik secara fisik */
            body.sidebar-animating aside#main-sidebar,
            body.sidebar-animating #main-content-wrapper {
                transition: width 0.22s cubic-bezier(0.4, 0, 0.2, 1), padding-left 0.22s cubic-bezier(0.4, 0, 0.2, 1) !important;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 dark:text-slate-100 bg-slate-100 dark:bg-slate-950">
        
        <div x-data="{ 
            sidebarOpen: false, 
            sidebarCollapsed: {{ $isSidebarCollapsed ? 'true' : 'false' }} || (localStorage.getItem('pos_sidebar_collapsed') === 'true'),
            toggleSidebarCollapse() {
                document.body.classList.add('sidebar-animating');
                this.sidebarCollapsed = !this.sidebarCollapsed;
                localStorage.setItem('pos_sidebar_collapsed', this.sidebarCollapsed);
                document.cookie = 'pos_sidebar_collapsed=' + this.sidebarCollapsed + '; path=/; max-age=31536000; SameSite=Lax';
                if (this.sidebarCollapsed) {
                    document.documentElement.classList.add('sidebar-collapsed');
                } else {
                    document.documentElement.classList.remove('sidebar-collapsed');
                }
                setTimeout(() => {
                    document.body.classList.remove('sidebar-animating');
                }, 220);
            },
            isDark: document.documentElement.classList.contains('dark'),
            toggleDark() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('pos_theme', 'light');
                    document.cookie = 'pos_theme=light; path=/; max-age=31536000; SameSite=Lax';
                    this.isDark = false;
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('pos_theme', 'dark');
                    document.cookie = 'pos_theme=dark; path=/; max-age=31536000; SameSite=Lax';
                    this.isDark = true;
                }
            }
        }" class="min-h-screen bg-slate-100 dark:bg-slate-950">

            <!-- SIDEBAR NAVIGATION (Task 34, 35, 41, 42 & Buka Tutup Sidebar) -->
            @include('layouts.sidebar', ['isSidebarCollapsed' => $isSidebarCollapsed])

            <!-- MAIN CONTENT AREA (OTOMATIS MELEBAR KETIKA SIDEBAR DITUTUP) -->
            <div id="main-content-wrapper"
                 class="flex flex-col min-h-screen">
                
                <!-- TOPBAR & SEARCH BAR (Task 43 & 44) -->
                @include('layouts.topbar')

                <!-- PAGE HEADING -->
                @if (isset($header))
                    <header class="bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 shadow-xs transition-colors duration-150">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- MAIN PAGE CONTENT -->
                <main class="flex-1 pb-12">
                    {{ $slot }}
                </main>
            </div>

        </div>

        <!-- Session Flash SweetAlert2 Toast Notifications -->
        @if(session('success') || session('error') || session('warning') || session('info') || session('status'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    @if(session('success'))
                        if (window.showToast) window.showToast(@json(session('success')), 'success');
                    @elseif(session('status'))
                        if (window.showToast) window.showToast(@json(session('status')), 'success');
                    @endif

                    @if(session('error'))
                        if (window.showToast) window.showToast(@json(session('error')), 'error');
                    @endif

                    @if(session('warning'))
                        if (window.showToast) window.showToast(@json(session('warning')), 'warning');
                    @endif

                    @if(session('info'))
                        if (window.showToast) window.showToast(@json(session('info')), 'info');
                    @endif
                });
            </script>
        @endif

    </body>
</html>