
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-heading', 'Dashboard')

@section('content')

    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">
            Selamat Datang di Admin Panel!
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Berikut ringkasan pengelolaan katalog produk Lactona Mart.
        </p>
    </div>

    <!-- Statistics -->
    <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2">

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

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-50 text-brand-accent">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-13ZM8 8h8m-8 4h8m-8 4h5"/>
                    </svg>
                </div>
            </div>

            <a
                href="{{ route('admin.categoryProducts.index') }}"
                class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-brand-accent hover:underline"
            >
                Kelola Kategori
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
