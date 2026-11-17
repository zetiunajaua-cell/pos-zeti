<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('customers.index') }}" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">
                    Edit Data Member: {{ $customer->name }}
                </h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">
                    ID Member: {{ $customer->member_code }} • Total Poin: {{ number_format($customer->points, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8"
             x-data="{
                 tier: '{{ old('membership_tier', $customer->membership_tier) }}',
                 discount: '{{ old('discount_percent', (float)$customer->discount_percent) }}',
                 setTier(t) {
                     this.tier = t;
                     if (t === 'Silver') this.discount = '5';
                     else if (t === 'Gold') this.discount = '10';
                     else if (t === 'Platinum') this.discount = '15';
                 }
             }">

            <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- NAMA PELANGGAN --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Pelanggan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $customer->name) }}"
                               required
                               class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border @error('name') border-rose-300 bg-rose-50/30 @else border-slate-200 bg-slate-50/50 @enderror text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                        @error('name')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- KODE MEMBER --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kode Member <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="member_code"
                               value="{{ old('member_code', $customer->member_code) }}"
                               style="text-transform: uppercase;"
                               required
                               class="w-full px-3.5 py-2.5 text-xs font-mono font-bold rounded-xl border @error('member_code') border-rose-300 bg-rose-50/30 @else border-slate-200 bg-slate-50/50 @enderror text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                        @error('member_code')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- NO TELEPON --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            No. Telepon / WhatsApp
                        </label>
                        <input type="text"
                               name="phone"
                               value="{{ old('phone', $customer->phone) }}"
                               class="w-full px-3.5 py-2.5 text-xs font-mono rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                    </div>

                    {{-- EMAIL --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Email Pelanggan
                        </label>
                        <input type="email"
                               name="email"
                               value="{{ old('email', $customer->email) }}"
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                    </div>
                </div>

                {{-- ALAMAT --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Alamat
                    </label>
                    <textarea name="address"
                              rows="2"
                              class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">{{ old('address', $customer->address) }}</textarea>
                </div>

                {{-- PILIHAN TIER MEMBERSHIP --}}
                <div class="pt-2 border-t border-slate-100">
                    <label class="block text-xs font-bold text-slate-700 mb-2">
                        Tingkat Keanggotaan (Membership Tier) <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <label class="cursor-pointer border rounded-2xl p-4 flex flex-col justify-between transition"
                               :class="tier === 'Silver' ? 'border-slate-500 bg-slate-100/70 text-slate-900 shadow-xs ring-2 ring-slate-400' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                               @click="setTier('Silver')">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black">● Silver Member</span>
                                <input type="radio" name="membership_tier" value="Silver" x-model="tier" class="text-slate-600">
                            </div>
                            <p class="text-[11px] text-slate-500 font-bold mt-2">Diskon Otomatis 5%</p>
                            <span class="text-[10px] text-slate-400 mt-0.5">Tingkat dasar keanggotaan</span>
                        </label>

                        <label class="cursor-pointer border rounded-2xl p-4 flex flex-col justify-between transition"
                               :class="tier === 'Gold' ? 'border-amber-500 bg-amber-50/60 text-amber-950 shadow-xs ring-2 ring-amber-400' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                               @click="setTier('Gold')">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black text-amber-800">★ Gold Member</span>
                                <input type="radio" name="membership_tier" value="Gold" x-model="tier" class="text-amber-600">
                            </div>
                            <p class="text-[11px] text-amber-700 font-bold mt-2">Diskon Otomatis 10%</p>
                            <span class="text-[10px] text-amber-600/70 mt-0.5">Tingkat loyal reguler</span>
                        </label>

                        <label class="cursor-pointer border rounded-2xl p-4 flex flex-col justify-between transition"
                               :class="tier === 'Platinum' ? 'border-indigo-600 bg-indigo-50/60 text-indigo-950 shadow-xs ring-2 ring-indigo-500' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                               @click="setTier('Platinum')">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black text-indigo-800">✦ Platinum VIP</span>
                                <input type="radio" name="membership_tier" value="Platinum" x-model="tier" class="text-indigo-600">
                            </div>
                            <p class="text-[11px] text-indigo-700 font-bold mt-2">Diskon Otomatis 15%</p>
                            <span class="text-[10px] text-indigo-600/70 mt-0.5">Tingkat tertinggi VIP</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- DISKON MEMBER (%) --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Diskon Khusus Member (%)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400">%</span>
                            <input type="number"
                                   step="any"
                                   name="discount_percent"
                                   x-model="discount"
                                   required
                                   class="w-full pl-8 pr-3.5 py-2.5 text-xs font-bold rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                        @error('discount_percent')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- POIN --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Poin Loyalitas
                        </label>
                        <input type="number"
                               name="points"
                               value="{{ old('points', $customer->points) }}"
                               required
                               class="w-full px-3.5 py-2.5 text-xs font-bold rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                        <p class="text-[10px] text-slate-400 mt-1">Dapat diubah secara manual jika ada penyesuaian/reward khusus.</p>
                    </div>
                </div>

                {{-- STATUS AKTIF --}}
                <div class="pt-2 border-t border-slate-100">
                    <label class="inline-flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $customer->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500">
                        <span class="text-xs font-bold text-slate-700">Member aktif</span>
                    </label>
                </div>

                {{-- ACTIONS --}}
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('customers.index') }}"
                       class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition active:scale-98">
                        Perbarui Member
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>

