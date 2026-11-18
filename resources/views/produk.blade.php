@extends('layouts.app')

@section('content')
    <!-- Bagian Hero -->
    <section class="relative w-full h-[700px] bg-brand-teal flex items-center bg-cover bg-center" style="background-image: url('{{ asset('storage/images/hero-model.jpg') }}');">
        <!-- Overlay Gradasi -->
        <div class="absolute inset-0 bg-gradient-to-r from-brand-dark/90 via-brand-teal/60 to-transparent"></div>
        
        <div class="relative z-10 px-12 md:px-24 max-w-3xl text-white">
            <!-- <span class="text-sm tracking-[0.2em] uppercase opacity-90 mb-4 block">Kualitas</span> -->
            <h2 class="w-full text-6xl font-serif mb-6">
                Memenuhi nutrisi keluarga, mulai dari ibu hamil hingga lansia  
            </h2>
            <p class="text-sm opacity-90 mb-10 max-w-md leading-relaxed">
                siap membantu pemenuhan gizi dan nutrisi keluarga, mulai dari lactona ibu untuk ibu hamil, hingga prolansa untuk lansia.
            </p>
            
            <div class="flex items-center space-x-6">
                <a href="#produk-kami" class="bg-brand-dark hover:bg-brand-teal border border-brand-teal transition-colors text-white px-8 py-3 rounded-full text-sm font-bold tracking-wider flex items-center">
                    Produk kami<span class="ml-2">&rarr;</span>
                </a>
                <!-- <button class="flex items-center space-x-2 hover:opacity-80 transition-opacity text-sm font-medium">
                    <span class="w-8 h-8 rounded-full border border-white flex items-center justify-center">
                        <svg class="w-3 h-3 fill-current ml-1" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </span>
                    <span>WATCH VIDEO</span>
                </button> -->
            </div>
        </div>
    </section>

    {{-- @php
    <!-- Kategori -->
    <section class="max-w-6xl mx-auto px-8 py-20 text-center">
        <div class="flex items-center justify-center mb-10">
            <div class="h-px bg-gray-200 w-16"></div>
            <h3 class="text-xs font-bold tracking-[0.2em] text-gray-500 mx-4 uppercase">Shop by Category</h3>
            <div class="h-px bg-gray-200 w-16"></div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-6 gap-6">
            <!-- Kategori 1: Serums -->
            <div class="flex flex-col items-center cursor-pointer group">
                <div class="w-28 h-28 rounded-full bg-brand-light flex items-center justify-center mb-4 overflow-hidden relative group-hover:shadow-md transition-shadow">
                    <!-- Ganti warna kotak dengan gambar produk kategori sesungguhnya -->
                    <div class="w-16 h-20 bg-brand-dark/20 rounded"></div>
                </div>
                <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Serums</h4>
                <p class="text-xs text-brand-teal mt-1 group-hover:underline">Explore</p>
            </div>
            <!-- Kategori 2: Moisturizers -->
            <div class="flex flex-col items-center cursor-pointer group">
                <div class="w-28 h-28 rounded-full bg-brand-light flex items-center justify-center mb-4 overflow-hidden relative group-hover:shadow-md transition-shadow">
                    <div class="w-20 h-16 bg-brand-dark/30 rounded"></div>
                </div>
                <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Moisturizers</h4>
                <p class="text-xs text-brand-teal mt-1 group-hover:underline">Explore</p>
            </div>
             <!-- Kategori 3: Cleansers -->
             <div class="flex flex-col items-center cursor-pointer group">
                <div class="w-28 h-28 rounded-full bg-brand-light flex items-center justify-center mb-4 overflow-hidden relative group-hover:shadow-md transition-shadow">
                    <div class="w-12 h-24 bg-brand-dark/25 rounded"></div>
                </div>
                <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cleansers</h4>
                <p class="text-xs text-brand-teal mt-1 group-hover:underline">Explore</p>
            </div>
            <!-- Kategori 4: Toners -->
            <div class="flex flex-col items-center cursor-pointer group">
                <div class="w-28 h-28 rounded-full bg-brand-light flex items-center justify-center mb-4 overflow-hidden relative group-hover:shadow-md transition-shadow">
                    <div class="w-14 h-22 bg-brand-dark/40 rounded"></div>
                </div>
                <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Toners</h4>
                <p class="text-xs text-brand-teal mt-1 group-hover:underline">Explore</p>
            </div>
            <!-- Kategori 5: Eye Care -->
            <div class="flex flex-col items-center cursor-pointer group">
                <div class="w-28 h-28 rounded-full bg-brand-light flex items-center justify-center mb-4 overflow-hidden relative group-hover:shadow-md transition-shadow">
                    <div class="w-16 h-12 bg-brand-dark/15 rounded"></div>
                </div>
                <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Eye Care</h4>
                <p class="text-xs text-brand-teal mt-1 group-hover:underline">Explore</p>
            </div>
            <!-- Kategori 6: Sun Care -->
            <div class="flex flex-col items-center cursor-pointer group">
                <div class="w-28 h-28 rounded-full bg-orange-50 flex items-center justify-center mb-4 overflow-hidden relative group-hover:shadow-md transition-shadow">
                    <div class="w-12 h-20 bg-orange-200 rounded"></div>
                </div>
                <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Sun Care</h4>
                <p class="text-xs text-brand-teal mt-1 group-hover:underline">Explore</p>
            </div>
        </div>
    </section>
    @endphp --}}

    <!-- Our Products -->
    <section id="produk-kami" class="max-w-6xl mx-auto px-8 py-20">
        <div class="flex justify-center items-center mb-8 border-b border-gray-200 pb-4">
            <h3 class="text-xl font-serif font-bold text-gray-800 uppercase tracking-wider">Our Products</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Kartu Produk 1 -->
            <div class="group">
                <div class="bg-brand-light relative aspect-[4/5] flex items-center justify-center p-6 mb-4 overflow-hidden">
                    <!-- Label Status -->
                    <span class="absolute top-4 left-4 bg-brand-dark text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider z-10">Best Seller</span>
                    <!-- Gambar Produk Dummy -->
                    <img src="{{ asset('produk/Lactona Skim.png') }}" alt="lactona skim">
                </div>
                <h4 class="text-sm font-medium text-gray-800 mb-1 leading-snug">Lactona Skim</h4>
                <!-- <div class="flex items-center space-x-1 mb-2">
                    <span class="text-yellow-400 text-xs">★★★★★</span>
                    <span class="text-xs text-gray-400">(245)</span>
                </div> -->
                <p class="text-lg font-bold text-gray-900 mb-4">Rp. 34.00</p>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-brand-dark hover:bg-brand-teal text-white text-xs font-bold py-2.5 rounded transition-colors uppercase tracking-wide">Lihat Detail &gt;</button>
                    <!-- <button class="w-10 h-10 border border-gray-200 rounded flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button> -->
                </div>
            </div>

            <!-- Kartu Produk 2 -->
             <div class="group">
                <div class="bg-gray-100 relative aspect-[4/5] flex items-center justify-center p-6 mb-4 overflow-hidden">
                    <span class="absolute top-4 left-4 bg-brand-teal text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider z-10">New</span>
                    <img src="{{ asset('produk/Lactona Ibu Cokelat.png') }}" alt="lactona ibu">
                </div>
                <h4 class="text-sm font-medium text-gray-800 mb-1 leading-snug">Lactona Ibu</h4>
                <!-- <div class="flex items-center space-x-1 mb-2">
                    <span class="text-yellow-400 text-xs">★★★★☆</span>
                    <span class="text-xs text-gray-400">(189)</span>
                </div> -->
                <p class="text-lg font-bold text-gray-900 mb-4">Rp. 42.00</p>
                <div class="flex space-x-2">
                    <a href="{{ url('/detail')}}" class="text-center flex-1 bg-brand-dark hover:bg-brand-teal text-white text-xs font-bold py-2.5 rounded transition-colors uppercase tracking-wide">Lihat Detail &gt;</a>
                    <!-- <button class="w-10 h-10 border border-gray-200 rounded flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button> -->
                </div>
            </div>

            <!-- Kartu Produk 3 -->
            <div class="group">
                <div class="bg-brand-light relative aspect-[4/5] flex items-center justify-center p-6 mb-4 overflow-hidden">
                    <span class="absolute top-4 left-4 bg-brand-accent text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider z-10">Limited</span>
                    <img src="{{ asset('produk/Lactona Gold 3.png') }}" alt="lactona gold 3+">
                </div>
                <h4 class="text-sm font-medium text-gray-800 mb-1 leading-snug">Lactona Gold 3+</h4>
                <!-- <div class="flex items-center space-x-1 mb-2">
                    <span class="text-yellow-400 text-xs">★★★★★</span>
                    <span class="text-xs text-gray-400">(312)</span>
                </div> -->
                <p class="text-lg font-bold text-gray-900 mb-4">Rp. 38.00</p>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-brand-dark hover:bg-brand-teal text-white text-xs font-bold py-2.5 rounded transition-colors uppercase tracking-wide">Lihat Detail &gt;</button>
                    <!-- <button class="w-10 h-10 border border-gray-200 rounded flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button> -->
                </div>
            </div>

            <!-- Kartu Produk 4 (Dengan Harga Diskon) -->
            <div class="group">
                <div class="bg-gray-100 relative aspect-[4/5] flex items-center justify-center p-6 mb-4 overflow-hidden">
                    <span class="absolute top-4 left-4 bg-teal-600 text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider z-10">Sale</span>
                    <img src="{{ asset('produk/Lactona Gold 3.png') }}" alt="lactona gold 3+">
                </div>
                <h4 class="text-sm font-medium text-gray-800 mb-1 leading-snug">Lactona 1+</h4>
                <!-- <div class="flex items-center space-x-1 mb-2">
                    <span class="text-yellow-400 text-xs">★★★★☆</span>
                    <span class="text-xs text-gray-400">(156)</span>
                </div> -->
                <div class="flex items-end space-x-2 mb-4">
                    <p class="text-lg font-bold text-gray-900">Rp. 24.00</p>
                    <p class="text-xs text-gray-400 line-through pb-1">Rp. 30.00</p>
                </div>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-brand-dark hover:bg-brand-teal text-white text-xs font-bold py-2.5 rounded transition-colors uppercase tracking-wide">Lihat Detail &gt;</button>
                    <!-- <button class="w-10 h-10 border border-gray-200 rounded flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button> -->
                </div>
            </div>

            <!-- Kartu Produk 1 -->
            <div class="group">
                <div class="bg-brand-light relative aspect-[4/5] flex items-center justify-center p-6 mb-4 overflow-hidden">
                    <!-- Label Status -->
                    <span class="absolute top-4 left-4 bg-brand-dark text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider z-10">Best Seller</span>
                    <!-- Gambar Produk Dummy -->
                    <img src="{{ asset('produk/Lactona Skim.png') }}" alt="lactona skim">
                </div>
                <h4 class="text-sm font-medium text-gray-800 mb-1 leading-snug">Lactona Skim</h4>
                <!-- <div class="flex items-center space-x-1 mb-2">
                    <span class="text-yellow-400 text-xs">★★★★★</span>
                    <span class="text-xs text-gray-400">(245)</span>
                </div> -->
                <p class="text-lg font-bold text-gray-900 mb-4">Rp. 34.00</p>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-brand-dark hover:bg-brand-teal text-white text-xs font-bold py-2.5 rounded transition-colors uppercase tracking-wide">Lihat Detail &gt;</button>
                    <!-- <button class="w-10 h-10 border border-gray-200 rounded flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button> -->
                </div>
            </div>

            <!-- Kartu Produk 2 -->
             <div class="group">
                <div class="bg-gray-100 relative aspect-[4/5] flex items-center justify-center p-6 mb-4 overflow-hidden">
                    <span class="absolute top-4 left-4 bg-brand-teal text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider z-10">New</span>
                    <img src="{{ asset('produk/Lactona Ibu Cokelat.png') }}" alt="lactona ibu">
                </div>
                <h4 class="text-sm font-medium text-gray-800 mb-1 leading-snug">Lactona Ibu</h4>
                <!-- <div class="flex items-center space-x-1 mb-2">
                    <span class="text-yellow-400 text-xs">★★★★☆</span>
                    <span class="text-xs text-gray-400">(189)</span>
                </div> -->
                <p class="text-lg font-bold text-gray-900 mb-4">Rp. 42.00</p>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-brand-dark hover:bg-brand-teal text-white text-xs font-bold py-2.5 rounded transition-colors uppercase tracking-wide">Lihat Detail &gt;</button>
                    <!-- <button class="w-10 h-10 border border-gray-200 rounded flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button> -->
                </div>
            </div>

            <!-- Kartu Produk 3 -->
            <div class="group">
                <div class="bg-brand-light relative aspect-[4/5] flex items-center justify-center p-6 mb-4 overflow-hidden">
                    <span class="absolute top-4 left-4 bg-brand-accent text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider z-10">Limited</span>
                    <img src="{{ asset('produk/Lactona Gold 3.png') }}" alt="lactona gold 3+">
                </div>
                <h4 class="text-sm font-medium text-gray-800 mb-1 leading-snug">Lactona Gold 3+</h4>
                <!-- <div class="flex items-center space-x-1 mb-2">
                    <span class="text-yellow-400 text-xs">★★★★★</span>
                    <span class="text-xs text-gray-400">(312)</span>
                </div> -->
                <p class="text-lg font-bold text-gray-900 mb-4">Rp. 38.00</p>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-brand-dark hover:bg-brand-teal text-white text-xs font-bold py-2.5 rounded transition-colors uppercase tracking-wide">Lihat Detail &gt;</button>
                    <!-- <button class="w-10 h-10 border border-gray-200 rounded flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button> -->
                </div>
            </div>

            <!-- Kartu Produk 4 (Dengan Harga Diskon) -->
            <div class="group">
                <div class="bg-gray-100 relative aspect-[4/5] flex items-center justify-center p-6 mb-4 overflow-hidden">
                    <span class="absolute top-4 left-4 bg-teal-600 text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider z-10">Sale</span>
                    <img src="{{ asset('produk/Lactona Gold 3.png') }}" alt="lactona gold 3+">
                </div>
                <h4 class="text-sm font-medium text-gray-800 mb-1 leading-snug">Lactona 1+</h4>
                <!-- <div class="flex items-center space-x-1 mb-2">
                    <span class="text-yellow-400 text-xs">★★★★☆</span>
                    <span class="text-xs text-gray-400">(156)</span>
                </div> -->
                <div class="flex items-end space-x-2 mb-4">
                    <p class="text-lg font-bold text-gray-900">Rp. 24.00</p>
                    <p class="text-xs text-gray-400 line-through pb-1">Rp. 30.00</p>
                </div>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-brand-dark hover:bg-brand-teal text-white text-xs font-bold py-2.5 rounded transition-colors uppercase tracking-wide">Lihat Detail &gt;</button>
                    <!-- <button class="w-10 h-10 border border-gray-200 rounded flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button> -->
                </div>
            </div>
        </div>
    </section>
@endsection