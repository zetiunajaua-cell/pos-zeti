<x-guest-layout maxWidth="max-w-5xl">
    <div class="bg-white rounded-3xl shadow-2xl shadow-slate-300/60 border border-slate-200/90 overflow-hidden grid grid-cols-1 lg:grid-cols-12">
        
        <!-- ========================================== -->
        <!-- PANEL KIRI: SHOWCASE FITUR & MOCKUP POS    -->
        <!-- (Brand POS Zeti: Royal Blue & Indigo)      -->
        <!-- ========================================== -->
        <div class="lg:col-span-7 bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-700 p-8 lg:p-10 text-white flex flex-col justify-between relative overflow-hidden">
            <!-- Background Glow & Shapes -->
            <div class="absolute -top-24 -left-24 w-72 h-72 bg-white/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-blue-400/25 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:20px_20px] opacity-10 pointer-events-none"></div>

            <!-- Top Header Showcase: Logo POS Zeti & Sparkles -->
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-white text-blue-600 flex items-center justify-center shadow-md shadow-blue-950/20 font-black">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="16" rx="3" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h5m-5 4h8"/>
                            <circle cx="16.5" cy="12.5" r="1.5" fill="currentColor"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-xl font-black tracking-tight text-white">POS Zeti</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-white/20 text-white backdrop-blur-xs">
                                Smart POS
                            </span>
                        </div>
                        <p class="text-xs text-blue-100 font-medium">Sistem Kasir & Manajemen Toko Modern</p>
                    </div>
                </div>

                <!-- Sparkle Kanan Atas -->
                <div class="text-blue-200 animate-pulse hidden sm:block">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                        <path d="M12 0l2.5 8.5L23 11l-8.5 2.5L12 22l-2.5-8.5L1 11l8.5-2.5z"/>
                    </svg>
                </div>
            </div>

            <!-- Central Mockup: POS Monitor & Floating Thermal Receipt -->
            <div class="relative z-10 my-8 py-2">
                <!-- Sparkle Dekorasi Kiri -->
                <div class="absolute -top-4 left-6 text-amber-300 animate-bounce">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M12 0l2.5 8.5L23 11l-8.5 2.5L12 22l-2.5-8.5L1 11l8.5-2.5z"/>
                    </svg>
                </div>

                <!-- Ilustrasi Monitor POS (Desktop Terminal) -->
                <div class="relative mx-auto max-w-[420px] bg-slate-900 rounded-2xl p-2.5 shadow-2xl shadow-blue-950/40 border border-blue-400/30">
                    <!-- Monitor Header Bar -->
                    <div class="bg-slate-800 rounded-t-xl px-3 py-1.5 flex items-center justify-between border-b border-slate-700">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        </div>
                        <span class="text-[10px] font-mono text-slate-400 font-semibold">terminal.pos-zeti.local</span>
                        <span class="w-3"></span>
                    </div>

                    <!-- Layar Dalam Monitor (Cashier POS UI Mockup) -->
                    <div class="bg-slate-100 rounded-b-xl p-3 text-slate-800 grid grid-cols-12 gap-2.5">
                        
                        <!-- Kiri Layar: Katalog Menu Cepat -->
                        <div class="col-span-7 space-y-2">
                            <!-- Kategori Tabs -->
                            <div class="flex items-center gap-1 text-[9px] font-bold">
                                <span class="px-2 py-0.5 rounded-md bg-blue-600 text-white">Semua</span>
                                <span class="px-2 py-0.5 rounded-md bg-white text-slate-600 border border-slate-200">☕ Kopi</span>
                                <span class="px-2 py-0.5 rounded-md bg-white text-slate-600 border border-slate-200">🍞 Snack</span>
                            </div>

                            <!-- Grid Produk Mini -->
                            <div class="grid grid-cols-2 gap-1.5">
                                <div class="bg-white p-2 rounded-lg border border-slate-200 shadow-2xs">
                                    <div class="w-full h-8 bg-amber-100 rounded flex items-center justify-center text-base mb-1">☕</div>
                                    <p class="text-[9px] font-bold truncate text-slate-800">Espresso Roast</p>
                                    <p class="text-[8px] font-extrabold text-blue-600">Rp 18.000</p>
                                </div>
                                <div class="bg-white p-2 rounded-lg border border-slate-200 shadow-2xs">
                                    <div class="w-full h-8 bg-orange-100 rounded flex items-center justify-center text-base mb-1">🥐</div>
                                    <p class="text-[9px] font-bold truncate text-slate-800">Croissant</p>
                                    <p class="text-[8px] font-extrabold text-blue-600">Rp 22.000</p>
                                </div>
                                <div class="bg-white p-2 rounded-lg border border-slate-200 shadow-2xs">
                                    <div class="w-full h-8 bg-indigo-100 rounded flex items-center justify-center text-base mb-1">🍵</div>
                                    <p class="text-[9px] font-bold truncate text-slate-800">Matcha Ice</p>
                                    <p class="text-[8px] font-extrabold text-blue-600">Rp 24.000</p>
                                </div>
                                <div class="bg-white p-2 rounded-lg border border-slate-200 shadow-2xs">
                                    <div class="w-full h-8 bg-blue-100 rounded flex items-center justify-center text-base mb-1">🥪</div>
                                    <p class="text-[9px] font-bold truncate text-slate-800">Toast Cheese</p>
                                    <p class="text-[8px] font-extrabold text-blue-600">Rp 20.000</p>
                                </div>
                            </div>
                        </div>

                        <!-- Kanan Layar: Keranjang Kasir & Tombol Bayar -->
                        <div class="col-span-5 bg-white rounded-lg p-2 border border-slate-200 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between border-b border-slate-100 pb-1 mb-1.5">
                                    <span class="text-[9px] font-black text-slate-800">Pesanan</span>
                                    <span class="text-[8px] font-black px-1.5 py-0.5 rounded bg-blue-100 text-blue-700">#04</span>
                                </div>
                                <div class="space-y-1 text-[8px]">
                                    <div class="flex justify-between font-medium">
                                        <span class="truncate">1x Espresso</span>
                                        <span class="font-bold">18k</span>
                                    </div>
                                    <div class="flex justify-between font-medium">
                                        <span class="truncate">1x Croissant</span>
                                        <span class="font-bold">22k</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-100 mt-2">
                                <div class="flex justify-between text-[9px] font-black text-slate-800 mb-1">
                                    <span>Total:</span>
                                    <span class="text-blue-600 font-bold">Rp 40.000</span>
                                </div>
                                <div class="w-full py-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded text-center text-[9px] font-extrabold shadow-xs">
                                    Bayar Kasir
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Kaki Monitor -->
                <div class="w-16 h-3 bg-slate-700 mx-auto rounded-b-md"></div>
                <div class="w-24 h-1.5 bg-slate-800 mx-auto rounded-full shadow-md"></div>

                <!-- Floating Thermal Receipt Slip (Meniru Struk Kasir di Contoh Gambar) -->
                <div class="absolute -bottom-4 -left-2 sm:left-4 bg-white text-slate-800 rounded-xl p-3 shadow-2xl border border-slate-100 w-48 sm:w-52 -rotate-3 hover:rotate-0 transition-transform duration-300">
                    <div class="flex items-center justify-between border-b border-dashed border-slate-200 pb-1.5 mb-1.5">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-ping"></span>
                            <span class="text-[9px] font-black text-slate-800 uppercase tracking-wider">POS Zeti Slip</span>
                        </div>
                        <span class="text-[8px] font-mono text-slate-400">#04-LUNAS</span>
                    </div>
                    <div class="space-y-1 text-[8px] text-slate-600">
                        <div class="flex justify-between font-semibold">
                            <span>1x Espresso (Less Sugar)</span>
                            <span>18.000</span>
                        </div>
                        <div class="flex justify-between font-semibold">
                            <span>1x Butter Croissant</span>
                            <span>22.000</span>
                        </div>
                    </div>
                    <div class="mt-2 pt-1.5 border-t border-dashed border-slate-200 flex items-center justify-between">
                        <span class="text-[9px] font-bold text-slate-500">Total Akhir:</span>
                        <span class="text-[10px] font-black text-blue-600">Rp 40.000</span>
                    </div>
                    <!-- Garis Barcode Mini -->
                    <div class="mt-1.5 flex items-center justify-center gap-0.5 opacity-60">
                        <span class="w-1 h-3 bg-slate-800"></span>
                        <span class="w-0.5 h-3 bg-slate-800"></span>
                        <span class="w-1.5 h-3 bg-slate-800"></span>
                        <span class="w-0.5 h-3 bg-slate-800"></span>
                        <span class="w-1 h-3 bg-slate-800"></span>
                        <span class="w-0.5 h-3 bg-slate-800"></span>
                        <span class="w-2 h-3 bg-slate-800"></span>
                        <span class="w-0.5 h-3 bg-slate-800"></span>
                        <span class="w-1 h-3 bg-slate-800"></span>
                    </div>
                </div>

                <!-- Sparkle Dekorasi Kanan Bawah -->
                <div class="absolute -bottom-2 right-4 text-blue-200">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                        <path d="M12 0l2.5 8.5L23 11l-8.5 2.5L12 22l-2.5-8.5L1 11l8.5-2.5z"/>
                    </svg>
                </div>
            </div>

            <!-- Bottom Value Proposition: Icon Bulat & Deskripsi (Sesuai Referensi Gambar) -->
            <div class="relative z-10 pt-4 border-t border-blue-400/30 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-900/60 border border-blue-400/40 flex items-center justify-center text-white shrink-0 shadow-inner">
                    <!-- Icon Scanner/POS Device -->
                    <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm sm:text-base font-black text-white leading-tight">
                        Tersedia untuk Desktop, Tablet, & Smartphone
                    </h3>
                    <p class="text-xs text-blue-100 font-medium mt-0.5">
                        Kelola pesanan kasir, cetak struk thermal, dan pantau stok toko real-time.
                    </p>
                </div>
            </div>

        </div>

        <!-- ========================================== -->
        <!-- PANEL KANAN: FORM LOGIN & AKUN DEMO        -->
        <!-- (Aksen Brand POS Zeti: Blue-Indigo)        -->
        <!-- ========================================== -->
        <div class="lg:col-span-5 p-8 sm:p-10 lg:p-12 flex flex-col justify-between bg-white"
             x-data="{ 
                email: '{{ old('email', '') }}', 
                password: '', 
                showPassword: false 
             }">
            
            <div>
                <!-- Top Brand Icon & Localization Badge -->
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/25">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="16" rx="3" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h5m-5 4h8"/>
                            <circle cx="16.5" cy="12.5" r="1.5" fill="currentColor"/>
                        </svg>
                    </div>

                    <!-- Badge POS Terminal & Bendera ID -->
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200/60">
                            POS Terminal
                        </span>
                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold" title="Bahasa Indonesia">
                            <span class="text-sm">🇮🇩</span>
                            <span class="text-[11px] font-bold">ID</span>
                        </div>
                    </div>
                </div>

                <!-- Form Heading -->
                <div class="mb-6">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Login
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                        Kamu dapat masuk sebagai Admin ataupun Kasir
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Form Login -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input id="email"
                                   type="email"
                                   name="email"
                                   x-model="email"
                                   required
                                   autofocus
                                   autocomplete="username"
                                   placeholder="Email akun Anda"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                    </div>

                    <!-- Password Input with Toggle Eye & Forgot Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Password
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-xs text-blue-600 hover:text-blue-700 font-bold transition flex items-center gap-1" href="{{ route('password.request') }}">
                                    Lupa Kata Sandi?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password"
                                   :type="showPassword ? 'text' : 'password'"
                                   name="password"
                                   x-model="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="Password"
                                   class="w-full pl-10 pr-11 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition">

                            <!-- Tombol Ikon Mata (Show / Hide Password) -->
                            <button type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-blue-600 focus:text-blue-600 transition cursor-pointer"
                                    title="Tampilkan / Sembunyikan Kata Sandi">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                    </div>

                    <!-- Remember Me -->
                    <div class="pt-1 flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                            <input id="remember_me"
                                   type="checkbox"
                                   name="remember"
                                   class="w-4 h-4 rounded-md border-slate-300 text-blue-600 shadow-xs focus:ring-blue-500 focus:ring-offset-0 transition cursor-pointer">
                            <span class="ms-2 text-xs font-semibold text-slate-600 hover:text-slate-800">
                                Ingat Saya
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button: Login (Sesuai Biru Brand POS Zeti) -->
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 hover:from-blue-700 hover:via-indigo-700 hover:to-violet-700 active:scale-[0.99] text-white font-black text-sm shadow-lg shadow-blue-500/25 transition flex items-center justify-center gap-2 cursor-pointer">
                            <span>Login</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Divider & 1-Click Demo Login -->
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <p class="text-center text-xs text-slate-400 font-semibold mb-3">
                        Atau pilih akun demo untuk masuk cepat:
                    </p>

                    <div class="grid grid-cols-2 gap-2">
                        <!-- Tombol Cepat Akun Admin -->
                        <button type="button"
                                @click="email = 'admin@gmail.com'; password = 'password'"
                                class="flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 transition group cursor-pointer">
                            <span class="text-base group-hover:scale-110 transition">👑</span>
                            <div class="text-left">
                                <p class="text-xs font-bold text-slate-800 group-hover:text-blue-700">Admin</p>
                                <p class="text-[10px] font-mono text-slate-400">admin@gmail.com</p>
                            </div>
                        </button>

                        <!-- Tombol Cepat Akun Kasir -->
                        <button type="button"
                                @click="email = 'kasir@gmail.com'; password = 'password'"
                                class="flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 transition group cursor-pointer">
                            <span class="text-base group-hover:scale-110 transition">☕</span>
                            <div class="text-left">
                                <p class="text-xs font-bold text-slate-800 group-hover:text-blue-700">Kasir</p>
                                <p class="text-[10px] font-mono text-slate-400">kasir@gmail.com</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer Hak Cipta -->
            <div class="mt-8 text-center text-xs text-slate-400">
                <p>Belum punya akun? Hubungi Admin Toko</p>
                <p class="text-[10px] text-slate-300 mt-1">POS Zeti Coffee & Retail System v1.3</p>
            </div>

        </div>

    </div>
</x-guest-layout>
