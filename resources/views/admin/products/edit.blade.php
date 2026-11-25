@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-heading', 'Edit Produk')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Edit Produk</h1>
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
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-8 p-4 sm:p-6 lg:p-8" data-product-wizard data-start-step="{{ $errors->has('nutrition_facts') || collect($errors->keys())->contains(fn ($key) => str_starts_with($key, 'nutrition_facts.')) ? 2 : 1 }}">
            @csrf
            @method('PUT')

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
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required maxlength="255" class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-brand-dark focus:ring-brand-dark">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="price" class="mb-1 block text-sm font-medium text-gray-700">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required min="0" step="0.01" class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-brand-dark focus:ring-brand-dark">
                    @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="mb-1 block text-sm font-medium text-gray-700">Keterangan / Deskripsi</label>
                    <textarea name="description" id="description" rows="4" class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-brand-dark focus:ring-brand-dark">{{ old('description', $product->description) }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
  
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Gambar Produk
                    </label>

                    <p class="mb-3 text-xs text-gray-500">
                        Pilih gambar utama, hapus gambar yang tidak diperlukan, atau tambahkan gambar baru.
                        Format PNG, JPG, WEBP maksimal 2 MB per gambar.
                    </p>

                    <input
                        type="hidden"
                        name="primary_image"
                        id="primary-image"
                        value="current"
                    >

                    <input
                        type="hidden"
                        name="remove_current_image"
                        id="remove-current-image-input"
                        value="0"
                    >

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
                            Klik untuk menambahkan gambar atau drag and drop
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Kamu dapat memilih beberapa gambar sekaligus.
                        </p>
                    </div>

                    <div
                        id="product-image-list"
                        class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4"
                    >
                        @if ($product->image)
                            <div
                                class="product-image-card relative rounded-lg border-2 border-brand-dark bg-gray-50 p-2"
                                data-image-type="current"
                                data-image-value="current"
                            >
                                <img
                                    src="{{ $product->image_url }}"
                                    alt="{{ $product->name }}"
                                    class="h-32 w-full rounded-md object-contain"
                                >

                                <button
                                    type="button"
                                    data-select-primary="current"
                                    class="primary-button mt-2 w-full rounded-md bg-brand-dark px-2 py-1.5 text-xs font-semibold text-white"
                                >
                                    Gambar Utama
                                </button>

                                <button
                                    type="button"
                                    data-remove-current
                                    class="absolute -right-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-600 text-lg font-bold text-white shadow hover:bg-red-700"
                                    title="Hapus gambar"
                                >
                                    ×
                                </button>

                                <p class="mt-1 text-center text-xs text-gray-500">
                                    Gambar utama saat ini
                                </p>
                            </div>
                        @endif

                        @foreach ($product->images as $galleryImage)
                            <div
                                class="product-image-card relative rounded-lg border border-gray-200 p-2"
                                data-image-type="gallery"
                                data-image-value="gallery:{{ $galleryImage->id }}"
                                data-gallery-id="{{ $galleryImage->id }}"
                            >
                                <img
                                    src="{{ $galleryImage->image_url }}"
                                    alt="Galeri {{ $product->name }}"
                                    class="h-32 w-full rounded-md object-contain"
                                >

                                <button
                                    type="button"
                                    data-select-primary="gallery:{{ $galleryImage->id }}"
                                    class="primary-button mt-2 w-full rounded-md border border-gray-300 px-2 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50"
                                >
                                    Jadikan Utama
                                </button>

                                <input
                                    type="checkbox"
                                    name="remove_gallery_images[]"
                                    value="{{ $galleryImage->id }}"
                                    class="hidden"
                                    data-remove-gallery-input
                                >

                                <button
                                    type="button"
                                    data-remove-gallery
                                    class="absolute -right-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-600 text-lg font-bold text-white shadow hover:bg-red-700"
                                    title="Hapus gambar"
                                >
                                    ×
                                </button>

                                <p class="mt-1 text-center text-xs text-gray-500">
                                    Gambar tersimpan
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <p id="image-empty-message" class="mt-3 text-sm text-gray-500 hidden">
                        Belum ada gambar produk.
                    </p>
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
                    <button type="button" data-step-next class="rounded-lg bg-brand-dark px-6 py-2 font-medium text-white hover:bg-brand-accent">Selanjutnya: Nutrition Facts</button>
                </div>
            </section>

            @php
                $nutritionEnabled = old('nutrition_facts_enabled', !empty($product->nutrition_facts));
            @endphp

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
                            @checked($nutritionEnabled)
                            class="peer sr-only"
                        >
                        <span aria-hidden="true" class="h-6 w-11 rounded-full bg-gray-300 transition-colors peer-checked:bg-brand-dark peer-focus-visible:outline-none peer-focus-visible:ring-2 peer-focus-visible:ring-brand-dark peer-focus-visible:ring-offset-2"></span>
                        <span aria-hidden="true" class="pointer-events-none absolute left-1 top-1 h-4 w-4 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                    </div>

                    <fieldset id="nutrition-fields-wrapper" class="min-w-0 space-y-6 {{ $nutritionEnabled ? '' : 'opacity-50' }}" {{ $nutritionEnabled ? '' : 'disabled' }}>
                        @include('admin.products.nutrition-fields')
                    </fieldset>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                    <button type="button" data-step-previous class="rounded-lg border border-gray-300 px-6 py-2 font-medium text-gray-700 hover:bg-gray-50">Kembali</button>
                    <button type="submit" class="rounded-lg bg-brand-dark px-6 py-2 font-medium text-white hover:bg-brand-accent">Simpan Perubahan</button>
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
    const removeCurrentInput = document.getElementById('remove-current-image-input');
    const emptyMessage = document.getElementById('image-empty-message');

    if (!input || !dropzone || !imageList || !primaryInput) return;

    let selectedFiles = [];
    let primaryChoice = primaryInput.value || 'current';

    function getVisibleCards() {
        return Array.from(imageList.querySelectorAll('.product-image-card'))
            .filter(card => !card.classList.contains('hidden'));
    }

    function hasChoice(value) {
        return getVisibleCards().some(card => card.dataset.imageValue === value);
    }

    function updatePrimary(value) {
        if (!hasChoice(value)) {
            const firstCard = getVisibleCards()[0];
            value = firstCard ? firstCard.dataset.imageValue : 'current';
        }

        primaryChoice = value;
        primaryInput.value = value;

        imageList.querySelectorAll('.product-image-card').forEach(card => {
            const isPrimary = card.dataset.imageValue === value;

            card.classList.toggle('border-2', isPrimary);
            card.classList.toggle('border-brand-dark', isPrimary);
            card.classList.toggle('bg-gray-50', isPrimary);
            card.classList.toggle('border-gray-200', !isPrimary);

            const button = card.querySelector('.primary-button');

            if (button) {
                button.textContent = isPrimary ? 'Gambar Utama' : 'Jadikan Utama';
                button.className = isPrimary
                    ? 'primary-button mt-2 w-full rounded-md bg-brand-dark px-2 py-1.5 text-xs font-semibold text-white'
                    : 'primary-button mt-2 w-full rounded-md border border-gray-300 px-2 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50';
            }
        });

        updateEmptyMessage();
    }

    function updateEmptyMessage() {
        const hasImages = getVisibleCards().length > 0 || selectedFiles.length > 0;
        emptyMessage.classList.toggle('hidden', hasImages);
    }

    function syncInput() {
        const dataTransfer = new DataTransfer();

        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });

        input.files = dataTransfer.files;
    }

    function renderNewImages() {
        imageList.querySelectorAll('[data-new-image]').forEach(card => card.remove());

        selectedFiles.forEach((file, index) => {
            const wrapper = document.createElement('div');
            const value = `new:${index}`;

            wrapper.dataset.newImage = '1';
            wrapper.dataset.imageValue = value;
            wrapper.className = 'product-image-card relative rounded-lg border border-gray-200 p-2';

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
            primaryButton.dataset.selectPrimary = value;
            primaryButton.className = 'primary-button mt-2 w-full rounded-md border border-gray-300 px-2 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50';
            primaryButton.textContent = 'Jadikan Utama';

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.textContent = '×';
            removeButton.title = 'Hapus gambar';
            removeButton.className = 'absolute -right-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-600 text-lg font-bold text-white shadow hover:bg-red-700';

            removeButton.addEventListener('click', () => {
                selectedFiles.splice(index, 1);

                if (primaryChoice === value) {
                    primaryChoice = 'current';
                } else if (primaryChoice.startsWith('new:')) {
                    const oldIndex = Number(primaryChoice.split(':')[1]);

                    if (oldIndex > index) {
                        primaryChoice = `new:${oldIndex - 1}`;
                    }
                }

                syncInput();
                renderNewImages();
                updatePrimary(primaryChoice);
            });

            const fileName = document.createElement('p');
            fileName.textContent = file.name;
            fileName.className = 'mt-1 truncate text-center text-xs text-gray-500';

            wrapper.append(image, primaryButton, removeButton, fileName);
            imageList.appendChild(wrapper);
        });

        updatePrimary(primaryChoice);
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
        renderNewImages();
    }

    imageList.addEventListener('click', event => {
        const primaryButton = event.target.closest('[data-select-primary]');

        if (primaryButton) {
            updatePrimary(primaryButton.dataset.selectPrimary);
            return;
        }

        const removeCurrentButton = event.target.closest('[data-remove-current]');

        if (removeCurrentButton) {
            if (!confirm('Hapus gambar utama saat ini?')) return;

            const card = removeCurrentButton.closest('.product-image-card');
            card.classList.add('hidden');
            removeCurrentInput.value = '1';

            if (primaryChoice === 'current') {
                updatePrimary('');
            } else {
                updateEmptyMessage();
            }

            return;
        }

        const removeGalleryButton = event.target.closest('[data-remove-gallery]');

        if (removeGalleryButton) {
            if (!confirm('Hapus gambar ini?')) return;

            const card = removeGalleryButton.closest('.product-image-card');
            const checkbox = card.querySelector('[data-remove-gallery-input]');
            const value = card.dataset.imageValue;

            checkbox.checked = true;
            card.classList.add('hidden');

            if (primaryChoice === value) {
                updatePrimary('');
            } else {
                updateEmptyMessage();
            }
        }
    });

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

    updatePrimary(primaryChoice);
    updateEmptyMessage();
});
</script>
@endsection
