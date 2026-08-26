@extends('layouts.app')

@section('content')

<div class="w-full px-8 py-8 flex justify-center">

    <div class="w-full max-w-3xl">

        {{-- ALERT ERROR UMUM / DUPLIKAT --}}
        @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-red-700 shadow-sm">
            <div class="flex items-center gap-2 text-lg font-black mb-1">
                ⚠️ Terjadi Kesalahan!
            </div>
            <ul class="list-disc list-inside text-base font-semibold space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- HEADER CARD --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 shadow-xl mb-8 p-8 text-white">

            <div class="absolute right-[-40px] top-[-40px] h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>

            <div class="relative flex items-center justify-between">

                <div>

                    <span class="inline-flex items-center rounded-full bg-white/20 px-4 py-1.5 text-xs font-semibold tracking-wide">

                        ✏️ Edit Data Kategori

                    </span>

                    <h1 class="mt-3 text-3xl md:text-4xl font-black">

                        Edit Kategori Produk

                    </h1>

                    <p class="mt-2 text-base text-amber-100 font-medium">

                        Perbarui nama atau pengelompokan kategori yang sudah ada.

                    </p>

                </div>

                <div class="hidden sm:flex h-20 w-20 items-center justify-center rounded-2xl bg-white/10 text-4xl">

                    ✏️

                </div>

            </div>

        </div>

        {{-- FORM CARD --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

            <form action="{{ route('categories.update', $category) }}" method="POST" novalidate class="p-8 md:p-10 space-y-8">

                @csrf
                @method('PUT')

                {{-- NAMA KATEGORI INPUT --}}
                <div class="space-y-3">

                    <label for="name" class="block text-lg font-extrabold text-gray-800">

                        Nama Kategori <span class="text-red-500">*</span>

                    </label>

                    <div class="relative rounded-2xl shadow-sm">

                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-xl">

                            🏷️

                        </div>

                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $category->name) }}"
                               placeholder="Contoh: Makanan, Minuman, Snack"
                               class="block w-full rounded-2xl border-2 pl-12 pr-4 py-4 text-lg font-semibold text-gray-800 placeholder-gray-400 focus:outline-none transition duration-150 {{ $errors->has('name') ? 'border-red-500 bg-red-50/30 focus:border-red-500 focus:ring-4 focus:ring-red-100' : 'border-gray-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-100' }}"
                               autofocus>

                    </div>

                    @error('name')
                        <p class="text-base font-bold text-red-600 mt-2 flex items-center gap-1.5">
                            🚨 {{ $message }}
                        </p>
                    @enderror

                    <p class="text-sm text-gray-400 font-medium">

                        💡 *Slug URL saat ini: <span class="font-mono font-bold text-gray-600">{{ $category->slug }}</span>*

                    </p>

                </div>

                {{-- ACTION BUTTONS --}}
                <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-4">

                    <a href="{{ route('categories.index') }}" 
                       class="px-6 py-3.5 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-base font-extrabold transition duration-150 flex items-center gap-2">

                        ⬅️ Batal

                    </a>

                    <button type="submit" 
                            class="px-8 py-3.5 rounded-2xl bg-amber-500 hover:bg-amber-600 text-gray-900 text-base font-extrabold shadow-lg shadow-amber-200 hover:shadow-amber-300 transition duration-150 flex items-center gap-2">

                        💾 Perbarui Kategori

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection