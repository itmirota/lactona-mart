@extends('layouts.admin')

@section('title', 'Pesanan')
@section('page-heading', 'Manajemen Pesanan')

@section('content')

@if (session('success'))
    <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Daftar Pesanan
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Kelola pesanan pelanggan Lactona Mart.
            </p>
        </div>
    </div>

    <!-- Filter -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.orders.index') }}"
            class="grid grid-cols-1 gap-3 md:grid-cols-3">

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">
                    Cari Pesanan
                </label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Nomor, nama, atau telepon..."
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-brand-dark focus:ring-2 focus:ring-brand-light"
                >
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">
                    Status Pesanan
                </label>
                <select
                    name="status"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-brand-dark focus:ring-2 focus:ring-brand-light"
                >
                    <option value="">Semua Status</option>
                    <option value="Menunggu Konfirmasi" @selected(request('status') === 'Menunggu Konfirmasi')>
                        Menunggu Konfirmasi
                    </option>
                    <option value="Diproses" @selected(request('status') === 'Diproses')>
                        Diproses
                    </option>
                    <option value="Selesai" @selected(request('status') === 'Selesai')>
                        Selesai
                    </option>
                    <option value="Dibatalkan" @selected(request('status') === 'Dibatalkan')>
                        Dibatalkan
                    </option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button
                    type="submit"
                    class="rounded-lg bg-brand-dark px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90"
                >
                    Cari
                </button>

                <a
                    href="{{ route('admin.orders.index') }}"
                    class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Pesanan -->
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">

                <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Nomor Pesanan</th>
                        <th class="px-5 py-4">Pelanggan</th>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4">Total</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse ($orders as $order)
                        <tr class="transition hover:bg-slate-50">

                            <td class="px-5 py-4">
                                <span class="font-semibold text-slate-800">
                                    {{ $order->order_number }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-medium text-slate-800">
                                    {{ $order->customer_name }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $order->customer_phone }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td class="px-5 py-4 font-semibold text-slate-800">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4">
                                @php
                                    $statusClass = match ($order->status) {
                                        'Menunggu Konfirmasi' => 'bg-amber-100 text-amber-700',
                                        'Diproses' => 'bg-blue-100 text-blue-700',
                                        'Selesai' => 'bg-green-100 text-green-700',
                                        'Dibatalkan' => 'bg-red-100 text-red-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp

                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Tombol Detail: Admin dan Superadmin -->
                                    <a
                                        href="{{ route('admin.orders.show', $order) }}"
                                        class="inline-flex items-center gap-2 rounded-lg bg-brand-light px-3 py-2 text-xs font-semibold text-brand-dark transition hover:opacity-80"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.8"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.8"
                                                d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z"
                                            />
                                        </svg>
                                        Detail
                                    </a>

                                    <!-- Tombol Hapus: Khusus Superadmin -->
                                    @hasrole('superadmin')
                                        <form
                                            action="{{ route('admin.orders.destroy', $order) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan {{ $order->order_number }}? Data pesanan dan seluruh rincian produknya akan dihapus permanen.')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-2 rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.8"
                                                        d="M3 6h18m-2 0-.9 14H5.9L5 6m4 0V4h6v2m-5 4v6m4-6v6"
                                                    />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @endhasrole
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <p class="font-medium text-slate-600">
                                    Belum ada pesanan.
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    Pesanan pelanggan akan muncul di sini.
                                </p>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($orders->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $orders->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
