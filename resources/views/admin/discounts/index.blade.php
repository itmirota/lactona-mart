@extends('layouts.admin')

@section('title', 'Diskon')
@section('page-heading', 'Manajemen Diskon')

@section('content')
<div class="p-6 space-y-6 bg-white border border-gray-100 rounded-xl shadow-sm">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Promo dan Diskon</h1>
            <p class="mt-1 text-sm text-slate-500">Atur potongan harga produk beserta keterangan dan masa berlakunya.</p>
        </div>

        <a href="{{ route('admin.discounts.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-dark px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-accent">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Promo
        </a>
    </div>

    @if (session('success'))
        <div role="status" class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Semua Promo</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalCampaigns) }}</p>
            <p class="mt-1 text-xs text-slate-500">Promo tersimpan</p>
        </div>
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-5 shadow-sm">
            <p class="text-sm font-medium text-emerald-800">Sedang Aktif</p>
            <p class="mt-2 text-3xl font-bold text-emerald-900">{{ number_format($activeCampaigns) }}</p>
            <p class="mt-1 text-xs text-emerald-700">Promo yang berlaku sekarang</p>
        </div>
        <div class="rounded-2xl border border-amber-200 bg-amber-50/70 p-5 shadow-sm">
            <p class="text-sm font-medium text-amber-800">Akan Datang</p>
            <p class="mt-2 text-3xl font-bold text-amber-900">{{ number_format($upcomingCampaigns) }}</p>
            <p class="mt-1 text-xs text-amber-700">Promo terjadwal</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-slate-900">Daftar Promo</h2>
                <p class="mt-1 text-xs text-slate-500">Satu promo dapat dipakai banyak produk.</p>
            </div>

            <div class="flex flex-wrap gap-2" aria-label="Filter status promo">
                @foreach ([
                    'all' => 'Semua',
                    'active' => 'Aktif',
                    'upcoming' => 'Akan datang',
                    'ended' => 'Berakhir',
                    'inactive' => 'Nonaktif',
                ] as $value => $label)
                    <a href="{{ route('admin.discounts.index', ['status' => $value]) }}"
                        @class([
                            'rounded-full px-3 py-1.5 text-xs font-semibold transition',
                            'bg-brand-dark text-white' => $filter === $value,
                            'bg-slate-100 text-slate-600 hover:bg-slate-200' => $filter !== $value,
                        ])>
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Promo</th>
                        <th class="px-5 py-4 font-semibold">Potongan</th>
                        <th class="px-5 py-4 font-semibold">Produk / Harga Promo</th>
                        <th class="px-5 py-4 font-semibold">Jadwal</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                        <th class="px-5 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($discounts as $discount)
                        <tr class="align-top transition hover:bg-slate-50/70">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-900">{{ $discount->promotion_name }}</p>
                                @if ($discount->description)
                                    <p class="mt-2 max-w-xs whitespace-normal text-xs leading-relaxed text-slate-500">{{ $discount->description }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if ($discount->discount_type === 'percentage')
                                    <span class="inline-flex rounded-full bg-violet-50 px-2.5 py-1 text-xs font-semibold text-violet-700">
                                        {{ number_format((float) $discount->discount_value, (float) $discount->discount_value == (int) $discount->discount_value ? 0 : 2, ',', '.') }}%
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                        Rp {{ number_format((float) $discount->discount_value, 0, ',', '.') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <p class="mb-1 text-xs font-semibold text-slate-500">{{ $discount->products->count() }} produk</p>
                                @forelse ($discount->products->take(3) as $product)
                                    <p class="mt-1 max-w-xs whitespace-normal text-xs leading-relaxed text-slate-700">
                                        {{ $product->name }}<span class="font-semibold text-emerald-700"> — Rp {{ number_format($discount->discountedPriceFor($product), 0, ',', '.') }}</span>
                                    </p>
                                @empty
                                    <span class="text-slate-400">Belum ada produk</span>
                                @endforelse
                                @if ($discount->products->count() > 3)
                                    <p class="mt-1 text-xs font-medium text-slate-500">+ {{ $discount->products->count() - 3 }} produk lainnya</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs leading-relaxed text-slate-600">
                                <p>{{ $discount->starts_at->format('d M Y, H:i') }} WIB</p>
                                <p class="mt-1 text-slate-400">sampai {{ $discount->ends_at ? $discount->ends_at->format('d M Y, H:i').' WIB' : 'tanpa batas akhir' }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold',
                                    'bg-emerald-50 text-emerald-700' => $discount->status === 'Aktif',
                                    'bg-amber-50 text-amber-700' => $discount->status === 'Akan Datang',
                                    'bg-slate-100 text-slate-600' => $discount->status === 'Nonaktif',
                                    'bg-rose-50 text-rose-700' => $discount->status === 'Berakhir',
                                ])>{{ $discount->status }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.discounts.edit', $discount) }}" class="rounded-lg bg-indigo-50 p-2 text-indigo-700 transition hover:bg-indigo-100" aria-label="Edit {{ $discount->promotion_name }}" title="Edit promo">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 2.651 2.651M6.75 17.25l3.75-.75L19.5 7.5a1.875 1.875 0 0 0-2.65-2.65L7.85 13.85l-1.1 3.4Z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" onsubmit="return confirm('Hapus promo ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-rose-50 p-2 text-rose-700 transition hover:bg-rose-100" aria-label="Hapus {{ $discount->promotion_name }}" title="Hapus promo">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12m-10 0 .667 12h6.666L16 7M9 7V4h6v3m-5 4v5m4-5v5"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m12 3 2.25 5.25L20 10.5l-5.75 2.25L12 18l-2.25-5.25L4 10.5l5.75-2.25L12 3Z"/>
                                    </svg>
                                </div>
                                <p class="mt-4 font-semibold text-slate-800">Belum ada promo diskon</p>
                                <p class="mt-1 text-sm text-slate-500">Buat promo untuk memberi potongan harga pada produk.</p>
                                <a href="{{ route('admin.discounts.create') }}" class="mt-4 inline-flex rounded-lg bg-brand-dark px-4 py-2 text-sm font-semibold text-white hover:opacity-90">Buat Promo</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($discounts->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">{{ $discounts->links() }}</div>
        @endif
    </div>
</div>
@endsection
