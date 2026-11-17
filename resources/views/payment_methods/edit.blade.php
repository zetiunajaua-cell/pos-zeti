<x-app-layout>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-base font-extrabold text-slate-900 leading-tight">Edit Metode Pembayaran: {{ $paymentMethod->name }}</h2>
            <p class="text-xs text-slate-400">Perbarui rincian metode pembayaran kanal kasir.</p>
        </div>

        <a href="{{ route('payment-methods.index') }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-xs">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3.5 text-rose-800 text-xs font-bold shadow-xs">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('payment-methods.update', $paymentMethod) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama Metode --}}
                <div class="space-y-1.5">
                    <label for="name" class="block text-xs font-bold text-slate-700">
                        Nama Metode Pembayaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $paymentMethod->name) }}"
                           required
                           class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-slate-800 py-2.5 px-3.5">
                </div>

                {{-- Tipe Kanal --}}
                <div class="space-y-1.5">
                    <label for="type" class="block text-xs font-bold text-slate-700">
                        Tipe Kanal Pembayaran <span class="text-rose-500">*</span>
                    </label>
                    <select id="type"
                            name="type"
                            required
                            class="w-full text-xs font-bold rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-slate-800 py-2.5 px-3.5">
                        <option value="cash" {{ old('type', $paymentMethod->type) === 'cash' ? 'selected' : '' }}>Tunai / Cash</option>
                        <option value="qris" {{ old('type', $paymentMethod->type) === 'qris' ? 'selected' : '' }}>QRIS (Barcode Dinamis / Statis)</option>
                        <option value="bank_transfer" {{ old('type', $paymentMethod->type) === 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="e_wallet" {{ old('type', $paymentMethod->type) === 'e_wallet' ? 'selected' : '' }}>E-Wallet (GoPay, ShopeePay, OVO, Dana)</option>
                    </select>
                </div>

                {{-- Nomor Rekening / Akun --}}
                <div class="space-y-1.5">
                    <label for="account_number" class="block text-xs font-bold text-slate-700">
                        Nomor Rekening / Nomor Telepon / ID Merchant
                    </label>
                    <input type="text"
                           id="account_number"
                           name="account_number"
                           value="{{ old('account_number', $paymentMethod->account_number) }}"
                           placeholder="Contoh: 8800123456 / 08123456789"
                           class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-slate-800 py-2.5 px-3.5">
                </div>

                {{-- Nama Pemilik Rekening --}}
                <div class="space-y-1.5">
                    <label for="account_name" class="block text-xs font-bold text-slate-700">
                        Nama Pemilik Rekening (a.n)
                    </label>
                    <input type="text"
                           id="account_name"
                           name="account_name"
                           value="{{ old('account_name', $paymentMethod->account_name) }}"
                           placeholder="Contoh: POS ZETI OFFICIAL / PT MAJU"
                           class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-slate-800 py-2.5 px-3.5">
                </div>
            </div>

            {{-- Instruksi Pembayaran --}}
            <div class="space-y-1.5">
                <label for="instructions" class="block text-xs font-bold text-slate-700">
                    Instruksi Pembayaran untuk Kasir / Pelanggan
                </label>
                <textarea id="instructions"
                          name="instructions"
                          rows="3"
                          class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-slate-800 py-2.5 px-3.5">{{ old('instructions', $paymentMethod->instructions) }}</textarea>
            </div>

            {{-- Status Aktif --}}
            <div class="flex items-center gap-3 pt-2">
                <input type="checkbox"
                       id="is_active"
                       name="is_active"
                       value="1"
                       {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500">
                <label for="is_active" class="text-xs font-bold text-slate-700">
                    Aktifkan metode ini di kasir POS
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('payment-methods.index') }}"
                   class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition active:scale-98">
                    Perbarui Metode Pembayaran
                </button>
            </div>
        </form>
    </div>

</div>

</x-app-layout>

