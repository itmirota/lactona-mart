@extends('layouts.admin')

@section('title', 'Buat Promo Diskon')
@section('page-heading', 'Buat Promo Diskon')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.discounts.index') }}" class="text-sm font-semibold text-brand-dark hover:underline">&larr; Kembali ke daftar promo</a>
        <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-900">Buat promo baru</h1>
        <p class="mt-1 text-sm text-slate-500">Pilih produk, tentukan jenis potongan, lalu atur keterangan dan jadwal promo.</p>
    </div>

    @include('admin.discounts.partials.form')
</div>
@endsection
