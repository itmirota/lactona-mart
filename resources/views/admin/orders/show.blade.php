
@extends('layouts.admin')

@section('title', 'Detail Pesanan')
@section('page-heading', 'Detail Pesanan')

@section('content')
<div class="space-y-6">

    <!-- Tombol Kembali -->
    <div>
        <a
            href="{{ route('admin.orders.index') }}"
            class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-brand-dark"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m15 18-6-6 6-6" />
            </svg>
            Kembali ke Daftar Pesanan
        </a>
    </div>

    <!-- Notifikasi -->
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Informasi Pesanan -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
            <div>
                <p class="text-sm text-slate-500">Nomor Pesanan</p>
                <h2 class="mt-1 text-xl font-bold text-slate-800">
                    #{{ $order->order_number }}
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    {{ $order->created_at->format('d M Y, H:i') }}
                </p>
            </div>

            <div>
                @php
                    $statusClasses = [
                        'Menunggu Konfirmasi' => 'bg-amber-100 text-amber-700',
                        'Diproses' => 'bg-blue-100 text-blue-700',
                        'Dikirim' => 'bg-indigo-100 text-indigo-700',
                        'Selesai' => 'bg-green-100 text-green-700',
                        'Dibatalkan' => 'bg-red-100 text-red-700',
                    ];
                @endphp

                <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-semibold
                    {{ $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-600' }}">
                    {{ $order->status }}
                </span>
            </div>
        </div>
    </div>

    <!-- Informasi Pelanggan -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="text-base font-bold text-slate-800">
            Informasi Pelanggan
        </h3>

        <div class="mt-5 grid gap-5 sm:grid-cols-2">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Nama Pelanggan
                </p>
                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ $order->customer_name }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Nomor Telepon
                </p>
                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ $order->customer_phone }}
                </p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Alamat Pengiriman
                </p>
                <p class="mt-1 text-sm leading-relaxed text-slate-700">
                    {{ $order->customer_address }}
                </p>
            </div>
        </div>
    </div>

    <!-- Rincian Produk -->
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-6">
            <h3 class="text-base font-bold text-slate-800">
                Rincian Produk
            </h3>
            <p class="mt-1 text-sm text-slate-500">
                Daftar produk dalam pesanan ini
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[650px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Produk</th>
                        <th class="px-6 py-4 font-semibold">Harga</th>
                        <th class="px-6 py-4 text-center font-semibold">Jumlah</th>
                        <th class="px-6 py-4 text-right font-semibold">Subtotal</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($order->items as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-800">
                                    {{ $item->product_name }}
                                </p>

                                @if ($item->variant_label)
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $item->variant_label }}
                                    </p>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4 text-center text-slate-600">
                                {{ $item->quantity }}
                            </td>

                            <td class="px-6 py-4 text-right font-semibold text-slate-800">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                Tidak ada rincian produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 bg-slate-50 px-6 py-5">
            <div class="flex items-center justify-between gap-4">
                <span class="font-semibold text-slate-600">
                    Total Pembayaran
                </span>
                <span class="text-xl font-bold text-brand-dark">
                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="text-base font-bold text-slate-800">
            Pengaturan Status Pesanan
        </h3>

        <p class="mt-1 text-sm text-slate-500">
            Perubahan status hanya dapat dilakukan oleh Superadmin.
        </p>

        <form
            action="{{ route('admin.orders.update-status', $order) }}"
            method="POST"
            class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-end"
        >
            @csrf
            @method('PATCH')

            <div class="flex-1">
                <label for="status" class="mb-2 block text-sm font-medium text-slate-700">
                    Status Pesanan
                </label>

                <select
                    name="status"
                    id="status"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-brand-dark focus:ring-2 focus:ring-brand-dark/10"
                    required
                >
                    @foreach ([
                        'Menunggu Konfirmasi',
                        'Diproses',
                        'Dikirim',
                        'Selesai',
                        'Dibatalkan',
                    ] as $status)
                        <option
                            value="{{ $status }}"
                            @selected(old('status', $order->status) === $status)
                        >
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button
                type="submit"
                class="rounded-lg bg-brand-dark px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90"
            >
                Simpan Status
            </button>
        </form>
    </div>

    <!-- Riwayat Perubahan Status -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="text-base font-bold text-slate-800">
            Riwayat Status Pesanan
        </h3>

        <p class="mt-1 text-sm text-slate-500">
            Catatan perubahan status pesanan.
        </p>

        <div class="mt-6 space-y-5">
            @forelse ($order->statusHistories as $history)
                <div class="relative flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-light text-brand-dark">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </div>

                        @if (!$loop->last)
                            <div class="mt-2 h-full min-h-6 w-px bg-slate-200"></div>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1 pb-3">
                        <p class="text-sm font-semibold text-slate-800">
                            {{ $history->new_status }}
                        </p>

                        @if ($history->old_status)
                            <p class="mt-1 text-xs text-slate-500">
                                Sebelumnya: {{ $history->old_status }}
                            </p>
                        @else
                            <p class="mt-1 text-xs text-slate-500">
                                Status awal pesanan
                            </p>
                        @endif

                        <p class="mt-2 text-xs text-slate-500">
                            Oleh:
                            <span class="font-medium text-slate-700">
                                {{ $history->user?->name ?? 'Pelanggan / Sistem' }}
                            </span>
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            {{ $history->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400">
                    Belum ada riwayat perubahan status.
                </p>
            @endforelse
        </div>
    </div>
</div>
@endsection
