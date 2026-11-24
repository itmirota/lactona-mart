@extends('layouts.admin')

@section('title', 'Tambah Admin')
@section('page-heading', 'Tambah Admin')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Tambah Akun Admin</h1>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-brand-dark hover:text-brand-accent">Kembali ke daftar</a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        @csrf

        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Nama admin</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="255" autocomplete="name" class="w-full rounded-lg border-slate-300 focus:border-brand-dark focus:ring-brand-dark">
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email" class="w-full rounded-lg border-slate-300 focus:border-brand-dark focus:ring-brand-dark">
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm font-medium text-slate-700">Kata sandi</label>
            <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" class="w-full rounded-lg border-slate-300 focus:border-brand-dark focus:ring-brand-dark">
            <p class="mt-1 text-xs text-slate-500">Minimal 8 karakter.</p>
            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="mb-1 block text-sm font-medium text-slate-700">Konfirmasi kata sandi</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" class="w-full rounded-lg border-slate-300 focus:border-brand-dark focus:ring-brand-dark">
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-slate-300 px-5 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
            <button type="submit" class="rounded-lg bg-brand-dark px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-accent">Buat Akun Admin</button>
        </div>
    </form>
</div>
@endsection
