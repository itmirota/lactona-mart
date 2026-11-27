
@extends('layouts.admin')

@section('title', 'Log Aktivitas')
@section('page-heading', 'Log Aktivitas')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div>
        <h3 class="text-xl font-bold text-slate-900">
            Riwayat Aktivitas
        </h3>
        <p class="mt-1 text-sm text-slate-500">
            Pantau seluruh aktivitas yang dilakukan oleh Admin dan Superadmin.
        </p>
    </div>

    <!-- Filter -->
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <form action="{{ route('admin.activity-logs.index') }}" method="GET">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Cari aktivitas
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari pengguna atau keterangan..."
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-brand-dark focus:ring-2 focus:ring-brand-light"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Menu
                    </label>

                    <select
                        name="module"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-brand-dark"
                    >
                        <option value="">Semua Menu</option>

                        @foreach ($modules as $module)
                            <option
                                value="{{ $module }}"
                                @selected(request('module') === $module)
                            >
                                {{ $module }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Jenis Aktivitas
                    </label>

                    <select
                        name="action"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-brand-dark"
                    >
                        <option value="">Semua Aktivitas</option>
                        <option value="created" @selected(request('action') === 'created')>
                            Ditambahkan
                        </option>
                        <option value="updated" @selected(request('action') === 'updated')>
                            Diperbarui
                        </option>
                        <option value="deleted" @selected(request('action') === 'deleted')>
                            Dihapus
                        </option>
                    </select>
                </div>

            </div>

            <div class="mt-4 flex flex-wrap items-center gap-2">
                <button
                    type="submit"
                    class="rounded-lg bg-brand-dark px-4 py-2.5 text-sm font-semibold text-white transition hover:opacity-90"
                >
                    Terapkan Filter
                </button>

                <a
                    href="{{ route('admin.activity-logs.index') }}"
                    class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Aktivitas -->
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h4 class="font-semibold text-slate-900">
                    Daftar Aktivitas
                </h4>
                <p class="mt-1 text-xs text-slate-500">
                    Menampilkan {{ $activityLogs->total() }} aktivitas
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">

                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Waktu</th>
                        <th class="px-5 py-4 font-semibold">Pengguna</th>
                        <th class="px-5 py-4 font-semibold">Aktivitas</th>
                        <th class="px-5 py-4 font-semibold">Menu</th>
                        <th class="px-5 py-4 font-semibold">Keterangan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse ($activityLogs as $log)
                        <tr class="transition hover:bg-slate-50">

                            <td class="whitespace-nowrap px-5 py-4">
                                <p class="font-medium text-slate-700">
                                    {{ $log->created_at->format('d M Y') }}
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $log->created_at->format('H:i:s') }}
                                </p>
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-medium text-slate-800">
                                    {{ $log->user?->name ?? 'Pengguna tidak tersedia' }}
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $log->user?->email ?? '-' }}
                                </p>
                            </td>

                            <td class="px-5 py-4">
                                @php
                                    $actionStyles = [
                                        'created' => 'bg-emerald-50 text-emerald-700',
                                        'updated' => 'bg-blue-50 text-blue-700',
                                        'deleted' => 'bg-red-50 text-red-700',
                                    ];

                                    $actionLabels = [
                                        'created' => 'Ditambahkan',
                                        'updated' => 'Diperbarui',
                                        'deleted' => 'Dihapus',
                                    ];
                                @endphp

                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $actionStyles[$log->action] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $actionLabels[$log->action] ?? ucfirst($log->action) }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                <span class="text-sm font-medium text-slate-700">
                                    {{ $log->module }}
                                </span>
                            </td>

                            <td class="max-w-md px-5 py-4">
                                <p class="leading-relaxed text-slate-600">
                                    {{ $log->description }}
                                </p>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-14 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 8v4l3 2m6-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>

                                    <p class="mt-3 font-medium text-slate-700">
                                        Belum ada aktivitas
                                    </p>

                                    <p class="mt-1 text-sm text-slate-400">
                                        Aktivitas akan muncul di sini setelah ada tindakan di panel admin.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($activityLogs->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $activityLogs->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
