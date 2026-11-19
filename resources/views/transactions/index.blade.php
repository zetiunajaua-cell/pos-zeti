<x-app-layout>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6"
     x-data="{
        cart: [],
        paidAmount: 0,
        selectedCategory: '{{ request('category_id') ?? '' }}',
        searchQuery: '',
        orderType: 'dine_in',
        orderNote: '',
        customerName: '',
        selectedPaymentMethodId: {{ $paymentMethods->first()?->id ?? 'null' }},
        selectedCustomerId: null,
        selectedPromotionId: null,
        toastMessage: '',
        toastType: 'info',
        toastVisible: false,
        isSubmitting: false,
        showVariantModal: false,
        variantModalProduct: null,
        showCustomerModal: false,
        customerSearch: '',
        showPromotionModal: false,
        promotionSearch: '',
        showOpenShiftModal: false,
        showCloseShiftModal: false,
        shiftStartingCash: 200000,
        shiftActualCash: {{ $activeShiftSummary['expected_cash'] ?? 0 }},

        get filteredCustomers() {
            const q = (this.customerSearch || '').toLowerCase().trim();
            if (!q) return this.customers;
            return this.customers.filter(c => 
                (c.name && c.name.toLowerCase().includes(q)) || 
                (c.member_code && c.member_code.toLowerCase().includes(q)) ||
                (c.phone && c.phone.toLowerCase().includes(q)) ||
                (c.membership_tier && c.membership_tier.toLowerCase().includes(q))
            );
        },

        get filteredPromotions() {
            const q = (this.promotionSearch || '').toLowerCase().trim();
            if (!q) return this.promotions;
            return this.promotions.filter(p => 
                (p.code && p.code.toLowerCase().includes(q)) || 
                (p.name && p.name.toLowerCase().includes(q))
            );
        },

        selectCustomer(customer) {
            this.selectedCustomerId = customer ? customer.id : null;
            if (customer && customer.name) {
                this.customerName = customer.name;
            }
            this.showCustomerModal = false;
            this.customerSearch = '';
        },

        selectPromotion(promotion) {
            this.selectedPromotionId = promotion ? promotion.id : null;
            this.showPromotionModal = false;
            this.promotionSearch = '';
        },

        showToast(message, type = 'error') {
            if (window.showToast) {
                const swalIcon = type === 'error' ? 'error' : (type === 'success' ? 'success' : (type === 'warning' ? 'warning' : 'info'));
                window.showToast(message, swalIcon);
            }
            this.toastMessage = message;
            this.toastType = type;
            this.toastVisible = true;
            setTimeout(() => {
                this.toastVisible = false;
            }, 3000);
        },

        matches(product) {
            const matchCategory = !this.selectedCategory || 
                                  this.selectedCategory === '' || 
                                  this.selectedCategory === 'null' || 
                                  String(product.category_id) === String(this.selectedCategory);
            const q = this.searchQuery.toLowerCase().trim();
            const matchQuery = !q ||
                product.name.toLowerCase().includes(q) ||
                product.sku.toLowerCase().includes(q);
            return Boolean(matchCategory && matchQuery);
        },

        handleScannerSubmit() {
            const query = this.searchQuery.trim().toLowerCase();
            if (!query) return;

            const found = this.allProducts.find(p => p.sku.toLowerCase() === query || p.name.toLowerCase() === query);
            if (found) {
                this.selectProduct(found);
                this.searchQuery = '';
            }
        },

        selectProduct(product) {
            if (product.stock <= 0) {
                this.showToast(`Stok ${product.name} telah habis!`, 'error');
                return;
            }

            if (product.variants && product.variants.length > 0) {
                this.variantModalProduct = product;
                this.showVariantModal = true;
            } else {
                this.addToCart(product, null);
            }
        },

        detectItemType(product) {
            const cat = (product.category_name || '').toLowerCase();
            const name = (product.name || '').toLowerCase();
            
            const drinkKeywords = ['minuman', 'drink', 'beverage', 'kopi', 'coffee', 'tea', 'teh', 'jus', 'juice', 'boba', 'latte', 'espresso', 'cappuccino', 'americano', 'macchiato', 'mocha', 'matcha', 'taro', 'soda', 'ice blend', 'shake', 'milk', 'susu', 'smoothie', 'frappe'];
            if (drinkKeywords.some(k => cat.includes(k) || name.includes(k))) {
                return 'drink';
            }

            const foodKeywords = ['makanan', 'food', 'snack', 'seblak', 'mie', 'nasi', 'pastry', 'roti', 'toast', 'bakso', 'ayam', 'goreng', 'cemilan', 'burger', 'croissant', 'dessert', 'cake', 'kue', 'dimsum'];
            if (foodKeywords.some(k => cat.includes(k) || name.includes(k))) {
                return 'food';
            }

            return 'general';
        },

        addToCart(product, variant = null) {
            const key = product.id + '_' + (variant ? variant.id : 'base');
            let item = this.cart.find(i => i.key === key);

            if (item) {
                if (item.quantity < product.stock) {
                    item.quantity++;
                } else {
                    this.showToast(`Stok ${product.name} tersisa ${product.stock} ${product.unit}.`, 'error');
                }
            } else {
                const addPrice = variant ? Number(variant.additional_price || 0) : 0;
                const unitPrice = Number(product.sell_price) + addPrice;
                const displayName = variant ? `${product.name} (${variant.name})` : product.name;
                const itemType = this.detectItemType(product);

                this.cart.push({
                    key: key,
                    id: product.id,
                    product_id: product.id,
                    product_variant_id: variant ? variant.id : null,
                    variant_name: variant ? variant.name : null,
                    name: displayName,
                    sku: product.sku,
                    price: unitPrice,
                    stock: Number(product.stock),
                    unit: product.unit,
                    quantity: 1,
                    note: '',
                    showNoteInput: false,
                    category_name: product.category_name || '',
                    itemType: itemType
                });
            }

            this.showVariantModal = false;
            this.variantModalProduct = null;
        },

        updateItemQty(item, val) {
            let cleanVal = String(val).replace(/[^0-9]/g, '');
            if (cleanVal === '' || cleanVal === '0') {
                cleanVal = '1';
            }
            let qty = parseInt(cleanVal, 10);
            if (qty > item.stock) {
                qty = item.stock;
                this.showToast(`Stok tidak mencukupi! Maksimal untuk ${item.name} hanya ${item.stock} ${item.unit}.`, 'error');
            }
            item.quantity = qty;
        },

        incrementItemQty(item) {
            if (item.quantity < item.stock) {
                item.quantity++;
            } else {
                this.showToast(`Stok maksimal untuk ${item.name} hanya ${item.stock} ${item.unit}.`, 'warning');
            }
        },

        decrementItemQty(item) {
            if (item.quantity > 1) {
                item.quantity--;
            } else {
                this.removeItem(item);
            }
        },

        removeItem(item) {
            this.cart = this.cart.filter(i => i.key !== item.key);
        },

        clearCart() {
            if (this.cart.length > 0) {
                if (window.confirmDialog) {
                    window.confirmDialog({
                        title: 'Kosongkan Keranjang?',
                        text: 'Semua item yang telah dipilih akan dihapus dari transaksi saat ini.',
                        icon: 'warning',
                        confirmButtonText: 'Ya, Kosongkan',
                        cancelButtonText: 'Batal',
                        isDanger: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.cart = [];
                            this.paidAmount = 0;
                            this.showToast('Keranjang berhasil dikosongkan.', 'info');
                        }
                    });
                } else if (confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang?')) {
                    this.cart = [];
                    this.paidAmount = 0;
                    this.showToast('Keranjang berhasil dikosongkan.', 'info');
                }
            }
        },

        setExactAmount() {
            this.paidAmount = this.total;
        },

        setCash(amount) {
            this.paidAmount = amount;
        },

        addCash(amount) {
            this.paidAmount = (Number(this.paidAmount) || 0) + amount;
        },

        appendItemNote(item, text) {
            this.toggleItemNote(item, text);
        },

        toggleItemNote(item, text) {
            if (!item.note || item.note.trim() === '') {
                item.note = text;
                return;
            }
            let parts = item.note.split(',').map(s => s.trim()).filter(Boolean);
            if (parts.includes(text)) {
                parts = parts.filter(p => p !== text);
            } else {
                parts.push(text);
            }
            item.note = parts.join(', ');
        },

        get currentPaymentMethod() {
            return this.paymentMethods.find(pm => pm.id === this.selectedPaymentMethodId) || this.paymentMethods[0] || {};
        },

        selectPaymentMethod(pm) {
            this.selectedPaymentMethodId = pm.id;
            // Jika non-tunai (QRIS / Transfer / E-Wallet), otomatis set nominal bayar pas
            if (pm.type !== 'cash') {
                this.setExactAmount();
            }
        },

        get totalItems() {
            return this.cart.reduce((total, item) => total + item.quantity, 0);
        },

        get currentCustomer() {
            if (!this.selectedCustomerId) return null;
            return this.customers.find(c => c.id == this.selectedCustomerId) || null;
        },

        get currentPromotion() {
            if (!this.selectedPromotionId) return null;
            return this.promotions.find(p => p.id == this.selectedPromotionId) || null;
        },

        get subtotal() {
            return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        },

        get memberDiscount() {
            if (this.currentCustomer && this.currentCustomer.discount_percent > 0) {
                return Math.round(this.subtotal * (Number(this.currentCustomer.discount_percent) / 100));
            }
            return 0;
        },

        get promoDiscount() {
            if (this.currentPromotion) {
                const promo = this.currentPromotion;
                if (this.subtotal < Number(promo.min_purchase || 0)) {
                    return 0;
                }
                if (promo.type === 'percentage') {
                    let disc = this.subtotal * (Number(promo.value) / 100);
                    if (promo.max_discount && Number(promo.max_discount) > 0) {
                        disc = Math.min(disc, Number(promo.max_discount));
                    }
                    return Math.round(disc);
                } else {
                    return Math.min(Number(promo.value), this.subtotal);
                }
            }
            return 0;
        },

        get totalDiscount() {
            return Math.min(this.subtotal, this.memberDiscount + this.promoDiscount);
        },

        get taxableSubtotal() {
            return Math.max(0, this.subtotal - this.totalDiscount);
        },

        get taxAmount() {
            if (!this.taxSettings || !this.taxSettings.tax_enabled) return 0;
            if (this.taxSettings.tax_type === 'percentage') {
                return Math.round(this.taxableSubtotal * (Number(this.taxSettings.tax_rate) / 100));
            }
            return Math.round(Number(this.taxSettings.tax_rate));
        },

        get serviceChargeAmount() {
            if (!this.taxSettings || !this.taxSettings.service_charge_enabled) return 0;
            if (this.taxSettings.service_charge_type === 'percentage') {
                return Math.round(this.taxableSubtotal * (Number(this.taxSettings.service_charge_rate) / 100));
            }
            return Math.round(Number(this.taxSettings.service_charge_rate));
        },

        get total() {
            return Math.max(0, this.taxableSubtotal + this.taxAmount + this.serviceChargeAmount);
        },

        get pointsEarned() {
            if (this.currentCustomer) {
                return Math.floor(this.total / 10000);
            }
            return 0;
        },

        get change() {
            if (this.paidAmount >= this.total) {
                return this.paidAmount - this.total;
            }
            return 0;
        },

        get isPaymentEnough() {
            return this.paidAmount >= this.total && this.total > 0;
        },

        formatRupiah(value) {
            return new Intl.NumberFormat('id-ID').format(value);
        },

        allProducts: {{ Js::from($products->map(fn($p) => [
            'id' => $p->id,
            'category_id' => $p->category_id,
            'sku' => $p->sku,
            'name' => $p->name,
            'image_url' => $p->image_url,
            'sell_price' => (float) $p->sell_price,
            'stock' => (int) $p->stock,
            'unit' => $p->unit,
            'category_name' => $p->category?->name ?? 'Uncategorized',
            'variants' => $p->variants->map(fn($v) => [
                'id' => $v->id,
                'name' => $v->name,
                'additional_price' => (float) $v->additional_price,
            ])
        ])) }},

        promotions: {{ Js::from($promotions->map(fn($pr) => [
            'id' => $pr->id,
            'name' => $pr->name,
            'code' => $pr->code,
            'type' => $pr->type,
            'value' => (float) $pr->value,
            'min_purchase' => (float) $pr->min_purchase,
            'max_discount' => $pr->max_discount ? (float) $pr->max_discount : null,
            'is_active' => (bool) $pr->is_active,
        ])) }},

        customers: {{ Js::from($customers->map(fn($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'member_code' => $c->member_code,
            'phone' => $c->phone ?? '-',
            'membership_tier' => $c->membership_tier,
            'discount_percent' => (float) $c->discount_percent,
            'points' => (int) $c->points,
        ])) }},

        taxSettings: {{ Js::from($taxSettings ?? [
            'tax_enabled' => false,
            'tax_name' => 'PPN 10%',
            'tax_type' => 'percentage',
            'tax_rate' => 10,
            'service_charge_enabled' => false,
            'service_charge_name' => 'Biaya Layanan',
            'service_charge_type' => 'percentage',
            'service_charge_rate' => 5,
        ]) }},

        paymentMethods: {{ Js::from($paymentMethods->map(fn($pm) => [
            'id' => $pm->id,
            'name' => $pm->name,
            'code' => $pm->code,
            'type' => $pm->type,
            'account_number' => $pm->account_number ?? null,
            'account_name' => $pm->account_name ?? null,
            'instructions' => $pm->instructions ?? null,
        ])) }}
     }"
>

    <!-- Floating Toast Notification -->
    <div x-show="toastVisible"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl border text-sm font-bold backdrop-blur-md"
         :class="{
            'bg-emerald-600/95 text-white border-emerald-500 shadow-emerald-500/20': toastType === 'success',
            'bg-rose-600/95 text-white border-rose-500 shadow-rose-500/20': toastType === 'error',
            'bg-slate-800/95 text-white border-slate-700 shadow-slate-900/20': toastType === 'info'
         }"
         style="display: none;">
        <span x-text="toastType === 'success' ? '✓' : (toastType === 'error' ? '✕' : 'ℹ')"></span>
        <span x-text="toastMessage"></span>
    </div>

    {{-- Alert Notifikasi Server --}}
    @if(session('success'))
        <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50/90 px-5 py-3.5 text-emerald-800 text-sm font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50/90 px-5 py-3.5 text-rose-800 text-sm font-semibold flex items-center gap-2.5">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Top Action Bar Kasir & Tombol Struk Terakhir (Task 6 & Section H) --}}
    <div class="mb-6 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700/60 p-5 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-600 text-white font-black flex items-center justify-center shadow-md shadow-blue-500/25 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="16" rx="3" stroke-width="2"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h5m-5 4h8"/>
                    <circle cx="16.5" cy="12.5" r="1.5" fill="currentColor"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300">
                        Kasir Terminal
                    </span>
                    <h1 class="text-base font-black text-slate-900 dark:text-white leading-tight">Transaksi Kasir</h1>
                </div>
                <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5">Pilih produk, tentukan varian, tipe pesanan, dan metode pembayaran.</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- Status & Kontrol Shift Kasir (Audit Laci Kas) --}}
            @if($activeShift)
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-xs font-bold text-emerald-800 dark:text-emerald-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Shift Buka {{ $activeShift->opened_at->format('H:i') }} &bull; Modal Rp {{ number_format($activeShift->starting_cash, 0, ',', '.') }}</span>
                </div>
                <button type="button"
                        @click="showCloseShiftModal = true"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition shadow-xs">
                    <span>🔒</span>
                    <span>Tutup Shift Kasir</span>
                </button>
            @else
                <button type="button"
                        @click="showOpenShiftModal = true"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white text-xs font-black transition shadow-sm">
                    <span>🔓</span>
                    <span>Buka Shift Kasir</span>
                </button>
            @endif

            @if($latestTransaction)
                <a href="{{ route('transactions.show', $latestTransaction->id) }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-blue-200 dark:border-blue-800 bg-blue-50/80 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/60 text-xs font-bold transition shadow-xs">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Struk Terakhir ({{ $latestTransaction->invoice_number }})</span>
                </a>
            @endif

            <a href="{{ route('reports.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Laporan</span>
            </a>
        </div>
    </div>

    <!-- MAIN TWO COLUMN LAYOUT -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- ================= KOLOM KIRI: KATALOG PRODUK & SEARCH (7/12) ================= --}}
        <div class="lg:col-span-7 xl:col-span-8 space-y-4">

            <!-- Search & Filter Bar -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 shadow-sm space-y-3">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text"
                           x-model="searchQuery"
                           @keydown.enter.prevent="handleScannerSubmit()"
                           placeholder="Cari nama produk atau scan barcode / SKU lalu tekan Enter..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/40 transition">
                </div>

                <!-- Dropdown Filter Kategori (Task 7) -->
                <div class="flex items-center gap-3 pt-1 border-t border-slate-100 dark:border-slate-800">
                    <label for="pos_category_dropdown" class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider shrink-0">
                        Kategori:
                    </label>
                    <select id="pos_category_dropdown"
                            x-model="selectedCategory"
                            class="text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 py-1.5 pl-3 pr-8 text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-slate-800 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-600 focus:ring-blue-500">
                        <option value="">Semua Kategori ({{ $products->count() }})</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <span class="text-[11px] text-slate-400 dark:text-slate-400 ml-auto hidden sm:inline">
                        Menampilkan <strong class="text-slate-700 dark:text-slate-200" x-text="allProducts.filter(p => matches(p)).length"></strong> item
                    </span>
                </div>
            </div>

            <!-- Product Cards Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                <template x-for="product in allProducts" :key="product.id">
                    <div x-show="matches(product)"
                         x-transition
                         class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 shadow-sm hover:shadow-md hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-150 flex flex-col justify-between group">
                        
                        <div>
                            <!-- Foto Produk (Task 23) -->
                            <div class="w-full h-32 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 mb-2.5 relative flex items-center justify-center border border-slate-100 dark:border-slate-700">
                                <template x-if="product.image_url">
                                    <img :src="product.image_url" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                </template>
                                <template x-if="!product.image_url">
                                    <div class="flex flex-col items-center justify-center text-slate-300 dark:text-slate-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </template>
                                <span class="absolute top-2 right-2 text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow-xs"
                                      :class="{
                                          'bg-emerald-50 dark:bg-emerald-950/70 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800': product.stock > 10,
                                          'bg-amber-50 dark:bg-amber-950/70 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800': product.stock <= 10 && product.stock > 0,
                                          'bg-rose-50 dark:bg-rose-950/70 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800': product.stock <= 0
                                      }"
                                      x-text="'Stok: ' + product.stock">
                                </span>
                            </div>

                            <!-- Header Card: Category -->
                            <div class="flex items-center justify-between gap-1 mb-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 truncate" x-text="product.category_name"></span>
                            </div>

                            <!-- Product Name & SKU & Variant Badge (Task 21) -->
                            <h3 class="font-bold text-slate-800 dark:text-slate-100 text-xs leading-snug line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"
                                x-text="product.name"></h3>
                            <div class="flex items-center gap-1.5 mt-1">
                                <p class="text-[10px] font-mono font-medium text-slate-400 dark:text-slate-400" x-text="product.sku"></p>
                                <template x-if="product.variants && product.variants.length > 0">
                                    <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9px] font-black bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800"
                                          x-text="product.variants.length + ' Varian'"></span>
                                </template>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-400 uppercase tracking-wider block">Harga</span>
                                <span class="text-sm font-black text-slate-900 dark:text-white" x-text="'Rp ' + formatRupiah(product.sell_price)"></span>
                            </div>

                            <button type="button"
                                    @click="selectProduct(product)"
                                    :disabled="product.stock <= 0"
                                    class="h-8 px-2.5 rounded-xl flex items-center justify-center font-bold text-xs transition-all shadow-sm gap-1"
                                    :class="product.stock > 0 ? 'bg-blue-600 text-white hover:bg-blue-700 active:scale-95 shadow-blue-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-300 dark:text-slate-600 cursor-not-allowed'">
                                <template x-if="product.variants && product.variants.length > 0">
                                    <span>Pilih Varian</span>
                                </template>
                                <template x-if="!product.variants || product.variants.length === 0">
                                    <span>+ Tambah</span>
                                </template>
                            </button>
                        </div>

                    </div>
                </template>
            </div>

            <!-- Empty Search State -->
            <div x-show="allProducts.filter(p => matches(p)).length === 0"
                 class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-12 text-center shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="text-base font-bold text-slate-800 dark:text-white">Tidak ada produk ditemukan</h4>
                <p class="text-xs text-slate-400 dark:text-slate-400 mt-1">Coba gunakan kata kunci pencarian atau kategori lain.</p>
            </div>

        </div>

        {{-- ================= KOLOM KANAN: KERANJANG & CHECKOUT (5/12) ================= --}}
        <div class="lg:col-span-5 xl:col-span-4 sticky top-20">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-md p-5 flex flex-col space-y-4">

                <!-- Header Keranjang & Tombol Kosongkan -->
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900 dark:text-white leading-tight">Keranjang Kasir</h2>
                            <p class="text-[11px] font-medium text-slate-400" x-text="totalItems + ' total unit produk'"></p>
                        </div>
                    </div>

                    <button type="button"
                            @click="clearCart()"
                            x-show="cart.length > 0"
                            class="text-xs font-bold text-rose-600 dark:text-rose-400 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/50 px-2.5 py-1 rounded-xl transition">
                        Kosongkan
                    </button>
                </div>

                <!-- Banner Nomor Antrean Harian Otomatis (Kafe / F&B) -->
                <div class="p-3 rounded-2xl bg-amber-50/90 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/80 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-amber-500 text-white font-black flex items-center justify-center text-sm shadow-2xs">
                            #
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-amber-700 dark:text-amber-400 block leading-tight">Nomor Antrean Berikutnya</span>
                            <span class="text-sm font-black text-amber-950 dark:text-amber-200 font-mono">{{ $nextQueueNumber }}</span>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold text-amber-700 dark:text-amber-300 bg-amber-100 dark:bg-amber-900/60 px-2.5 py-1 rounded-lg">
                        ✓ Otomatis dari Sistem
                    </span>
                </div>

                <!-- SELECTOR TIPE PESANAN: DINE IN VS TAKE AWAY (Task 19) -->
                <div>
                    <span class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Tipe Pesanan Pelanggan</span>
                    <div class="grid grid-cols-2 gap-2 bg-slate-100/80 dark:bg-slate-800/80 p-1 rounded-2xl border border-slate-200/60 dark:border-slate-700">
                        <button type="button"
                                @click="orderType = 'dine_in'"
                                class="py-2 px-3 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition"
                                :class="orderType === 'dine_in' ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-xs' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200'">
                            <span>🍽️</span>
                            <span>Dine In (Makan di Tempat)</span>
                        </button>
                        <button type="button"
                                @click="orderType = 'take_away'"
                                class="py-2 px-3 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition"
                                :class="orderType === 'take_away' ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-xs' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200'">
                            <span>🛍️</span>
                            <span>Take Away (Bawa Pulang)</span>
                        </button>
                    </div>
                </div>

                <!-- List Item Keranjang -->
                <div class="divide-y divide-slate-100 dark:divide-slate-800 max-h-[440px] overflow-y-auto pr-1">
                    <template x-for="item in cart" :key="item.key">
                        <div class="py-3.5 space-y-2">
                            <!-- Baris 1: Nama Produk & Total Subtotal Harga -->
                            <div class="flex items-start justify-between gap-2">
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-snug break-words flex-1" x-text="item.name"></h4>
                                <span class="text-xs font-black text-slate-900 dark:text-white shrink-0 text-right"
                                      x-text="'Rp ' + formatRupiah(item.price * item.quantity)"></span>
                            </div>

                            <!-- Baris 2: Harga Satuan & Stepper Jumlah (+ / -) & Hapus -->
                            <div class="flex items-center justify-between gap-2">
                                <div class="text-[11px] text-slate-400 flex items-center gap-1.5">
                                    <span x-text="'Rp ' + formatRupiah(item.price)"></span>
                                    <span>×</span>
                                    <span class="font-bold text-slate-700 dark:text-slate-300" x-text="item.quantity + ' ' + item.unit"></span>
                                </div>

                                <div class="flex items-center gap-1.5 shrink-0">
                                    {{-- Stepper Quantity dengan Tombol Kurang (-) dan Tambah (+) --}}
                                    <div class="inline-flex items-center rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100/80 dark:bg-slate-800/80 p-0.5 shadow-2xs">
                                        <!-- Tombol Kurang (-) di sebelah kiri -->
                                        <button type="button"
                                                @click="decrementItemQty(item)"
                                                title="Kurangi jumlah (-)"
                                                class="w-6 h-6 flex items-center justify-center rounded-lg bg-white dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 shadow-2xs transition active:scale-90">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                                            </svg>
                                        </button>

                                        <!-- Input Jumlah di Tengah -->
                                        <input type="text"
                                               inputmode="numeric"
                                               pattern="[0-9]*"
                                               :value="item.quantity"
                                               @input="updateItemQty(item, $event.target.value)"
                                               title="Ketik jumlah item secara langsung"
                                               class="w-8 sm:w-9 text-center py-0.5 px-0.5 bg-transparent border-0 text-xs font-black text-slate-800 dark:text-white focus:ring-0 focus:outline-none">

                                        <!-- Tombol Tambah (+) di sebelah kanan -->
                                        <button type="button"
                                                @click="incrementItemQty(item)"
                                                title="Tambah jumlah (+)"
                                                class="w-6 h-6 flex items-center justify-center rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-2xs transition active:scale-90">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <button type="button"
                                            @click="removeItem(item)"
                                            title="Hapus dari keranjang"
                                            class="text-slate-300 dark:text-slate-600 hover:text-rose-500 dark:hover:text-rose-400 transition p-1.5 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/40">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Baris 3: Tombol Kustom Racikan Minuman / Catatan Makanan / Catatan Item (Konteks Cerdas) -->
                            <div class="pt-0.5">
                                <!-- Tombol Racikan Khusus MINUMAN -->
                                <template x-if="(item.itemType || 'drink') === 'drink'">
                                    <button type="button"
                                            @click="item.showNoteInput = !item.showNoteInput"
                                            class="w-full flex items-center justify-between px-3 py-1.5 rounded-xl text-[11px] font-bold transition border shadow-2xs cursor-pointer"
                                            :class="item.note ? 'bg-amber-100 text-amber-900 border-amber-300 dark:bg-amber-950/80 dark:text-amber-200 dark:border-amber-700' : 'bg-amber-50/80 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-800/80 hover:bg-amber-100'">
                                        <div class="flex items-center gap-1.5 truncate">
                                            <span class="text-xs">☕</span>
                                            <span class="truncate" x-text="item.note ? ('Racikan: ' + item.note) : '+ Kustom Racikan Minuman'"></span>
                                        </div>
                                        <div class="flex items-center gap-1 text-[10px] shrink-0 font-black text-amber-700 dark:text-amber-300">
                                            <span x-text="item.showNoteInput ? 'Tutup ▲' : 'Buka Racikan ▼'"></span>
                                        </div>
                                    </button>
                                </template>

                                <!-- Tombol Catatan Khusus MAKANAN -->
                                <template x-if="item.itemType === 'food'">
                                    <button type="button"
                                            @click="item.showNoteInput = !item.showNoteInput"
                                            class="w-full flex items-center justify-between px-3 py-1.5 rounded-xl text-[11px] font-bold transition border shadow-2xs cursor-pointer"
                                            :class="item.note ? 'bg-orange-100 text-orange-900 border-orange-300 dark:bg-orange-950/80 dark:text-orange-200 dark:border-orange-700' : 'bg-orange-50/80 dark:bg-orange-950/40 text-orange-800 dark:text-orange-300 border-orange-200 dark:border-orange-800/80 hover:bg-orange-100'">
                                        <div class="flex items-center gap-1.5 truncate">
                                            <span class="text-xs">🍽️</span>
                                            <span class="truncate" x-text="item.note ? ('Catatan: ' + item.note) : '+ Catatan Khusus Makanan'"></span>
                                        </div>
                                        <div class="flex items-center gap-1 text-[10px] shrink-0 font-black text-orange-700 dark:text-orange-300">
                                            <span x-text="item.showNoteInput ? 'Tutup ▲' : 'Buka Catatan ▼'"></span>
                                        </div>
                                    </button>
                                </template>

                                <!-- Tombol Catatan Khusus BARANG UMUM -->
                                <template x-if="item.itemType === 'general'">
                                    <button type="button"
                                            @click="item.showNoteInput = !item.showNoteInput"
                                            class="w-full flex items-center justify-between px-3 py-1.5 rounded-xl text-[11px] font-bold transition border shadow-2xs cursor-pointer"
                                            :class="item.note ? 'bg-blue-100 text-blue-900 border-blue-300 dark:bg-blue-950/80 dark:text-blue-200 dark:border-blue-700' : 'bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:bg-slate-100'">
                                        <div class="flex items-center gap-1.5 truncate">
                                            <span class="text-xs">📝</span>
                                            <span class="truncate" x-text="item.note ? ('Catatan: ' + item.note) : '+ Tambah Catatan Item'"></span>
                                        </div>
                                        <div class="flex items-center gap-1 text-[10px] shrink-0 font-black text-slate-600 dark:text-slate-300">
                                            <span x-text="item.showNoteInput ? 'Tutup ▲' : 'Buka Catatan ▼'"></span>
                                        </div>
                                    </button>
                                </template>
                            </div>

                            <!-- Baris 4: Panel Pilihan Racikan Cepat & Catatan Sesuai Jenis Item -->
                            <div x-show="item.showNoteInput" x-transition class="mt-2 p-3 rounded-2xl border space-y-2.5"
                                 :class="{
                                     'bg-amber-50/90 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800/80': (item.itemType || 'drink') === 'drink',
                                     'bg-orange-50/90 dark:bg-orange-950/40 border-orange-200 dark:border-orange-800/80': item.itemType === 'food',
                                     'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700': item.itemType === 'general'
                                 }">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black uppercase tracking-wider"
                                          :class="{
                                              'text-amber-800 dark:text-amber-300': (item.itemType || 'drink') === 'drink',
                                              'text-orange-800 dark:text-orange-300': item.itemType === 'food',
                                              'text-slate-700 dark:text-slate-300': item.itemType === 'general'
                                          }">
                                        <span x-show="(item.itemType || 'drink') === 'drink'">☕ PILIH RACIKAN CEPAT (MINUMAN):</span>
                                        <span x-show="item.itemType === 'food'">🍽️ PILIH CATATAN KHUSUS (MAKANAN):</span>
                                        <span x-show="item.itemType === 'general'">📝 PILIH CATATAN KHUSUS:</span>
                                    </span>
                                    <button type="button"
                                            @click="item.note = ''; item.showNoteInput = false"
                                            class="text-[10px] font-bold text-rose-500 hover:text-rose-700">
                                        ✕ Bersihkan Catatan
                                    </button>
                                </div>

                                {{-- Barisan Tombol Cepat Custom Racikan Minuman Kafe --}}
                                <div x-show="(item.itemType || 'drink') === 'drink'" class="flex flex-wrap items-center gap-1.5">
                                    <button type="button"
                                            @click="toggleItemNote(item, 'Less Ice')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('Less Ice')) ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-slate-800 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-900 hover:bg-blue-50'">
                                        ❄️ Less Ice
                                    </button>
                                    <button type="button"
                                            @click="toggleItemNote(item, 'No Ice')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('No Ice')) ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-slate-800 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-900 hover:bg-blue-50'">
                                        🧊 No Ice
                                    </button>
                                    <button type="button"
                                            @click="toggleItemNote(item, 'Panas / Hot')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('Panas / Hot')) ? 'bg-amber-600 text-white border-amber-600' : 'bg-white dark:bg-slate-800 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-900 hover:bg-amber-50'">
                                        🔥 Hot (Panas)
                                    </button>
                                    <button type="button"
                                            @click="toggleItemNote(item, 'Less Sugar')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('Less Sugar')) ? 'bg-purple-600 text-white border-purple-600' : 'bg-white dark:bg-slate-800 text-purple-700 dark:text-purple-300 border-purple-200 dark:border-purple-900 hover:bg-purple-50'">
                                        🍬 Less Sugar
                                    </button>
                                    <button type="button"
                                            @click="toggleItemNote(item, 'No Sugar')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('No Sugar')) ? 'bg-rose-600 text-white border-rose-600' : 'bg-white dark:bg-slate-800 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-900 hover:bg-rose-50'">
                                        🚫 No Sugar
                                    </button>
                                    <button type="button"
                                            @click="toggleItemNote(item, 'Oatmilk')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('Oatmilk')) ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white dark:bg-slate-800 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-900 hover:bg-emerald-50'">
                                        🥛 Oatmilk
                                    </button>
                                    <button type="button"
                                            @click="toggleItemNote(item, 'Extra Shot')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('Extra Shot')) ? 'bg-amber-700 text-white border-amber-700' : 'bg-white dark:bg-slate-800 text-amber-800 dark:text-amber-200 border-amber-300 dark:border-amber-800 hover:bg-amber-50'">
                                        ☕ Extra Shot
                                    </button>
                                </div>

                                {{-- Barisan Tombol Cepat Catatan Makanan --}}
                                <div x-show="item.itemType === 'food'" class="flex flex-wrap items-center gap-1.5">
                                    <button type="button"
                                            @click="toggleItemNote(item, 'Pedas')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('Pedas') && !item.note.includes('Tidak Pedas') && !item.note.includes('Sangat Pedas')) ? 'bg-orange-600 text-white border-orange-600' : 'bg-white dark:bg-slate-800 text-orange-700 dark:text-orange-300 border-orange-200 dark:border-orange-900 hover:bg-orange-50'">
                                        🌶️ Pedas
                                    </button>
                                    <button type="button"
                                            @click="toggleItemNote(item, 'Sangat Pedas')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('Sangat Pedas')) ? 'bg-rose-600 text-white border-rose-600' : 'bg-white dark:bg-slate-800 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-900 hover:bg-rose-50'">
                                        🔥 Sangat Pedas
                                    </button>
                                    <button type="button"
                                            @click="toggleItemNote(item, 'Tidak Pedas')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('Tidak Pedas')) ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white dark:bg-slate-800 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-900 hover:bg-emerald-50'">
                                        🥦 Tidak Pedas
                                    </button>
                                    <button type="button"
                                            @click="toggleItemNote(item, 'Pisah Sambal')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('Pisah Sambal')) ? 'bg-amber-600 text-white border-amber-600' : 'bg-white dark:bg-slate-800 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-900 hover:bg-amber-50'">
                                        🥣 Pisah Sambal
                                    </button>
                                    <button type="button"
                                            @click="toggleItemNote(item, 'Hangatkan / Panaskan')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('Hangatkan / Panaskan')) ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-slate-800 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-900 hover:bg-blue-50'">
                                        ♨️ Hangatkan
                                    </button>
                                    <button type="button"
                                            @click="toggleItemNote(item, 'Pisah Kuah')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('Pisah Kuah')) ? 'bg-teal-600 text-white border-teal-600' : 'bg-white dark:bg-slate-800 text-teal-700 dark:text-teal-300 border-teal-200 dark:border-teal-900 hover:bg-teal-50'">
                                        🥡 Pisah Kuah
                                    </button>
                                    <button type="button"
                                            @click="toggleItemNote(item, 'Tanpa Bawang')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('Tanpa Bawang')) ? 'bg-purple-600 text-white border-purple-600' : 'bg-white dark:bg-slate-800 text-purple-700 dark:text-purple-300 border-purple-200 dark:border-purple-900 hover:bg-purple-50'">
                                        🚫 Tanpa Bawang
                                    </button>
                                </div>

                                {{-- Barisan Tombol Cepat Catatan Barang Umum --}}
                                <div x-show="item.itemType === 'general'" class="flex flex-wrap items-center gap-1.5">
                                    <button type="button"
                                            @click="toggleItemNote(item, 'Bungkus Kado')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('Bungkus Kado')) ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white dark:bg-slate-800 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-900 hover:bg-indigo-50'">
                                        🎁 Bungkus Kado
                                    </button>
                                    <button type="button"
                                            @click="toggleItemNote(item, 'Bubble Wrap')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('Bubble Wrap')) ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white dark:bg-slate-800 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-900 hover:bg-indigo-50'">
                                        📦 Bubble Wrap
                                    </button>
                                    <button type="button"
                                            @click="toggleItemNote(item, 'Kantong Terpisah')"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg border transition shadow-2xs"
                                            :class="(item.note && item.note.includes('Kantong Terpisah')) ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white dark:bg-slate-800 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-900 hover:bg-indigo-50'">
                                        🛍️ Kantong Terpisah
                                    </button>
                                </div>

                                <input type="text"
                                       x-model="item.note"
                                       :placeholder="(item.itemType || 'drink') === 'drink' ? 'Atau ketik racikan minuman (cth: Less sweet, sirup vanilla)...' : (item.itemType === 'food' ? 'Atau ketik catatan makanan (cth: jangan terlalu asin, ekstra piring)...' : 'Tuliskan catatan khusus untuk item ini...')"
                                       class="w-full text-[11px] font-medium py-1.5 px-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>
                    </template>

                    <div x-show="cart.length === 0" class="py-8 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/60 text-slate-300 dark:text-slate-600 mx-auto flex items-center justify-center mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Keranjang masih kosong</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">Pilih produk di sebelah kiri untuk checkout.</p>
                    </div>
                </div>

                <!-- Pilihan Meja / Catatan Transaksi (Opsional) -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <span x-text="orderType === 'dine_in' ? 'Pilihan No. Meja (Opsional)' : 'Catatan Pesanan (Opsional)'"></span>
                        </label>
                        <span class="text-[10px] text-slate-400">Antrean <strong class="text-amber-600 font-mono">{{ $nextQueueNumber }}</strong> otomatis</span>
                    </div>

                    <!-- Tombol Cepat Pilihan Meja (Khusus jika Makan di Tempat / Dine In) -->
                    <template x-if="orderType === 'dine_in'">
                        <div class="flex flex-wrap items-center gap-1 mb-1.5">
                            <button type="button" @click="orderNote = 'Meja 01'" class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700" :class="{'bg-blue-100 text-blue-800 border-blue-300': orderNote === 'Meja 01'}">Meja 01</button>
                            <button type="button" @click="orderNote = 'Meja 02'" class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700" :class="{'bg-blue-100 text-blue-800 border-blue-300': orderNote === 'Meja 02'}">Meja 02</button>
                            <button type="button" @click="orderNote = 'Meja 03'" class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700" :class="{'bg-blue-100 text-blue-800 border-blue-300': orderNote === 'Meja 03'}">Meja 03</button>
                            <button type="button" @click="orderNote = 'Meja 04'" class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700" :class="{'bg-blue-100 text-blue-800 border-blue-300': orderNote === 'Meja 04'}">Meja 04</button>
                            <button type="button" @click="orderNote = 'Area Bar'" class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700" :class="{'bg-blue-100 text-blue-800 border-blue-300': orderNote === 'Area Bar'}">Bar</button>
                            <button type="button" @click="orderNote = ''" x-show="orderNote" class="text-[10px] font-bold px-1.5 py-0.5 rounded-md text-rose-500 hover:bg-rose-50">✕ Reset</button>
                        </div>
                    </template>

                    <input type="text"
                           x-model="orderNote"
                           :placeholder="orderType === 'dine_in' ? 'Ketik nomor meja atau pilih tombol di atas (opsional)...' : 'Contoh: Bungkus terpisah (opsional, boleh dikosongkan)...'"
                           class="w-full text-xs font-medium py-1.5 px-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:bg-white dark:focus:bg-slate-800 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- INPUT NAMA PELANGGAN (CUSTOMER) -->
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="customer_name_input" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Nama Pelanggan (Customer)
                        </label>
                        <span class="text-[10px] text-slate-400">Untuk struk & panggilan barista</span>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input type="text"
                               id="customer_name_input"
                               x-model="customerName"
                               placeholder="Ketik nama pelanggan (contoh: Kak Sarah)..."
                               class="w-full pl-9 pr-8 py-2 rounded-xl text-xs font-bold border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:bg-white dark:focus:bg-slate-700 text-slate-800 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 transition">
                        <button type="button"
                                x-show="customerName"
                                @click="customerName = ''"
                                class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-rose-500 transition cursor-pointer"
                                title="Hapus nama">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <!-- PILIHAN PELANGGAN / MEMBERSHIP (Modal Selector Canggih) -->
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Pelanggan / Member (Loyalitas)
                        </label>
                        <template x-if="currentCustomer">
                            <span class="text-[10px] font-black text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 px-2 py-0.5 rounded-full"
                                  x-text="currentCustomer.membership_tier + ' (' + currentCustomer.discount_percent + '% Diskon)'"></span>
                        </template>
                    </div>

                    <!-- Trigger Card Buka Modal Pemilih Member -->
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-800/80 p-2.5 transition hover:border-blue-400 dark:hover:border-blue-500 cursor-pointer group"
                         @click="showCustomerModal = true; $nextTick(() => { if($refs.customerSearchInput) $refs.customerSearchInput.focus(); })">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 font-black text-xs"
                                     :class="currentCustomer ? 'bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300' : 'bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-300'">
                                    <span x-text="currentCustomer ? currentCustomer.name.substring(0,1).toUpperCase() : '👤'"></span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-slate-800 dark:text-white truncate"
                                         x-text="currentCustomer ? currentCustomer.name + ' (' + currentCustomer.member_code + ')' : 'Umum / Non-Member'"></div>
                                    <div class="text-[10px] text-slate-400 truncate"
                                         x-text="currentCustomer ? (currentCustomer.phone ? currentCustomer.phone + ' • ' : '') + formatRupiah(currentCustomer.points) + ' Poin' : 'Klik untuk mencari atau memilih member'"></div>
                                </div>
                            </div>

                            <div class="flex items-center gap-1 shrink-0">
                                <template x-if="currentCustomer">
                                    <button type="button"
                                            @click.stop="selectCustomer(null)"
                                            title="Lepas member (Kembali ke Non-Member)"
                                            class="p-1 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </template>
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-900 group-hover:bg-blue-100 transition">
                                    <span x-text="currentCustomer ? 'Ganti' : 'Pilih Member'"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <template x-if="currentCustomer">
                        <div class="mt-1.5 flex items-center justify-between text-[11px] bg-emerald-50/50 dark:bg-emerald-950/30 p-2 rounded-xl border border-emerald-200/60 dark:border-emerald-900/50">
                            <span class="text-slate-500 dark:text-slate-400">Poin Saat Ini: <strong class="text-slate-800 dark:text-slate-200" x-text="formatRupiah(currentCustomer.points) + ' Poin'"></strong></span>
                            <span class="text-emerald-700 dark:text-emerald-400 font-bold" x-text="'+' + pointsEarned + ' Poin dari pesanan ini'"></span>
                        </div>
                    </template>
                </div>

                <!-- KUPON PROMO / DISKON (Modal Selector Canggih) -->
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Kupon Promo / Diskon
                        </label>
                        <template x-if="currentPromotion">
                            <span class="text-[10px] font-black text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-800 px-2 py-0.5 rounded-full"
                                  x-text="currentPromotion.code + ' (' + (currentPromotion.type === 'percentage' ? currentPromotion.value + '%' : 'Rp ' + formatRupiah(currentPromotion.value)) + ')'"></span>
                        </template>
                    </div>

                    <!-- Trigger Card Buka Modal Pemilih Promo -->
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-800/80 p-2.5 transition hover:border-indigo-400 dark:hover:border-indigo-500 cursor-pointer group"
                         @click="showPromotionModal = true">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 font-black text-xs"
                                     :class="currentPromotion ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-300'">
                                    <span x-text="currentPromotion ? '🏷️' : '🎟️'"></span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-slate-800 dark:text-white truncate"
                                         x-text="currentPromotion ? currentPromotion.code + ' - ' + currentPromotion.name : 'Tanpa Kupon Promo'"></div>
                                    <div class="text-[10px] text-slate-400 truncate"
                                         x-text="currentPromotion ? (currentPromotion.type === 'percentage' ? 'Diskon ' + currentPromotion.value + '%' : 'Potongan Rp ' + formatRupiah(currentPromotion.value)) : 'Klik untuk memilih kupon promo yang aktif'"></div>
                                </div>
                            </div>

                            <div class="flex items-center gap-1 shrink-0">
                                <template x-if="currentPromotion">
                                    <button type="button"
                                            @click.stop="selectPromotion(null)"
                                            title="Lepas kupon promo"
                                            class="p-1 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </template>
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-900 group-hover:bg-indigo-100 transition">
                                    <span x-text="currentPromotion ? 'Ganti' : 'Pilih Promo'"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <template x-if="currentPromotion && subtotal < currentPromotion.min_purchase">
                        <p class="text-[10px] text-rose-600 dark:text-rose-400 font-bold mt-1">
                            * Promo ini membutuhkan minimal belanja Rp <span x-text="formatRupiah(currentPromotion.min_purchase)"></span>
                        </p>
                    </template>
                </div>

                <!-- PILIHAN METODE PEMBAYARAN DINAMIS (Task 18) -->
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
                    <span class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Metode Pembayaran</span>
                    <div class="grid grid-cols-2 gap-2">
                        <template x-for="pm in paymentMethods" :key="pm.id">
                            <button type="button"
                                    @click="selectPaymentMethod(pm)"
                                    class="p-2.5 rounded-xl border text-left transition flex flex-col justify-between"
                                    :class="selectedPaymentMethodId === pm.id ? 'bg-blue-50/80 dark:bg-blue-950/60 border-blue-500 dark:border-blue-400 ring-2 ring-blue-500/20 text-blue-900 dark:text-blue-200' : 'bg-slate-50 dark:bg-slate-800/80 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/60'">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-extrabold" x-text="pm.name"></span>
                                    <span class="w-2 h-2 rounded-full" :class="selectedPaymentMethodId === pm.id ? 'bg-blue-600 dark:bg-blue-400' : 'bg-slate-300 dark:bg-slate-600'"></span>
                                </div>
                                <template x-if="pm.account_number">
                                    <span class="text-[10px] font-mono text-slate-400 dark:text-slate-500 mt-1" x-text="pm.account_number"></span>
                                </template>
                            </button>
                        </template>
                    </div>

                    <!-- Info & Petunjuk Pembayaran Non-Tunai (QRIS / Bank Transfer / E-Wallet) -->
                    <template x-if="currentPaymentMethod && currentPaymentMethod.type !== 'cash'">
                        <div class="mt-2.5 p-3 rounded-2xl bg-blue-50/80 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800/70 text-xs space-y-1.5 transition">
                            <div class="flex items-center justify-between">
                                <span class="font-extrabold text-blue-900 dark:text-blue-300 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span x-text="'Instruksi ' + currentPaymentMethod.name"></span>
                                </span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 uppercase tracking-wider">Non-Tunai</span>
                            </div>
                            
                            <div class="text-[11px] text-slate-600 dark:text-slate-300 space-y-0.5 pt-0.5">
                                <template x-if="currentPaymentMethod.account_number">
                                    <p>
                                        <span class="text-slate-400 dark:text-slate-400">No. Rekening / NMID / No. HP:</span> 
                                        <strong class="font-mono text-slate-900 dark:text-white" x-text="currentPaymentMethod.account_number"></strong>
                                    </p>
                                </template>
                                <template x-if="currentPaymentMethod.account_name">
                                    <p>
                                        <span class="text-slate-400 dark:text-slate-400">Atas Nama:</span> 
                                        <strong class="text-slate-900 dark:text-white" x-text="currentPaymentMethod.account_name"></strong>
                                    </p>
                                </template>
                                <template x-if="currentPaymentMethod.instructions">
                                    <p class="text-slate-500 dark:text-slate-400 italic" x-text="currentPaymentMethod.instructions"></p>
                                </template>
                            </div>

                            <div class="mt-1 pt-1.5 border-t border-blue-200/60 dark:border-blue-800/60 text-[10px] text-amber-800 dark:text-amber-300 font-semibold flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span>Verifikasi Kasir: Pastikan pelanggan telah selesai mentransfer / scan dan menunjukkan bukti pembayaran sebelum menekan tombol checkout.</span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Ringkasan Tagihan & Input Bayar -->
                <div class="pt-3 border-t border-slate-100 dark:border-slate-800 space-y-3">
                    
                    <div class="space-y-1.5 pb-2 border-b border-slate-100 dark:border-slate-800 text-xs">
                        <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                            <span>Subtotal Belanja</span>
                            <span class="font-bold text-slate-700 dark:text-slate-200" x-text="'Rp ' + formatRupiah(subtotal)"></span>
                        </div>
                        <template x-if="memberDiscount > 0">
                            <div class="flex items-center justify-between text-emerald-600 dark:text-emerald-400 font-bold">
                                <span>Diskon Member (<span x-text="currentCustomer.membership_tier"></span>)</span>
                                <span x-text="'- Rp ' + formatRupiah(memberDiscount)"></span>
                            </div>
                        </template>
                        <template x-if="promoDiscount > 0">
                            <div class="flex items-center justify-between text-indigo-600 dark:text-indigo-400 font-bold">
                                <span>Diskon Kupon (<span x-text="currentPromotion.code"></span>)</span>
                                <span x-text="'- Rp ' + formatRupiah(promoDiscount)"></span>
                            </div>
                        </template>
                        <template x-if="taxAmount > 0">
                            <div class="flex items-center justify-between text-slate-600 dark:text-slate-300 font-semibold">
                                <span x-text="taxSettings.tax_name"></span>
                                <span x-text="'+ Rp ' + formatRupiah(taxAmount)"></span>
                            </div>
                        </template>
                        <template x-if="serviceChargeAmount > 0">
                            <div class="flex items-center justify-between text-slate-600 dark:text-slate-300 font-semibold">
                                <span x-text="taxSettings.service_charge_name"></span>
                                <span x-text="'+ Rp ' + formatRupiah(serviceChargeAmount)"></span>
                            </div>
                        </template>
                    </div>

                    <div class="flex items-baseline justify-between">
                        <div>
                            <span class="text-xs font-extrabold text-slate-400 dark:text-slate-400 uppercase tracking-wider block">Total Tagihan</span>
                            <template x-if="currentCustomer && total > 0">
                                <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold" x-text="'+' + pointsEarned + ' Poin didapat'"></span>
                            </template>
                        </div>
                        <span class="text-xl font-black text-slate-900 dark:text-white tracking-tight"
                              x-text="'Rp ' + formatRupiah(total)"></span>
                    </div>

                    <!-- Input Nominal Pembayaran -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Jumlah Pembayaran <span x-text="'(' + (currentPaymentMethod.name || 'Tunai') + ')'"></span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 font-extrabold text-xs">
                                Rp
                            </div>
                            <input type="number"
                                   min="0"
                                   step="1000"
                                   x-model.number="paidAmount"
                                   placeholder="0"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-extrabold text-slate-900 dark:text-white placeholder-slate-300 dark:placeholder-slate-600 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/40 transition">
                        </div>
                    </div>

                    <!-- Tombol Uang Cepat (Fast Cash & Pecahan Populer) -->
                    <div class="space-y-1.5">
                        <div class="grid grid-cols-4 gap-1.5">
                            <button type="button"
                                    @click="setExactAmount()"
                                    class="px-2 py-1.5 rounded-xl bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 dark:hover:bg-blue-900/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 text-[11px] font-black transition">
                                Uang Pas
                            </button>
                            <button type="button"
                                    @click="setCash(50000)"
                                    class="px-2 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-[11px] font-black transition">
                                50.000
                            </button>
                            <button type="button"
                                    @click="setCash(100000)"
                                    class="px-2 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-[11px] font-black transition">
                                100.000
                            </button>
                            <button type="button"
                                    @click="paidAmount = 0"
                                    class="px-2 py-1.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-900/50 text-[11px] font-bold transition">
                                Reset (0)
                            </button>
                        </div>
                        <div class="grid grid-cols-4 gap-1.5">
                            <button type="button"
                                    @click="addCash(10000)"
                                    class="px-2 py-1 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-[10px] font-bold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                +10k
                            </button>
                            <button type="button"
                                    @click="addCash(20000)"
                                    class="px-2 py-1 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-[10px] font-bold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                +20k
                            </button>
                            <button type="button"
                                    @click="addCash(50000)"
                                    class="px-2 py-1 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-[10px] font-bold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                +50k
                            </button>
                            <button type="button"
                                    @click="addCash(100000)"
                                    class="px-2 py-1 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-[10px] font-bold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                +100k
                            </button>
                        </div>
                    </div>

                    <!-- Status Kembalian / Kurang -->
                    <div class="p-3 rounded-2xl border text-xs"
                         :class="{
                             'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300': isPaymentEnough,
                             'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300': paidAmount > 0 && !isPaymentEnough,
                             'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400': paidAmount == 0 || !total
                         }">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold" x-text="isPaymentEnough ? 'Kembalian:' : (paidAmount > 0 ? 'Kurang Bayar:' : 'Status Bayar:')"></span>
                            <span class="font-extrabold text-sm"
                                  x-text="isPaymentEnough ? ('Rp ' + formatRupiah(change)) : (paidAmount > 0 ? ('- Rp ' + formatRupiah(total - paidAmount)) : 'Belum Dibayar')"></span>
                        </div>
                    </div>

                    <!-- Pesan Error In-App Ketika Kurang Bayar (Task 8) -->
                    <div x-show="paidAmount > 0 && !isPaymentEnough"
                         x-transition
                         class="p-3 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-700 dark:text-rose-300 text-xs font-bold flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Uang pembayaran kurang <span x-text="'Rp ' + formatRupiah(total - paidAmount)"></span>. Transaksi diblokir sampai uang pas/cukup.</span>
                    </div>

                    <!-- Form Submission Real -->
                    <form method="POST"
                          action="{{ route('transactions.store') }}"
                          @submit.prevent="if (!isPaymentEnough) { showToast('Uang pembayaran masih kurang! Silakan lengkapi pembayaran.', 'error'); return false; } isSubmitting = true; $el.submit();">
                        @csrf

                        <template x-for="(item, index) in cart" :key="item.key">
                            <div>
                                <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">
                                <input type="hidden" :name="'items[' + index + '][product_variant_id]'" :value="item.product_variant_id">
                                <input type="hidden" :name="'items[' + index + '][note]'" :value="item.note">
                                <input type="hidden" :name="'items[' + index + '][quantity]'" :value="item.quantity">
                            </div>
                        </template>

                        <input type="hidden" name="customer_id" :value="selectedCustomerId">
                        <input type="hidden" name="customer_name" :value="customerName">
                        <input type="hidden" name="promotion_id" :value="selectedPromotionId">
                        <input type="hidden" name="paid_amount" :value="paidAmount">
                        <input type="hidden" name="payment_method_id" :value="selectedPaymentMethodId">
                        <input type="hidden" name="order_type" :value="orderType">
                        <input type="hidden" name="note" :value="orderNote">

                        <button type="submit"
                                :disabled="cart.length === 0 || !isPaymentEnough || isSubmitting"
                                class="w-full mt-2 py-3.5 px-4 rounded-2xl font-extrabold text-sm flex items-center justify-center gap-2 transition-all shadow-md"
                                :class="{
                                    'bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:from-blue-700 hover:to-indigo-700 shadow-blue-500/25 active:scale-98 cursor-pointer': cart.length > 0 && isPaymentEnough && !isSubmitting,
                                    'bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-600 shadow-none cursor-not-allowed': cart.length === 0 || !isPaymentEnough || isSubmitting
                                }">
                            <template x-if="isSubmitting">
                                <span class="flex items-center gap-2">
                                    <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                    Memproses Checkout...
                                </span>
                            </template>
                            <template x-if="!isSubmitting">
                                <span>Selesaikan Pembayaran & Cetak Struk</span>
                            </template>
                        </button>
                    </form>

                </div>

            </div>
        </div>

    </div>

    <!-- MODAL PILIH VARIAN PRODUK (Task 21) -->
    <div x-show="showVariantModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4"
         style="display: none;">
        <div @click.away="showVariantModal = false"
             class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-md w-full p-6 space-y-4">
            
            <div class="flex items-start justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pilih Varian Produk</span>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white" x-text="variantModalProduct?.name"></h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Harga Dasar: <strong class="text-blue-600 dark:text-blue-400" x-text="'Rp ' + (variantModalProduct ? formatRupiah(variantModalProduct.sell_price) : 0)"></strong>
                    </p>
                </div>
                <button type="button"
                        @click="showVariantModal = false"
                        class="p-1 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- List Pilihan Varian -->
            <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                <template x-for="v in (variantModalProduct?.variants || [])" :key="v.id">
                    <button type="button"
                            @click="addToCart(variantModalProduct, v)"
                            class="w-full p-3.5 rounded-2xl border border-slate-200/90 dark:border-slate-800 hover:border-blue-500 dark:hover:border-blue-400 hover:bg-blue-50/50 dark:hover:bg-slate-800/60 transition flex items-center justify-between text-left group">
                        <div>
                            <span class="text-xs font-extrabold text-slate-800 dark:text-slate-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" x-text="v.name"></span>
                            <template x-if="v.additional_price > 0">
                                <span class="block text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold mt-0.5" x-text="'+ Rp ' + formatRupiah(v.additional_price)"></span>
                            </template>
                            <template x-if="!v.additional_price || v.additional_price == 0">
                                <span class="block text-[10px] text-slate-400 font-medium mt-0.5">+ Rp 0 (Standar)</span>
                            </template>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-black text-slate-900 dark:text-white"
                                  x-text="'Rp ' + formatRupiah(Number(variantModalProduct?.sell_price || 0) + Number(v.additional_price || 0))"></span>
                            <span class="block text-[10px] font-bold text-blue-600 dark:text-blue-400 mt-0.5">Pilih &raquo;</span>
                        </div>
                    </button>
                </template>
            </div>

            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                <button type="button"
                        @click="showVariantModal = false"
                        class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                    Batal
                </button>
            </div>

        </div>
    </div>

    <!-- MODAL PEMILIHAN PELANGGAN / MEMBER (Searchable Modal) -->
    <div x-show="showCustomerModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4"
         style="display: none;">
        <div @click.away="showCustomerModal = false"
             class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-xl w-full max-h-[85vh] flex flex-col overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between shrink-0 bg-slate-50/70 dark:bg-slate-800/40">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 dark:text-white">Pilih Pelanggan / Member</h3>
                        <p class="text-[11px] text-slate-400">Pilih dari <span x-text="customers.length"></span> member terdaftar</p>
                    </div>
                </div>
                <button type="button"
                        @click="showCustomerModal = false"
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Search Bar Input Live -->
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 shrink-0">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text"
                           x-model="customerSearch"
                           x-ref="customerSearchInput"
                           placeholder="Ketik nama pelanggan, no telepon/WA, atau kode member..."
                           class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/40 transition">
                    <button type="button"
                            x-show="customerSearch"
                            @click="customerSearch = ''"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- List Member Scrollable -->
            <div class="p-4 overflow-y-auto flex-1 space-y-2">
                <!-- Option: Pelanggan Umum / Non-Member -->
                <button type="button"
                        @click="selectCustomer(null)"
                        class="w-full text-left p-3.5 rounded-2xl border transition flex items-center justify-between"
                        :class="!selectedCustomerId ? 'bg-blue-50/80 dark:bg-blue-950/60 border-blue-500 ring-2 ring-blue-500/20' : 'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700/60'">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-300 font-bold">
                            👤
                        </div>
                        <div>
                            <div class="text-xs font-extrabold text-slate-800 dark:text-white">Umum / Non-Member</div>
                            <div class="text-[11px] text-slate-400">Transaksi reguler tanpa potongan diskon member</div>
                        </div>
                    </div>
                    <span class="text-xs font-bold px-3 py-1.5 rounded-xl transition"
                          :class="!selectedCustomerId ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600'">
                        <span x-text="!selectedCustomerId ? '✓ Terpilih' : 'Pilih'"></span>
                    </span>
                </button>

                <!-- List Filtered Members -->
                <template x-for="cust in filteredCustomers" :key="cust.id">
                    <button type="button"
                            @click="selectCustomer(cust)"
                            class="w-full text-left p-3.5 rounded-2xl border transition flex items-center justify-between"
                            :class="selectedCustomerId === cust.id ? 'bg-blue-50/80 dark:bg-blue-950/60 border-blue-500 ring-2 ring-blue-500/20' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700/80 hover:bg-slate-50 dark:hover:bg-slate-700/50'">
                        <div class="flex items-center gap-3 min-w-0 flex-1 pr-3">
                            <!-- Avatar / Initial -->
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xs font-black shrink-0"
                                 :class="{
                                     'bg-amber-100 text-amber-800 border border-amber-300': cust.membership_tier === 'Gold',
                                     'bg-slate-200 text-slate-800 border border-slate-300': cust.membership_tier === 'Silver',
                                     'bg-purple-100 text-purple-800 border border-purple-300': cust.membership_tier === 'Platinum',
                                     'bg-blue-100 text-blue-800 border border-blue-200': !['Gold','Silver','Platinum'].includes(cust.membership_tier)
                                 }">
                                <span x-text="cust.name.substring(0,2).toUpperCase()"></span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-extrabold text-slate-900 dark:text-white truncate" x-text="cust.name"></span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider"
                                          :class="{
                                              'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300': cust.membership_tier === 'Gold',
                                              'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300': cust.membership_tier === 'Silver',
                                              'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300': cust.membership_tier === 'Platinum',
                                              'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300': !['Gold','Silver','Platinum'].includes(cust.membership_tier)
                                          }"
                                          x-text="cust.membership_tier"></span>
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5 flex flex-wrap items-center gap-2">
                                    <span class="font-mono text-slate-500 dark:text-slate-400" x-text="cust.member_code"></span>
                                    <span>&bull;</span>
                                    <span x-text="cust.phone || '-'"></span>
                                    <span>&bull;</span>
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400" x-text="'Diskon ' + cust.discount_percent + '%'"></span>
                                    <span>&bull;</span>
                                    <span class="font-semibold text-blue-600 dark:text-blue-400" x-text="formatRupiah(cust.points) + ' Poin'"></span>
                                </div>
                            </div>
                        </div>

                        <span class="text-xs font-bold px-3 py-1.5 rounded-xl transition shrink-0"
                              :class="selectedCustomerId === cust.id ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200'">
                            <span x-text="selectedCustomerId === cust.id ? '✓ Terpilih' : 'Pilih'"></span>
                        </span>
                    </button>
                </template>

                <!-- Zero State Filter -->
                <div x-show="filteredCustomers.length === 0" class="py-8 text-center text-slate-400 text-xs">
                    <p class="font-bold">Member tidak ditemukan</p>
                    <p class="text-[11px] mt-1">Coba kata kunci pencarian nama atau nomor HP lainnya.</p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 flex items-center justify-between shrink-0">
                <span class="text-[11px] text-slate-500 dark:text-slate-400">
                    Menampilkan <strong class="text-slate-800 dark:text-white" x-text="filteredCustomers.length"></strong> dari <span x-text="customers.length"></span> member
                </span>
                <button type="button"
                        @click="showCustomerModal = false"
                        class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-200 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL PEMILIHAN KUPON PROMO / DISKON (Searchable Modal) -->
    <div x-show="showPromotionModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4"
         style="display: none;">
        <div @click.away="showPromotionModal = false"
             class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 text-left shadow-2xl max-w-lg w-full max-h-[85vh] flex flex-col overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between shrink-0 bg-slate-50/70 dark:bg-slate-800/40">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 dark:text-white">Pilih Kupon Promo / Diskon</h3>
                        <p class="text-[11px] text-slate-400">Total <span x-text="promotions.length"></span> promo aktif tersedia</p>
                    </div>
                </div>
                <button type="button"
                        @click="showPromotionModal = false"
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Search Bar Input Live -->
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 shrink-0">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text"
                           x-model="promotionSearch"
                           placeholder="Ketik kode kupon promo atau nama diskon..."
                           class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/40 transition">
                    <button type="button"
                            x-show="promotionSearch"
                            @click="promotionSearch = ''"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- List Promo Scrollable -->
            <div class="p-4 overflow-y-auto flex-1 space-y-2.5">
                <!-- Option: Tanpa Promo -->
                <button type="button"
                        @click="selectPromotion(null)"
                        class="w-full text-left p-3.5 rounded-2xl border transition flex items-center justify-between"
                        :class="!selectedPromotionId ? 'bg-blue-50/80 dark:bg-blue-950/60 border-blue-500 ring-2 ring-blue-500/20' : 'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700/60'">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-300 font-bold">
                            🎟️
                        </div>
                        <div>
                            <div class="text-xs font-extrabold text-slate-800 dark:text-white">Tanpa Kupon Promo</div>
                            <div class="text-[11px] text-slate-400">Tidak menggunakan voucher potongan promo</div>
                        </div>
                    </div>
                    <span class="text-xs font-bold px-3 py-1.5 rounded-xl transition"
                          :class="!selectedPromotionId ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600'">
                        <span x-text="!selectedPromotionId ? '✓ Terpilih' : 'Pilih'"></span>
                    </span>
                </button>

                <!-- List Filtered Promos -->
                <template x-for="p in filteredPromotions" :key="p.id">
                    <button type="button"
                            @click="selectPromotion(p)"
                            class="w-full text-left p-4 rounded-2xl border transition flex items-center justify-between group"
                            :class="selectedPromotionId === p.id ? 'bg-indigo-50/80 dark:bg-indigo-950/60 border-indigo-500 ring-2 ring-indigo-500/20' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700/80 hover:bg-slate-50 dark:hover:bg-slate-700/50'">
                        <div class="flex items-center gap-3 min-w-0 flex-1 pr-3">
                            <div class="w-11 h-11 rounded-2xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 flex items-center justify-center font-black text-sm shrink-0 shadow-2xs">
                                %
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs font-black text-indigo-600 dark:text-indigo-400 tracking-wider bg-indigo-50 dark:bg-indigo-950/80 px-2 py-0.5 rounded-lg border border-indigo-200 dark:border-indigo-800"
                                          x-text="p.code"></span>
                                    <span class="text-xs font-extrabold text-slate-900 dark:text-white truncate" x-text="p.name"></span>
                                </div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 flex flex-wrap items-center gap-2">
                                    <span class="font-black text-emerald-600 dark:text-emerald-400"
                                          x-text="'Diskon: ' + (p.type === 'percentage' ? p.value + '%' : 'Rp ' + formatRupiah(p.value))"></span>
                                    <span>&bull;</span>
                                    <span>Min. Belanja: <strong class="text-slate-700 dark:text-slate-300" x-text="'Rp ' + formatRupiah(p.min_purchase)"></strong></span>
                                </div>
                                <template x-if="subtotal < p.min_purchase">
                                    <p class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold mt-1">
                                        ⚠️ Belanja saat ini (Rp <span x-text="formatRupiah(subtotal)"></span>) belum mencapai batas minimal
                                    </p>
                                </template>
                            </div>
                        </div>

                        <span class="text-xs font-bold px-3 py-1.5 rounded-xl transition shrink-0"
                              :class="selectedPromotionId === p.id ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 group-hover:bg-slate-200'">
                            <span x-text="selectedPromotionId === p.id ? '✓ Digunakan' : 'Gunakan'"></span>
                        </span>
                    </button>
                </template>

                <!-- Zero State Filter -->
                <div x-show="filteredPromotions.length === 0" class="py-8 text-center text-slate-400 text-xs">
                    <p class="font-bold">Kupon promo tidak ditemukan</p>
                    <p class="text-[11px] mt-1">Tidak ada kode promo yang cocok dengan pencarian.</p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 flex items-center justify-between shrink-0">
                <span class="text-[11px] text-slate-500 dark:text-slate-400">
                    Menampilkan <strong class="text-slate-800 dark:text-white" x-text="filteredPromotions.length"></strong> dari <span x-text="promotions.length"></span> promo
                </span>
                <button type="button"
                        @click="showPromotionModal = false"
                        class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-200 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         MODAL BUKA SHIFT KASIR (Input Modal Kas Awal)
         ========================================================================= --}}
    <div x-show="showOpenShiftModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         style="display: none;">
        
        <div @click.away="showOpenShiftModal = false"
             class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-md w-full p-6 space-y-4">
            
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 flex items-center justify-center text-xl shrink-0 font-black">
                    🔓
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-900 dark:text-white">Buka Shift Kasir Baru</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Masukkan modal kas awal (uang receh/kembalian di laci kasir).</p>
                </div>
            </div>

            <form action="{{ route('cashier-shifts.open') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        Modal Kas Awal (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-extrabold text-slate-400">Rp</span>
                        <input type="number"
                               name="starting_cash"
                               min="0"
                               step="1000"
                               x-model.number="shiftStartingCash"
                               required
                               placeholder="200000"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-black text-slate-900 dark:text-white focus:border-amber-500 focus:ring-amber-500">
                    </div>
                    
                    <!-- Tombol Cepat Pecahan Modal Awal -->
                    <div class="grid grid-cols-3 gap-1.5 mt-2">
                        <button type="button" @click="shiftStartingCash = 100000" class="py-1 px-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-amber-50 text-[11px] font-bold text-slate-700 dark:text-slate-300">Rp 100.000</button>
                        <button type="button" @click="shiftStartingCash = 200000" class="py-1 px-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-amber-50 text-[11px] font-bold text-slate-700 dark:text-slate-300">Rp 200.000</button>
                        <button type="button" @click="shiftStartingCash = 500000" class="py-1 px-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-amber-50 text-[11px] font-bold text-slate-700 dark:text-slate-300">Rp 500.000</button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Catatan Shift (Opsional)
                    </label>
                    <input type="text"
                           name="notes"
                           placeholder="Contoh: Shift Pagi Barista (07:00 - 15:00)"
                           class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-white">
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button"
                            @click="showOpenShiftModal = false"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white text-xs font-black shadow-md shadow-amber-500/20">
                        Buka Shift Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- =========================================================================
         MODAL TUTUP SHIFT KASIR (Rekonsiliasi Kas Laci & Cetak Z-Report)
         ========================================================================= --}}
    @if($activeShift)
    <div x-show="showCloseShiftModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         style="display: none;">
        
        <div @click.away="showCloseShiftModal = false"
             class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-lg w-full p-6 space-y-4">
            
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 flex items-center justify-center text-xl shrink-0 font-black">
                    🔒
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-900 dark:text-white">Tutup Shift Kasir (Z-Report)</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Rekonsiliasi uang kas fisik di laci kasir sebelum serah terima shift.</p>
                </div>
            </div>

            <!-- Ringkasan Shift Aktif -->
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-500">Jam Buka Shift:</span>
                    <strong class="text-slate-800 dark:text-white">{{ $activeShift->opened_at->format('d M Y, H:i') }}</strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Modal Kas Awal:</span>
                    <span class="font-bold text-slate-800 dark:text-white">Rp {{ number_format($activeShift->starting_cash, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">(+) Penjualan Kas (Tunai):</span>
                    <span class="font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($activeShiftSummary['cash_sales'] ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">(+) Penjualan Non-Tunai (QRIS/Transfer):</span>
                    <span class="font-bold text-blue-600 dark:text-blue-400">Rp {{ number_format($activeShiftSummary['non_cash_sales'] ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Total Transaksi:</span>
                    <span class="font-black text-slate-900 dark:text-white">{{ $activeShiftSummary['transactions_count'] ?? 0 }} Struk</span>
                </div>
                <div class="pt-2 border-t border-slate-200 dark:border-slate-700 flex justify-between font-black text-sm">
                    <span class="text-slate-700 dark:text-slate-300">Uang Kas Sistem (Expected):</span>
                    <span class="text-blue-600 dark:text-blue-400">Rp {{ number_format($activeShiftSummary['expected_cash'] ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <form action="{{ route('cashier-shifts.close', $activeShift->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        Hitungan Uang Kas Fisik di Laci (Actual Cash) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-extrabold text-slate-400">Rp</span>
                        <input type="number"
                               name="actual_cash"
                               min="0"
                               step="500"
                               x-model.number="shiftActualCash"
                               required
                               placeholder="Hitung uang tunai di laci..."
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-black text-slate-900 dark:text-white focus:border-rose-500 focus:ring-rose-500">
                    </div>

                    <!-- Live Selisih Status -->
                    <div class="mt-2 p-2.5 rounded-xl text-xs flex items-center justify-between font-bold"
                         :class="{
                             'bg-emerald-50 text-emerald-800 border border-emerald-200': shiftActualCash == {{ (float)($activeShiftSummary['expected_cash'] ?? 0) }},
                             'bg-rose-50 text-rose-800 border border-rose-200': shiftActualCash < {{ (float)($activeShiftSummary['expected_cash'] ?? 0) }},
                             'bg-blue-50 text-blue-800 border border-blue-200': shiftActualCash > {{ (float)($activeShiftSummary['expected_cash'] ?? 0) }}
                         }">
                        <span>Estimasi Selisih:</span>
                        <span class="font-black"
                              x-text="shiftActualCash == {{ (float)($activeShiftSummary['expected_cash'] ?? 0) }}
                                      ? '✓ PAS (SEIMBANG)'
                                      : (shiftActualCash > {{ (float)($activeShiftSummary['expected_cash'] ?? 0) }}
                                          ? '+ LEBIH Rp ' + formatRupiah(shiftActualCash - {{ (float)($activeShiftSummary['expected_cash'] ?? 0) }})
                                          : '- KURANG Rp ' + formatRupiah({{ (float)($activeShiftSummary['expected_cash'] ?? 0) }} - shiftActualCash))">
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Catatan Penutupan Shift (Opsional)
                    </label>
                    <textarea name="notes"
                              rows="2"
                              placeholder="Keterangan kondisi kasir, serah terima shift..."
                              class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-white"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button"
                            @click="showCloseShiftModal = false"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-700 hover:to-rose-800 text-white text-xs font-black shadow-md shadow-rose-600/20">
                        Tutup Shift & Cetak Z-Report
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>

</x-app-layout>
