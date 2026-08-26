<x-app-layout>

<div class="w-full px-8 py-8 flex justify-center">

    <div class="w-full max-w-3xl">

        {{-- KOTAK PERINGATAN MERAH DI ATAS --}}
        @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-red-700 shadow-sm">
            <div class="flex items-center gap-2 text-lg font-black mb-1">
                ⚠️ Terjadi Kesalahan Validasi!
            </div>
            <ul class="list-disc list-inside text-base font-semibold space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- HEADER CARD --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 shadow-xl mb-8 p-8 text-white">

            <div class="absolute right-[-40px] top-[-40px] h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>

            <div class="relative flex items-center justify-between">

                <div>

                    <span class="inline-flex items-center rounded-full bg-white/20 px-4 py-1.5 text-xs font-semibold tracking-wide">

                        ➕ Add New Data

                    </span>

                    <h1 class="mt-3 text-3xl md:text-4xl font-black">

                        Tambah Kategori Baru

                    </h1>

                    <p class="mt-2 text-base text-indigo-100 font-medium">

                        Buat kelompok produk baru untuk mempermudah transaksi POS.

                    </p>

                </div>

                <div class="hidden sm:flex h-20 w-20 items-center justify-center rounded-2xl bg-white/10 text-4xl">

                    🏷️

                </div>

            </div>

        </div>

        {{-- FORM CARD --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Diberi novalidate agar diserahkannya langsung ke Laravel --}}
            <form action="{{ route('categories.store') }}" method="POST" novalidate class="p-8 md:p-10 space-y-8">

                @csrf

                {{-- NAMA KATEGORI INPUT --}}
                <div class="space-y-3">

                    <label for="name" class="block text-lg font-extrabold text-gray-800">

                        Nama Kategori <span class="text-red-500">*</span>

                    </label>

                    <div class="relative rounded-2xl shadow-sm">

                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-xl">

                            🏷️

                        </div>

                        {{-- Atribut required dihapus agar form lolos dikirim ke Laravel untuk divalidasi --}}
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               placeholder="Contoh: Makanan, Minuman, Snack, Minuman Dingin"
                               class="block w-full rounded-2xl border-2 pl-12 pr-4 py-4 text-lg font-semibold text-gray-800 placeholder-gray-400 focus:outline-none transition duration-150 {{ $errors->has('name') ? 'border-red-500 bg-red-50/30 focus:border-red-500 focus:ring-4 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100' }}"
                               autofocus>

                    </div>

                    {{-- PERINGATAN MERAH DI BAWAH KOLOM --}}
                    @error('name')
                        <p class="text-base font-bold text-red-600 mt-2 flex items-center gap-1.5">
                            🚨 {{ $message }}
                        </p>
                    @enderror

                    <p class="text-sm text-gray-400 font-medium">

                        💡 *Slug URL akan otomatis terbuat berdasarkan nama kategori yang kamu masukkan.*

                    </p>

                </div>

                {{-- ACTION BUTTONS --}}
                <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-4">

                    <a href="{{ route('categories.index') }}" 
                       class="px-6 py-3.5 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-base font-extrabold transition duration-150 flex items-center gap-2">

                        ⬅️ Kembali

                    </a>

                    <button type="submit" 
                            class="px-8 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-base font-extrabold shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transition duration-150 flex items-center gap-2">

                        💾 Simpan Kategori

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</x-app-layout>