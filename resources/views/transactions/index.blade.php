<x-app-layout>

<div
    class="max-w-7xl mx-auto px-6 py-8"
    x-data="{
        cart: [],
        paidAmount: 0,

        addToCart(product) {
            let item = this.cart.find(i => i.id === product.id);

            if (item) {
                if (item.quantity < product.stock) {
                    item.quantity++;
                } else {
                    alert(
                        `Stok produk ${product.name} tidak mencukupi. Stok tersedia hanya ${product.stock} ${product.unit}.`
                    );
                }
            } else {
                if (product.stock <= 0) {
                    alert(
                        `Stok produk ${product.name} tidak tersedia.`
                    );
                    return;
                }

                this.cart.push({
                    id: product.id,
                    name: product.name,
                    sku: product.sku,
                    price: Number(product.sell_price),
                    stock: Number(product.stock),
                    unit: product.unit,
                    quantity: 1
                });
            }
        },

        increase(item) {
            if (item.quantity < item.stock) {
                item.quantity++;
            } else {
                alert(
                    `Stok produk ${item.name} tidak mencukupi. Stok tersedia hanya ${item.stock} ${item.unit}.`
                );
            }
        },

        decrease(item) {
            if (item.quantity > 1) {
                item.quantity--;
            } else {
                this.removeItem(item);
            }
        },

        removeItem(item) {
            this.cart = this.cart.filter(i => i.id !== item.id);
        },

        get totalItems() {
            return this.cart.reduce(
                (total, item) => total + item.quantity,
                0
            );
        },

        get total() {
            return this.cart.reduce(
                (total, item) => total + (item.price * item.quantity),
                0
            );
        },

        get change() {
            if (this.paidAmount >= this.total) {
                return this.paidAmount - this.total;
            }

            return 0;
        },

        get isPaymentEnough() {
            return this.paidAmount >= this.total;
        },

        formatRupiah(value) {
            return new Intl.NumberFormat('id-ID').format(value);
        }
    }"
>

    {{-- NOTIFIKASI SUKSES --}}
    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
            <div class="flex items-center gap-2">
                <span class="text-xl">✅</span>
                <span class="font-semibold">
                    {{ session('success') }}
                </span>
            </div>
        </div>
    @endif

    {{-- NOTIFIKASI ERROR --}}
    @if(session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
            <div class="flex items-center gap-2">
                <span class="text-xl">❌</span>
                <span class="font-semibold">
                    {{ session('error') }}
                </span>
            </div>
        </div>
    @endif


    {{-- HEADER --}}
    <div class="mb-6">

        <h1 class="text-3xl font-bold text-gray-800">
            Transaksi Kasir
        </h1>

        <p class="mt-1 text-gray-500">
            Pilih produk untuk dimasukkan ke keranjang.
        </p>

    </div>


    {{-- POS AREA --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


        {{-- ================= PRODUK ================= --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6">

            {{-- SEARCH --}}
            <form
                method="GET"
                action="{{ route('transactions.index') }}"
            >

                @if($categoryId)

                    <input
                        type="hidden"
                        name="category_id"
                        value="{{ $categoryId }}"
                    >

                @endif

                <div class="flex gap-3">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama atau SKU produk..."
                        class="flex-1 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                    <button
                        type="submit"
                        class="px-6 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700"
                    >
                        Cari
                    </button>

                    <a
                        href="{{ route('transactions.index') }}"
                        class="px-5 py-3 rounded-xl bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300"
                    >
                        Reset
                    </a>

                </div>

            </form>


            {{-- KATEGORI --}}
            <div class="mt-5">

                <p class="text-sm font-bold text-gray-700 mb-3">
                    Kategori Produk
                </p>

                <div class="flex flex-wrap gap-3">

                    {{-- SEMUA --}}
                    <a
                        href="{{ route('transactions.index', ['search' => $search]) }}"
                        class="px-5 py-2.5 rounded-xl font-semibold
                        {{ empty($categoryId)
                            ? 'bg-blue-600 text-white'
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        Semua
                    </a>


                    {{-- KATEGORI DATABASE --}}
                    @foreach($categories as $category)

                        <a
                            href="{{ route('transactions.index', [
                                'category_id' => $category->id,
                                'search' => $search
                            ]) }}"
                            class="px-5 py-2.5 rounded-xl font-semibold
                            {{ $categoryId == $category->id
                                ? 'bg-blue-600 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                        >
                            {{ $category->name }}
                        </a>

                    @endforeach

                </div>

            </div>


            {{-- PEMBATAS --}}
            <div class="border-b border-gray-200 my-6"></div>


            {{-- PRODUCT GRID --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                @forelse($products as $product)

                    <div
                        class="border border-gray-200 rounded-2xl p-4 hover:shadow-md transition"
                    >

                        <div class="text-4xl text-center mb-3">
                            📦
                        </div>


                        <h3 class="font-bold text-gray-800 truncate">
                            {{ $product->name }}
                        </h3>


                        <p class="text-sm text-gray-400 mt-1">
                            {{ $product->sku }}
                        </p>


                        <p class="text-xs text-gray-500 mt-2">
                            {{ $product->category->name }}
                        </p>


                        <p class="text-blue-600 font-bold mt-3">
                            Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                        </p>


                        <p class="text-sm text-gray-500 mt-1">
                            Stok: {{ $product->stock }} {{ $product->unit }}
                        </p>


                        {{-- TAMBAH --}}
                        <button
                            type="button"

                            @click="addToCart({
                                id: {{ $product->id }},
                                name: @js($product->name),
                                sku: @js($product->sku),
                                sell_price: {{ $product->sell_price }},
                                stock: {{ $product->stock }},
                                unit: @js($product->unit)
                            })"

                            class="w-full mt-4 rounded-xl bg-blue-600 px-4 py-2.5 text-white font-semibold hover:bg-blue-700"
                        >
                            + Tambah
                        </button>

                    </div>

                @empty

                    <div class="col-span-full text-center py-10 text-gray-500">
                        Produk tidak ditemukan.
                    </div>

                @endforelse

            </div>

        </div>


        {{-- ================= KERANJANG ================= --}}
        <div class="bg-white rounded-2xl shadow p-6 h-fit">

            {{-- HEADER --}}
            <div class="flex items-center justify-between mb-5">

                <div>

                    <h2 class="text-xl font-bold text-gray-800">
                        Keranjang
                    </h2>

                    <p class="text-sm text-gray-400">
                        Produk yang dipilih
                    </p>

                </div>


                <span
                    class="rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-600"
                    x-text="totalItems + ' Item'"
                ></span>

            </div>


            {{-- KERANJANG KOSONG --}}
            <div
                x-show="cart.length === 0"
                class="py-12 text-center border-2 border-dashed border-gray-200 rounded-2xl"
            >

                <div class="text-5xl mb-3">
                    🛒
                </div>

                <p class="font-semibold text-gray-600">
                    Keranjang masih kosong
                </p>

                <p class="text-sm text-gray-400 mt-1">
                    Pilih produk di sebelah kiri.
                </p>

            </div>


            {{-- ISI KERANJANG --}}
            <div
                x-show="cart.length > 0"
                class="space-y-4"
            >

                <template x-for="item in cart" :key="item.id">

                    <div class="border border-gray-200 rounded-xl p-4">

                        {{-- NAMA --}}
                        <div class="flex justify-between gap-3">

                            <div class="min-w-0">

                                <h3
                                    class="font-bold text-gray-800 truncate"
                                    x-text="item.name"
                                ></h3>

                                <p
                                    class="text-xs text-gray-400 mt-1"
                                    x-text="item.sku"
                                ></p>

                                <p class="text-blue-600 font-semibold mt-2">
                                    Rp
                                    <span
                                        x-text="formatRupiah(item.price)"
                                    ></span>
                                </p>

                            </div>


                            {{-- HAPUS --}}
                            <button
                                type="button"
                                @click="removeItem(item)"
                                class="text-red-500 hover:text-red-700 font-bold"
                            >
                                ✕
                            </button>

                        </div>


                        {{-- QTY + SUBTOTAL --}}
                        <div class="flex items-center justify-between mt-4">

                            <div class="flex items-center border border-gray-200 rounded-lg">

                                <button
                                    type="button"
                                    @click="decrease(item)"
                                    class="px-3 py-1.5 text-gray-600 hover:bg-gray-100"
                                >
                                    −
                                </button>

                                <span
                                    class="px-4 font-semibold"
                                    x-text="item.quantity"
                                ></span>

                                <button
                                    type="button"
                                    @click="increase(item)"
                                    class="px-3 py-1.5 text-gray-600 hover:bg-gray-100"
                                >
                                    +
                                </button>

                            </div>


                            {{-- SUBTOTAL --}}
                            <div class="text-right">

                                <p class="text-xs text-gray-400">
                                    Subtotal
                                </p>

                                <p class="font-bold text-gray-800">
                                    Rp
                                    <span
                                        x-text="formatRupiah(item.price * item.quantity)"
                                    ></span>
                                </p>

                            </div>

                        </div>

                    </div>

                </template>

            </div>


            {{-- ================= PEMBAYARAN ================= --}}
            <div
                x-show="cart.length > 0"
                class="border-t border-gray-200 mt-6 pt-5"
            >

                {{-- TOTAL --}}
                <div class="flex justify-between text-gray-500">

                    <span>
                        Total
                    </span>

                    <span class="font-bold text-gray-800">
                        Rp
                        <span x-text="formatRupiah(total)"></span>
                    </span>

                </div>


                {{-- JUMLAH BAYAR --}}
                <div class="mt-5">

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Jumlah Bayar
                    </label>

                    <input
                        type="number"
                        min="0"
                        step="1000"
                        x-model.number="paidAmount"
                        placeholder="Contoh: 100000"

                        class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                </div>


                {{-- PERINGATAN UANG KURANG --}}
                <div
                    x-show="paidAmount > 0 && paidAmount < total"
                    class="mt-3 rounded-xl bg-red-50 border border-red-200 px-4 py-3"
                >

                    <p class="text-sm font-semibold text-red-600">
                        ⚠️ Uang pembayaran kurang.
                    </p>

                    <p class="text-sm text-red-500 mt-1">
                        Kekurangan:
                        Rp
                        <span
                            x-text="formatRupiah(total - paidAmount)"
                        ></span>
                    </p>

                </div>


                {{-- KEMBALIAN --}}
                <div class="flex justify-between mt-4">

                    <span class="text-gray-500">
                        Kembalian
                    </span>

                    <span
                        class="font-bold"
                        :class="isPaymentEnough
                            ? 'text-green-600'
                            : 'text-gray-400'"
                    >
                        Rp
                        <span x-text="formatRupiah(change)"></span>
                    </span>

                </div>


                {{-- FORM CHECKOUT --}}
                <form
                    method="POST"
                    action="{{ route('transactions.store') }}"
                    class="mt-5"
                >

                    @csrf


                    {{-- DATA KERANJANG --}}
                    <template
                        x-for="(item, index) in cart"
                        :key="item.id"
                    >

                        <div>

                            <input
                                type="hidden"
                                :name="'items[' + index + '][product_id]'"
                                :value="item.id"
                            >

                            <input
                                type="hidden"
                                :name="'items[' + index + '][quantity]'"
                                :value="item.quantity"
                            >

                        </div>

                    </template>


                    {{-- JUMLAH BAYAR --}}
                    <input
                        type="hidden"
                        name="paid_amount"
                        :value="paidAmount"
                    >


                    {{-- CHECKOUT --}}
                    <button
                        type="submit"

                        :disabled="
                            cart.length === 0 ||
                            paidAmount < total ||
                            paidAmount <= 0
                        "

                        class="w-full rounded-xl px-5 py-3 font-bold transition"

                        :class="
                            cart.length === 0 ||
                            paidAmount < total ||
                            paidAmount <= 0
                                ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                : 'bg-blue-600 text-white hover:bg-blue-700'
                        "
                    >
                        Checkout
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

</x-app-layout>