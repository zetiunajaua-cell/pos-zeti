<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">
                    Kelola Pelanggan & Membership
                </h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">
                    Data keanggotaan pelanggan, tingkatan tier (Silver, Gold, Platinum), diskon otomatis, dan poin loyalitas.
                </p>
            </div>
            <a href="{{ route('customers.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition active:scale-98">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                <span>Registrasi Member Baru</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 font-black">✕</button>
            </div>
        @endif

        {{-- METRIC CARDS --}}
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3.5">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Pelanggan</span>
                <h3 class="text-xl font-black text-slate-800 dark:text-white mt-1">{{ $totalCustomers }}</h3>
                <span class="text-[10px] text-slate-400 font-medium">Terdaftar</span>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Tier Silver (5%)</span>
                <h3 class="text-xl font-black text-slate-700 dark:text-slate-200 mt-1">{{ $silverCount }}</h3>
                <span class="text-[10px] text-slate-400 font-medium">Member Silver</span>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider block">Tier Gold (10%)</span>
                <h3 class="text-xl font-black text-amber-600 dark:text-amber-400 mt-1">{{ $goldCount }}</h3>
                <span class="text-[10px] text-amber-600 dark:text-amber-400 font-medium">Member Gold</span>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider block">Platinum (15%)</span>
                <h3 class="text-xl font-black text-indigo-600 dark:text-indigo-400 mt-1">{{ $platinumCount }}</h3>
                <span class="text-[10px] text-indigo-600 dark:text-indigo-400 font-medium">Member VIP</span>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200/80 dark:border-slate-800 shadow-xs col-span-2 sm:col-span-1">
                <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block">Poin Beredar</span>
                <h3 class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($totalPoints, 0, ',', '.') }}</h3>
                <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium">Akumulasi poin</span>
            </div>
        </div>

        {{-- FILTER & PENCARIAN --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 shadow-xs">
            <form action="{{ route('customers.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[220px]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari kode member, nama, atau no HP..."
                           class="w-full pl-9 pr-4 py-2 text-xs font-medium rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                </div>

                <select name="tier"
                        onchange="this.form.submit()"
                        class="py-2 px-3 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                    <option value="" class="dark:bg-slate-800 dark:text-white">Semua Tier Membership</option>
                    <option value="Silver" class="dark:bg-slate-800 dark:text-white" {{ request('tier') === 'Silver' ? 'selected' : '' }}>Silver</option>
                    <option value="Gold" class="dark:bg-slate-800 dark:text-white" {{ request('tier') === 'Gold' ? 'selected' : '' }}>Gold</option>
                    <option value="Platinum" class="dark:bg-slate-800 dark:text-white" {{ request('tier') === 'Platinum' ? 'selected' : '' }}>Platinum</option>
                </select>

                <select name="status"
                        onchange="this.form.submit()"
                        class="py-2 px-3 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                    <option value="" class="dark:bg-slate-800 dark:text-white">Semua Status</option>
                    <option value="active" class="dark:bg-slate-800 dark:text-white" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" class="dark:bg-slate-800 dark:text-white" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
                    Filter
                </button>

                @if(request('search') || request('tier') || request('status'))
                    <a href="{{ route('customers.index') }}"
                       class="px-3 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- TABEL PELANGGAN --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                            <th class="py-3.5 px-4">Member ID</th>
                            <th class="py-3.5 px-4">Nama Pelanggan</th>
                            <th class="py-3.5 px-4">Kontak</th>
                            <th class="py-3.5 px-4 text-center">Tier</th>
                            <th class="py-3.5 px-4 text-center">Diskon Member</th>
                            <th class="py-3.5 px-4 text-center">Poin Loyalitas</th>
                            <th class="py-3.5 px-4 text-center">Transaksi</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-700 dark:text-slate-300">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200/60 dark:border-slate-700">
                                        {{ $customer->member_code ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-slate-900 dark:text-white block text-xs">{{ $customer->name }}</span>
                                    @if($customer->address)
                                        <span class="text-[10px] text-slate-400 block truncate max-w-xs">{{ $customer->address }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300">
                                    <div class="font-mono text-xs">{{ $customer->phone ?? '-' }}</div>
                                    @if($customer->email)
                                        <div class="text-[10px] text-slate-400 truncate">{{ $customer->email }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($customer->membership_tier === 'Platinum')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300 border border-purple-200 dark:border-purple-800 shadow-2xs">
                                            ✦ Platinum
                                        </span>
                                    @elseif($customer->membership_tier === 'Gold')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                            ★ Gold
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                            ● Silver
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="font-black text-indigo-700 dark:text-indigo-400 text-xs">
                                        {{ (float)$customer->discount_percent }}%
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        {{ number_format($customer->points, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="text-slate-600 dark:text-slate-300 font-semibold">{{ $customer->transactions_count ?? 0 }}x</span>
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    @if($customer->is_active)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('customers.edit', $customer) }}"
                                           class="p-1.5 text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-slate-800 rounded-lg transition"
                                           title="Edit Data Member">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        <form action="{{ route('customers.destroy', $customer) }}"
                                              method="POST"
                                              onsubmit="confirmSubmit(event, 'Data pelanggan {{ addslashes($customer->name) }} akan dihapus dari sistem.', 'Hapus Pelanggan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-1.5 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-slate-800 rounded-lg transition"
                                                    title="Hapus Pelanggan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 mx-auto flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <p class="font-bold text-slate-600 dark:text-slate-300">Belum ada data pelanggan</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Daftarkan pelanggan dan anggota membership baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($customers->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>

