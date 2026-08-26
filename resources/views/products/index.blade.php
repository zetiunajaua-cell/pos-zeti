<x-app-layout>

<div class="w-full px-6 py-6">

{{-- SUCCESS --}}
@if(session('success'))
<div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700 shadow-sm text-base">
    {{ session('success') }}
</div>
@endif

{{-- ERROR --}}
@if(session('error'))
<div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700 shadow-sm text-base">
    {{ session('error') }}
</div>
@endif

{{-- VALIDATION --}}
@if ($errors->any())
<div class="mb-4 rounded-xl border border-red-300 bg-red-50 p-4 shadow-sm text-base">

    <h3 class="font-bold text-red-700 text-base">
        Terjadi Kesalahan
    </h3>

    <ul class="list-disc ml-5 mt-2 text-red-600">

        @foreach($errors->all() as $error)

        <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>
@endif

{{-- HERO --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-700 shadow-lg">

    <div class="absolute right-[-80px] top-[-80px] h-60 w-60 rounded-full bg-white/10 blur-2xl"></div>

    <div class="relative flex items-center justify-between px-8 py-8">

        <div>

            <span class="inline-flex items-center rounded-full bg-white/20 px-4 py-1.5 text-xs text-white font-medium">

                📦 Product Management

            </span>

            <h1 class="mt-3 text-3xl md:text-4xl font-extrabold text-white">

                Data Produk

            </h1>

            <p class="mt-2 text-base text-purple-100">

                Kelola seluruh data produk dengan cepat, aman, dan profesional.

            </p>

           <div class="mt-5">

                <a href="{{ route('products.index') }}"
                    class="inline-block rounded-xl border border-white/30 bg-white/10 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/20 transition">

                    🔄 Refresh

                </a>

            </div>
        </div>

        <div class="hidden lg:flex">

            <div class="flex h-32 w-32 items-center justify-center rounded-full bg-white/10">

                <span class="text-6xl">

                    📦

                </span>

            </div>

        </div>

    </div>

</div>

{{-- STATISTIC --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mt-6">

    {{-- Total Produk --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 font-medium text-sm">
                    Total Produk
                </p>

                <h2 class="text-3xl md:text-4xl font-black text-blue-600 mt-1">
                    {{ $totalProduct }}
                </h2>

                <p class="text-xs text-gray-400 mt-1">
                    Semua produk
                </p>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-3xl">

                📦

            </div>

        </div>

    </div>

    {{-- Produk Aktif --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 font-medium text-sm">
                    Produk Aktif
                </p>

                <h2 class="text-3xl md:text-4xl font-black text-green-600 mt-1">
                    {{ $totalActive }}
                </h2>

                <p class="text-xs text-gray-400 mt-1">
                    Status aktif
                </p>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center text-3xl">

                ✅

            </div>

        </div>

    </div>

    {{-- Total Kategori --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 font-medium text-sm">
                    Total Kategori
                </p>

                <h2 class="text-3xl md:text-4xl font-black text-purple-600 mt-1">
                    {{ $totalCategory }}
                </h2>

                <p class="text-xs text-gray-400 mt-1">
                    Jenis kategori
                </p>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-purple-100 flex items-center justify-center text-3xl">

                🗂️

            </div>

        </div>

    </div>

    {{-- Total Stok --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 font-medium text-sm">
                    Total Stok
                </p>

                <h2 class="text-3xl md:text-4xl font-black text-orange-500 mt-1">
                    {{ $totalStock }}
                </h2>

                <p class="text-xs text-gray-400 mt-1">
                    Seluruh stok
                </p>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center text-3xl">

                📊

            </div>

        </div>

    </div>

</div>

{{-- TOOLBAR --}}
<div id="tableArea" class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        {{-- Search Form --}}
        <form action="{{ route('products.index') }}#tableArea"
              method="GET"
              class="flex flex-1 gap-3">

            <div class="relative flex-1">

                <input
                    type="text"
                    id="searchInput"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari SKU, Nama Produk Atau Kategori..."
                    class="w-full rounded-xl border border-gray-200 pl-11 pr-11 py-3.5 text-base text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">

                {{-- Ikon Kaca Pembesar (Kiri) --}}
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base">

                    🔍

                </div>

                {{-- Tombol (X) Clear Input (Kanan) --}}
                <button
                    type="button"
                    onclick="clearSearchInput()"
                    title="Bersihkan Pencarian"
                    class="absolute right-3.5 top-1/2 -translate-y-1/2 w-6 h-6 flex items-center justify-center rounded-full bg-gray-200 text-gray-500 hover:bg-red-500 hover:text-white text-xs font-bold transition">
                    ✕
                </button>

            </div>

            <button
                type="submit"
                class="rounded-xl bg-blue-600 hover:bg-blue-700 px-7 py-3.5 text-base text-white font-semibold transition">

                Cari

            </button>

            <a href="{{ route('products.index') }}#tableArea"
              class="rounded-xl bg-gray-100 hover:bg-gray-200 px-6 py-3.5 text-base font-semibold text-gray-600 transition flex items-center justify-center">

                Reset

            </a>

        </form>

        {{-- Button Tambah Produk --}}
        <a href="{{ route('products.create') }}"
          class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3.5 text-base font-bold text-white shadow hover:opacity-95 transition whitespace-nowrap">

            ➕&nbsp;Tambah Produk

        </a>

    </div>

</div>

{{-- TABLE --}}
<div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    <div class="px-7 py-5 border-b bg-gray-50/50">

        <h2 class="text-xl font-bold text-gray-800">
            📋 Daftar Produk
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Total {{ $totalProduct }} produk tersedia.
        </p>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-base">

            <thead class="bg-slate-100 text-gray-800 font-bold border-b border-gray-200 text-base">

                <tr>

                    <th class="px-6 py-5 text-left">SKU</th>

                    <th class="px-6 py-5 text-left">Nama Produk</th>

                    <th class="px-6 py-5 text-center">Kategori</th>

                    <th class="px-6 py-5 text-center">Harga Jual</th>

                    <th class="px-6 py-5 text-center">Stok</th>

                    <th class="px-6 py-5 text-center">Satuan</th>

                    <th class="px-6 py-5 text-center">Status</th>

                    <th class="px-6 py-5 text-center">Aksi</th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse($products as $product)

                <tr class="hover:bg-sky-50/50 transition duration-150">

                    {{-- SKU --}}
                    <td class="px-6 py-5 font-bold text-gray-700 text-base whitespace-nowrap">
                        {{ $product->sku }}
                    </td>

                    {{-- Nama Produk --}}
                    <td class="px-6 py-5">

                        <div class="flex items-center gap-3.5">

                            <div class="w-10 h-10 rounded-xl bg-blue-100/70 flex items-center justify-center text-xl">

                                📦

                            </div>

                            <div class="font-bold text-gray-800 text-base whitespace-nowrap">

                                {{ $product->name }}

                            </div>

                        </div>

                    </td>

                    {{-- Kategori --}}
                    <td class="px-6 py-5 text-center">

                        <span class="inline-flex px-4 py-2 rounded-full bg-indigo-50 text-indigo-700 font-bold text-sm whitespace-nowrap">

                            {{ $product->category->name ?? '-' }}

                        </span>

                    </td>

                    {{-- Harga --}}
                    <td class="px-6 py-5 text-center font-black text-green-600 text-lg whitespace-nowrap">

                        Rp {{ number_format($product->sell_price,0,',','.') }}

                    </td>

                    {{-- Stok --}}
                    <td class="px-6 py-5 text-center">

                        @if($product->stock <= 10)

                            <span class="inline-flex px-4 py-2 rounded-full bg-red-100 text-red-700 font-extrabold text-sm">

                                {{ $product->stock }}

                            </span>

                        @elseif($product->stock <= 30)

                            <span class="inline-flex px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 font-extrabold text-sm">

                                {{ $product->stock }}

                            </span>

                        @else

                            <span class="inline-flex px-4 py-2 rounded-full bg-green-100 text-green-800 font-extrabold text-sm">

                                {{ $product->stock }}

                            </span>

                        @endif

                    </td>

                    {{-- Satuan --}}
                    <td class="px-6 py-5 text-center">

                        <span class="bg-gray-100 px-4 py-2 rounded-full text-sm font-bold text-gray-700">

                            {{ $product->unit }}

                        </span>

                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-5 text-center whitespace-nowrap">

                        @if($product->is_active)

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 text-green-800 px-4 py-2 text-sm font-bold">

                                🟢 Aktif

                            </span>

                        @else

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 text-red-800 px-4 py-2 text-sm font-bold">

                                🔴 Nonaktif

                            </span>

                        @endif

                    </td>

                    {{-- Aksi --}}
                    <td class="px-6 py-5">

                        <div class="flex justify-center gap-2.5">

                            <a href="{{ route('products.edit',$product) }}"
                                class="bg-amber-400 hover:bg-amber-500 text-gray-900 px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition flex items-center gap-1">

                                ✏️ Edit

                            </a>

                            <form action="{{ route('products.destroy',$product) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus produk ini?')">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition flex items-center gap-1">

                                    🗑️ Hapus

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="8" class="py-16 text-center">

                        <div class="text-6xl">

                            📦

                        </div>

                        <h2 class="mt-4 text-xl font-bold text-gray-700">

                            Belum ada produk

                        </h2>

                        <p class="mt-1 text-base text-gray-500">

                            Silakan tambahkan produk pertama.

                        </p>

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</div>

{{-- JavaScript Sederhana --}}
<script>
    function clearSearchInput() {
        const input = document.getElementById('searchInput');
        input.value = '';
        input.focus();
    }

    // Auto-Scroll ke area pencarian/tabel jika ada parameter search di URL
    document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('search')) {
            const tableArea = document.getElementById('tableArea');
            if (tableArea) {
                tableArea.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
</script>

</x-app-layout>