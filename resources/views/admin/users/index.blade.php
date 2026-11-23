@extends('layouts.admin')

@section('title', 'Manajemen Admin')
@section('page-heading', 'Manajemen Admin')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Akun Admin</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola akun yang dapat mengakses dashboard katalog.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center rounded-lg bg-brand-dark px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-accent">
            + Tambah Admin
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 p-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-slate-900">Daftar Admin</h2>
                <p class="mt-1 text-sm text-slate-500">{{ number_format($users->total()) }} akun admin</p>
            </div>

            <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-2">
                <label for="search" class="sr-only">Cari nama atau email</label>
                <input id="search" name="search" type="search" value="{{ $search }}" placeholder="Cari nama atau email" class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-dark focus:ring-brand-dark sm:w-64">
                <button type="submit" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Nama</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Dibuat</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($users as $user)
                        <tr>
                            <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-900">{{ $user->name }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->is_active ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-500">{{ $user->created_at?->format('d/m/Y') ?? '-' }}</td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="rounded-md border border-slate-200 px-3 py-1.5 text-xs font-semibold text-brand-dark hover:bg-brand-light">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus akun admin ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-50">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-500">
                                @if ($search !== '')
                                    Tidak ada admin yang cocok dengan pencarian.
                                @else
                                    Belum ada akun admin. Tambahkan akun pertama untuk mulai mengelola dashboard.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
