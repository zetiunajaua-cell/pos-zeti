<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">

            <div class="flex items-center gap-10">

                <!-- Logo POS Zeti -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-extrabold text-blue-600 tracking-tight hover:opacity-80 transition">
                        POS Zeti
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:flex items-center">

                    <a href="{{ route('dashboard') }}" 
                       class="text-xl font-bold transition-colors py-2 border-b-2 {{ request()->routeIs('dashboard') ? 'text-blue-600 border-blue-600' : 'text-gray-600 border-transparent hover:text-blue-600' }}">
                        Dashboard
                    </a>

                    {{-- MENU KHUSUS ADMIN --}}
                    @if(optional(auth()->user()->role)->name === 'Admin')

                        <a href="{{ route('products.index') }}" 
                           class="text-xl font-bold transition-colors py-2 border-b-2 {{ request()->routeIs('products.*') ? 'text-blue-600 border-blue-600' : 'text-gray-600 border-transparent hover:text-blue-600' }}">
                            Products
                        </a>

                        <a href="{{ route('categories.index') }}" 
                           class="text-xl font-bold transition-colors py-2 border-b-2 {{ request()->routeIs('categories.*') ? 'text-blue-600 border-blue-600' : 'text-gray-600 border-transparent hover:text-blue-600' }}">
                            Categories
                        </a>

                    {{-- MENU KHUSUS KASIR --}}
                    @elseif(optional(auth()->user()->role)->name === 'Kasir')

                        <a href="{{ route('transactions.index') }}" 
                           class="text-xl font-bold transition-colors py-2 border-b-2 {{ request()->routeIs('transactions.*') ? 'text-blue-600 border-blue-600' : 'text-gray-600 border-transparent hover:text-blue-600' }}">
                            Transaksi
                        </a>

                    @endif

                    {{-- MENU LAPORAN (BISA DIAKSES ADMIN & KASIR) --}}
                    <a href="{{ route('reports.index') }}" 
                       class="text-xl font-bold transition-colors py-2 border-b-2 {{ request()->routeIs('reports.*') ? 'text-blue-600 border-blue-600' : 'text-gray-600 border-transparent hover:text-blue-600' }}">
                        Laporan
                    </a>

                </div>

            </div>

            <!-- Profile Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative" x-data="{ openProfile: false }">
                    <button @click="openProfile = !openProfile" class="inline-flex items-center gap-2 text-xl font-bold text-gray-700 hover:text-blue-600 transition focus:outline-none">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="openProfile" @click.away="openProfile = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-100 z-50" style="display: none;">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-base text-gray-700 hover:bg-gray-100">
                            Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-base text-gray-700 hover:bg-gray-100" onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-xl font-bold text-gray-700 hover:bg-gray-100">Dashboard</a>
            @if(optional(auth()->user()->role)->name === 'Admin')
                <a href="{{ route('products.index') }}" class="block px-4 py-2 text-xl font-bold text-gray-700 hover:bg-gray-100">Products</a>
                <a href="{{ route('categories.index') }}" class="block px-4 py-2 text-xl font-bold text-gray-700 hover:bg-gray-100">Categories</a>
            @elseif(optional(auth()->user()->role)->name === 'Kasir')
                <a href="{{ route('transactions.index') }}" class="block px-4 py-2 text-xl font-bold text-gray-700 hover:bg-gray-100">Transaksi</a>
            @endif
            <a href="{{ route('reports.index') }}" class="block px-4 py-2 text-xl font-bold text-gray-700 hover:bg-gray-100">Laporan</a>
        </div>
    </div>
</nav>