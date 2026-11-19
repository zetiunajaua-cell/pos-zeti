<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil Pengguna
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Kelola data identitas diri, kata sandi akun, serta tinjau performa dan riwayat aktivitas Anda.
                </p>
            </div>

            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-2xs">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ 
        activeTab: 'profile',
        showCurrentPass: false,
        showNewPass: false,
        showConfirmPass: false,
        showDeleteModal: false
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Success / Status Alerts -->
            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold flex items-center justify-between shadow-xs transition">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Informasi profil akun Anda berhasil diperbarui.</span>
                    </div>
                    <button type="button" @click="show = false" class="text-emerald-600 hover:text-emerald-800">&times;</button>
                </div>
            @endif

            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold flex items-center justify-between shadow-xs transition">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Kata sandi akun Anda berhasil diperbarui dengan aman.</span>
                    </div>
                    <button type="button" @click="show = false" class="text-emerald-600 hover:text-emerald-800">&times;</button>
                </div>
            @endif

            <!-- HERO PROFILE CARD (DISESUAIKAN DENGAN UI/UX POS ZETI) -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-700/60 relative overflow-hidden">
                <!-- Background Glow -->
                <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full {{ $roleName === 'Admin' ? 'bg-purple-500/10' : 'bg-emerald-500/10' }} blur-3xl pointer-events-none"></div>

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
                    
                    <!-- Left: Identity & Role -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                        <!-- Avatar Profil -->
                        <div class="relative shrink-0">
                            @if($user->avatar_url)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                     class="w-20 h-20 rounded-3xl object-cover ring-4 {{ $roleName === 'Admin' ? 'ring-purple-100 dark:ring-purple-900/40' : 'ring-blue-100 dark:ring-blue-900/40' }} shadow-lg">
                            @else
                                <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr {{ $roleName === 'Admin' ? 'from-purple-600 to-indigo-600 ring-4 ring-purple-100 dark:ring-purple-900/40' : 'from-blue-600 to-teal-600 ring-4 ring-blue-100 dark:ring-blue-900/40' }} text-white font-black text-2xl flex items-center justify-center shadow-lg">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif
                            <!-- Role Badge Icon Overlay -->
                            <div class="absolute -bottom-1 -right-1 w-7 h-7 rounded-xl {{ $roleName === 'Admin' ? 'bg-purple-700 text-white' : 'bg-emerald-600 text-white' }} flex items-center justify-center shadow-md">
                                @if($roleName === 'Admin')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                @endif
                            </div>
                        </div>

                        <!-- Name & Credentials -->
                        <div class="space-y-1.5">
                            <div class="flex flex-wrap items-center gap-2.5">
                                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                                    {{ $user->name }}
                                </h1>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $roleName === 'Admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' }}">
                                    {{ $roleName === 'Admin' ? 'Super Administrator' : 'Kasir Terminal POS' }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                    Aktif
                                </span>
                            </div>

                            <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span>{{ $user->email }}</span>
                                <span>&bull;</span>
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Bergabung sejak {{ $user->created_at->format('d M Y') }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Right: Quick Role Stats -->
                    <div class="grid grid-cols-2 sm:grid-cols-2 gap-3 shrink-0">
                        @if($roleName === 'Kasir')
                            <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Transaksi</span>
                                <span class="text-base font-black text-slate-900 dark:text-white mt-0.5 block">
                                    {{ number_format($stats['total_transactions'] ?? 0) }} Struk
                                </span>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Omset Tercapai</span>
                                <span class="text-base font-black text-blue-600 dark:text-blue-400 mt-0.5 block">
                                    Rp {{ number_format($stats['total_sales'] ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        @else
                            <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Katalog Produk</span>
                                <span class="text-base font-black text-slate-900 dark:text-white mt-0.5 block">
                                    {{ number_format($stats['total_products'] ?? 0) }} Item
                                </span>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Log Aktivitas</span>
                                <span class="text-base font-black text-purple-600 dark:text-purple-400 mt-0.5 block">
                                    {{ number_format($stats['total_activities'] ?? 0) }} Aktivitas
                                </span>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            <!-- TABS NAVIGATION -->
            <div class="flex flex-wrap gap-2 border-b border-slate-200 dark:border-slate-800 pb-2">
                <button type="button" @click="activeTab = 'profile'"
                        :class="activeTab === 'profile' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Informasi Akun
                </button>

                <button type="button" @click="activeTab = 'security'"
                        :class="activeTab === 'security' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Keamanan & Kata Sandi
                </button>

                <button type="button" @click="activeTab = 'activity'"
                        :class="activeTab === 'activity' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                    @if($roleName === 'Kasir')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Riwayat Transaksi Saya
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Riwayat Aktivitas Log
                    @endif
                </button>

                <button type="button" @click="activeTab = 'danger'"
                        :class="activeTab === 'danger' ? 'bg-rose-600 text-white shadow-md shadow-rose-500/20' : 'bg-white dark:bg-slate-800 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-slate-700'"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 ml-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus Akun
                </button>
            </div>

            <!-- TAB 1: INFORMASI AKUN -->
            <div x-show="activeTab === 'profile'" class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-700/60 space-y-6">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Informasi Data Pengguna</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Unggah foto profil pribadi serta perbarui nama lengkap dan alamat email akun kasir/admin Anda.</p>
                </div>

                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6"
                      x-data="{ 
                          photoName: null, 
                          photoPreview: null,
                          deleteAvatar: false,
                          hasExistingAvatar: {{ $user->avatar ? 'true' : 'false' }}
                      }">
                    @csrf
                    @method('patch')

                    <!-- Input Hidden Status Hapus Foto -->
                    <input type="hidden" name="delete_avatar" :value="deleteAvatar ? '1' : '0'">

                    <!-- SECTION UPLOAD FOTO PROFIL -->
                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-700/80">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-3">
                            Foto Profil Akun
                        </label>
                        
                        <div class="flex flex-col sm:flex-row items-center gap-5">
                            <!-- Preview Foto Rounded 3xl -->
                            <div class="relative shrink-0 group">
                                <!-- File input tersembunyi -->
                                <input type="file" id="avatar" name="avatar" class="hidden" x-ref="photoInput"
                                       accept="image/png,image/jpeg,image/jpg,image/webp"
                                       @change="
                                           photoName = $refs.photoInput.files[0]?.name;
                                           const reader = new FileReader();
                                           reader.onload = (e) => {
                                               photoPreview = e.target.result;
                                               deleteAvatar = false;
                                           };
                                           if ($refs.photoInput.files[0]) {
                                               reader.readAsDataURL($refs.photoInput.files[0]);
                                           }
                                       ">

                                <!-- Tampilan saat user memilih file foto baru (live preview) -->
                                <template x-if="photoPreview && !deleteAvatar">
                                    <div class="relative">
                                        <img :src="photoPreview" alt="Preview Foto" class="w-24 h-24 rounded-3xl object-cover ring-4 ring-blue-500/30 shadow-md">
                                        <span class="absolute top-1 right-1 px-1.5 py-0.5 rounded-md bg-blue-600 text-white text-[9px] font-black uppercase">
                                            Baru
                                        </span>
                                    </div>
                                </template>

                                <!-- Tampilan foto profil saat ini (jika ada dan tidak dihapus) -->
                                <template x-if="!photoPreview && hasExistingAvatar && !deleteAvatar">
                                    <div class="relative">
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-3xl object-cover ring-4 ring-slate-200 dark:ring-slate-700 shadow-md">
                                    </div>
                                </template>

                                <!-- Tampilan inisial default (jika belum ada foto atau dipilih untuk dihapus) -->
                                <template x-if="(!photoPreview && !hasExistingAvatar) || deleteAvatar">
                                    <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr {{ $roleName === 'Admin' ? 'from-purple-600 to-indigo-600 ring-4 ring-purple-100 dark:ring-purple-900/40' : 'from-blue-600 to-teal-600 ring-4 ring-blue-100 dark:ring-blue-900/40' }} text-white font-black text-3xl flex items-center justify-center shadow-md">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                </template>

                                <!-- Tombol Overlay Kamera untuk Klik Cepat -->
                                <button type="button" @click="$refs.photoInput.click()"
                                        class="absolute inset-0 rounded-3xl bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white text-[11px] font-bold cursor-pointer backdrop-blur-2xs"
                                        title="Klik untuk memilih foto baru">
                                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Ubah
                                </button>
                            </div>

                            <!-- Tombol Aksi & Keterangan Format -->
                            <div class="space-y-2 text-center sm:text-left flex-1">
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                                    <button type="button" @click="$refs.photoInput.click()"
                                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span x-text="photoPreview || hasExistingAvatar ? 'Ganti Foto' : 'Pilih Foto Sendiri'">Pilih Foto Sendiri</span>
                                    </button>

                                    <!-- Tombol Batal / Hapus Foto -->
                                    <template x-if="photoPreview">
                                        <button type="button" @click="photoPreview = null; photoName = null; $refs.photoInput.value = ''"
                                                class="px-3.5 py-2 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 text-xs font-semibold transition cursor-pointer">
                                            Batal Pilih
                                        </button>
                                    </template>

                                    <template x-if="!photoPreview && hasExistingAvatar && !deleteAvatar">
                                        <button type="button" @click="deleteAvatar = true"
                                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-xs font-bold transition cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus Foto
                                        </button>
                                    </template>

                                    <template x-if="deleteAvatar">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold">
                                            <span>Foto akan dihapus saat disimpan</span>
                                            <button type="button" @click="deleteAvatar = false" class="underline ml-1 cursor-pointer">Batal</button>
                                        </span>
                                    </template>
                                </div>

                                <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                    Format didukung: JPG, JPEG, PNG, WEBP (Maksimal 2 MB). Foto akan ditampilkan di profil, header atas, dan menu akun Anda.
                                </p>
                                @if ($errors->has('avatar'))
                                    <p class="mt-1 text-xs text-rose-500 font-bold">{{ $errors->first('avatar') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input id="name" name="name" type="text"
                                   value="{{ old('name', $user->name) }}" required autofocus
                                   class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                            @if ($errors->has('name'))
                                <p class="mt-1 text-xs text-rose-500 font-medium">{{ $errors->first('name') }}</p>
                            @endif
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                Alamat Email <span class="text-rose-500">*</span>
                            </label>
                            <input id="email" name="email" type="email"
                                   value="{{ old('email', $user->email) }}" required
                                   class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                            @if ($errors->has('email'))
                                <p class="mt-1 text-xs text-rose-500 font-medium">{{ $errors->first('email') }}</p>
                            @endif
                        </div>

                        <!-- Peran Pengguna (Readonly) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                Peran / Hak Akses Sistem
                            </label>
                            <input type="text" value="{{ $roleName }}" disabled
                                   class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs font-bold cursor-not-allowed">
                            <p class="text-[10px] text-slate-400 mt-1">Peran akun ditentukan langsung oleh manajemen administrator.</p>
                        </div>

                        <!-- Tanggal Akun Dibuat (Readonly) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                Tanggal Registrasi
                            </label>
                            <input type="text" value="{{ $user->created_at->format('d F Y, H:i:s') }}" disabled
                                   class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs font-semibold cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Email Verification Notice if Applicable -->
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-xs">
                            <p>
                                Alamat email Anda belum terverifikasi.
                                <button form="send-verification" class="underline font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800">
                                    Klik di sini untuk mengirim ulang tautan verifikasi.
                                </button>
                            </p>
                        </div>
                    @endif

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold shadow-md shadow-blue-500/25 transition">
                            Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: KEAMANAN & KATA SANDI -->
            <div x-show="activeTab === 'security'" class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-700/60 space-y-6" style="display: none;">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Perbarui Kata Sandi</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Pastikan akun Anda menggunakan kata sandi yang panjang dan aman demi menjaga integritas data transaksi kasir.</p>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="space-y-5 max-w-xl">
                    @csrf
                    @method('put')

                    <!-- Kata Sandi Saat Ini -->
                    <div>
                        <label for="update_password_current_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                            Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input id="update_password_current_password" name="current_password"
                                   :type="showCurrentPass ? 'text' : 'password'"
                                   autocomplete="current-password"
                                   class="w-full pr-10 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" @click="showCurrentPass = !showCurrentPass"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                                <svg x-show="!showCurrentPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showCurrentPass" class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        @if ($errors->updatePassword->has('current_password'))
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $errors->updatePassword->first('current_password') }}</p>
                        @endif
                    </div>

                    <!-- Kata Sandi Baru -->
                    <div>
                        <label for="update_password_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                            Kata Sandi Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input id="update_password_password" name="password"
                                   :type="showNewPass ? 'text' : 'password'"
                                   autocomplete="new-password"
                                   class="w-full pr-10 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" @click="showNewPass = !showNewPass"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                                <svg x-show="!showNewPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showNewPass" class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        @if ($errors->updatePassword->has('password'))
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $errors->updatePassword->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Konfirmasi Kata Sandi Baru -->
                    <div>
                        <label for="update_password_password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                            Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input id="update_password_password_confirmation" name="password_confirmation"
                                   :type="showConfirmPass ? 'text' : 'password'"
                                   autocomplete="new-password"
                                   class="w-full pr-10 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" @click="showConfirmPass = !showConfirmPass"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                                <svg x-show="!showConfirmPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showConfirmPass" class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        @if ($errors->updatePassword->has('password_confirmation'))
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold shadow-md shadow-blue-500/25 transition">
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 3: RIWAYAT KINERJA / AKTIVITAS -->
            <div x-show="activeTab === 'activity'" class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-700/60 space-y-6" style="display: none;">
                @if($roleName === 'Kasir')
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Riwayat Transaksi Kasir Anda</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Menampilkan 5 transaksi terakhir yang Anda proses di kasir.</p>
                        </div>
                        <a href="{{ route('transactions.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 text-white hover:bg-blue-700 transition">
                            + Transaksi Baru
                        </a>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-700">
                        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead class="bg-slate-50/80 dark:bg-slate-900/60 text-[11px] font-black uppercase text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                                <tr>
                                    <th class="px-4 py-3">No. Invoice</th>
                                    <th class="px-4 py-3">Waktu</th>
                                    <th class="px-4 py-3">Pelanggan</th>
                                    <th class="px-4 py-3">Metode Bayar</th>
                                    <th class="px-4 py-3 text-right">Total Tagihan</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @forelse($recentTransactions as $tx)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/40 transition">
                                        <td class="px-4 py-3 font-mono font-bold text-slate-800 dark:text-white">{{ $tx->invoice_number }}</td>
                                        <td class="px-4 py-3">{{ $tx->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-4 py-3">{{ $tx->customer?->name ?? 'Pelanggan Umum' }}</td>
                                        <td class="px-4 py-3 uppercase font-semibold text-blue-600">{{ $tx->paymentMethod?->name ?? $tx->payment_method }}</td>
                                        <td class="px-4 py-3 text-right font-black text-slate-800 dark:text-white">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($tx->status === 'completed')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">SELESAI</span>
                                            @elseif($tx->status === 'refunded')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">REFUND</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">VOID</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ route('transactions.show', $tx) }}" class="font-bold text-blue-600 hover:underline">Lihat Struk &rarr;</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-slate-400">Belum ada transaksi yang diproses.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Admin Activity Audit Log -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Riwayat Audit Aktivitas Anda</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Menampilkan 5 rekaman jejak aktivitas terkini yang dilakukan oleh akun ini.</p>
                        </div>
                        <a href="{{ route('activity-logs.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-indigo-600 text-white hover:bg-indigo-700 transition">
                            Lihat Semua Log &rarr;
                        </a>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-700">
                        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead class="bg-slate-50/80 dark:bg-slate-900/60 text-[11px] font-black uppercase text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                                <tr>
                                    <th class="px-4 py-3">Waktu</th>
                                    <th class="px-4 py-3">Tindakan</th>
                                    <th class="px-4 py-3">Deskripsi</th>
                                    <th class="px-4 py-3">IP Address</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @forelse($recentActivities as $act)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/40 transition">
                                        <td class="px-4 py-3 font-mono font-medium text-slate-500 dark:text-slate-400">{{ $act->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                                                {{ $act->action }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 font-medium text-slate-800 dark:text-white">{{ $act->description }}</td>
                                        <td class="px-4 py-3 font-mono text-[11px] text-slate-400">{{ $act->ip_address ?? '127.0.0.1' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-slate-400">Belum ada rekaman log aktivitas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- TAB 4: HAPUS AKUN (DANGER ZONE) -->
            <div x-show="activeTab === 'danger'" class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-rose-100 dark:border-rose-900/30 space-y-5" style="display: none;">
                <div>
                    <h3 class="text-base font-extrabold text-rose-600 dark:text-rose-400 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Zona Berbahaya: Hapus Akun
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Setelah akun Anda dihapus, semua data sesi autentikasi dan informasi pribadi terkait akan dihapus secara permanen. Pastikan Anda telah mengunduh data penting sebelum melanjutkan.
                    </p>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-800 dark:text-white">Tindakan ini tidak dapat dibatalkan</p>
                        <p class="text-[11px] text-slate-400">Diperlukan konfirmasi kata sandi akun sebelum proses penghapusan dilakukan.</p>
                    </div>

                    <button type="button" @click="showDeleteModal = true"
                            class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-500/20 transition">
                        Hapus Akun Saya
                    </button>
                </div>
            </div>

            <!-- MODAL KONFIRMASI HAPUS AKUN -->
            <div x-show="showDeleteModal || {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }}"
                 x-transition
                 class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4"
                 style="display: none;">
                <div @click.away="showDeleteModal = false"
                     class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-2xl max-w-md w-full p-6 space-y-4">
                    <div class="flex items-center gap-3 text-rose-600">
                        <div class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-950/50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Konfirmasi Penghapusan Akun</h3>
                            <p class="text-xs text-slate-400">Tindakan ini permanen.</p>
                        </div>
                    </div>

                    <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                        @csrf
                        @method('delete')

                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda benar-benar ingin menghapus akun ini secara permanen:
                        </p>

                        <div>
                            <label for="password" class="sr-only">Kata Sandi</label>
                            <input id="password" name="password" type="password" required
                                   placeholder="Masukkan kata sandi Anda..."
                                   class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white p-2.5 focus:border-rose-500 focus:ring-rose-500">
                            @if ($errors->userDeletion->has('password'))
                                <p class="mt-1 text-xs text-rose-500 font-medium">{{ $errors->userDeletion->first('password') }}</p>
                            @endif
                        </div>

                        <div class="flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                            <button type="button" @click="showDeleteModal = false"
                                    class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                Ya, Hapus Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>