<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                    Pengaturan Sistem & Toko
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Kelola identitas bisnis, informasi struk, konfigurasi pajak & biaya tambahan, serta tema aplikasi dan akun kasir.
                </p>
            </div>
            
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ 
            activeTab: '{{ (request('tab') === 'cashiers' || $errors->has('password') || $errors->has('store_branch')) ? 'cashiers' : request('tab', 'identity') }}', 
            isDarkNow: document.documentElement.classList.contains('dark'),
            showAddCashierModal: false,
            showEditCashierModal: false,
            showDetailCashierModal: false,
            cashierSearch: '',
            editCashierData: {
                id: '',
                name: '',
                email: '',
                store_branch: '',
                phone: '',
                is_active: true,
                actionUrl: ''
            },
            detailCashierData: {
                id: '',
                name: '',
                email: '',
                store_branch: '',
                phone: '',
                is_active: true,
                transactions_count: 0,
                created_at: ''
            },
            openEdit(cashier, actionUrl) {
                this.editCashierData = {
                    id: cashier.id,
                    name: cashier.name,
                    email: cashier.email,
                    store_branch: cashier.store_branch || '{{ setting('store_name', 'Toko Utama') }}',
                    phone: cashier.phone || '',
                    is_active: Boolean(cashier.is_active),
                    actionUrl: actionUrl
                };
                this.showEditCashierModal = true;
            },
            openDetail(cashier) {
                this.detailCashierData = {
                    id: cashier.id,
                    name: cashier.name,
                    email: cashier.email,
                    store_branch: cashier.store_branch || '{{ setting('store_name', 'Toko Utama') }}',
                    phone: cashier.phone || '-',
                    is_active: Boolean(cashier.is_active),
                    transactions_count: cashier.transactions_count || 0,
                    created_at: cashier.created_at_formatted || '-'
                };
                this.showDetailCashierModal = true;
            }
        }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm font-semibold flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm font-semibold flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm font-semibold space-y-1">
                    <p class="font-bold flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Terdapat kesalahan pengisian data:
                    </p>
                    <ul class="list-disc list-inside text-xs space-y-1 ml-5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tabs Header (4 Tab Navigasi) -->
            <div class="flex flex-wrap gap-2 border-b border-slate-200 dark:border-slate-800 pb-2">
                <!-- TAB 1: IDENTITAS TOKO -->
                <button type="button" @click="activeTab = 'identity'"
                        :class="activeTab === 'identity' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Identitas Toko & Struk (Task 31)
                </button>

                <!-- TAB 2: PAJAK & BIAYA -->
                <button type="button" @click="activeTab = 'tax_fees'"
                        :class="activeTab === 'tax_fees' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/></svg>
                    Pajak & Biaya Tambahan (Task 29)
                </button>

                <!-- TAB 3: TAMPILAN -->
                <button type="button" @click="activeTab = 'appearance'"
                        :class="activeTab === 'appearance' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    Tampilan Light & Dark (Task 30)
                </button>

                <!-- TAB 4: AKUN KASIR & MULTI-TOKO -->
                <button type="button" @click="activeTab = 'cashiers'"
                        :class="activeTab === 'cashiers' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Akun Kasir (Kelola Kasir)</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black"
                          :class="activeTab === 'cashiers' ? 'bg-white/20 text-white' : 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300'">
                        {{ $cashiers->count() }}
                    </span>
                </button>
            </div>

            <!-- FORM PENGATURAN TOKO (TAB 1, 2, 3) -->
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- TAB 1: IDENTITAS TOKO (TASK 31) -->
                <div x-show="activeTab === 'identity'" class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-700/60 space-y-6">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Identitas Bisnis & Brand</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Informasi ini akan muncul pada header aplikasi, laporan, dan struk belanja pelanggan.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Toko -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                                Nama Toko / Bisnis <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="store_name" value="{{ old('store_name', $settings['store_name']) }}" required
                                   class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Contoh: POS Zeti Store">
                        </div>

                        <!-- Slogan / Sub-judul -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                                Slogan / Deskripsi Singkat
                            </label>
                            <input type="text" name="store_slogan" value="{{ old('store_slogan', $settings['store_slogan']) }}"
                                   class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Contoh: Point of Sale & Retail Modern">
                        </div>

                        <!-- Telepon / WhatsApp -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                                Nomor Telepon / WA Kasir
                            </label>
                            <input type="text" name="store_phone" value="{{ old('store_phone', $settings['store_phone']) }}"
                                   class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="0812-3456-7890">
                        </div>

                        <!-- Email Toko -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                                Alamat Email Bisnis
                            </label>
                            <input type="email" name="store_email" value="{{ old('store_email', $settings['store_email']) }}"
                                   class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="info@tokosaya.com">
                        </div>

                        <!-- Website / Sosmed -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                                Website / Instagram
                            </label>
                            <input type="text" name="store_website" value="{{ old('store_website', $settings['store_website']) }}"
                                   class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="www.tokosaya.com / @tokosaya">
                        </div>

                        <!-- Logo Toko -->
                        <div x-data="{ logoPreview: null, removeExistingLogo: false }">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                                Logo Toko (Opsional, JPG/PNG/WEBP maks 10MB)
                            </label>
                            
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                <!-- Preview Logo Saat Ini atau Baru -->
                                <div class="shrink-0">
                                    <template x-if="logoPreview">
                                        <div class="relative w-14 h-14 rounded-2xl overflow-hidden border-2 border-blue-500 shadow-md bg-slate-100 dark:bg-slate-800">
                                            <img :src="logoPreview" alt="Pratinjau Logo Baru" class="w-full h-full object-cover">
                                            <button type="button" @click="logoPreview = null; $refs.logoInput.value = '';"
                                                    class="absolute top-0 right-0 bg-rose-600 text-white rounded-bl-lg p-0.5 text-[10px]" title="Batalkan pilihan">
                                                ✕
                                            </button>
                                        </div>
                                    </template>

                                    <template x-if="!logoPreview">
                                        <div>
                                            @if(!empty($settings['store_logo']))
                                                <div x-show="!removeExistingLogo" class="relative w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-xs bg-slate-100 dark:bg-slate-800">
                                                    <img src="{{ asset('storage/' . $settings['store_logo']) }}" alt="Logo Saat Ini" class="w-full h-full object-cover">
                                                </div>
                                                <div x-show="removeExistingLogo" class="w-14 h-14 rounded-2xl border-2 border-dashed border-rose-300 dark:border-rose-800 flex items-center justify-center text-rose-500 text-[10px] font-bold text-center p-1 bg-rose-50/50 dark:bg-rose-950/30">
                                                    Dihapus
                                                </div>
                                            @else
                                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-600 flex items-center justify-center text-white shadow-md shadow-blue-500/25">
                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <rect x="3" y="4" width="18" height="16" rx="3" stroke-width="2"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h5m-5 4h8"/>
                                                        <circle cx="16.5" cy="12.5" r="1.5" fill="currentColor"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </template>
                                </div>

                                <div class="flex-1 space-y-2 w-full">
                                    <input type="file" name="store_logo" accept="image/*" x-ref="logoInput"
                                           @change="const file = $event.target.files[0]; if (file) { logoPreview = URL.createObjectURL(file); removeExistingLogo = false; }"
                                           class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900/40 dark:file:text-blue-300 hover:file:bg-blue-100 cursor-pointer">
                                    
                                    <div class="flex flex-wrap items-center gap-3 text-[11px] text-slate-400">
                                        <span>Maks. 10 MB per file. Format: PNG, JPG, JPEG, WEBP.</span>
                                        @if(!empty($settings['store_logo']))
                                            <label class="inline-flex items-center gap-1.5 cursor-pointer text-rose-600 dark:text-rose-400 font-bold hover:underline">
                                                <input type="checkbox" name="remove_logo" value="1" x-model="removeExistingLogo" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                                <span>Hapus logo saat ini (kembali ke default)</span>
                                            </label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alamat Toko -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                                Alamat Lengkap Toko
                            </label>
                            <textarea name="store_address" rows="2"
                                      class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Jl. Raya Utama No. 88, Kota Solusi">{{ old('store_address', $settings['store_address']) }}</textarea>
                        </div>
                    </div>

                    <hr class="border-slate-100 dark:border-slate-700">

                    <div>
                        <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Pesan Kustom Struk Belanja (Task 10)</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Salam pembuka dan ucapan terima kasih yang tercetak pada struk kasir.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                                Salam Pembuka Struk (Header)
                            </label>
                            <input type="text" name="receipt_header_msg" value="{{ old('receipt_header_msg', $settings['receipt_header_msg']) }}"
                                   class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Selamat Datang di POS ZETI! Selamat Berbelanja.">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                                Ucapan Terima Kasih & Kunjungan (Footer)
                            </label>
                            <input type="text" name="receipt_footer_msg" value="{{ old('receipt_footer_msg', $settings['receipt_footer_msg']) }}"
                                   class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Terima Kasih Atas Kunjungan Anda! Silakan Berkunjung Kembali.">
                        </div>
                    </div>
                </div>

                <!-- TAB 2: PAJAK & BIAYA TAMBAHAN (TASK 29) -->
                <div x-show="activeTab === 'tax_fees'" class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-700/60 space-y-6" style="display: none;">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Pengaturan Pajak (PPN/PB1) & Biaya Layanan</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Atur pengenaan pajak penjualan dan biaya tambahan kasir yang dihitung secara otomatis saat checkout.</p>
                    </div>

                    <!-- CARD PAJAK -->
                    <div class="p-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/40 space-y-4"
                         x-data="{ taxActive: {{ $settings['tax_enabled'] == '1' ? 'true' : 'false' }} }">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center font-black text-sm">
                                    %
                                </div>
                                <div>
                                    <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Pajak Penjualan (PPN / PB1)</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Tambahkan tarif pajak pada setiap transaksi pelanggan secara otomatis.</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="tax_enabled" value="1" x-model="taxActive" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2" :class="{ 'opacity-50 pointer-events-none': !taxActive }">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Pajak</label>
                                <input type="text" name="tax_name" value="{{ old('tax_name', $settings['tax_name']) }}"
                                       class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tipe Perhitungan</label>
                                <select name="tax_type" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white">
                                    <option value="percentage" {{ old('tax_type', $settings['tax_type']) === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                    <option value="fixed" {{ old('tax_type', $settings['tax_type']) === 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nilai / Tarif</label>
                                <input type="number" step="0.01" min="0" name="tax_rate" value="{{ old('tax_rate', $settings['tax_rate']) }}"
                                       class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white"
                                       placeholder="10">
                            </div>
                        </div>
                    </div>

                    <!-- CARD SERVICE CHARGE -->
                    <div class="p-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/40 space-y-4"
                         x-data="{ feeActive: {{ $settings['service_charge_enabled'] == '1' ? 'true' : 'false' }} }">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center font-black text-sm">
                                    +
                                </div>
                                <div>
                                    <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Biaya Layanan (Service Charge / Takeaway Fee)</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Tambahan biaya operasional atau servis untuk transaksi kasir.</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="service_charge_enabled" value="1" x-model="feeActive" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-purple-600"></div>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2" :class="{ 'opacity-50 pointer-events-none': !feeActive }">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Biaya Layanan</label>
                                <input type="text" name="service_charge_name" value="{{ old('service_charge_name', $settings['service_charge_name']) }}"
                                       class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tipe Perhitungan</label>
                                <select name="service_charge_type" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white">
                                    <option value="percentage" {{ old('service_charge_type', $settings['service_charge_type']) === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                    <option value="fixed" {{ old('service_charge_type', $settings['service_charge_type']) === 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nilai / Tarif</label>
                                <input type="number" step="0.01" min="0" name="service_charge_rate" value="{{ old('service_charge_rate', $settings['service_charge_rate']) }}"
                                       class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white"
                                       placeholder="5">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: TAMPILAN LIGHT & DARK (TASK 30) -->
                <div x-show="activeTab === 'appearance'" class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-700/60 space-y-6" style="display: none;">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Preferensi Tampilan Aplikasi (Task 30)</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Pilih tema antarmuka sesuai kenyamanan mata pengguna. Anda juga dapat menggantinya langsung kapan saja melalui tombol matahari/bulan di menu navigasi.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        <!-- Card Mode Terang -->
                        <div @click="document.documentElement.classList.remove('dark'); localStorage.setItem('pos_theme', 'light'); isDarkNow = false;"
                             :class="!isDarkNow ? 'ring-2 ring-blue-600 bg-blue-50/30 dark:bg-slate-800' : 'opacity-70 hover:opacity-100'"
                             class="cursor-pointer p-6 rounded-2xl border border-slate-200 dark:border-slate-700 transition flex flex-col items-center text-center space-y-4">
                            <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center shadow-inner">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Mode Terang (Light Mode)</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Latar belakang putih bersih dengan kontras tajam, cocok untuk ruangan terang.</p>
                            </div>
                            <span x-show="!isDarkNow" class="text-[10px] font-black uppercase px-3 py-1 rounded-full bg-blue-600 text-white">
                                Sedang Aktif
                            </span>
                        </div>

                        <!-- Card Mode Gelap -->
                        <div @click="document.documentElement.classList.add('dark'); localStorage.setItem('pos_theme', 'dark'); isDarkNow = true;"
                             :class="isDarkNow ? 'ring-2 ring-blue-600 bg-blue-50/30 dark:bg-slate-900' : 'opacity-70 hover:opacity-100'"
                             class="cursor-pointer p-6 rounded-2xl border border-slate-200 dark:border-slate-700 transition flex flex-col items-center text-center space-y-4">
                            <div class="w-16 h-16 rounded-2xl bg-slate-800 text-amber-400 flex items-center justify-center shadow-inner">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Mode Gelap (Dark Mode)</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Latar belakang gelap elegan, nyaman untuk penggunaan kasir di tempat redup atau malam hari.</p>
                            </div>
                            <span x-show="isDarkNow" class="text-[10px] font-black uppercase px-3 py-1 rounded-full bg-blue-600 text-white" style="display: none;">
                                Sedang Aktif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan Pengaturan (Hanya untuk Tab 1, 2, 3) -->
                <div x-show="activeTab !== 'cashiers'" class="flex items-center justify-end gap-3 pt-4">
                    <button type="submit"
                            class="px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold shadow-lg shadow-blue-500/25 transition transform active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Semua Pengaturan
                    </button>
                </div>
            </form>

            <!-- ========================================================================= -->
            <!-- TAB 4: AKUN KASIR & MULTI-TOKO (FITUR BARU)                              -->
            <!-- ========================================================================= -->
            <div x-show="activeTab === 'cashiers'" style="display: none;" class="space-y-6">

                <!-- Header Banner Akun Kasir & Multi-Cabang -->
                <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white rounded-3xl p-6 sm:p-7 shadow-lg border border-indigo-800/40 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-400/20 text-blue-300 border border-blue-400/30">
                                Multi-Store & Cashier Access
                            </span>
                            <span class="text-xs text-indigo-300 font-semibold">• {{ $cashiers->count() }} Kasir Terdaftar</span>
                        </div>
                        <h3 class="text-lg font-black tracking-tight">Manajemen Akun Kasir dan Penugasan Toko</h3>
                        <p class="text-xs text-indigo-200/80 mt-1 max-w-2xl leading-relaxed">
                            Admin dapat mengelola akses kasir, melihat detail performa transaksi, serta menambahkan akun kasir baru jika toko Anda memiliki beberapa cabang toko (multi-outlet).
                        </p>
                    </div>

                    <button type="button" @click="showAddCashierModal = true"
                            class="px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-black shadow-lg shadow-blue-600/30 transition transform active:scale-95 flex items-center gap-2 shrink-0 self-start md:self-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Tambah User Kasir</span>
                    </button>
                </div>

                <!-- Statistik Ringkas Kasir & Multi-Toko -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700/60 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase">Total User Kasir</span>
                            <p class="text-base font-black text-slate-900 dark:text-white">{{ $cashiers->count() }} Pengguna</p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700/60 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase">Kasir Aktif</span>
                            <p class="text-base font-black text-emerald-600 dark:text-emerald-400">{{ $cashiers->where('is_active', true)->count() }} Akun Siap Kerja</p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700/60 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase">Cabang / Lokasi Toko</span>
                            <p class="text-base font-black text-slate-900 dark:text-white">{{ $cashiers->pluck('store_branch')->unique()->filter()->count() ?: 1 }} Lokasi Toko</p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700/60 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase">Total Transaksi Kasir</span>
                            <p class="text-base font-black text-purple-600 dark:text-purple-400">{{ number_format($cashiers->sum('transactions_count'), 0, ',', '.') }} Struk Terjual</p>
                        </div>
                    </div>
                </div>

                <!-- Filter & Daftar Tabel Akun Kasir -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/60 overflow-hidden">
                    <div class="p-5 border-b border-slate-100 dark:border-slate-700/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="relative flex-1 max-w-sm">
                            <input type="text" x-model="cashierSearch" placeholder="Cari nama kasir, cabang toko, email..."
                                   class="w-full pl-9 pr-4 py-2 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-xs font-semibold text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <span class="text-xs text-slate-400 font-semibold">
                            Menampilkan seluruh akun kasir yang memiliki akses POS
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead class="bg-slate-50/80 dark:bg-slate-900/60 text-[11px] font-black uppercase text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                                <tr>
                                    <th class="px-5 py-3.5">Akun Kasir</th>
                                    <th class="px-5 py-3.5">Toko / Cabang (Multi-Toko)</th>
                                    <th class="px-5 py-3.5">No. Telepon</th>
                                    <th class="px-5 py-3.5">Status Akses</th>
                                    <th class="px-5 py-3.5">Total Penjualan</th>
                                    <th class="px-5 py-3.5 text-right">Aksi & Kontrol</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @forelse($cashiers as $c)
                                    @php
                                        $initials = strtoupper(substr($c->name, 0, 2));
                                        $branch = $c->store_branch ?: setting('store_name', 'Toko Utama');
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/40 transition"
                                        x-show="!cashierSearch || '{{ strtolower($c->name . ' ' . $c->email . ' ' . $branch) }}'.includes(cashierSearch.toLowerCase())">
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="relative shrink-0">
                                                    @if($c->avatar_url)
                                                        <img src="{{ $c->avatar_url }}" alt="{{ $c->name }}" class="w-9 h-9 rounded-xl object-cover shadow-sm">
                                                    @else
                                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black text-xs shadow-sm">
                                                            {{ $initials }}
                                                        </div>
                                                    @endif
                                                    <!-- Titik status aktif / tidak aktif -->
                                                    <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 rounded-full {{ $c->is_active ? 'bg-emerald-500' : 'bg-rose-500' }} ring-2 ring-white dark:ring-slate-800"
                                                          title="{{ $c->is_active ? 'Akun Kasir: Aktif' : 'Akun Kasir: Dinonaktifkan' }}"></span>
                                                </div>
                                                <div>
                                                    <span class="font-bold text-slate-900 dark:text-white block text-sm">{{ $c->name }}</span>
                                                    <span class="text-[11px] text-slate-400 font-mono">{{ $c->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-100 dark:border-indigo-800 text-indigo-700 dark:text-indigo-300 font-bold text-xs">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                <span>{{ $branch }}</span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 font-mono text-slate-500 dark:text-slate-400">
                                            {{ $c->phone ?: '-' }}
                                        </td>
                                        <td class="px-5 py-4">
                                            @if($c->is_active)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    Non-Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="font-bold text-slate-800 dark:text-white">{{ $c->transactions_count }}</span>
                                            <span class="text-[10px] text-slate-400 block">Struk Penjualan</span>
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <!-- Tombol Detail -->
                                                <button type="button"
                                                        @click="openDetail({
                                                            id: {{ $c->id }},
                                                            name: '{{ addslashes($c->name) }}',
                                                            email: '{{ addslashes($c->email) }}',
                                                            store_branch: '{{ addslashes($branch) }}',
                                                            phone: '{{ addslashes($c->phone ?: '-') }}',
                                                            is_active: {{ $c->is_active ? 'true' : 'false' }},
                                                            transactions_count: {{ $c->transactions_count }},
                                                            created_at_formatted: '{{ $c->created_at->format('d M Y H:i') }}'
                                                        })"
                                                        class="p-2 rounded-xl bg-slate-100 dark:bg-slate-700/60 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/30 text-slate-600 dark:text-slate-300 transition"
                                                        title="Lihat Detail Akun Kasir">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </button>

                                                <!-- Tombol Edit -->
                                                <button type="button"
                                                        @click="openEdit({
                                                            id: {{ $c->id }},
                                                            name: '{{ addslashes($c->name) }}',
                                                            email: '{{ addslashes($c->email) }}',
                                                            store_branch: '{{ addslashes($branch) }}',
                                                            phone: '{{ addslashes($c->phone ?: '') }}',
                                                            is_active: {{ $c->is_active ? 'true' : 'false' }}
                                                        }, '{{ route('cashiers.update', $c) }}')"
                                                        class="p-2 rounded-xl bg-slate-100 dark:bg-slate-700/60 hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-amber-900/30 text-slate-600 dark:text-slate-300 transition"
                                                        title="Edit Data Kasir">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>

                                                <!-- Toggle Aktif / Non-aktif -->
                                                <form action="{{ route('cashiers.toggle', $c) }}" method="POST" class="inline"
                                                      onsubmit="confirmSubmit(event, 'Status akses kasir {{ addslashes($c->name) }} akan diubah menjadi {{ $c->is_active ? 'Nonaktif' : 'Aktif' }}.', 'Ubah Status Akses?', false)">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="p-2 rounded-xl bg-slate-100 dark:bg-slate-700/60 hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-900/30 text-slate-600 dark:text-slate-300 transition"
                                                            title="{{ $c->is_active ? 'Nonaktifkan Kasir' : 'Aktifkan Kasir' }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                                    </button>
                                                </form>

                                                <!-- Tombol Hapus -->
                                                <form action="{{ route('cashiers.destroy', $c) }}" method="POST" class="inline"
                                                      onsubmit="confirmSubmit(event, 'Akun kasir {{ addslashes($c->name) }} akan dihapus dari sistem POS.', 'Hapus Akun Kasir?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="p-2 rounded-xl bg-slate-100 dark:bg-slate-700/60 hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-900/30 text-slate-600 dark:text-slate-300 transition"
                                                            title="Hapus Akun Kasir">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                            Belum ada akun kasir yang terdaftar. Klik "+ Tambah User Kasir" di atas untuk menambahkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- ========================================================================= -->
            <!-- MODAL 1: TAMBAH AKUN KASIR BARU                                           -->
            <!-- ========================================================================= -->
            <div x-show="showAddCashierModal"
                 style="display: none;"
                 class="fixed inset-0 z-50 overflow-y-auto"
                 aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showAddCashierModal"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="showAddCashierModal = false"
                         class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                    <div x-show="showAddCashierModal"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100 dark:border-slate-700">
                        
                        <form action="{{ route('cashiers.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="is_cashier_form" value="1">

                            <div class="p-6 border-b border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-slate-900 dark:text-white">Tambah User Kasir Baru</h3>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Buat akun kasir dan tetapkan ke cabang toko</p>
                                    </div>
                                </div>
                                <button type="button" @click="showAddCashierModal = false" class="p-1 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <div class="p-6 space-y-4">
                                <!-- Nama Kasir -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                        Nama Lengkap Kasir <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="name" required placeholder="Contoh: Budi Santoso"
                                           class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-xs font-semibold text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Nama Toko / Cabang -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                        Nama Toko / Cabang Toko <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="store_branch" required value="{{ setting('store_name', 'Toko Utama') }}"
                                           placeholder="Contoh: Toko Utama, Cabang 1, Cabang Bandung"
                                           class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-xs font-semibold text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                    <p class="text-[10px] text-slate-400 mt-1">Dapat disesuaikan jika toko Anda memiliki beberapa cabang lokasi kasir.</p>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                        Alamat Email (Untuk Login) <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="email" name="email" required placeholder="kasir.cabang1@gmail.com"
                                           class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-xs font-semibold text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Telepon -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                        Nomor Telepon / WhatsApp
                                    </label>
                                    <input type="text" name="phone" placeholder="0812-xxxx-xxxx"
                                           class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-xs font-semibold text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Password & Konfirmasi -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" x-data="{ showPw: false, showPwC: false }">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                            Password <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input :type="showPw ? 'text' : 'password'" name="password" required minlength="6" placeholder="Min. 6 karakter"
                                                   class="w-full pr-9 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-xs font-semibold text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                            <button type="button" @click="showPw = !showPw" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-blue-600 transition" title="Tampilkan/Sembunyikan">
                                                <svg x-show="!showPw" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <svg x-show="showPw" x-cloak class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                            Konfirmasi Password <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input :type="showPwC ? 'text' : 'password'" name="password_confirmation" required minlength="6" placeholder="Ulangi password"
                                                   class="w-full pr-9 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-xs font-semibold text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                            <button type="button" @click="showPwC = !showPwC" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-blue-600 transition" title="Tampilkan/Sembunyikan">
                                                <svg x-show="!showPwC" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <svg x-show="showPwC" x-cloak class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Aktif -->
                                <div class="pt-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" checked
                                               class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Akun Langsung Aktif (Dapat Login ke POS)</span>
                                    </label>
                                </div>
                            </div>

                            <div class="p-6 bg-slate-50 dark:bg-slate-900/40 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-end gap-3">
                                <button type="button" @click="showAddCashierModal = false"
                                        class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition">
                                    Simpan Akun Kasir
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- MODAL 2: EDIT AKUN KASIR                                                  -->
            <!-- ========================================================================= -->
            <div x-show="showEditCashierModal"
                 style="display: none;"
                 class="fixed inset-0 z-50 overflow-y-auto"
                 aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showEditCashierModal"
                         @click="showEditCashierModal = false"
                         class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                    <div x-show="showEditCashierModal"
                         class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100 dark:border-slate-700">
                        
                        <form :action="editCashierData.actionUrl" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="is_cashier_form" value="1">

                            <div class="p-6 border-b border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-slate-900 dark:text-white">Edit Akun Kasir</h3>
                                        <p class="text-xs text-slate-500 dark:text-slate-400" x-text="'Memperbarui data akun ' + editCashierData.name"></p>
                                    </div>
                                </div>
                                <button type="button" @click="showEditCashierModal = false" class="p-1 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <div class="p-6 space-y-4">
                                <!-- Nama Kasir -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                        Nama Lengkap Kasir <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="name" x-model="editCashierData.name" required
                                           class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-xs font-semibold text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Toko / Cabang -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                        Nama Toko / Cabang Toko <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="store_branch" x-model="editCashierData.store_branch" required
                                           class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-xs font-semibold text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                        Alamat Email <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="email" name="email" x-model="editCashierData.email" required
                                           class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-xs font-semibold text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Telepon -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                        Nomor Telepon / WhatsApp
                                    </label>
                                    <input type="text" name="phone" x-model="editCashierData.phone"
                                           class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-xs font-semibold text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Reset Password (Opsional) -->
                                <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-800 space-y-3">
                                    <div>
                                        <span class="text-xs font-bold text-slate-800 dark:text-white block">Reset Password Akun (Opsional)</span>
                                        <span class="text-[10px] text-slate-400">Kosongkan jika tidak ingin mengubah password akun kasir ini.</span>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" x-data="{ showEditPw: false, showEditPwC: false }">
                                        <div class="relative">
                                            <input :type="showEditPw ? 'text' : 'password'" name="password" minlength="6" placeholder="Password Baru"
                                                   class="w-full pr-9 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white">
                                            <button type="button" @click="showEditPw = !showEditPw" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-blue-600 transition" title="Tampilkan/Sembunyikan">
                                                <svg x-show="!showEditPw" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <svg x-show="showEditPw" x-cloak class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                            </button>
                                        </div>
                                        <div class="relative">
                                            <input :type="showEditPwC ? 'text' : 'password'" name="password_confirmation" minlength="6" placeholder="Konfirmasi Password Baru"
                                                   class="w-full pr-9 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white">
                                            <button type="button" @click="showEditPwC = !showEditPwC" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-blue-600 transition" title="Tampilkan/Sembunyikan">
                                                <svg x-show="!showEditPwC" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <svg x-show="showEditPwC" x-cloak class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Aktif -->
                                <div>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" x-model="editCashierData.is_active"
                                               class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Status Akun Aktif</span>
                                    </label>
                                </div>
                            </div>

                            <div class="p-6 bg-slate-50 dark:bg-slate-900/40 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-end gap-3">
                                <button type="button" @click="showEditCashierModal = false"
                                        class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-md shadow-amber-500/20 transition">
                                    Perbarui Akun
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- MODAL 3: DETAIL AKUN KASIR                                                -->
            <!-- ========================================================================= -->
            <div x-show="showDetailCashierModal"
                 style="display: none;"
                 class="fixed inset-0 z-50 overflow-y-auto"
                 aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showDetailCashierModal"
                         @click="showDetailCashierModal = false"
                         class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                    <div x-show="showDetailCashierModal"
                         class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-slate-100 dark:border-slate-700">
                        
                        <div class="p-6 border-b border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                            <h3 class="text-base font-black text-slate-900 dark:text-white">Detail Akun Kasir</h3>
                            <button type="button" @click="showDetailCashierModal = false" class="p-1 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="p-6 space-y-5">
                            <!-- Profil Hero Mini -->
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black text-lg shadow-md shrink-0">
                                    <span x-text="detailCashierData.name ? detailCashierData.name.substring(0,2).toUpperCase() : 'KA'"></span>
                                </div>
                                <div>
                                    <h4 class="text-base font-black text-slate-900 dark:text-white" x-text="detailCashierData.name"></h4>
                                    <p class="text-xs font-mono text-slate-400" x-text="detailCashierData.email"></p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                                              :class="detailCashierData.is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300'"
                                              x-text="detailCashierData.is_active ? 'Aktif' : 'Non-Aktif'"></span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase">Role: Kasir POS</span>
                                    </div>
                                </div>
                            </div>

                            <!-- List Detail Info -->
                            <div class="bg-slate-50 dark:bg-slate-900/60 rounded-2xl p-4 space-y-3 border border-slate-100 dark:border-slate-800 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500 dark:text-slate-400 font-semibold">Toko / Cabang Penugasan</span>
                                    <span class="font-bold text-indigo-600 dark:text-indigo-400" x-text="detailCashierData.store_branch"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500 dark:text-slate-400 font-semibold">Nomor Telepon / WA</span>
                                    <span class="font-mono text-slate-800 dark:text-slate-200" x-text="detailCashierData.phone"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500 dark:text-slate-400 font-semibold">Total Struk Transaksi</span>
                                    <span class="font-black text-slate-900 dark:text-white" x-text="detailCashierData.transactions_count + ' Transaksi Penjualan'"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500 dark:text-slate-400 font-semibold">Terdaftar Sejak</span>
                                    <span class="font-mono text-slate-600 dark:text-slate-300" x-text="detailCashierData.created_at"></span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 bg-slate-50 dark:bg-slate-900/40 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-end">
                            <button type="button" @click="showDetailCashierModal = false"
                                    class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md transition">
                                Tutup
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
