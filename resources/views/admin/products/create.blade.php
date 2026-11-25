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
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 p-4 sm:p-6 lg:p-8" data-product-wizard data-start-step="{{ $errors->has('nutrition_facts') || collect($errors->keys())->contains(fn ($key) => str_starts_with($key, 'nutrition_facts.')) ? 2 : 1 }}">
            @csrf

            <ol class="relative mb-8 grid grid-cols-2" aria-label="Langkah formulir produk">
                <li data-step-indicator="1" class="relative z-10 flex min-w-0 flex-col items-center gap-2 text-center text-xs font-medium text-gray-500 sm:text-sm">
                    <span data-step-progress aria-hidden="true" class="absolute left-1/2 -right-1/2 top-4 z-0 h-0.5 bg-gray-200 transition-colors"></span>
                    <span data-step-marker class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-bold text-gray-600">1</span>
                    <span class="px-1">Informasi Produk</span>
                </li>
                <li data-step-indicator="2" class="relative z-10 flex min-w-0 flex-col items-center gap-2 text-center text-xs font-medium text-gray-500 sm:text-sm">
                    <span data-step-marker class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-bold text-gray-600">2</span>
                    <span class="px-1">Nutrition Facts</span>
                </li>
            </ol>

            <section data-product-step="1" class="space-y-8">
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
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Gambar Produk
                    </label>

                    <p class="mb-3 text-xs text-gray-500">
                        Tambahkan beberapa gambar produk. Pilih salah satu gambar sebagai gambar utama.
                        Format PNG, JPG, WEBP maksimal 2 MB per gambar.
                    </p>

                    <input type="hidden" name="primary_image" id="primary-image" value="new:0">

                    <div
                        id="gallery-dropzone"
                        class="cursor-pointer rounded-lg border-2 border-dashed border-gray-300 p-5 text-center transition-colors hover:border-brand-dark"
                    >
                        <input
                            type="file"
                            id="gallery-images"
                            name="gallery_images[]"
                            accept="image/png,image/jpeg,image/webp"
                            multiple
                            class="hidden"
                        >

                        <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                        <p class="mt-2 text-sm font-medium text-brand-dark">
                            Klik untuk memilih gambar atau drag and drop
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Kamu dapat memilih beberapa gambar sekaligus.
                        </p>
                    </div>

                    <div
                        id="product-image-list"
                        class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4"
                    ></div>

                    <p id="image-empty-message" class="mt-3 text-sm text-gray-500">
                        Belum ada gambar dipilih.
                    </p>
                </div>
            </div>
                
            @include('admin.products.partials.variant-fields')

                <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                    <a href="{{ route('admin.products.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 text-center font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                    <button type="button" data-step-next class="rounded-lg bg-brand-dark px-6 py-2 font-medium text-white hover:bg-brand-accent">Selanjutnya: Nutrition Facts</button>
                </div>
            </section>

            <section data-product-step="2" class="hidden space-y-6">
                <div class="space-y-4 rounded-xl border border-gray-200 p-4 sm:p-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-800">Nutrition Facts</h2>
                        <p class="mt-1 text-sm text-gray-500">Pengisian informasi nilai gizi bersifat opsional.</p>
                    </div>
                    <label class="inline-flex w-fit cursor-pointer items-center gap-3 text-sm font-medium text-gray-700">
                        <span>Isi Nutrition Facts</span>
                        <span class="relative inline-flex">
                        <input
                            type="checkbox"
                            name="nutrition_facts_enabled"
                            id="nutrition-facts-enabled"
                            value="1"
                            role="switch"
                            @checked(old('nutrition_facts_enabled'))
                            class="peer sr-only"
                        >
                        <span aria-hidden="true" class="h-6 w-11 rounded-full bg-gray-300 transition-colors peer-checked:bg-brand-dark peer-focus-visible:outline-none peer-focus-visible:ring-2 peer-focus-visible:ring-brand-dark peer-focus-visible:ring-offset-2"></span>
                        <span aria-hidden="true" class="pointer-events-none absolute left-1 top-1 h-4 w-4 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                    </div>

                    <fieldset id="nutrition-fields-wrapper" class="min-w-0 space-y-6 {{ old('nutrition_facts_enabled') ? '' : 'opacity-50' }}" {{ old('nutrition_facts_enabled') ? '' : 'disabled' }}>
                        @include('admin.products.nutrition-fields')
                    </fieldset>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                    <button type="button" data-step-previous class="rounded-lg border border-gray-300 px-6 py-2 font-medium text-gray-700 hover:bg-gray-50">Kembali</button>
                    <button type="submit" class="rounded-lg bg-brand-dark px-6 py-2 font-medium text-white hover:bg-brand-accent">Simpan Produk</button>
                </div>
            </section>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('gallery-images');
    const dropzone = document.getElementById('gallery-dropzone');
    const imageList = document.getElementById('product-image-list');
    const primaryInput = document.getElementById('primary-image');
    const emptyMessage = document.getElementById('image-empty-message');

    if (!input || !dropzone || !imageList || !primaryInput) return;

    let selectedFiles = [];
    let primaryIndex = 0;

    function syncInput() {
        const dataTransfer = new DataTransfer();

        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });

        input.files = dataTransfer.files;
    }

    function renderImages() {
        imageList.innerHTML = '';

        if (selectedFiles.length === 0) {
            emptyMessage.classList.remove('hidden');
            primaryInput.value = '';
            return;
        }

        emptyMessage.classList.add('hidden');

        if (primaryIndex >= selectedFiles.length) {
            primaryIndex = 0;
        }

        primaryInput.value = `new:${primaryIndex}`;

        selectedFiles.forEach((file, index) => {
            const wrapper = document.createElement('div');

            wrapper.className = index === primaryIndex
                ? 'relative rounded-lg border-2 border-brand-dark bg-gray-50 p-2'
                : 'relative rounded-lg border border-gray-200 p-2';

            const image = document.createElement('img');
            image.alt = file.name;
            image.className = 'h-32 w-full rounded-md object-contain';

            const reader = new FileReader();
            reader.onload = event => {
                image.src = event.target.result;
            };
            reader.readAsDataURL(file);

            const primaryButton = document.createElement('button');
            primaryButton.type = 'button';
            primaryButton.textContent = index === primaryIndex
                ? 'Gambar Utama'
                : 'Jadikan Utama';

            primaryButton.className = index === primaryIndex
                ? 'mt-2 w-full rounded-md bg-brand-dark px-2 py-1.5 text-xs font-semibold text-white'
                : 'mt-2 w-full rounded-md border border-gray-300 px-2 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50';

            primaryButton.addEventListener('click', () => {
                primaryIndex = index;
                renderImages();
            });

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.textContent = '×';
            removeButton.title = 'Hapus gambar';
            removeButton.className = 'absolute -right-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-600 text-lg font-bold text-white shadow hover:bg-red-700';

            removeButton.addEventListener('click', () => {
                selectedFiles.splice(index, 1);

                if (index === primaryIndex) {
                    primaryIndex = 0;
                } else if (index < primaryIndex) {
                    primaryIndex--;
                }

                syncInput();
                renderImages();
            });

            const fileName = document.createElement('p');
            fileName.textContent = file.name;
            fileName.className = 'mt-1 truncate text-center text-xs text-gray-500';

            wrapper.append(image, primaryButton, removeButton, fileName);
            imageList.appendChild(wrapper);
        });
    }

    function addFiles(files) {
        const validTypes = ['image/png', 'image/jpeg', 'image/webp'];

        Array.from(files).forEach(file => {
            if (!validTypes.includes(file.type)) {
                alert(`${file.name}: format harus PNG, JPG, atau WEBP.`);
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert(`${file.name}: ukuran maksimal 2 MB.`);
                return;
            }

            const alreadyAdded = selectedFiles.some(existing =>
                existing.name === file.name &&
                existing.size === file.size &&
                existing.lastModified === file.lastModified
            );

            if (!alreadyAdded) {
                selectedFiles.push(file);
            }
        });

        syncInput();
        renderImages();
    }

    dropzone.addEventListener('click', event => {
        if (event.target.closest('button')) return;
        input.click();
    });

    input.addEventListener('change', function () {
        addFiles(this.files);
    });

    dropzone.addEventListener('dragover', event => {
        event.preventDefault();
        dropzone.classList.add('border-brand-dark', 'bg-gray-50');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('border-brand-dark', 'bg-gray-50');
    });

    dropzone.addEventListener('drop', event => {
        event.preventDefault();
        dropzone.classList.remove('border-brand-dark', 'bg-gray-50');
        addFiles(event.dataTransfer.files);
    });

    renderImages();
});      
</script>
@endsection
