<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lactona Mart</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('lactona-logo.png') }}">
    <!-- Memuat Tailwind CSS melalui Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-800 antialiased bg-gray-50 relative">
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
    <nav class="bg-brand-dark sticky top-0 z-50 text-white py-5 transition-all duration-300" x-data="{ open: false }">
        <div class=" max-w-7xl mx-auto flex items-center justify-between">
            
            <!-- Bagian Kiri: Logo -->
            <div class="flex items-center space-x-2">
                <div class="w-30 h-9 flex items-center justify-center">
                    <img class="w-20" src="{{ asset('lactona-logo.png') }}" alt="" srcset="">
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-wide leading-tight">Lactona Mart</h1>
                    <span class="text-[10px] tracking-widest uppercase opacity-80">Tumbuh bersama lactona</span>
                </div>
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
                <button class="hover:opacity-80 relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span class="absolute -top-2 -right-2 bg-white text-brand-dark text-xs rounded-full h-4 w-4 flex items-center justify-center font-bold">2</span>
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
                <li><a href="{{ route('admin.products.index') }}" class="block text-brand-accent hover:underline font-semibold transition-colors">ADMIN PANEL</a></li>
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

</body>
</html>
