<x-guest-layout>
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl shadow-slate-200/50 p-6 sm:p-8">
        
        <!-- Header Branding POS (Task 33, 34, 40) -->
        <div class="text-center mb-6">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-600 text-white flex items-center justify-center mx-auto shadow-lg shadow-blue-500/25 mb-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                Pemulihan Kata Sandi
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-1.5 leading-relaxed">
                Lupa kata sandi akun Anda? Masukkan alamat email yang terdaftar dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi baru.
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Alamat Email Terdaftar
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
                           placeholder="nama@toko.com"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-sm shadow-md shadow-blue-500/25 active:scale-98 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Kirim Tautan Reset Sandi</span>
                </button>
            </div>
        </form>

        <div class="mt-6 pt-5 border-t border-slate-100 text-center">
            <a href="{{ route('login') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition inline-flex items-center gap-1.5">
                &larr; Kembali ke Halaman Login
            </a>
        </div>

    </div>
</x-guest-layout>