@extends('layouts.admin')

@section('title', 'Edit Admin')
@section('page-heading', 'Edit Admin')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Edit Akun Admin</h1>
            <p class="mt-1 text-sm text-slate-500">Peran akun tetap Admin dan tidak dapat diubah dari form ini.</p>
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

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Nama admin</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required maxlength="255" autocomplete="name" class="w-full rounded-lg border-slate-300 focus:border-brand-dark focus:ring-brand-dark">
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required maxlength="255" autocomplete="email" class="w-full rounded-lg border-slate-300 focus:border-brand-dark focus:ring-brand-dark">
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="is_active" class="mb-1 block text-sm font-medium text-slate-700">Status akun</label>
            <select id="is_active" name="is_active" required class="w-full rounded-lg border-slate-300 focus:border-brand-dark focus:ring-brand-dark">
                <option value="1" @selected((string) old('is_active', (int) $user->is_active) === '1')>Aktif</option>
                <option value="0" @selected((string) old('is_active', (int) $user->is_active) === '0')>Nonaktif</option>
            </select>
            <p class="mt-1 text-xs text-slate-500">Admin nonaktif tidak dapat masuk ke dashboard.</p>
            @error('is_active') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="rounded-lg bg-slate-50 p-4">
            <h2 class="text-sm font-semibold text-slate-800">Ganti kata sandi</h2>
            <p class="mt-1 text-xs text-slate-500">Kosongkan kedua kolom ini jika kata sandi tidak ingin diubah.</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-slate-700">Kata sandi baru</label>
                    <input id="password" name="password" type="password" minlength="8" autocomplete="new-password" class="w-full rounded-lg border-slate-300 focus:border-brand-dark focus:ring-brand-dark">
                    @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-medium text-slate-700">Konfirmasi kata sandi baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" autocomplete="new-password" class="w-full rounded-lg border-slate-300 focus:border-brand-dark focus:ring-brand-dark">
                </div>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-slate-300 px-5 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
            <button type="submit" class="rounded-lg bg-brand-dark px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-accent">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
