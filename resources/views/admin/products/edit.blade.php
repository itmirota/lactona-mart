@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-heading', 'Edit Produk')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Edit Produk: {{ $product->name }}</h1>
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
        <form
            action="{{ route('admin.products.update', $product->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-4 sm:p-6 lg:p-8"
            data-product-wizard
            data-start-step="{{ $errors->has('nutrition_facts') || collect($errors->keys())->contains(fn ($key) => str_starts_with($key, 'nutrition_facts.')) ? 2 : 1 }}"
        >
            @csrf
            @method('PUT')

            <ol class="mb-6 grid grid-cols-2 gap-3" aria-label="Langkah formulir produk">
                <li data-step-indicator="1" class="flex min-w-0 items-center gap-2 rounded-lg border border-gray-200 px-3 py-3 text-sm font-medium text-gray-500 sm:gap-3 sm:px-4">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-bold">1</span>
                    <span>Informasi Produk</span>
                </li>
                <li data-step-indicator="2" class="flex min-w-0 items-center gap-2 rounded-lg border border-gray-200 px-3 py-3 text-sm font-medium text-gray-500 sm:gap-3 sm:px-4">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-bold">2</span>
                    <span>Nutrition Facts</span>
                </li>
            </ol>

            <section data-product-step="1" class="space-y-8">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="category_id" class="mb-1 block text-sm font-medium text-gray-700">Kategori Produk <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" required class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-brand-dark focus:ring-brand-dark">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-brand-dark focus:ring-brand-dark">
                </div>

                <div class="md:col-span-2">
                    <label for="price" class="mb-1 block text-sm font-medium text-gray-700">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required min="0" class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-brand-dark focus:ring-brand-dark">
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="mb-1 block text-sm font-medium text-gray-700">Keterangan / Deskripsi</label>
                    <textarea name="description" id="description" rows="4" class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-brand-dark focus:ring-brand-dark">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="image" class="mb-1 block text-sm font-medium text-gray-700">Ganti Gambar Produk (Opsional)</label>
                    @if ($product->image)
                        <div class="mb-4">
                            <p class="mb-2 text-sm text-gray-500">Gambar saat ini:</p>
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-32 w-auto rounded-md border border-gray-200 object-contain">
                        </div>
                    @endif

                    <div class="mt-1 flex cursor-pointer justify-center rounded-lg border-2 border-dashed border-gray-300 px-6 pb-6 pt-5 transition-colors hover:border-brand-dark" onclick="document.getElementById('image').click()">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex justify-center text-sm text-gray-600">
                                <label for="image" class="cursor-pointer rounded-md bg-white font-medium text-brand-dark hover:text-brand-accent">
                                    <span>Upload file baru</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/png,image/jpeg,image/webp" onchange="updateFileName(this)">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500" id="file-name-display">PNG, JPG, WEBP maks. 2 MB. Kosongkan jika tidak ingin mengganti gambar.</p>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $nutritionEnabled = old(
                    'nutrition_facts_enabled',
                    !empty($product->nutrition_facts)
                );
            @endphp

            @include('admin.products.partials.variant-fields')

            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('admin.products.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 text-center font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="button" data-step-next class="rounded-lg bg-brand-dark px-6 py-2 font-medium text-white hover:bg-brand-accent">
                    Selanjutnya: Nutrition Facts
                </button>
            </div>
            </section>

            <section data-product-step="2" class="hidden space-y-6">
                <div class="space-y-4 rounded-xl border border-gray-200 p-4 sm:p-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-800">Nutrition Facts</h2>
                        <p class="mt-1 text-sm text-gray-500">Pengisian informasi nilai gizi bersifat opsional.</p>
                    </div>
                    <label class="inline-flex w-fit cursor-pointer items-center gap-2 text-sm font-medium text-gray-700">
                        <input
                            type="checkbox"
                            name="nutrition_facts_enabled"
                            value="1"
                            @checked($nutritionEnabled)
                        >
                        Isi Nutrition Facts
                    </label>
                    </div>

                    <fieldset
                        id="nutrition-fields-wrapper"
                        class="min-w-0 space-y-6 {{ $nutritionEnabled ? '' : 'opacity-50' }}"
                        {{ $nutritionEnabled ? '' : 'disabled' }}
                    >
                        @include('admin.products.nutrition-fields')
                    </fieldset>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                    <button type="button" data-step-previous class="rounded-lg border border-gray-300 px-6 py-2 font-medium text-gray-700 hover:bg-gray-50">
                        Kembali
                    </button>
                    <button type="submit" class="rounded-lg bg-brand-dark px-8 py-2 font-medium text-white hover:bg-brand-accent">Simpan Perubahan</button>
                </div>
            </section>
        </form>
    </div>
</div>

<script>
    function updateFileName(input) {
        const fileNameDisplay = document.getElementById('file-name-display');

        if (input.files && input.files[0]) {
            fileNameDisplay.textContent = 'File baru terpilih: ' + input.files[0].name;
            fileNameDisplay.classList.add('font-semibold', 'text-brand-dark');
        } else {
            fileNameDisplay.textContent = 'PNG, JPG, WEBP maks. 2 MB. Kosongkan jika tidak ingin mengganti gambar.';
            fileNameDisplay.classList.remove('font-semibold', 'text-brand-dark');
        }
    }
</script>
@endsection
