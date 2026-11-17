<x-app-layout>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- Notifikasi Sukses / Gagal --}}
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 text-emerald-800 text-xs font-bold flex items-center gap-2.5 shadow-xs">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3.5 text-rose-800 text-xs font-bold flex items-center gap-2.5 shadow-xs">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- STATISTIK COMPACT METODE BAYAR --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Metode</span>
                <h3 class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mt-1">{{ $paymentMethods->count() }}</h3>
                <span class="text-[10px] text-slate-400 font-medium">Kanal pembayaran terdaftar</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Metode Aktif</span>
                <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">
                    {{ $paymentMethods->where('is_active', true)->count() }}
                </h3>
                <span class="text-[10px] text-slate-400 font-medium">Tersedia di terminal kasir POS</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tipe Pembayaran</span>
                <h3 class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-1">Dinamis</h3>
                <span class="text-[10px] text-slate-400 font-medium">Tunai, QRIS, Bank, E-Wallet</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
        </div>
    </div>

    {{-- TOOLBAR ACTION --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 shadow-xs flex items-center justify-between">
        <div>
            <h2 class="text-sm font-extrabold text-slate-900 dark:text-white leading-tight">Pengaturan Metode Pembayaran</h2>
            <p class="text-xs text-slate-400">Atur kanal pembayaran kasir (Tunai, QRIS, Transfer Bank, E-Wallet) tanpa ubah kode.</p>
        </div>

        <a href="{{ route('payment-methods.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition active:scale-98">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Metode Pembayaran</span>
        </a>
    </div>

    {{-- TABEL DATA METODE PEMBAYARAN --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="py-3.5 px-4 w-16 text-center">No</th>
                        <th class="py-3.5 px-4">Nama Metode</th>
                        <th class="py-3.5 px-4">Tipe & Kode</th>
                        <th class="py-3.5 px-4">Informasi Rekening / Akun</th>
                        <th class="py-3.5 px-4 text-center">Status Kasir</th>
                        <th class="py-3.5 px-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($paymentMethods as $index => $pm)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-3 px-4 text-center font-bold text-slate-400">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-800 dark:text-white text-sm">{{ $pm->name }}</span>
                                @if($pm->instructions)
                                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">{{ $pm->instructions }}</p>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-1.5">
                                    @if($pm->type === 'cash')
                                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-extrabold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">TUNAI</span>
                                    @elseif($pm->type === 'qris')
                                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-extrabold bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">QRIS</span>
                                    @elseif($pm->type === 'bank_transfer')
                                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-extrabold bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">BANK TRANSFER</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-extrabold bg-violet-50 dark:bg-violet-950/50 text-violet-700 dark:text-violet-300 border border-violet-200 dark:border-violet-800">E-WALLET</span>
                                    @endif
                                    <span class="font-mono text-[11px] text-slate-400">{{ $pm->code }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-slate-600 dark:text-slate-300">
                                @if($pm->account_number)
                                    <span class="font-mono font-bold text-slate-700 dark:text-slate-200 text-xs">{{ $pm->account_number }}</span>
                                    @if($pm->account_name)
                                        <span class="text-slate-400 text-[11px]"> (a.n {{ $pm->account_name }})</span>
                                    @endif
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <form action="{{ route('payment-methods.toggle', $pm) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-extrabold transition {{ $pm->is_active ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-200' }}"
                                            title="Klik untuk ubah status">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $pm->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $pm->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </button>
                                </form>
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('payment-methods.edit', $pm) }}"
                                       class="p-1.5 text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-slate-800 rounded-lg transition"
                                       title="Edit Metode">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <form action="{{ route('payment-methods.destroy', $pm) }}"
                                          method="POST"
                                          onsubmit="confirmSubmit(event, 'Metode pembayaran {{ addslashes($pm->name) }} akan dihapus dari pilihan kasir.', 'Hapus Metode Pembayaran?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-slate-800 rounded-lg transition"
                                                title="Hapus Metode">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Belum ada metode pembayaran</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

</x-app-layout>

