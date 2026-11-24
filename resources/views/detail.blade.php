@extends('layouts.storefront')

@section('content')
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
    <div x-data="{ 
        mainImage: '{{ isset($product) && $product ? $product->image_url : asset('produk/Lactona Ibu Cokelat.png') }}', 
        selectedVarian: 'Cokelat',
        isZoomed: false 
        }"
        class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-16">
        
        <!-- Left Column: Product Images -->
        <!-- Left Column: Product Images -->
        <!-- Inisialisasi Alpine.js state 'mainImage' dengan gambar default pertama -->
        <div class="md:col-span-7 flex flex-col-reverse md:flex-row gap-4">
            
            <!-- Thumbnails -->
            <div class="flex md:flex-col gap-4 overflow-x-auto md:w-20 shrink-0">
                <!-- Thumbnail 1 -->
                <!-- x-on:click memperbarui nilai 'mainImage' sesuai dengan gambar ini -->
                <!-- x-bind:class memberikan efek visual (border hitam tebal) jika gambar ini sedang aktif -->
                <button @click="mainImage = '{{ asset('produk/Lactona Ibu Cokelat.png') }}'"
                        :class="{ 'border-black opacity-100': mainImage === '{{ asset('produk/Lactona Ibu Cokelat.png') }}', 'border-gray-200 opacity-70 hover:opacity-100': mainImage !== '/produk/lactona ibu cokelat.png' }"
                        class="w-16 h-20 md:w-full md:h-24 bg-gray-100 rounded border-2 overflow-hidden relative shrink-0 transition-opacity">
                     <!-- Perhatikan: src thumbnail bisa versi resolusi kecil, tapi URL di @click harus resolusi besar -->
                     <img src="{{ asset('produk/Lactona Ibu Cokelat.png') }}" class="w-full h-full object-cover" alt="Thumb 1">
                </button>

                <!-- Thumbnail 2 -->
                <button @click="mainImage = '{{ asset('produk/Lactona Ibu Stroberi.png') }}'"
                        :class="{ 'border-black opacity-100': mainImage === '{{ asset('produk/Lactona Ibu Stroberi.png') }}', 'border-gray-200 opacity-70 hover:opacity-100': mainImage !== '/produk/lactona ibu stroberi.png' }"
                        class="w-16 h-20 md:w-full md:h-24 bg-gray-100 rounded border-2 overflow-hidden relative shrink-0 transition-opacity">
                     <img src="{{ asset('produk/Lactona Ibu Stroberi.png') }}" class="w-full h-full object-cover" alt="Thumb 2">
                </button>

                <!-- Thumbnail 3 -->
                <button @click="mainImage = '{{ asset('produk/Lactona Ibu Vanila.png') }}'"
                        :class="{ 'border-black opacity-100': mainImage === '{{ asset('produk/Lactona Ibu Vanila.png') }}', 'border-gray-200 opacity-70 hover:opacity-100': mainImage !== '/produk/lactona ibu vanila.png' }"
                        class="w-16 h-20 md:w-full md:h-24 bg-gray-100 rounded border-2 overflow-hidden relative shrink-0 transition-opacity">
                    <img src="{{ asset('produk/Lactona Ibu Vanila.png') }}" class="w-full h-full object-cover" alt="Thumb 3">
                </button>

                <button class="w-16 h-20 md:w-full h-10 bg-gray-50 flex items-center justify-center rounded border border-gray-200 hover:bg-gray-100 shrink-0">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>

            <!-- Main Image Area -->
            <!-- Inisialisasi state isZoomed = false pada pembungkus utama jika belum ada -->
            <div class="flex-grow bg-gray-50 rounded-xl relative group">
                
                <!-- Elemen gambar utama -->
                <div class="relative overflow-hidden cursor-zoom-in" @click="isZoomed = true">
                    <img :src="mainImage" 
                        x-transition.opacity.duration.300ms
                        class="w-full h-full object-cover min-h-[400px] md:min-h-[600px]" alt="Main Product">
                    
                    <!-- Ikon kaca pembesar (akan muncul saat hover) -->
                    <button class="absolute bottom-6 right-6 bg-white p-3 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                        <!-- Ikon Zoom In -->
                        <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal/Overlay untuk Zoom In -->
                <!-- Hanya tampil saat isZoomed bernilai true -->
                <div x-show="isZoomed" 
                    x-transition.opacity 
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm"
                    style="display: none;">
                    
                    <!-- Tombol Close (X) -->
                    <button @click="isZoomed = false" class="absolute top-8 right-8 text-white hover:text-gray-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <!-- Gambar yang diperbesar -->
                    <!-- Menggunakan @click.away agar modal tertutup jika mengklik di luar gambar -->
                    <img :src="mainImage" 
                        @click.away="isZoomed = false"
                        class="max-w-[90vw] max-h-[90vh] object-contain cursor-zoom-out" 
                        alt="Zoomed Product">
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
                @if ($product?->currentDiscount)
                    <span class="mb-2 inline-flex rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-rose-700">
                        {{ $product->currentDiscount->promotion_name }}
                    </span>
                    <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                        <span class="text-base font-medium text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <span class="text-3xl font-bold text-rose-700">Rp {{ number_format($product->discounted_price, 0, ',', '.') }}</span>
                    </div>
                    @if ($product->currentDiscount->description)
                        <p class="mt-2 max-w-xl text-sm leading-relaxed text-gray-500">{{ $product->currentDiscount->description }}</p>
                    @endif
                @else
                    <span class="text-3xl font-bold text-gray-900">Rp {{ number_format($product?->price ?? 0, 0, ',', '.') }}</span>
                @endif
            </div>

            <p class="text-gray-600 text-sm leading-relaxed mb-8">
                {{ $product?->description ?? 'Deskripsi produk belum tersedia.' }}
            </p>

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
            <div class="mb-8">
                
                <div class="flex justify-between items-center mb-3">
                    <!-- x-text akan otomatis mengganti tulisan di dalam span sesuai tombol yang diklik -->
                    <h3 class="text-sm font-semibold">Size: <span x-text="selectedVarian" class="font-normal text-gray-600 ml-1"></span></h3>
                    <!-- <a href="#" class="text-sm text-gray-500 underline flex items-center hover:text-black">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>
                        Size Guide
                    </a> -->
                </div>

                <div class="grid grid-cols-5 gap-3">
                    <!-- Tombol S -->
                    <!-- @click mengubah nilai selectedVarian -->
                    <!-- :class memberikan warna hitam (aktif) jika state cocok, dan abu-abu (tidak aktif) jika tidak cocok -->
                    <button @click="mainImage = '{{ asset('produk/Lactona Ibu Cokelat.png') }}'; selectedVarian = 'Cokelat'"
                            :class="selectedVarian === 'Cokelat' ? 'border-brand-dark bg-brand-dark text-white' : 'border-gray-300 hover:border-brand-dark hover:bg-gray-50'"
                            class="border py-3 text-sm font-medium rounded transition-colors">
                        Cokelat
                    </button>

                    <!-- Tombol M -->
                    <button @click="mainImage = '{{ asset('produk/Lactona Ibu Stroberi.png') }}'; selectedVarian = 'Stroberi'"
                            :class="selectedVarian === 'Stroberi' ? 'border-brand-dark bg-brand-dark text-white' : 'border-gray-300 hover:border-brand-dark hover:bg-gray-50'"
                            class="border py-3 text-sm font-medium rounded transition-colors">
                        Stroberi
                    </button>

                    <!-- Tombol L -->
                    <button @click="mainImage = '{{ asset('produk/Lactona Ibu Vanila.png') }}'; selectedVarian = 'Vanila'"
                            :class="selectedVarian === 'Vanila' ? 'border-brand-dark bg-brand-dark text-white' : 'border-gray-300 hover:border-brand-dark hover:bg-gray-50'"
                            class="border py-3 text-sm font-medium rounded transition-colors">
                        Vanila
                    </button>

                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-4 mb-8">
            <a :href="`https://wa.me/08993932789?text=Halo MinTona, aku ingin membeli produk lactona ibu rasa ${selectedVarian}`" 
            target="_blank" 
            class="flex-grow bg-brand-dark hover:bg-gray-800 text-white font-semibold py-4 px-8 rounded flex justify-center items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <span>Beli Sekarang</span>
            </a>
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
    <div x-data="{activeTab: 'details'}" class="mt-20 border-t border-gray-200 pt-10 grid grid-cols-1 md:grid-cols-2 gap-12">
        
        <!-- Left: Tabs and Info -->
        <div>
            <div class="flex space-x-8 border-b border-gray-200 mb-6">
                <button @click="activeTab = 'details'"
                    :class="activeTab === 'details' ? 'border-b-2 border-black font-semibold text-gray-900' : 'border-b-2 border-transparent text-gray-500 hover:text-black font-medium'"
                    class="pb-2 text-sm transition-colors">
                    Details
                </button>
                <button @click="activeTab = 'nutrision'"
                    :class="activeTab === 'nutrision' ? 'border-b-2 border-black font-semibold text-gray-900' : 'border-b-2 border-transparent text-gray-500 hover:text-black font-medium'"
                    class="pb-2 text-sm transition-colors">
                    Nutrition Facts
                </button>
            </div>

            <!-- Konten Tab 1: Details -->
            <!-- x-show memastikan bagian ini hanya tampil jika activeTab adalah 'details' -->
            <div x-show="activeTab === 'details'" 
                x-transition.opacity.duration.300ms>
                <p class="text-gray-600 text-sm leading-relaxed mb-6">
                    Lactona Ibu mengandung Protein yang sangat dibutuhkan bagi nutrisi ibu menyusui. Per sajian Lactona Ibu mengandung 7g yang akan membantu memenuhi kebutuhan protein ibu hamil.
                </p>
                <ul class="space-y-3 text-sm text-gray-800">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Protein</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Tinggi Kalsium</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Tinggi Zat Besi</span>
                    </li>
                </ul>
            </div>

            <!-- Nutrition Facts tab -->
            <div x-show="activeTab === 'nutrision'" x-transition.opacity.duration.300ms style="display: none;">
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
                                    <table class="min-w-full border-collapse border border-gray-200 text-sm text-gray-800">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border border-gray-200 p-2 text-left">{{ $group['title'] }}</th>
                                                <th class="border border-gray-200 p-2 text-center">Per Saji</th>
                                                @foreach ($columns as $column)
                                                    <th class="border border-gray-200 p-2 text-center">{{ $column['label'] }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach ($group['items'] as $item)
                                                <tr>
                                                    <td class="border border-gray-200 p-2">{{ $item['name'] }}</td>
                                                    <td class="border border-gray-200 p-2 text-center font-semibold">
                                                        {{ trim(($item['value'] ?? '') . ' ' . ($item['unit'] ?? '')) ?: '-' }}
                                                    </td>
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
        </div>
        <!-- Right: Detail Image -->
        <div class="h-64 md:rounded-lg overflow-hidden bg-gray-100 relative">
             <img src="{{ asset('produk/Lactona Ibu Cokelat.png') }}" class="w-full h-full object-cover" alt="Detail texture">
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
