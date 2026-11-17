<x-guest-layout>
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl shadow-slate-200/50 p-6 sm:p-8">
        
        <!-- Header Branding POS (Task 33 & 34) -->
        <div class="text-center mb-6">
            <!-- Logo POS Modern (Task 34) -->
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-600 text-white flex items-center justify-center mx-auto shadow-lg shadow-blue-500/25 mb-3 group hover:scale-105 transition-transform duration-200">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="16" rx="3" stroke-width="2"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h5m-5 4h8"/>
                    <circle cx="16.5" cy="12.5" r="1.5" fill="currentColor"/>
                </svg>
            </div>
            <!-- Nama Aplikasi POS Cukup POS (Task 33) -->
            <div class="inline-flex items-center gap-1.5 justify-center">
                <h1 class="text-3xl font-black bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent tracking-tight">
                    POS
                </h1>
                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-700">
                    Terminal
                </span>
            </div>
            <p class="text-xs text-slate-400 font-medium mt-1">Sistem Kasir & Manajemen Toko Modern</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Alamat Email
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                    </div>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           autocomplete="username"
                           placeholder="nama@toko.com"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
            </div>

            <!-- Password & Forgot Password (Task 40) -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Kata Sandi
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-xs text-blue-600 hover:text-blue-800 font-bold transition flex items-center gap-1" href="{{ route('password.request') }}">
                            <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            Lupa Kata Sandi?
                        </a>
                    @endif
                </div>
                <div class="relative" x-data="{ showPassword: false }">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <input id="password"
                           :type="showPassword ? 'text' : 'password'"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="••••••••"
                           class="w-full pl-10 pr-11 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition">

                    <!-- Tombol Ikon Mata (Show / Hide Password) -->
                    <button type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-blue-600 focus:text-blue-600 transition cursor-pointer"
                            title="Tampilkan / Sembunyikan Kata Sandi">
                        <!-- Ikon Mata Terbuka saat kata sandi tersembunyi (klik untuk melihat) -->
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <!-- Ikon Mata Dicoret saat kata sandi sedang terlihat (klik untuk menyembunyikan) -->
                        <svg x-show="showPassword" x-cloak class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
            </div>

            <!-- Remember Me (Task 14) -->
            <div class="pt-1">
                <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                    <input id="remember_me"
                           type="checkbox"
                           name="remember"
                           class="w-4 h-4 rounded-md border-slate-300 text-blue-600 shadow-xs focus:ring-blue-500 focus:ring-offset-0 transition cursor-pointer">
                    <span class="ms-2.5 text-xs font-semibold text-slate-600 hover:text-slate-800">
                        Ingat Saya (Simpan Sesi Login)
                    </span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-sm shadow-md shadow-blue-500/25 active:scale-98 transition flex items-center justify-center gap-2">
                    <span>Masuk ke Akun</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>

        <!-- Akun Demo Cepat (Footer) -->
        <div class="mt-6 pt-5 border-t border-slate-100 text-center">
            <p class="text-[11px] font-semibold text-slate-400">Akun Terdaftar:</p>
            <div class="mt-2 flex items-center justify-center gap-2 text-[10px] font-mono text-slate-500">
                <span class="px-2 py-1 bg-slate-100 rounded-lg">admin@gmail.com</span>
                <span class="px-2 py-1 bg-slate-100 rounded-lg">kasir@gmail.com</span>
            </div>
        </div>

    </div>
</x-guest-layout>