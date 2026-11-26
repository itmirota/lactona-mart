@extends('layouts.storefront')

@section('content')

@php
    $hasNutritionFacts = !empty($product?->nutrition_facts);
    
    $variantData = $product?->variantTypes
        ->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
                'affects_price' => (bool) $type->affects_price,
                'variants' => $type->variants
                    ->where('is_active', true)
                    ->map(function ($variant) {
                        return [
                            'id' => $variant->id,
                            'name' => $variant->name,
                            'price' => $variant->price,
                            'image' => $variant->image
                                ? \Illuminate\Support\Facades\Storage::url($variant->image)
                                : null,
                        ];
                    })
                    ->values()
                    ->all(),
            ];
        })
        ->filter(fn ($type) => count($type['variants']) > 0)
        ->values()
        ->all();

    $activeDiscount = $product?->currentDiscount;

    $discountData = [
        'promotion_name' => $activeDiscount?->promotion_name,
        'description' => $activeDiscount?->description,
        'type' => $activeDiscount?->discount_type,
        'value' => (float) ($activeDiscount?->discount_value ?? 0),
    ];

    // Daftar gambar produk: gambar utama + gambar tambahan
    $productImages = collect();

    if ($product?->image_url) {
        $productImages->push([
            'url' => $product->image_url,
            'label' => 'Gambar utama',
        ]);
    }

    foreach ($product?->images ?? [] as $index => $image) {
        $productImages->push([
            'url' => $image->image_url,
            'label' => 'Gambar ' . ($index + 2),
        ]);
    }

    $defaultProductImage = asset('produk/Lactona Ibu Cokelat.png');
    $initialProductImage = $productImages->first()['url'] ?? $defaultProductImage;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sans text-gray-900">
    
    <!-- Top Breadcrumb / Promo bar -->
    <!-- <div class="flex justify-between items-center text-xs text-gray-500 mb-8 border-b pb-4">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            <span>Free Shipping on orders over $99</span>
        </div>
        <div class="flex items-center space-x-2">
            <span>USD $</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </div>
    </div> -->

    <!-- Main Product Section -->
    <div
        x-data="{
            productImage: @js($initialProductImage),
            mainImage: @js($initialProductImage),
            productImages: @js($productImages->values()->all()),
            selectedProductImage: @js($initialProductImage),

            basePrice: @js((float) ($product?->price ?? 0)),
            productId: @js($product?->id),
            productName: @js($product?->name ?? 'Lactona Ibu'),
            variantTypes: @js($variantData),
            discount: @js($discountData),
            cartMessage: '',

            selectedVariants: {},
            lensActive: false,
            lensVisible: false,
            lensX: 0,
            lensY: 0,
            lensWidth: 0,
            lensHeight: 0,
            lensSize: 200,
            lensZoom: 1.5,

            init() {
                this.selectedVariants = {};

                this.mainImage = this.selectedProductImage;
            },

            getSelectedVariant(type) {
                const selectedId = this.selectedVariants[type.id];

                if (!selectedId) {
                    return null;
                }

                return type.variants.find(
                    variant => variant.id === selectedId
                ) ?? null;
            },

            get selectedBasePrice() {
                const priceType = this.variantTypes.find(
                    type => type.affects_price
                );

                if (!priceType) {
                    return this.basePrice;
                }

                const selected = this.getSelectedVariant(priceType);

                if (selected && Number(selected.price) > 0) {
                    return Number(selected.price);
                }

                return this.basePrice;
            },

            get finalPrice() {
                const price = this.selectedBasePrice;
                const value = Number(this.discount.value || 0);

                if (this.discount.type === 'percentage') {
                    return Math.max(0, price - (price * value / 100));
                }

                if (this.discount.type === 'fixed') {
                    return Math.max(0, price - value);
                }

                return price;
            },

            get hasDiscount() {
                return (
                    ['percentage', 'fixed'].includes(this.discount.type)
                    && Number(this.discount.value) > 0
                );
            },

            formatPrice(value) {
                return new Intl.NumberFormat('id-ID').format(
                    Math.round(Number(value) || 0)
                );
            },

            updateSelection() {
                const selected = this.variantTypes
                    .map(type => this.getSelectedVariant(type))
                    .filter(Boolean);

                const imageVariant = selected.find(variant => variant.image);

                this.mainImage = imageVariant
                    ? imageVariant.image
                    : this.selectedProductImage;
            },

            selectProductImage(image) {
                this.selectedProductImage = image;
                this.mainImage = image;
            },

            selectVariant(typeId, variantId) {
                this.selectedVariants[typeId] = variantId;
                this.cartMessage = '';
                this.updateSelection();
            },

            addToCart() {
                const missingType = this.variantTypes.find(
                    type => !this.getSelectedVariant(type)
                );

                if (missingType) {
                    this.cartMessage = 'Pilih ' + missingType.name + ' terlebih dahulu.';

                    return;
                }

                const selectedVariants = this.variantTypes.map(type => {
                    const variant = this.getSelectedVariant(type);

                    return {
                        typeId: type.id,
                        variantId: variant.id,
                        typeName: type.name,
                        name: variant.name,
                    };
                });

                window.Alpine.store('cart').add({
                    productId: this.productId,
                    name: this.productName,
                    image: this.mainImage,
                    unitPrice: this.finalPrice,
                    variants: selectedVariants,
                });

                this.cartMessage = '';
            },

            toggleLens() {
                this.lensActive = !this.lensActive;

                if (this.lensActive) {
                    // Aktifkan lensa dan tampilkan di tengah gambar
                    const rect = this.$refs.mainImageArea.getBoundingClientRect();

                    this.lensWidth = rect.width;
                    this.lensHeight = rect.height;
                    this.lensX = rect.width / 2;
                    this.lensY = rect.height / 2;
                    this.lensVisible = true;
                } else {
                    this.lensVisible = false;
                }
            },

            moveLens(event) {
                if (!this.lensActive) return;

                const rect = event.currentTarget.getBoundingClientRect();

                this.lensWidth = rect.width;
                this.lensHeight = rect.height;

                this.lensX = Math.max(
                    this.lensSize / 2,
                    Math.min(
                        event.clientX - rect.left,
                        rect.width - this.lensSize / 2
                    )
                );

                this.lensY = Math.max(
                    this.lensSize / 2,
                    Math.min(
                        event.clientY - rect.top,
                        rect.height - this.lensSize / 2
                    )
                );

                this.lensVisible = true;
            },

            hideLens() {
                this.lensVisible = false;
            },
        }"
        class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-16"
    >       
        <!-- Left Column: Product Images -->
        <!-- Inisialisasi Alpine.js state 'mainImage' dengan gambar default pertama -->
        <div class="md:col-span-7 flex flex-col-reverse md:flex-row gap-4">
            
            <!-- Thumbnail Produk -->
            <div class="flex md:flex-col gap-4 overflow-x-auto md:overflow-y-auto md:w-20 shrink-0">

                <template x-for="(image, index) in productImages" :key="index">
                    <button
                        type="button"
                        @click="selectProductImage(image.url)"
                        :class="selectedProductImage === image.url
                            ? 'border-brand-dark ring-1 ring-brand-dark'
                            : 'border-gray-200 hover:border-gray-400'"
                        class="w-16 h-20 md:w-full md:h-24 bg-gray-100 rounded border-2 overflow-hidden relative shrink-0 transition-all"
                        :title="image.label"
                    >
                        <img
                            :src="image.url"
                            class="w-full h-full object-cover"
                            :alt="image.label"
                        >
                    </button>
                </template>

            </div>
           
            <!-- Main Image Area -->
            <div class="flex-grow bg-gray-50 rounded-xl relative group">

                <!-- Area Gambar Utama -->
                <div
                    x-ref="mainImageArea"
                    class="h-[700px] relative overflow-hidden"
                    :class="lensActive ? 'cursor-none' : 'cursor-default'"
                    @mousemove="moveLens($event)"
                    @mouseleave="hideLens()"
                    @click="if (lensActive) toggleLens()"
                >
                    <!-- Gambar Produk -->
                    <img
                        :src="mainImage"
                        x-transition.opacity.duration.300ms
                        class="w-full h-full object-contain min-h-[400px] md:min-h-[600px]"
                        alt="Main Product"
                    >

                    <!-- Tombol Kaca Pembesar -->
                    <button
                        type="button"
                        @click.stop="toggleLens()"
                        :class="lensActive
                            ? 'bg-brand-dark text-white'
                            : 'bg-white text-gray-800 hover:bg-gray-100'"
                        :aria-pressed="lensActive"
                        :title="lensActive ? 'Nonaktifkan kaca pembesar' : 'Aktifkan kaca pembesar'"
                        class="absolute bottom-6 right-6 z-20 p-3 rounded-full shadow-lg transition-colors"
                    >
                        <svg
                            x-show="!lensActive"
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"
                            />
                        </svg>

                        <svg
                            x-show="lensActive"
                            x-cloak
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>

                    <!-- Kaca Pembesar -->
                    <div
                        x-show="lensActive && lensVisible"
                        x-transition.opacity.duration.150ms
                        class="absolute z-10 rounded-full overflow-hidden border-4 border-white shadow-2xl pointer-events-none"
                        :style="{
                            width: lensSize + 'px',
                            height: lensSize + 'px',
                            left: (lensX - lensSize / 2) + 'px',
                            top: (lensY - lensSize / 2) + 'px'
                        }"
                        style="display: none;"
                    >
                        <img
                            :src="mainImage"
                            class="absolute max-w-none object-contain"
                            alt="Perbesaran gambar produk"
                            :style="{
                                width: (lensWidth * lensZoom) + 'px',
                                height: (lensHeight * lensZoom) + 'px',
                                left: (-(lensX * lensZoom - lensSize / 2)) + 'px',
                                top: (-(lensY * lensZoom - lensSize / 2)) + 'px'
                            }"
                        >
                    </div>

                </div>
            </div>
        </div>

        <!-- Right Column: Product Details & Cart -->
        <div class="md:col-span-5 flex flex-col md:pl-4">
            
            <!-- Badges -->
            <div class="mb-4">
                <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1 rounded">New Arrival</span>
            </div>

            <!-- Title & Rating -->
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-gray-900 mb-2 leading-tight">{{ $product?->name ?? 'Lactona Ibu' }}</h1>
            
            <!-- <div class="flex items-center space-x-2 mb-6">
                <div class="flex text-black text-sm">
                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                </div>
                <span class="text-sm font-medium">4.8</span>
                <span class="text-sm text-gray-500 underline cursor-pointer">(128 reviews)</span>
            </div> -->

            <!-- Price -->
            <div class="mb-6">
                <div
                    x-show="hasDiscount"
                    x-cloak
                >
                    <span
                        x-show="discount.promotion_name"
                        x-text="discount.promotion_name"
                        class="mb-2 inline-flex rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-rose-700"
                    ></span>

                    <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                        <span
                            class="text-base font-medium text-gray-400 line-through"
                            x-text="'Rp ' + formatPrice(selectedBasePrice)"
                        ></span>

                        <span
                            class="text-3xl font-bold text-rose-700"
                            x-text="'Rp ' + formatPrice(finalPrice)"
                        ></span>
                    </div>
                </div>

                <div
                    x-show="!hasDiscount"
                    x-cloak
                >
                    <span
                        class="text-3xl font-bold text-gray-900"
                        x-text="'Rp ' + formatPrice(finalPrice)"
                    ></span>
                </div>

                <p
                    x-show="hasDiscount && discount.description"
                    x-text="discount.description"
                    class="mt-2 max-w-xl text-sm leading-relaxed text-gray-500"
                ></p>
            </div>

            <!-- Color Selection -->
            <!-- <div class="mb-6">
                <h3 class="text-sm font-semibold mb-3">Color: <span class="font-normal text-gray-600 ml-1">Charcoal Gray</span></h3>
                <div class="flex space-x-3">
                    <button class="w-8 h-8 rounded-full bg-gray-600 ring-2 ring-offset-2 ring-black focus:outline-none"></button>
                    <button class="w-8 h-8 rounded-full bg-gray-300 ring-1 ring-gray-300 hover:ring-2 hover:ring-offset-2 hover:ring-gray-400 focus:outline-none"></button>
                    <button class="w-8 h-8 rounded-full bg-stone-200 ring-1 ring-gray-300 hover:ring-2 hover:ring-offset-2 hover:ring-gray-400 focus:outline-none"></button>
                    <button class="w-8 h-8 rounded-full bg-black ring-1 ring-gray-300 hover:ring-2 hover:ring-offset-2 hover:ring-gray-400 focus:outline-none"></button>
                </div>
            </div> -->

            <!-- Varian Selection -->
            <div
                class="mb-8"
                x-show="variantTypes.length > 0"
                x-cloak
            >
                <template x-for="type in variantTypes" :key="type.id">
                    <div class="mb-5">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-sm font-semibold">
                                <span x-text="type.name"></span>:

                                <span
                                    x-text="getSelectedVariant(type)?.name ?? '-'"
                                    class="font-normal text-gray-600 ml-1"
                                ></span>
                            </h3>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <template
                                x-for="variant in type.variants"
                                :key="variant.id"
                            >
                                <button
                                    type="button"
                                    @click="selectVariant(type.id, variant.id)"
                                    :class="selectedVariants[type.id] == variant.id
                                        ? 'border-brand-dark bg-brand-dark text-white'
                                        : 'border-gray-300 hover:border-brand-dark hover:bg-gray-50'"
                                    class="border px-4 py-3 text-sm font-medium rounded transition-colors"
                                    x-text="variant.name"
                                ></button>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Action Buttons -->
            <div class="mb-8">
                <p
                    x-show="cartMessage"
                    x-text="cartMessage"
                    x-cloak
                    role="alert"
                    class="mb-3 text-sm font-medium text-rose-600"
                ></p>
                @if ($product)
                    <button
                        type="button"
                        @click="addToCart()"
                        class="flex w-full items-center justify-center gap-2 rounded bg-brand-dark px-8 py-4 font-semibold text-white transition-colors hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-brand-dark focus:ring-offset-2"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span>Tambah ke Keranjang</span>
                    </button>
                @endif
                <!-- <button class="w-14 h-14 border border-gray-300 rounded flex items-center justify-center hover:border-brand-dark hover:bg-gray-50 transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </button> -->
            </div>

            <!-- Trust Badges -->
            <div class="grid grid-cols-3 gap-4 border-t border-gray-200 pt-6">
                 <div class="flex flex-col items-center md:items-start text-center md:text-left">
                    <svg class="w-6 h-6 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span class="text-[10px] font-bold uppercase tracking-wider block">Free Shipping</span>
                    <span class="text-[9px] text-gray-500">On orders over $99</span>
                </div>
                <div class="flex flex-col items-center md:items-start text-center md:text-left">
                    <svg class="w-6 h-6 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span class="text-[10px] font-bold uppercase tracking-wider block">Easy Returns</span>
                    <span class="text-[9px] text-gray-500">30-day return policy</span>
                </div>
                 <div class="flex flex-col items-center md:items-start text-center md:text-left">
                    <svg class="w-6 h-6 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span class="text-[10px] font-bold uppercase tracking-wider block">Secure Payment</span>
                    <span class="text-[9px] text-gray-500">100% secure checkout</span>
                </div>
            </div>

        </div>
    </div>

    <!-- Tabs & Extra Details -->
    <div x-data="{activeTab: 'details', productImage: @js($product?->image_url ?? asset('produk/Lactona Ibu Cokelat.png'))}" class="mt-20 border-t border-gray-200 pt-10 grid grid-cols-1 md:grid-cols-2 gap-12">
        
        <!-- Left: Tabs and Info -->
        <div>
            <div class="flex space-x-8 border-b border-gray-200 mb-6">
                <button
                    @click="activeTab = 'details'"
                    :class="activeTab === 'details'
                        ? 'border-b-2 border-black font-semibold text-gray-900'
                        : 'border-b-2 border-transparent text-gray-500 hover:text-black font-medium'"
                    class="pb-2 text-sm transition-colors"
                >
                    Details
                </button>

                @if ($hasNutritionFacts)
                    <button
                        @click="activeTab = 'nutrision'"
                        :class="activeTab === 'nutrision'
                            ? 'border-b-2 border-black font-semibold text-gray-900'
                            : 'border-b-2 border-transparent text-gray-500 hover:text-black font-medium'"
                        class="pb-2 text-sm transition-colors"
                    >
                        Nutrition Facts
                    </button>
                @endif
            </div>

            <!-- Konten Tab 1: Details -->
            <div
                x-show="activeTab === 'details'"
                x-transition.opacity.duration.300ms
            >
                @if ($product?->description)
                    <div class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">
                        {{ $product->description }}
                    </div>
                @else
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Keterangan produk belum tersedia.
                    </p>
                @endif
            </div>

            <!-- Nutrition Facts tab -->
            <div x-show="activeTab === 'nutrision'" x-transition.opacity.duration.300ms style="display: none;">
                @if ($hasNutritionFacts)
                    <!-- Nutrition Facts tab -->
                    <div
                        x-show="activeTab === 'nutrision'"
                        x-transition.opacity.duration.300ms
                        style="display: none;"
                    >
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">
                        Berikut adalah detail informasi nilai gizi (Nutrition Facts) dari produk {{ $product?->name ?? 'ini' }} per takaran saji.
                    </p>

                    @php($nutritionFacts = $product?->nutrition_facts ?? [])

                    @if (empty($nutritionFacts))
                        <p class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm text-gray-500">
                            Nutrition Facts belum diisi untuk produk ini.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            @if (isset($nutritionFacts['groups']))
                                <div class="mb-4 grid grid-cols-1 gap-2 text-sm sm:grid-cols-2">
                                    <p><span class="font-semibold">Takaran Saji:</span> {{ $nutritionFacts['serving_size'] ?? '-' }}</p>
                                    <p><span class="font-semibold">Jumlah Sajian per Kemasan:</span> {{ $nutritionFacts['servings_per_package'] ?? '-' }}</p>
                                </div>

                                <div class="space-y-4">
                                    @foreach ($nutritionFacts['groups'] as $group)
                                        @php($columns = $group['columns'] ?? $nutritionFacts['columns'] ?? [])
                                        @php($useServing = $group['use_serving'] ?? true)
                                        <table class="min-w-full border-collapse border border-gray-200 text-sm text-gray-800">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th class="border border-gray-200 p-2 text-left">{{ $group['title'] }}</th>
                                                    @if ($useServing)
                                                        <th class="border border-gray-200 p-2 text-center">Per Saji</th>
                                                    @endif
                                                    @foreach ($columns as $column)
                                                        <th class="border border-gray-200 p-2 text-center">{{ $column['label'] }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                @foreach ($group['items'] as $item)
                                                    <tr>
                                                        <td class="border border-gray-200 p-2">{{ $item['name'] }}</td>
                                                        @if ($useServing)
                                                            <td class="border border-gray-200 p-2 text-center font-semibold">
                                                                {{ trim(($item['value'] ?? '') . ' ' . ($item['unit'] ?? '')) ?: '-' }}
                                                            </td>
                                                        @endif
                                                        @foreach ($columns as $columnIndex => $column)
                                                            <td class="border border-gray-200 p-2 text-center">
                                                                {{ $item['percentages'][$columnIndex] ?? '-' }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endforeach
                                </div>
                            @else
                                <table class="min-w-full border-collapse border border-gray-200 text-sm text-gray-800">
                                    <thead>
                                        <tr class="border-b border-gray-200 bg-gray-100">
                                            <th class="border-r border-gray-200 p-2 text-left" rowspan="2">Komponen Gizi</th>
                                            <th class="border-r border-gray-200 p-2 text-center" rowspan="2">Per Saji</th>
                                            <th class="p-2 text-center" colspan="2">% AKG</th>
                                        </tr>
                                        <tr class="border-b border-gray-200 bg-gray-50">
                                            <th class="border-r border-gray-200 p-2 text-center">Hamil</th>
                                            <th class="p-2 text-center">Menyusui</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($nutritionFactSections as $section)
                                            @if ($section['show_heading'])
                                                <tr class="bg-gray-50 font-semibold">
                                                    <td class="border-r border-gray-200 p-2" colspan="4">{{ $section['title'] }}</td>
                                                </tr>
                                            @endif

                                            @foreach ($section['rows'] as $row)
                                                <tr>
                                                    <td class="border-r border-gray-200 p-2">{{ $row['label'] }}</td>
                                                    @if ($row['single'])
                                                        <td class="p-2 font-semibold" colspan="3">{{ data_get($nutritionFacts, $row['key'] . '.per_serving') ?: '-' }}</td>
                                                    @else
                                                        <td class="border-r border-gray-200 p-2 text-center font-semibold">{{ data_get($nutritionFacts, $row['key'] . '.per_serving') ?: '-' }}</td>
                                                        <td class="border-r border-gray-200 p-2 text-center">{{ data_get($nutritionFacts, $row['key'] . '.pregnancy') ?: '-' }}</td>
                                                        <td class="p-2 text-center">{{ data_get($nutritionFacts, $row['key'] . '.lactation') ?: '-' }}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    @endif
                    </div>
                @endif
            </div>
        </div>
        <!-- Right: Detail Image -->
        <div class="h-64 md:rounded-lg overflow-hidden bg-gray-100 relative">
            <img
                :src="productImage"
                class="w-full h-full object-contain"
                alt="Foto produk"
            >
        </div>
    </div>

    {{--
    <!-- Related Products -->
    <div class="mt-24">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h2 class="text-xl font-bold tracking-tight">You May Also Like</h2>
            <a href="#" class="text-sm font-medium text-gray-600 hover:text-black hover:underline flex items-center">
                View All <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            
            <!-- Related 1 -->
            <div class="group cursor-pointer">
                <div class="bg-gray-100 rounded-lg overflow-hidden mb-3 relative aspect-[3/4]">
                    <img src="https://images.unsplash.com/photo-1572495641004-28421ae52e52?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500" alt="Related 1">
                    <button class="absolute bottom-3 right-3 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow hover:bg-gray-100 transition-colors opacity-0 group-hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button>
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-1">Minimal Hoodie</h4>
                        <p class="text-sm text-gray-500 font-medium">$54.99</p>
                    </div>
                </div>
            </div>

            <!-- Related 2 -->
            <div class="group cursor-pointer">
                <div class="bg-gray-100 rounded-lg overflow-hidden mb-3 relative aspect-[3/4]">
                    <img src="https://images.unsplash.com/photo-1614251052601-5264b3837920?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500" alt="Related 2">
                     <button class="absolute bottom-3 right-3 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow hover:bg-gray-100 transition-colors opacity-0 group-hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button>
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-1">Classic Sweatshirt</h4>
                        <p class="text-sm text-gray-500 font-medium">$49.99</p>
                    </div>
                </div>
            </div>

            <!-- Related 3 -->
            <div class="group cursor-pointer">
                <div class="bg-gray-100 rounded-lg overflow-hidden mb-3 relative aspect-[3/4]">
                    <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500" alt="Related 3">
                     <button class="absolute bottom-3 right-3 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow hover:bg-gray-100 transition-colors opacity-0 group-hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button>
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-1">Zip Up Hoodie</h4>
                        <p class="text-sm text-gray-500 font-medium">$64.99</p>
                    </div>
                </div>
            </div>

             <!-- Related 4 -->
             <div class="group cursor-pointer">
                <div class="bg-gray-100 rounded-lg overflow-hidden mb-3 relative aspect-[3/4]">
                    <img src="https://images.unsplash.com/photo-1556821840-3a63f95609a7?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500" alt="Related 4">
                     <button class="absolute bottom-3 right-3 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow hover:bg-gray-100 transition-colors opacity-0 group-hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button>
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-1">Essential Hoodie</h4>
                        <p class="text-sm text-gray-500 font-medium">$59.99</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    --}}
</div>
@endsection
