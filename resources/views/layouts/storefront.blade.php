<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lactona Mart</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('lactona-logo.png') }}">
    <!-- Memuat Tailwind CSS melalui Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    x-data
    :class="{ 'overflow-hidden': $store.cart.isOpen }"
    @keydown.escape.window="$store.cart.handleEscape()"
    data-whatsapp-number="{{ preg_replace('/\D+/', '', (string) config('services.whatsapp.admin_number')) }}"
    data-cart-products-url="{{ route('cart.products') }}"
    data-checkout-url="{{ route('checkout.store') }}"
    class="font-sans text-gray-800 antialiased bg-gray-50 relative"
>
    <!-- Top Bar -->
    <div class="bg-brand-dark text-white text-xs px-8 py-2 flex justify-between items-center opacity-95">
        <div class="hidden md:block">
            <!-- <span class="mr-2">🚚</span> Free Shipping on Orders Over $50 -->
        </div>
        <div class="flex space-x-4">
            <!-- <a href="#" class="hover:text-gray-300">Track Order</a> -->
            <a href="#" class="hover:text-gray-300">FAQ</a>
            <!-- <a href="#" class="hover:text-gray-300">Store Locator</a> -->
            <a href="#" class="hover:text-gray-300">Contact Us</a>
        </div>
    </div>

    <!-- Navigasi Utama menggunakan Alpine.js -->
    <!-- Atribut x-data=" { open: false } " menginisialisasi state 'open' untuk menu mobile -->
    <nav class="bg-brand-dark sticky top-0 z-50 text-white py-4 transition-all duration-300" x-data="{ open: false }">
        <div class=" max-w-7xl mx-auto flex items-center justify-between">
            
            <!-- Bagian Kiri: Logo -->
            <div class="w-85 h-25 flex items-center justify-start">
                <img
                    class="w-48 h-auto object-contain"
                    src="{{ asset('Logo Lactona Mart Berkilau.png') }}"
                    alt="Lactona Mart"
                >
            </div>
            
            <!-- Bagian Tengah: Menu Desktop (Disembunyikan saat layar kecil) -->
            <ul x-data="{ activeMenu: '{{ request()->segment(1) ?: 'home' }}' }" class="hidden md:flex space-x-8 text-sm font-medium tracking-wide">
                <li :class="activeMenu === 'home' ? 'border-b-2 border-white pb-1' : 'hover:opacity-80 pb-1 border-b-2 border-transparent transition-colors cursor-pointer'">
                    <a href="/">HOME</a>
                </li>
                <li :class="activeMenu === 'produk-kami' ? 'border-b-2 border-white pb-1' : 'hover:opacity-80 pb-1 border-b-2 border-transparent transition-colors cursor-pointer'">
                    <a href="/produk-kami">PRODUK</a>
                </li>
                <li :class="activeMenu === 'collection' ? 'border-b-2 border-white pb-1' :          'hover:opacity-80 pb-1 border-b-2 border-transparent transition-colors cursor-pointer'">
                    <a href="#">COLLECTION</a>
                </li>
                <li :class="activeMenu === 'aboutUs' ? 'border-b-2 border-white pb-1' : 'hover:opacity-80 pb-1 border-b-2 border-transparent transition-colors cursor-pointer'">
                    <a href="#">ABOUT US</a>
                </li>
            </ul>

            <!-- Bagian Kanan: Ikon (Pencarian, Keranjang) & Tombol Hamburger Mobile -->
            <div class="flex items-center space-x-6">
                <!-- Ikon Pencarian (Hidden di Mobile) -->
                <button class="hidden md:block hover:opacity-80">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
                <!-- Ikon Keranjang -->
                <button
                    type="button"
                    @click="$store.cart.open()"
                    :aria-expanded="$store.cart.isOpen.toString()"
                    aria-controls="cart-drawer-panel"
                    aria-label="Buka keranjang"
                    class="hover:opacity-80 relative"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span
                        x-show="$store.cart.totalProducts > 0"
                        x-text="$store.cart.totalProducts"
                        class="absolute -top-2 -right-2 flex h-4 min-w-4 items-center justify-center rounded-full bg-white px-1 text-xs font-bold text-brand-dark"
                        style="display: none;"
                    ></span>
                </button>

                <!-- Tombol Hamburger Menu (Hanya muncul di layar kecil) -->
                <!-- x-on:click memicu pergantian nilai state 'open' (true menjadi false, dan sebaliknya) -->
                <button @click="open = !open" class="md:hidden focus:outline-none hover:opacity-80">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Menu Dropdown Mobile -->
        <!-- x-show menampilkan div ini hanya ketika state 'open' bernilai true -->
        <!-- x-transition memberikan efek animasi halus saat menu dibuka/tutup -->
        <div x-show="open" 
             @click.away="open = false" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-4"
             class="md:hidden absolute top-full left-0 w-full bg-brand-dark/95 backdrop-blur-sm border-t border-brand-teal/50 shadow-lg pb-6"
             style="display: none;"> <!-- style="display: none;" mencegah kedipan menu saat halaman pertama dimuat -->
            
            <ul class="flex flex-col px-8 pt-4 space-y-4 text-sm font-medium tracking-wide">
                <li><a href="/" class="block hover:text-brand-accent transition-colors">HOME</a></li>
                <li><a href="/produk-kami" class="block hover:text-brand-accent transition-colors">PRODUK</a></li>
                <li><a href="#" class="block hover:text-brand-accent transition-colors">COLLECTION</a></li>
                <li><a href="#" class="block hover:text-brand-accent transition-colors">ABOUT US</a></li>
                <!-- <li><a href="{{ route('admin.products.index') }}" class="block text-brand-accent hover:underline font-semibold transition-colors">ADMIN PANEL</a></li> -->
                <li class="pt-4 border-t border-brand-teal/30">
                     <!-- Input pencarian khusus mobile -->
                    <div class="relative text-gray-800">
                        <input type="text" placeholder="Search..." class="w-full py-2 pl-3 pr-10 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-brand-accent">
                        <svg class="absolute right-3 top-2.5 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Konten Spesifik Halaman Akan Disisipkan Di Sini -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer Trust Badges -->
    <footer class="border-t border-gray-200 py-4 bg-white">
        <div class="max-w-6xl mx-auto px-8 grid grid-cols-2 md:grid-cols-4 gap-4 text-center md:text-left divide-x divide-gray-100">
            <div class="flex flex-col md:flex-row items-center justify-center md:justify-start space-y-2 md:space-y-0 md:space-x-3 px-4">
                <span class="text-2xl opacity-70">🚚</span>
                <div>
                    <h5 class="text-xs font-bold text-gray-800 uppercase">Free Shipping</h5>
                    <p class="text-[10px] text-gray-500 mt-0.5">On orders over $50</p>
                </div>
            </div>
             <div class="flex flex-col md:flex-row items-center justify-center md:justify-start space-y-2 md:space-y-0 md:space-x-3 px-4">
                <span class="text-2xl opacity-70">🛡️</span>
                <div>
                    <h5 class="text-xs font-bold text-gray-800 uppercase">Secure Payment</h5>
                    <p class="text-[10px] text-gray-500 mt-0.5">100% safe & secure</p>
                </div>
            </div>
            <div class="flex flex-col md:flex-row items-center justify-center md:justify-start space-y-2 md:space-y-0 md:space-x-3 px-4">
                <span class="text-2xl opacity-70">🔄</span>
                <div>
                    <h5 class="text-xs font-bold text-gray-800 uppercase">Easy Returns</h5>
                    <p class="text-[10px] text-gray-500 mt-0.5">30 days return policy</p>
                </div>
            </div>
            <div class="flex flex-col md:flex-row items-center justify-center md:justify-start space-y-2 md:space-y-0 md:space-x-3 px-4">
                <span class="text-2xl opacity-70">🎧</span>
                <div>
                    <h5 class="text-xs font-bold text-gray-800 uppercase">Customer Support</h5>
                    <p class="text-[10px] text-gray-500 mt-0.5">We're here to help</p>
                </div>
            </div>
        </div>
        <div class="max-w-6xl text-right mt-4 text-[10px]">
        &copy; 2026 E-commerce PT Mirota KSM. All rights reserved.
        </div>
    </footer>

    <div
        x-cloak
        x-show="$store.cart.isOpen"
        class="fixed inset-0 z-[70]"
        x-transition:enter="transition-opacity ease-out duration-[400ms]"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-[400ms]"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display: none;"
    >
        <button
            type="button"
            @click="$store.cart.close()"
            aria-label="Tutup keranjang"
            class="absolute inset-0 h-full w-full bg-gray-950/45"
        ></button>

        <aside
            id="cart-drawer-panel"
            x-show="$store.cart.isOpen"
            role="dialog"
            aria-modal="true"
            aria-labelledby="cart-drawer-title"
            tabindex="-1"
            @click.stop
            class="absolute inset-y-0 right-0 flex h-full w-full flex-col bg-white shadow-2xl outline-none sm:max-w-lg"
            x-transition:enter="transform transition-transform ease-out duration-500"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition-transform ease-in duration-[350ms]"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
        >
            <header class="flex shrink-0 items-center justify-between border-b border-gray-200 px-5 py-4 sm:px-7">
                <div>
                    <h2 id="cart-drawer-title" class="text-xl font-bold text-gray-900">Keranjang Belanja</h2>
                    <p class="mt-0.5 text-sm text-gray-500">
                        <span x-text="$store.cart.totalProducts"></span> Produk
                    </p>
                </div>
                <button
                    type="button"
                    @click="$store.cart.close()"
                    aria-label="Tutup keranjang"
                    class="rounded-full p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-dark"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </header>

            <div class="flex-1 overflow-y-auto px-5 py-5 sm:px-7">
                <template x-if="$store.cart.items.length === 0">
                    <div class="flex min-h-[45vh] flex-col items-center justify-center text-center">
                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-brand-light text-brand-dark">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900">Keranjang masih kosong</h3>
                        <p class="mt-1 max-w-xs text-sm text-gray-500">Pilih produk yang kamu butuhkan, lalu tambahkan ke keranjang.</p>
                        <a
                            href="{{ route('products.public') }}"
                            @click="$store.cart.close()"
                            class="mt-5 inline-flex items-center justify-center rounded-md bg-brand-dark px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-teal"
                        >
                            Lihat Produk
                        </a>
                    </div>
                </template>

                <template x-if="$store.cart.items.length > 0">
                    <div class="space-y-6">
                        <div>
                            <h3 class="mb-1 text-base font-bold text-gray-900">
                                Daftar Produk
                            </h3>

                        <div class="divide-y divide-gray-100">
                            <template x-for="item in $store.cart.items" :key="item.key">
                                <article class="flex gap-4 py-4 first:pt-0">
                                    <img
                                        :src="item.image"
                                        :alt="item.name"
                                        class="h-20 w-20 shrink-0 rounded-md bg-gray-50 object-contain p-2"
                                    >
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <h3 class="truncate text-sm font-semibold text-gray-900" x-text="item.name"></h3>
                                                <p x-show="item.variantLabel" x-text="item.variantLabel" class="mt-1 text-xs text-gray-500"></p>
                                                <p class="mt-2 text-sm font-semibold text-gray-800">
                                                    Rp <span x-text="$store.cart.formatPrice(item.unitPrice)"></span>
                                                </p>
                                            </div>
                                            <button
                                                type="button"
                                                @click="$store.cart.remove(item.key)"
                                                :aria-label="'Hapus ' + item.name"
                                                class="shrink-0 rounded p-1 text-gray-400 transition hover:bg-rose-50 hover:text-rose-600"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 7h12m-10 0V5h8v2m-7 2v12m6-12v12M5 7l1 14h12l1-14"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="mt-3 flex items-center justify-between gap-3">
                                            <div class="inline-flex items-center rounded border border-gray-200">
                                                <button
                                                    type="button"
                                                    @click="$store.cart.updateQuantity(item.key, -1)"
                                                    :aria-label="'Kurangi jumlah ' + item.name"
                                                    class="flex h-8 w-8 items-center justify-center text-gray-600 transition hover:bg-gray-100"
                                                >−</button>
                                                <span class="min-w-8 text-center text-sm font-medium" x-text="item.quantity"></span>
                                                <button
                                                    type="button"
                                                    @click="$store.cart.updateQuantity(item.key, 1)"
                                                    :aria-label="'Tambah jumlah ' + item.name"
                                                    class="flex h-8 w-8 items-center justify-center text-gray-600 transition hover:bg-gray-100"
                                                >+</button>
                                            </div>
                                            <p class="text-sm font-semibold text-gray-900">
                                                Rp <span x-text="$store.cart.formatPrice(item.unitPrice * item.quantity)"></span>
                                            </p>
                                        </div>
                                    </div>
                                </article>
                            </template>
                        </div>
                    </div>

                        <div class="space-y-3">
                            <button
                                type="button"
                                @click="$store.cart.toggleProductPicker()"
                                :aria-expanded="$store.cart.addProductsOpen.toString()"
                                aria-controls="cart-product-picker"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-brand-dark transition hover:text-brand-teal"
                            >
                                <svg
                                    class="h-5 w-5 transition-transform duration-200"
                                    :class="$store.cart.addProductsOpen ? 'rotate-45' : ''"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14"></path>
                                </svg>
                                <span x-text="$store.cart.addProductsOpen ? 'Tutup pilihan produk' : 'Tambah produk lain'"></span>
                            </button>

                            <div
                                id="cart-product-picker"
                                x-show="$store.cart.addProductsOpen"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-2"
                                class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"
                            >
                                <div class="border-b border-gray-100 p-3">
                                    <label for="cart-product-search" class="sr-only">Cari produk</label>
                                    <div class="relative">
                                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M19 10.5a8.5 8.5 0 1 1-17 0 8.5 8.5 0 0 1 17 0Z"></path>
                                        </svg>
                                        <input
                                            id="cart-product-search"
                                            type="search"
                                            x-model="$store.cart.productSearch"
                                            placeholder="Cari nama produk..."
                                            class="w-full rounded-lg border-gray-200 py-2 pl-9 pr-3 text-sm focus:border-brand-dark focus:ring-brand-dark"
                                        >
                                    </div>
                                </div>

                                <div
                                    x-show="$store.cart.productsLoading"
                                    role="status"
                                    class="px-4 py-6 text-center text-sm text-gray-500"
                                >
                                    Memuat produk...
                                </div>

                                <div
                                    x-show="$store.cart.productsError"
                                    class="space-y-3 px-4 py-6 text-center"
                                >
                                    <p x-text="$store.cart.productsError" class="text-sm text-gray-600"></p>
                                    <button
                                        type="button"
                                        @click="$store.cart.loadAddableProducts()"
                                        class="text-sm font-semibold text-brand-dark underline decoration-brand-teal underline-offset-2"
                                    >
                                        Coba lagi
                                    </button>
                                </div>

                                <template x-if="!$store.cart.productsLoading && !$store.cart.productsError && $store.cart.filteredAddableProducts.length === 0">
                                    <p
                                        x-text="$store.cart.addableProducts.length === 0 ? 'Belum ada produk yang tersedia.' : ($store.cart.productSearch.trim() ? 'Produk tidak ditemukan.' : 'Semua produk sudah ada di keranjang.')"
                                        class="px-4 py-6 text-center text-sm text-gray-500"
                                    ></p>
                                </template>

                                <p
                                    x-show="$store.cart.lastAddedProduct"
                                    x-text="$store.cart.lastAddedProduct + ' ditambahkan ke keranjang.'"
                                    x-cloak
                                    role="status"
                                    aria-live="polite"
                                    class="px-3 pt-3 text-xs font-medium text-brand-teal"
                                ></p>

                                <div
                                    x-show="!$store.cart.productsLoading && !$store.cart.productsError && $store.cart.filteredAddableProducts.length > 0"
                                    class="max-h-80 divide-y divide-gray-100 overflow-y-auto"
                                >
                                    <template x-for="product in $store.cart.filteredAddableProducts" :key="product.id">
                                        <article class="space-y-2 p-2">
                                            <div class="flex items-center gap-2.5">
                                                <img
                                                    :src="product.image"
                                                    :alt="product.name"
                                                    class="h-16 w-16 shrink-0 rounded-lg bg-gray-50 object-contain p-1"
                                                >
                                                <div class="min-w-0 flex-1">
                                                    <h4 class="truncate text-sm font-semibold text-gray-900" x-text="product.name"></h4>
                                                    <p class="mt-0.5 text-xs font-semibold text-brand-dark">
                                                        <span x-show="product.variantTypes.some(type => type.affectsPrice) && !product.variantTypes.some(type => type.affectsPrice && product.selectedVariants[type.id])">Mulai </span>
                                                        Rp <span x-text="$store.cart.formatPrice($store.cart.priceForProduct(product))"></span>
                                                    </p>
                                                </div>
                                            </div>

                                            <template x-if="product.variantTypes.length > 0">
                                                <div class="space-y-1.5">
                                                    <template x-for="type in product.variantTypes" :key="type.id">
                                                        <div>
                                                            <label
                                                                :for="'cart-product-' + product.id + '-variant-' + type.id"
                                                                class="sr-only"
                                                                x-text="'Pilih ' + type.name + ' untuk ' + product.name"
                                                            ></label>
                                                            <select
                                                                :id="'cart-product-' + product.id + '-variant-' + type.id"
                                                                x-model="product.selectedVariants[type.id]"
                                                                @change="product.variantError = ''"
                                                                :aria-label="'Pilih ' + type.name + ' untuk ' + product.name"
                                                                class="w-full rounded-lg border-gray-200 py-1.5 text-xs focus:border-brand-dark focus:ring-brand-dark"
                                                            >
                                                                <option value="" x-text="'Pilih ' + type.name"></option>
                                                                <template x-for="variant in type.variants" :key="variant.id">
                                                                    <option
                                                                        :value="variant.id"
                                                                        x-text="variant.name + (type.affectsPrice && Number(variant.price) > 0 ? ' — Rp ' + $store.cart.formatPrice(variant.price) : '')"
                                                                    ></option>
                                                                </template>
                                                            </select>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>

                                            <p
                                                x-show="product.variantError"
                                                x-text="product.variantError"
                                                role="alert"
                                                class="text-xs font-medium text-rose-600"
                                            ></p>
                                            <div class="flex justify-end">
                                                <button
                                                    type="button"
                                                    @click="$store.cart.quickAddProduct(product)"
                                                    :aria-label="'Tambah ' + product.name + ' ke keranjang'"
                                                    class="inline-flex min-h-8 items-center gap-1.5 rounded-full bg-brand-dark px-3 py-1 text-xs font-semibold text-white shadow-sm transition-all hover:bg-brand-teal hover:shadow-md active:scale-95 focus:outline-none focus:ring-2 focus:ring-brand-dark focus:ring-offset-2"
                                                >
                                                    <svg
                                                        class="h-3 w-3"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                        aria-hidden="true"
                                                    >
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14m-7-7h14"></path>
                                                    </svg>
                                                    <span>Tambah</span>
                                                </button>
                                            </div>
                                        </article>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <form id="cart-checkout-form" @submit.prevent="$store.cart.sendToWhatsapp($event.target)" class="space-y-4 border-t border-gray-200 pt-6">
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Data Pembeli</h3>
                                <p class="mt-1 text-sm text-gray-500">Admin akan menggunakan data ini untuk menghubungi kamu.</p>
                            </div>

                            <div>
                                <label for="cart-customer-name" class="mb-1.5 block text-sm font-medium text-gray-700">Nama lengkap</label>
                                <input
                                    id="cart-customer-name"
                                    name="name"
                                    type="text"
                                    autocomplete="name"
                                    required
                                    class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-brand-dark focus:ring-brand-dark"
                                    placeholder="Alex Lactona"
                                >
                            </div>

                            <div>
                                <label for="cart-customer-phone" class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Nomor WhatsApp
                                </label>

                                <input
                                    id="cart-customer-phone"
                                    name="phone"
                                    type="text"
                                    inputmode="numeric"
                                    pattern="[0-9]+"
                                    autocomplete="tel"
                                    required
                                    class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-brand-dark focus:ring-brand-dark"
                                    placeholder="Contoh: 081234567890"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                >
                            </div>                  

                            <div>
                                <label for="cart-customer-address" class="mb-1.5 block text-sm font-medium text-gray-700">Alamat lengkap</label>
                                <textarea
                                    id="cart-customer-address"
                                    name="address"
                                    rows="3"
                                    autocomplete="street-address"
                                    required
                                    class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-brand-dark focus:ring-brand-dark"
                                    placeholder="Nama Jalan, No.Rumah, Kelurahan, Kecamatan, Kota, dan Kode Pos"
                                ></textarea>
                            </div>
                        </form>
                    </div>
                </template>
            </div>

            <footer
                x-show="$store.cart.items.length > 0"
                class="shrink-0 border-t border-gray-200 bg-white px-5 pb-[max(1rem,env(safe-area-inset-bottom))] pt-4 sm:px-7"
            >
                <h3 class="mb-3 text-base font-bold text-gray-900">
                    Detail Pesanan
                </h3>
                <div class="mb-2 flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total item</span>
                    <span
                        class="font-medium text-gray-900"
                        x-text="$store.cart.totalQuantity + ' item'"
                    ></span>
                </div>
                <div class="mb-1 flex items-center justify-between text-sm">
                    <span class="text-gray-600">Subtotal produk</span>
                    <span class="font-semibold text-gray-900">Rp <span x-text="$store.cart.formatPrice($store.cart.subtotal)"></span></span>
                </div>
                <p class="mb-4 text-xs text-gray-500">Ongkir akan dikonfirmasi oleh admin melalui WhatsApp.</p>
                <button
                    type="submit"
                    form="cart-checkout-form"
                    :disabled="$store.cart.items.length === 0 || !$store.cart.whatsappNumber"
                    class="flex w-full items-center justify-center gap-2 rounded-md bg-brand-dark px-5 py-3.5 text-sm font-bold text-white transition hover:bg-brand-teal focus:outline-none focus:ring-2 focus:ring-brand-dark focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M20.52 3.48A11.87 11.87 0 0 0 12.05 0C5.46 0 .1 5.36.1 11.95c0 2.1.55 4.16 1.59 5.98L0 24l6.23-1.63a11.9 11.9 0 0 0 5.82 1.48h.01c6.59 0 11.95-5.36 11.95-11.95 0-3.19-1.24-6.19-3.49-8.42Zm-8.47 18.35h-.01a9.9 9.9 0 0 1-5.04-1.38l-.36-.21-3.7.97.99-3.61-.23-.37a9.87 9.87 0 0 1-1.52-5.28c0-5.47 4.45-9.92 9.93-9.92 2.65 0 5.14 1.03 7.01 2.91a9.87 9.87 0 0 1 2.9 7.02c0 5.47-4.45 9.92-9.92 9.92Zm5.45-7.43c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.95 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.47-.89-.79-1.49-1.77-1.67-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.49s1.07 2.89 1.22 3.09c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.76-.72 2.01-1.42.25-.7.25-1.3.17-1.42-.07-.13-.27-.2-.57-.35Z"></path>
                    </svg>
                    Lanjutkan ke WhatsApp
                </button>
            </footer>

            <div
                x-cloak
                x-show="$store.cart.pendingRemoval"
                @click.self="$store.cart.cancelRemoval()"
                role="presentation"
                class="fixed inset-0 z-[90] flex items-center justify-center bg-gray-950/35 p-4"
                x-transition:enter="transition-opacity ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                <section
                    x-show="$store.cart.pendingRemoval"
                    role="alertdialog"
                    aria-modal="true"
                    aria-labelledby="cart-removal-title"
                    aria-describedby="cart-removal-description"
                    tabindex="-1"
                    @click.stop
                    class="w-full max-w-sm rounded-2xl border border-brand-light bg-white p-5 shadow-2xl sm:p-6"
                    x-transition:enter="transform transition ease-out duration-200"
                    x-transition:enter-start="scale-95 opacity-0"
                    x-transition:enter-end="scale-100 opacity-100"
                    x-transition:leave="transform transition ease-in duration-150"
                    x-transition:leave-start="scale-100 opacity-100"
                    x-transition:leave-end="scale-95 opacity-0"
                >
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-11 0h16"></path>
                        </svg>
                    </div>

                    <h3 id="cart-removal-title" class="mt-4 text-center font-serif text-xl font-bold text-brand-dark">
                        Hapus Produk?
                    </h3>
                    <p id="cart-removal-description" class="mt-2 text-center text-sm leading-relaxed text-gray-600">
                        <span
                            class="font-semibold text-gray-800"
                            x-text="$store.cart.pendingRemoval ? $store.cart.pendingRemoval.name : ''"
                        ></span>
                        akan dihapus dari keranjang.
                    </p>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <button
                            id="cart-removal-cancel"
                            type="button"
                            @click="$store.cart.cancelRemoval()"
                            class="rounded-xl bg-brand-light px-4 py-3 text-sm font-semibold text-brand-dark transition hover:bg-brand-light/80 focus:outline-none focus:ring-2 focus:ring-brand-dark focus:ring-offset-2"
                        >
                            Batal
                        </button>
                        <button
                            type="button"
                            @click="$store.cart.confirmRemoval()"
                            class="rounded-xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-600 focus:ring-offset-2"
                        >
                            Hapus
                        </button>
                    </div>
                </section>
            </div>
        </aside>
    </div>

</body>
</html>
