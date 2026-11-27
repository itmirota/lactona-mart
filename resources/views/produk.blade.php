@extends('layouts.storefront')

@section('content')
@php
    $heroImage = asset('storage/images/hero-model.jpg');
@endphp

    <section
        class="relative w-full h-[700px] bg-brand-teal flex items-center bg-cover bg-center"
        style="background-image: url('{{ $heroImage }}');"
    >
        <!-- Overlay Gradasi -->
        <div class="absolute inset-0 bg-gradient-to-r from-brand-dark/90 via-brand-teal/60 to-transparent"></div>
        
        <div class="relative z-10 px-12 md:px-24 max-w-3xl text-white">
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
            </div>
        </div>
    </section>

    <!-- Our Products -->
    <section id="produk-kami" class="max-w-6xl mx-auto px-8 py-20">
        <div class="flex justify-center items-center mb-8 border-b border-gray-200 pb-4">
            <h3 class="text-xl font-serif font-bold text-gray-800 uppercase tracking-wider">Our Products</h3>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            @forelse ($products as $product)

                <div class="group">

                    {{-- Gambar Produk --}}
                    <div class="{{ $loop->even ? 'bg-gray-100' : 'bg-brand-light' }} relative aspect-[4/5] flex items-center justify-center p-6 mb-4 overflow-hidden">

                        @if ($product->image)
                            <img
                                src="{{ $product->image_url }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105"
                            >
                        @else
                            <span class="text-sm text-gray-400">
                                Gambar belum tersedia
                            </span>
                        @endif

                    </div>

                    {{-- Nama Produk --}}
                    <h4 class="text-sm font-medium text-gray-800 mb-1 leading-snug">
                        {{ $product->name }}
                    </h4>

                    {{-- Harga --}}
                    <div class="mb-4 min-h-12">
                        @if ($product->currentDiscount)
                            <span class="mb-1 inline-flex rounded-full bg-rose-50 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-rose-700">
                                {{ $product->currentDiscount->promotion_name }}
                            </span>
                            <div class="flex flex-wrap items-baseline gap-x-2">
                                <span class="text-xs font-medium text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="text-lg font-bold text-rose-700">Rp {{ number_format($product->discounted_price, 0, ',', '.') }}</span>
                            </div>
                        @else
                            <p class="text-lg font-bold text-gray-900">
                                Rp. {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        @endif
                    </div>

                    {{-- Detail Produk --}}
                    <div class="flex space-x-2">
                        <a
                            href="{{ route('detail', $product) }}"
                            class="text-center flex-1 bg-brand-dark hover:bg-brand-teal text-white text-xs font-bold py-2.5 rounded transition-colors uppercase tracking-wide"
                        >
                            Lihat Detail &gt;
                        </a>
                    </div>

                </div>

            @empty

                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">
                        Belum ada produk yang tersedia.
                    </p>
                </div>

            @endforelse

        </div>

        @if(isset($products) && method_exists($products, 'links'))
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @endif
    </section>
@endsection
