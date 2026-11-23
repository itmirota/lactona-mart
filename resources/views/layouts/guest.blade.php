<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Lactona Mart | Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased">
        <div class="flex min-h-screen flex-col items-center justify-center bg-slate-50 px-4 py-10 sm:px-6">
            <a href="{{ route('home') }}" class="mb-6 flex items-center gap-3">
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-dark text-xl font-bold text-white">L</span>
                <span>
                    <span class="block text-lg font-bold leading-tight text-slate-900">Lactona Mart</span>
                    <span class="text-sm text-slate-500">Administrator Panel</span>
                </span>
            </a>

            <div class="w-full max-w-md overflow-hidden rounded-xl border border-slate-200 bg-white px-6 py-7 shadow-sm sm:px-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
