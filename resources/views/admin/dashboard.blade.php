
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-heading', 'Dashboard')

@section('content')

    <!-- Welcome Section & Search -->
    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <!-- Welcome Section -->
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Selamat Datang di Admin Panel!
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Berikut ringkasan pengelolaan katalog produk Lactona Mart.
            </p>
        </div>

        <!-- Search Produk -->
        <div
            x-data="{
                query: '',
                results: [],
                loading: false,
                open: false,
                searchUrl: @js(route('admin.products.search')),

                searchProducts() {
                    const keyword = this.query.trim();

                    if (!keyword) {
                        this.results = [];
                        this.open = false;
                        return;
                    }

                    this.open = true;
                    this.loading = true;

                    fetch(`${this.searchUrl}?q=${encodeURIComponent(keyword)}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Gagal mencari produk.');
                            }

                            return response.json();
                        })
                        .then(data => {
                            this.results = data;
                        })
                        .catch(error => {
                            console.error(error);
                            this.results = [];
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            }"
            @click.outside="open = false"
            class="relative w-full lg:max-w-md"
        >
            <!-- Input Search -->
            <div class="relative">
                <input
                    type="text"
                    x-model="query"
                    @input.debounce.300ms="searchProducts()"
                    @focus="if (query.trim()) open = true"
                    @keydown.escape="open = false"
                    autocomplete="off"
                    placeholder="Cari nama produk..."
                    class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm text-slate-700 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-brand-dark focus:ring-2 focus:ring-brand-dark/10"
                >

                <!-- Search Icon -->
                <svg
                    class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <circle cx="11" cy="11" r="7" stroke-width="1.8"/>
                    <path
                        d="m16 16 4 4"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    />
                </svg>

                <!-- Loading Indicator -->
                <div
                    x-show="loading"
                    class="absolute right-4 top-1/2 -translate-y-1/2"
                >
                    <svg
                        class="h-5 w-5 animate-spin text-brand-dark"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        />
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                        />
                    </svg>
                </div>
            </div>

            <!-- Dropdown Search Results -->
            <div
                x-cloak
                x-show="open"
                x-transition
                class="absolute left-0 right-0 top-full z-50 mt-2 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl"
            >

                <!-- Hasil Pencarian -->
                <template x-for="product in results" :key="product.id">
                    <a
                        :href="product.url"
                        class="flex items-center justify-between gap-3 border-b border-slate-100 px-4 py-3 transition last:border-0 hover:bg-slate-50"
                    >
                        <div class="min-w-0">
                            <p
                                class="truncate text-sm font-semibold text-slate-800"
                                x-text="product.name"
                            ></p>

                            <p
                                class="mt-1 text-xs text-slate-500"
                                x-text="product.category"
                            ></p>
                        </div>

                        <div class="shrink-0 text-right">
                            <p class="text-xs font-semibold text-brand-dark">
                                Rp <span x-text="product.price"></span>
                            </p>

                            <span class="mt-1 inline-flex items-center gap-1 text-xs text-slate-400">
                                Edit
                                <svg
                                    class="h-3 w-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M5 12h14m-6-6 6 6-6 6"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </span>
                        </div>
                    </a>
                </template>

                <!-- Loading -->
                <div
                    x-show="loading && results.length === 0"
                    class="px-4 py-5 text-center text-sm text-slate-500"
                >
                    Mencari produk...
                </div>

                <!-- Tidak Ditemukan -->
                <div
                    x-show="!loading && results.length === 0 && query.trim() !== ''"
                    class="px-4 py-6 text-center"
                >
                    <svg
                        class="mx-auto h-8 w-8 text-slate-300"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            cx="11"
                            cy="11"
                            r="7"
                            stroke-width="1.5"
                        />
                        <path
                            d="m16 16 4 4"
                            stroke-width="1.5"
                            stroke-linecap="round"
                        />
                    </svg>

                    <p class="mt-2 text-sm font-medium text-slate-600">
                        Produk tidak ditemukan
                    </p>

                    <p class="mt-1 text-xs text-slate-400">
                        Coba gunakan kata kunci lainnya.
                    </p>
                </div>

            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-2">

        <!-- Total Products -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Total Produk
                    </p>

                    <h3 class="mt-3 text-3xl font-bold text-slate-900">
                        {{ number_format($totalProducts) }}
                    </h3>

                    <p class="mt-2 text-xs text-slate-500">
                        Produk terdaftar dalam katalog
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-brand-dark">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M20 7.5 12 3 4 7.5m16 0-8 4.5m8-4.5v9L12 21m0-9L4 7.5m8 4.5v9m-8-13.5v9L12 21"/>
                    </svg>
                </div>
            </div>

            <a
                href="{{ route('admin.products.index') }}"
                class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-brand-dark hover:underline"
            >
                Kelola Produk
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <!-- Total Categories -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Total Kategori
                    </p>

                    <h3 class="mt-3 text-3xl font-bold text-slate-900">
                        {{ number_format($totalCategories) }}
                    </h3>

                    <p class="mt-2 text-xs text-slate-500">
                        Kategori produk terdaftar
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-50 text-yellow-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-13ZM8 8h8m-8 4h8m-8 4h5"/>
                    </svg>
                </div>
            </div>

            <a
                href="{{ route('admin.categoryProducts.index') }}"
                class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-yellow-600 hover:underline"
            >
                Kelola Kategori
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <!-- Active Products -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Produk Aktif
                    </p>

                    <h3 class="mt-3 text-3xl font-bold text-slate-900">
                        {{ number_format($activeProducts) }}
                    </h3>

                    <p class="mt-2 text-xs text-slate-500">
                        Produk ditampilkan di katalog
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
            </div>

            <a
                href="{{ route('admin.products.index', ['status' => 'active']) }}"
                class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:underline"
            >
                Lihat Produk Aktif
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <!-- Inactive Products -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Produk Nonaktif
                    </p>

                    <h3 class="mt-3 text-3xl font-bold text-slate-900">
                        {{ number_format($inactiveProducts) }}
                    </h3>

                    <p class="mt-2 text-xs text-slate-500">
                        Produk tidak ditampilkan di katalog
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M9.75 9.75 14.25 14.25m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
            </div>

            <a
                href="{{ route('admin.products.index', ['status' => 'inactive']) }}"
                class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-rose-700 hover:underline"
            >
                Lihat Produk Nonaktif
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </div>

    <!-- Recent Products -->
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-slate-900">
                    Produk Terbaru
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Lima produk terakhir yang ditambahkan ke katalog.
                </p>
            </div>

            <a
                href="{{ route('admin.products.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-brand-dark hover:underline"
            >
                Lihat Semua
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] text-left text-sm">

                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Nama Produk</th>
                        <th class="px-6 py-4 font-semibold">Kategori</th>
                        <th class="px-6 py-4 font-semibold">Harga</th>
                        <th class="px-6 py-4 font-semibold">Ditambahkan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse ($recentProducts as $product)
                        <tr class="transition hover:bg-slate-50">

                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-800">
                                    {{ $product->name }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                    {{ $product->category?->name ?? 'Tanpa Kategori' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 font-medium text-slate-700">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $product->created_at?->format('d M Y') ?? '-' }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <p class="font-medium text-slate-700">
                                    Belum ada produk
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    Produk yang ditambahkan akan muncul di sini.
                                </p>

                                <a
                                    href="{{ route('admin.products.create') }}"
                                    class="mt-4 inline-flex rounded-lg bg-brand-dark px-4 py-2 text-sm font-semibold text-white transition hover:opacity-90"
                                >
                                    Tambah Produk
                                </a>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    </div>

@endsection
