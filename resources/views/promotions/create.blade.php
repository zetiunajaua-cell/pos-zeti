<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('promotions.index') }}" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">
                    Tambah Promo & Diskon Baru
                </h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">
                    Buat kode kupon diskon baru untuk digunakan di kasir transaksi.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8"
             x-data="{ type: '{{ old('type', 'percentage') }}' }">

            <form action="{{ route('promotions.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- NAMA PROMO --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Promo <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Contoh: Diskon Pelajar Akhir Pekan"
                               required
                               class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border @error('name') border-rose-300 bg-rose-50/30 @else border-slate-200 bg-slate-50/50 @enderror text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                        @error('name')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- KODE PROMO --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kode Promo <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                   name="code"
                                   value="{{ old('code') }}"
                                   placeholder="Contoh: WEEKEND10"
                                   style="text-transform: uppercase;"
                                   required
                                   class="w-full px-3.5 py-2.5 text-xs font-mono font-bold rounded-xl border @error('code') border-rose-300 bg-rose-50/30 @else border-slate-200 bg-slate-50/50 @enderror text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Kode unik huruf kapital & angka tanpa spasi.</p>
                        @error('code')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- TIPE DISKON --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">
                        Tipe Potongan Diskon <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer border rounded-2xl p-4 flex items-center gap-3 transition"
                               :class="type === 'percentage' ? 'border-blue-600 bg-blue-50/40 text-blue-900 shadow-xs' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'">
                            <input type="radio" name="type" value="percentage" x-model="type" class="text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="text-xs font-black block">Persentase (%)</span>
                                <span class="text-[10px] text-slate-400">Potongan persen dari subtotal belanja</span>
                            </div>
                        </label>

                        <label class="cursor-pointer border rounded-2xl p-4 flex items-center gap-3 transition"
                               :class="type === 'fixed' ? 'border-blue-600 bg-blue-50/40 text-blue-900 shadow-xs' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'">
                            <input type="radio" name="type" value="fixed" x-model="type" class="text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="text-xs font-black block">Nominal Tetap (Rp)</span>
                                <span class="text-[10px] text-slate-400">Potongan langsung berupa nilai rupiah</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    {{-- NILAI POTONGAN --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nilai Potongan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400"
                                  x-text="type === 'percentage' ? '%' : 'Rp'"></span>
                            <input type="number"
                                   step="any"
                                   name="value"
                                   value="{{ old('value') }}"
                                   placeholder="Contoh: 10 atau 10000"
                                   required
                                   class="w-full pl-9 pr-3.5 py-2.5 text-xs font-bold rounded-xl border @error('value') border-rose-300 bg-rose-50/30 @else border-slate-200 bg-slate-50/50 @enderror text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                        @error('value')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- MINIMUM BELANJA --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Minimum Belanja (Rp)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400">Rp</span>
                            <input type="number"
                                   name="min_purchase"
                                   value="{{ old('min_purchase', 0) }}"
                                   placeholder="0"
                                   class="w-full pl-9 pr-3.5 py-2.5 text-xs font-bold rounded-xl border @error('min_purchase') border-rose-300 bg-rose-50/30 @else border-slate-200 bg-slate-50/50 @enderror text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Isi 0 jika tanpa syarat min. belanja.</p>
                        @error('min_purchase')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- MAKSIMAL DISKON --}}
                    <div x-show="type === 'percentage'" x-transition>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Maksimal Potongan (Rp)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400">Rp</span>
                            <input type="number"
                                   name="max_discount"
                                   value="{{ old('max_discount') }}"
                                   placeholder="Contoh: 20000"
                                   class="w-full pl-9 pr-3.5 py-2.5 text-xs font-bold rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Kosongkan jika tanpa batas maksimum.</p>
                        @error('max_discount')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- PERIODE PROMO --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2 border-t border-slate-100">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Mulai Berlaku
                        </label>
                        <input type="date"
                               name="start_date"
                               value="{{ old('start_date') }}"
                               class="w-full px-3.5 py-2.5 text-xs font-medium rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                        <p class="text-[10px] text-slate-400 mt-1">Kosongkan jika langsung aktif hari ini.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Berakhir (Expired)
                        </label>
                        <input type="date"
                               name="end_date"
                               value="{{ old('end_date') }}"
                               class="w-full px-3.5 py-2.5 text-xs font-medium rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                        <p class="text-[10px] text-slate-400 mt-1">Kosongkan jika berlaku selamanya.</p>
                    </div>
                </div>

                {{-- STATUS AKTIF --}}
                <div class="pt-2 border-t border-slate-100">
                    <label class="inline-flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500">
                        <span class="text-xs font-bold text-slate-700">Promo langsung aktif dan siap digunakan</span>
                    </label>
                </div>

                {{-- ACTIONS --}}
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('promotions.index') }}"
                       class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition active:scale-98">
                        Simpan Promo
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>

