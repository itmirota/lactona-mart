@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page-heading', 'Tambah Produk')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Produk Baru</h1>
        <a href="{{ route('admin.products.index') }}" class="flex items-center text-gray-600 hover:text-brand-dark">
            <svg class="mr-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0 7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
            <ul class="list-inside list-disc text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 p-4 sm:p-6 lg:p-8">
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="category_id" class="mb-1 block text-sm font-medium text-gray-700">Kategori Produk <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" required class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-brand-dark focus:ring-brand-dark">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="255" placeholder="Contoh: Lactona Ibu Rasa Cokelat" class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-brand-dark focus:ring-brand-dark">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="price" class="mb-1 block text-sm font-medium text-gray-700">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0" step="0.01" placeholder="Contoh: 59000" class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-brand-dark focus:ring-brand-dark">
                    @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="mb-1 block text-sm font-medium text-gray-700">Keterangan / Deskripsi</label>
                    <textarea name="description" id="description" rows="4" placeholder="Deskripsi singkat produk..." class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-brand-dark focus:ring-brand-dark">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="image" class="mb-1 block text-sm font-medium text-gray-700">Gambar Produk</label>
                    <input id="image" name="image" type="file" accept="image/png,image/jpeg,image/webp" class="block w-full rounded-lg border border-gray-300 text-sm text-gray-600 file:mr-4 file:rounded-l-lg file:border-0 file:bg-brand-light file:px-4 file:py-2 file:font-medium file:text-brand-dark" onchange="updateFileName(this)">
                    <p class="mt-1 text-xs text-gray-500" id="file-name-display">PNG, JPG, WEBP maks. 2 MB</p>
                    @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            @include('admin.products.partials.variant-fields')

            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('admin.products.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 text-center font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" class="rounded-lg bg-brand-dark px-6 py-2 font-medium text-white hover:bg-brand-accent">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileNameDisplay = document.getElementById('file-name-display');

    if (fileNameDisplay) {
        fileNameDisplay.textContent = input.files[0]?.name ?? 'PNG, JPG, WEBP maks. 2 MB';
    }
}
</script>
@endsection
