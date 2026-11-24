
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') - Lactona Mart</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">

    <div class="min-h-screen">

        <!-- Mobile Overlay -->
        <div
            data-sidebar-overlay
            class="fixed inset-0 z-40 hidden bg-slate-950/40 md:hidden"
        ></div>

        <!-- Sidebar -->
        <aside
            id="admin-sidebar"
            class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform duration-300 md:translate-x-0"
        >
            <!-- Logo -->
            <div class="flex h-20 items-center gap-3 border-b border-slate-100 px-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-dark text-lg font-bold text-white">
                    L
                </div>

                <div>
                    <h1 class="text-base font-bold tracking-tight text-slate-900">
                        Lactona Mart
                    </h1>
                    <p class="text-xs text-slate-500">Administration Panel</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 space-y-7 overflow-y-auto px-4 py-6">

                @can('dashboard.view')
                    <div>
                        <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Menu Utama
                        </p>

                        <div class="space-y-1">
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                            {{ request()->routeIs('admin.dashboard') ? 'bg-brand-light text-brand-dark' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-dark' }}"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M3 10.5 12 3l9 7.5M5.25 9v11.25h13.5V9M9 20.25v-6h6v6"/>
                            </svg>
                            Dashboard
                        </a>
                        </div>
                    </div>
                @endcan

                @canany(['products.manage', 'discounts.manage', 'categories.manage'])
                    <div>
                        <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Manajemen Katalog
                        </p>

                        <div class="space-y-1">
                        @can('products.manage')
                        <a
                            href="{{ route('admin.products.index') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                            {{ request()->routeIs('admin.products.*') ? 'bg-brand-light text-brand-dark' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-dark' }}"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M20 7.5 12 3 4 7.5m16 0-8 4.5m8-4.5v9L12 21m0-9L4 7.5m8 4.5v9m-8-13.5v9L12 21"/>
                            </svg>
                            Produk
                        </a>
                        @endcan

                        @can('discounts.manage')
                        <a
                            href="{{ route('admin.discounts.index') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                            {{ request()->routeIs('admin.discounts.*') ? 'bg-brand-light text-brand-dark' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-dark' }}"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="m12 3 2.25 5.25L20 10.5l-5.75 2.25L12 18l-2.25-5.25L4 10.5l5.75-2.25L12 3Zm6.5 12.5.9 2.1 2.1.9-2.1.9-.9 2.1-.9-2.1-2.1-.9 2.1-.9.9-2.1Z"/>
                            </svg>
                            Diskon
                        </a>
                        @endcan

                        @can('categories.manage')
                        <a
                            href="{{ route('admin.categoryProducts.index') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                            {{ request()->routeIs('admin.categoryProducts.*') ? 'bg-brand-light text-brand-dark' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-dark' }}"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-13ZM8 8h8m-8 4h8m-8 4h5"/>
                            </svg>
                            Kategori
                        </a>
                        @endcan
                        </div>
                    </div>
                @endcanany

                @can('users.manage')
                    <div>
                        <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Manajemen Akun
                        </p>

                        <a
                            href="{{ route('admin.users.index') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                            {{ request()->routeIs('admin.users.*') ? 'bg-brand-light text-brand-dark' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-dark' }}"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m16-10a4 4 0 0 0 0-8m4 18v-2a4 4 0 0 0-3-3.87M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/>
                            </svg>
                            Manajemen Admin
                        </a>
                    </div>
                @endcan

            </nav>

            <!-- Sidebar Footer -->
            <div class="border-t border-slate-100 p-4">
                <div class="rounded-xl bg-slate-50 p-3">
                    <p class="text-xs font-semibold text-slate-700">Lactona Mart</p>
                    <p class="mt-1 text-xs text-slate-500">Admin Management</p>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="min-h-screen md:pl-64">

            <!-- Header -->
            <header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur sm:px-6 lg:px-8">

                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        data-sidebar-toggle
                        aria-controls="admin-sidebar"
                        aria-expanded="false"
                        class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 md:hidden"
                        aria-label="Buka navigasi"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">
                            @yield('page-heading', 'Dashboard')
                        </h2>
                        <p class="hidden text-xs text-slate-500 sm:block">
                            Kelola katalog Lactona Mart
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                    </div>

                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-light font-semibold text-brand-dark">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-brand-dark">
                            Keluar
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

        </div>
    </div>

</body>
</html>
