<x-app-layout>

<div class="w-full px-8 py-8">

{{-- SUCCESS --}}
@if(session('success'))
<div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-5 text-green-700 shadow-sm text-lg font-medium">
    {{ session('success') }}
</div>
@endif

{{-- ERROR --}}
@if(session('error'))
<div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-red-700 shadow-sm text-lg font-medium">
    {{ session('error') }}
</div>
@endif

{{-- HERO BANNER --}}
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 shadow-xl mb-8">

    <div class="absolute right-[-80px] top-[-80px] h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>

    <div class="relative flex items-center justify-between px-10 py-10">

        <div>

            <span class="inline-flex items-center rounded-full bg-white/20 px-5 py-2 text-sm text-white font-semibold">

                🗂️ Category Management

            </span>

            <h1 class="mt-4 text-4xl md:text-5xl font-black text-white">

                Kategori Produk

            </h1>

            <p class="mt-3 text-lg text-indigo-100 font-medium">

                Kelola kelompok dan pengelompokan produk aplikasi POS Zeti.

            </p>

            <div class="mt-6">

                <a href="{{ route('categories.create') }}"
                   class="inline-flex items-center gap-2.5 rounded-2xl bg-white px-6 py-3.5 text-lg font-black text-indigo-700 shadow-md hover:bg-indigo-50 transition">

                    ➕ Tambah Kategori Baru

                </a>

            </div>

        </div>

        <div class="hidden lg:flex">

            <div class="flex h-40 w-40 items-center justify-center rounded-full bg-white/10">

                <span class="text-7xl">

                    🗂️

                </span>

            </div>

        </div>

    </div>

</div>

{{-- STATISTIC CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    {{-- Total Kategori --}}
    <div class="bg-white rounded-3xl p-7 shadow-sm border border-gray-100 hover:shadow-md transition">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 font-semibold text-base">
                    Total Kategori
                </p>

                <h2 class="text-4xl md:text-5xl font-black text-purple-600 mt-2">
                    {{ $categories->count() }}
                </h2>

                <p class="text-sm text-gray-400 mt-1 font-medium">
                    Jenis kategori terdaftar
                </p>

            </div>

            <div class="w-16 h-16 rounded-2xl bg-purple-100 flex items-center justify-center text-4xl">

                📁

            </div>

        </div>

    </div>

    {{-- Total Produk Terhubung (Perbaikan Hitungan) --}}
    <div class="bg-white rounded-3xl p-7 shadow-sm border border-gray-100 hover:shadow-md transition">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 font-semibold text-base">
                    Total Produk
                </p>

                <h2 class="text-4xl md:text-5xl font-black text-blue-600 mt-2">
                    {{ $categories->reduce(function($carry, $cat) {
                        return $carry + ($cat->products_count ?? ($cat->products ? $cat->products->count() : 0));
                    }, 0) }}
                </h2>

                <p class="text-sm text-gray-400 mt-1 font-medium">
                    Produk dalam kategori
                </p>

            </div>

            <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center text-4xl">

                📦

            </div>

        </div>

    </div>

    {{-- Status Kategori --}}
    <div class="bg-white rounded-3xl p-7 shadow-sm border border-gray-100 hover:shadow-md transition">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 font-semibold text-base">
                    Status Kategori
                </p>

                <h2 class="text-3xl font-black text-emerald-600 mt-2">
                    Siap Digunakan
                </h2>

                <p class="text-sm text-gray-400 mt-1 font-medium">
                    Terhubung dengan menu produk
                </p>

            </div>

            <div class="w-16 h-16 rounded-2xl bg-emerald-100 flex items-center justify-center text-4xl">

                ✅

            </div>

        </div>

    </div>

</div>

{{-- TABLE CATEGORIES --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

    <!-- Tombol ganda di header ini sudah dibuang -->
    <div class="px-8 py-6 border-b bg-gray-50/50">

        <h2 class="text-2xl font-black text-gray-800">
            📋 Daftar Kategori
        </h2>

        <p class="text-base text-gray-500 mt-1 font-medium">
            Menampilkan seluruh data kategori yang tersedia.
        </p>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-left border-collapse">

            <thead class="bg-slate-100 text-gray-800 font-extrabold border-b border-gray-200 text-lg">

                <tr>

                    <th class="px-8 py-6 text-center w-24">No</th>

                    <th class="px-8 py-6 text-left">Nama Kategori</th>

                    <th class="px-8 py-6 text-center">Slug</th>

                    <th class="px-8 py-6 text-center">Jumlah Produk</th>

                    <th class="px-8 py-6 text-center">Aksi</th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse($categories as $index => $category)

                <tr class="hover:bg-indigo-50/50 transition duration-150">

                    {{-- No --}}
                    <td class="px-8 py-6 text-center font-bold text-gray-600 text-lg">
                        {{ $index + 1 }}
                    </td>

                    {{-- Nama Kategori --}}
                    <td class="px-8 py-6">

                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 rounded-2xl bg-purple-100 flex items-center justify-center text-2xl shrink-0">

                                🏷️

                            </div>

                            <div class="font-black text-gray-800 text-xl whitespace-nowrap">

                                {{ $category->name }}

                            </div>

                        </div>

                    </td>

                    {{-- Slug --}}
                    <td class="px-8 py-6 text-center">

                        <span class="inline-flex px-5 py-2.5 rounded-2xl bg-slate-100 text-slate-800 font-mono font-bold text-base">

                            {{ $category->slug }}

                        </span>

                    </td>

                    {{-- Jumlah Produk --}}
                    <td class="px-8 py-6 text-center">

                        <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-indigo-100/70 text-indigo-800 font-black text-base">

                            📦 {{ $category->products_count ?? ($category->products ? $category->products->count() : 0) }} Produk

                        </span>

                    </td>

                    {{-- Aksi --}}
                    <td class="px-8 py-6">

                        <div class="flex justify-center gap-3">

                            <a href="{{ route('categories.edit', $category) }}"
                               class="bg-amber-400 hover:bg-amber-500 text-gray-900 px-5 py-2.5 rounded-2xl text-base font-extrabold shadow-sm transition flex items-center gap-1.5">

                                ✏️ Edit

                            </a>

                            <form action="{{ route('categories.destroy', $category) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-2xl text-base font-extrabold shadow-sm transition flex items-center gap-1.5">

                                    🗑️ Hapus

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="py-20 text-center">

                        <div class="text-7xl">

                            🗂️

                        </div>

                        <h2 class="mt-4 text-2xl font-black text-gray-700">

                            Belum ada kategori

                        </h2>

                        <p class="mt-2 text-lg text-gray-500 font-medium">

                            Silakan buat kategori pertama kamu.

                        </p>

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</div>

</x-app-layout>