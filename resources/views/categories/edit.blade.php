<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- HEADER BANNER COMPACT --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 shadow-md p-6 sm:p-8 text-white">
            <div class="absolute right-[-40px] top-[-40px] h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
            <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-xs font-semibold backdrop-blur-xs">
                        <span>✏️</span> Edit Kategori
                    </div>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Perbarui: {{ $category->name }}
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm text-indigo-100 font-medium">
                        Ubah nama kategori produk untuk sistem kasir dan inventaris.
                    </p>
                </div>
                <a href="{{ route('categories.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white/20 hover:bg-white/30 text-white font-bold text-xs backdrop-blur-xs transition self-start sm:self-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Kategori</span>
                </a>
            </div>
        </div>

        {{-- ERROR ALERT --}}
        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50/90 p-5 text-rose-800 text-xs font-semibold shadow-xs">
                <div class="flex items-center gap-2 text-sm font-bold mb-2">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Periksa Kembali Data Input:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM CARD FULL WIDTH COMPACT --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
            <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="max-w-2xl space-y-4">
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nama Kategori <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $category->name) }}"
                               required
                               autofocus
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/30' : '' }}">
                        @error('name')
                            <p class="text-[11px] font-semibold text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- BUTTONS --}}
                <div class="pt-6 border-t border-slate-100 flex items-center gap-3">
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 active:scale-98 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Perbarui Kategori</span>
                    </button>
                    <a href="{{ route('categories.index') }}"
                       class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                        Batal
                    </a>
                </div>

            </form>
        </div>

    </div>
</x-app-layout>
