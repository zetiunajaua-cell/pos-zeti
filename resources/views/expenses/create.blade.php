<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Catat Pengeluaran Baru
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Catat biaya operasional atau pembelanjaan toko untuk perhitungan akurat laba dan rugi.
                </p>
            </div>

            <a href="{{ route('expenses.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs font-semibold space-y-1">
                    <p class="font-bold">Mohon perbaiki kesalahan berikut:</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data"
                  class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-700/60 space-y-6">
                @csrf

                <!-- Kategori & Tanggal -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                            Kategori Pengeluaran <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" required
                                class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                            Tanggal Pengeluaran <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required
                               class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Judul Keterangan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        Judul / Keterangan Pengeluaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="Contoh: Pembelian cup & sedotan plastik 500 pcs"
                           class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Nominal Pengeluaran -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        Nominal Biaya (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-slate-400">Rp</span>
                        <input type="number" step="1" min="0" name="amount" value="{{ old('amount') }}" required
                               placeholder="150000"
                               class="w-full pl-11 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Foto Bukti / Struk Pembayaran -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        Foto Bukti / Struk Pembayaran (Opsional, JPG/PNG maks 2MB)
                    </label>
                    <input type="file" name="receipt_image" accept="image/*"
                           class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900/40 dark:file:text-blue-300 hover:file:bg-blue-100">
                </div>

                <!-- Catatan Tambahan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        Catatan Tambahan
                    </label>
                    <textarea name="note" rows="3" placeholder="Informasi nota toko, vendor, atau catatan khusus..."
                              class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">{{ old('note') }}</textarea>
                </div>

                <!-- Tombol Submit -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <a href="{{ route('expenses.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold shadow-lg shadow-blue-500/25 transition">
                        Simpan Pengeluaran
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
