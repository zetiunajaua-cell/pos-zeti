<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Banner Gradient -->
            <div class="relative bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-2xl p-6 mb-6 text-white shadow-lg flex items-center justify-between">
                <div>
                    <span class="bg-white/25 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">Product Management</span>
                    <h1 class="text-2xl font-bold mt-2">Edit Produk</h1>
                    <p class="text-blue-100 text-sm mt-1">Perbarui data produk sesuai dengan perubahan terbaru di toko.</p>
                </div>
                <div class="hidden sm:block bg-white/10 p-3 rounded-xl">
                    <svg class="w-12 h-12 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
            </div>

            <!-- Form Card Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                        <span class="font-semibold block mb-1">Terjadi kesalahan pada input:</span>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('products.update', $product) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('products.form')

                    <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('products.index') }}" 
                           class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition duration-150">
                            Kembali
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl shadow-md transition duration-150">
                            Perbarui Produk
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</x-app-layout>