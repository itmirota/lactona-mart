@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page-heading', 'Tambah Produk')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-6 flex items-center justify-between">
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
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
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
                </div>

                <div class="md:col-span-2">
                    <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Lactona Ibu Rasa Cokelat" class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-brand-dark focus:ring-brand-dark">
                </div>

                <div class="md:col-span-2">
                    <label for="price" class="mb-1 block text-sm font-medium text-gray-700">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0" placeholder="Contoh: 59000" class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-brand-dark focus:ring-brand-dark">
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="mb-1 block text-sm font-medium text-gray-700">Keterangan / Deskripsi</label>
                    <textarea name="description" id="description" rows="4" placeholder="Deskripsi singkat produk..." class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-brand-dark focus:ring-brand-dark">{{ old('description') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="image" class="mb-1 block text-sm font-medium text-gray-700">Gambar Produk</label>
                    <div class="mt-1 flex cursor-pointer justify-center rounded-lg border-2 border-dashed border-gray-300 px-6 pb-6 pt-5 transition-colors hover:border-brand-dark" onclick="document.getElementById('image').click()">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex justify-center text-sm text-gray-600">
                                <label for="image" class="cursor-pointer rounded-md bg-white font-medium text-brand-dark hover:text-brand-accent">
                                    <span>Upload file</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/png,image/jpeg,image/webp" onchange="updateFileName(this)">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500" id="file-name-display">PNG, JPG, WEBP maks. 2 MB</p>
                        </div>
                    </div>
                </div>
            </div>

            @include('admin.products.partials.variant-fields')

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" class="rounded-lg bg-brand-dark px-8 py-2 font-medium text-white hover:bg-brand-accent">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateFileName(input) {
        const fileNameDisplay = document.getElementById('file-name-display');

        if (input.files && input.files[0]) {
            fileNameDisplay.textContent = 'File terpilih: ' + input.files[0].name;
            fileNameDisplay.classList.add('font-semibold', 'text-brand-dark');
        } else {
            fileNameDisplay.textContent = 'PNG, JPG, WEBP maks. 2 MB';
            fileNameDisplay.classList.remove('font-semibold', 'text-brand-dark');
        }
    }
</script>
@endsection
