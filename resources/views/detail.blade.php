@extends('layouts.app')

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
        mainImage: '{{ asset('produk/Lactona Ibu Cokelat.png') }}', 
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
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-gray-900 mb-2 leading-tight">Lactona Ibu</h1>
            
            <!-- <div class="flex items-center space-x-2 mb-6">
                <div class="flex text-black text-sm">
                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                </div>
                <span class="text-sm font-medium">4.8</span>
                <span class="text-sm text-gray-500 underline cursor-pointer">(128 reviews)</span>
            </div> -->

            <!-- Price -->
            <div class="flex items-end space-x-4 mb-6">
                <span class="text-3xl font-bold text-gray-900">Rp. 59.99</span>
                <span class="text-lg text-gray-400 line-through mb-1">Rp. 89.99</span>
                <!-- <span class="bg-black text-white text-xs font-bold px-2 py-1 rounded mb-1.5">33% OFF</span> -->
            </div>

            <p class="text-gray-600 text-sm leading-relaxed mb-8">
                susu yang diformulasikan khusus untuk bantu penuhi kebutuhan nutrisi ibu yang meningkat selama masa kehamilan.
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
                    Nutrision Fact
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

            <!-- Konten Tab 2: Nutrision Fact -->
            <!-- x-show memastikan bagian ini hanya tampil jika activeTab adalah 'nutrision' -->
            <div x-show="activeTab === 'nutrision'" 
                x-transition.opacity.duration.300ms 
                style="display: none;"> <!-- style="display: none;" mencegah kedipan awal -->
                <p class="text-gray-600 text-sm leading-relaxed mb-6">
                    <!-- Masukkan teks Informasi Nilai Gizi di sini -->
                    Berikut adalah detail informasi nilai gizi (Nutrition Facts) dari produk Lactona Ibu per takaran saji.
                </p>
                <!-- Anda bisa menambahkan struktur tabel HTML atau daftar (list) untuk merinci kandungan gizinya di sini -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-gray-800 border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-200">
                                <th class="p-2 text-left border-r border-gray-200" rowspan="2">Komponen Gizi</th>
                                <th class="p-2 text-center border-r border-gray-200" rowspan="2">Per Saji</th>
                                <th class="p-2 text-center" colspan="2">% AKG</th>
                            </tr>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="p-2 text-center border-r border-gray-200">Hamil</th>
                                <th class="p-2 text-center">Menyusui</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="p-2 border-r border-gray-200">Takaran Saji</td>
                                <td class="p-2 font-semibold" colspan="3">3 sendok makan penuh (40g)[cite: 1]</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Jumlah Sajian per Kemasan</td>
                                <td class="p-2 font-semibold" colspan="3">± 5[cite: 1]</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Energi Total</td>
                                <td class="p-2 font-semibold" colspan="3">170 kkal</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Energi dari Lemak</td>
                                <td class="p-2 font-semibold" colspan="3">35 kkal</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Energi dari Lemak Jenuh</td>
                                <td class="p-2 font-semibold" colspan="3">15 kkal</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Lemak Total</td>
                                <td class="p-2 text-center border-r border-gray-200 font-semibold">4 g</td>
                                <td class="p-2 text-center border-r border-gray-200">5%</td>
                                <td class="p-2 text-center">4%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Lemak Trans</td>
                                <td class="p-2 text-center border-r border-gray-200 font-semibold">0 g</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center">-</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Kolesterol</td>
                                <td class="p-2 text-center border-r border-gray-200 font-semibold">5 mg</td>
                                <td class="p-2 text-center border-r border-gray-200">2%</td>
                                <td class="p-2 text-center">2%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Lemak Jenuh</td>
                                <td class="p-2 text-center border-r border-gray-200 font-semibold">2 g</td>
                                <td class="p-2 text-center border-r border-gray-200">9%</td>
                                <td class="p-2 text-center">9%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Protein</td>
                                <td class="p-2 text-center border-r border-gray-200 font-semibold">8 g</td>
                                <td class="p-2 text-center border-r border-gray-200">11%</td>
                                <td class="p-2 text-center">11%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Karbohidrat Total</td>
                                <td class="p-2 text-center border-r border-gray-200 font-semibold">24 g</td>
                                <td class="p-2 text-center border-r border-gray-200">7%</td>
                                <td class="p-2 text-center">7%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Serat Pangan</td>
                                <td class="p-2 text-center border-r border-gray-200 font-semibold">3 g</td>
                                <td class="p-2 text-center border-r border-gray-200">10%</td>
                                <td class="p-2 text-center">9%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Serat Pangan Larut</td>
                                <td class="p-2 text-center border-r border-gray-200 font-semibold">2 g</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center">-</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Gula Total</td>
                                <td class="p-2 text-center border-r border-gray-200 font-semibold">18 g</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center">-</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Sukrosa</td>
                                <td class="p-2 text-center border-r border-gray-200 font-semibold">8 g</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center">-</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Natrium</td>
                                <td class="p-2 text-center border-r border-gray-200 font-semibold">75 mg</td>
                                <td class="p-2 text-center border-r border-gray-200">5%</td>
                                <td class="p-2 text-center">5%</td>
                            </tr>
                            <!-- Vitamin dan Mineral -->
                            <tr class="bg-gray-50 font-semibold">
                                <td class="p-2 border-r border-gray-200" colspan="4">Vitamin dan Mineral</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Vitamin A</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">20%</td>
                                <td class="p-2 text-center">20%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Vitamin D3</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">10%</td>
                                <td class="p-2 text-center">10%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Vitamin E</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">25%</td>
                                <td class="p-2 text-center">20%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Vitamin K</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">6%</td>
                                <td class="p-2 text-center">6%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Vitamin B1</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">30%</td>
                                <td class="p-2 text-center">30%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Vitamin B2</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">25%</td>
                                <td class="p-2 text-center">25%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Vitamin B3</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">30%</td>
                                <td class="p-2 text-center">30%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Vitamin B5</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">25%</td>
                                <td class="p-2 text-center">20%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Vitamin B6</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">35%</td>
                                <td class="p-2 text-center">35%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Vitamin B9</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">35%</td>
                                <td class="p-2 text-center">40%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Vitamin B12</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">20%</td>
                                <td class="p-2 text-center">20%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Vitamin C</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">35%</td>
                                <td class="p-2 text-center">30%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Kolin</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">10%</td>
                                <td class="p-2 text-center">10%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Biotin</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">80%</td>
                                <td class="p-2 text-center">70%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Kalium</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">6%</td>
                                <td class="p-2 text-center">6%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Kalsium</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">25%</td>
                                <td class="p-2 text-center">25%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Fosfor</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">30%</td>
                                <td class="p-2 text-center">30%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Magnesium</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">15%</td>
                                <td class="p-2 text-center">20%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Zink</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">15%</td>
                                <td class="p-2 text-center">15%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Zat Besi</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">35%</td>
                                <td class="p-2 text-center">40%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Mangan</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">20%</td>
                                <td class="p-2 text-center">15%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Iodium</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">30%</td>
                                <td class="p-2 text-center">25%</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Selenium</td>
                                <td class="p-2 text-center border-r border-gray-200">-</td>
                                <td class="p-2 text-center border-r border-gray-200">35%</td>
                                <td class="p-2 text-center">30%</td>
                            </tr>
                            <!-- Komponen Lain -->
                            <tr class="bg-gray-50 font-semibold">
                                <td class="p-2 border-r border-gray-200" colspan="4">Komponen Lain</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">DHA</td>
                                <td class="p-2 font-semibold" colspan="3">11 mg</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Omega 3</td>
                                <td class="p-2 font-semibold" colspan="3">55,8 mg</td>
                            </tr>
                            <tr>
                                <td class="p-2 border-r border-gray-200">Omega 6</td>
                                <td class="p-2 font-semibold" colspan="3">629,4 mg</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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