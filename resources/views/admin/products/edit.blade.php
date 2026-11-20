@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-heading', 'Edit Produk')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Produk: {{ $product->name }}</h1>
        <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-brand-dark flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <!-- Menampilkan pesan error validasi jika ada -->
    @if ($errors->any())
        <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-200">
            <p class="mb-2 font-semibold">Periksa kembali data berikut:</p>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Form Edit -->
        <!-- Jangan lupa menambahkan enctype="multipart/form-data" jika ada upload gambar -->
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf <!-- CSRF Token Laravel (Wajib) -->
            @method('PUT') <!-- Method Spoofing untuk mengubah POST menjadi PUT sesuai standar rute resource -->

             <!-- info langkah -->
            <div class="mb-8 flex items-center gap-3">
                <div id="step-indicator-1"
                     class="flex items-center gap-2 font-semibold text-brand-dark">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-dark text-white">1</span>
                    <span>Data Produk</span>
                </div>

                <div class="h-px flex-1 bg-gray-200"></div>

                <div id="step-indicator-2"
                     class="flex items-center gap-2 text-gray-400">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200">2</span>
                    <span>Nutrition Facts</span>
                </div>
            </div>

            <!-- Edit Data Produk -->
            <section id="product-step">
                <h2 class="mb-1 text-lg font-semibold text-gray-800">Informasi Produk</h2>
                <p class="mb-6 text-sm text-gray-500">
                    Perbarui informasi utama produk.
                </p>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="category_id" class="mb-1 block text-sm font-medium text-gray-700">
                            Kategori Produk <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" id="category_id" required
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-brand-dark focus:ring-brand-dark">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="name" class="mb-1 block text-sm font-medium text-gray-700">
                            Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                               value="{{ old('name', $product->name) }}" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-brand-dark focus:ring-brand-dark">
                    </div>

                    <div class="md:col-span-2">
                        <label for="price" class="mb-1 block text-sm font-medium text-gray-700">
                            Harga (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="price" id="price"
                               value="{{ old('price', $product->price) }}" required min="0"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-brand-dark focus:ring-brand-dark">
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="mb-1 block text-sm font-medium text-gray-700">
                            Keterangan / Deskripsi
                        </label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-brand-dark focus:ring-brand-dark">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <!-- Upload gambar -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Ganti Gambar Produk (Opsional)
                        </label>

                        @if ($product->image)
                            <div class="mb-4">
                                <p class="mb-2 text-sm text-gray-500">Gambar saat ini:</p>
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="h-36 w-36 rounded-lg border border-gray-200 bg-white object-contain p-2">
                            </div>
                        @endif

                        <div id="drop-zone"
                             class="rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 px-6 py-8 text-center transition hover:border-brand-dark hover:bg-blue-50">
                            <input id="image" name="image" type="file"
                                   accept="image/png,image/jpeg,image/jpg,image/webp"
                                   class="sr-only">

                            <svg class="mx-auto mb-3 h-12 w-12 text-gray-400"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="1.5"
                                      d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 7.5L12 3m0 0L7.5 7.5M12 3v13.5"/>
                            </svg>

                            <p class="text-sm text-gray-600">Seret dan jatuhkan gambar baru ke sini</p>
                            <p class="my-2 text-xs text-gray-400">atau</p>

                            <label for="image"
                                   class="inline-flex cursor-pointer rounded-lg bg-brand-dark px-4 py-2 text-sm font-medium text-white hover:bg-brand-accent">
                                Upload File Baru
                            </label>

                            <p class="mt-3 text-xs text-gray-500">
                                PNG, JPG, JPEG, WEBP. Maksimal 2 MB.
                                Kosongkan jika tidak ingin mengganti gambar.
                            </p>
                            <p id="file-name-display" class="mt-2 hidden text-sm font-medium text-brand-dark"></p>
                        </div>

                        <div id="image-preview-container" class="mt-4 hidden">
                            <p class="mb-2 text-sm font-medium text-gray-700">Preview Gambar Baru:</p>
                            <img id="image-preview" src="" alt="Preview gambar baru"
                                 class="h-36 w-36 rounded-lg border border-gray-200 bg-white object-contain p-2">
                            <button type="button" id="remove-image"
                                    class="mt-2 block text-sm font-medium text-red-600 hover:text-red-800">
                                Batalkan gambar baru
                            </button>
                        </div>
                    </div>
                </div>

                @include('admin.products.partials.variant-fields')

                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('admin.products.index') }}"
                       class="rounded-lg border border-gray-300 px-6 py-2 font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="button" id="next-step"
                            class="rounded-lg bg-brand-dark px-8 py-2 font-medium text-white hover:bg-brand-accent">
                        Selanjutnya
                    </button>
                </div>
            </section>

            <!-- Data Nutrition Facts -->
            <section id="nutrition-step" class="hidden">
                <div class="mb-6 flex flex-col gap-4 rounded-xl border border-gray-200 bg-gray-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-800">Nutrition Facts</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Pengisian informasi nilai gizi bersifat opsional.
                        </p>
                    </div>

                    @php
                        $nutritionEnabled = old(
                            'nutrition_facts_enabled',
                            !empty($product->nutrition_facts) ? 1 : 0
                        );
                    @endphp

                    <label class="inline-flex cursor-pointer items-center gap-3">
                        <span id="nutrition-toggle-label" class="text-sm font-semibold text-gray-600">OFF</span>
                        <input type="checkbox" id="nutrition-toggle"
                               name="nutrition_facts_enabled" value="1"
                               class="peer sr-only"
                               {{ $nutritionEnabled ? 'checked' : '' }}>
                        <span class="relative h-6 w-11 rounded-full bg-gray-300 transition peer-checked:bg-brand-dark
                                     after:absolute after:left-1 after:top-1 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all
                                     peer-checked:after:translate-x-5"></span>
                    </label>
                </div>

                <fieldset id="nutrition-fields-wrapper"
                          class="space-y-6 {{ $nutritionEnabled ? '' : 'opacity-50' }}"
                          {{ $nutritionEnabled ? '' : 'disabled' }}>
                    @include('admin.products.nutrition-fields')
                </fieldset>

                <div class="mt-8 flex flex-col-reverse justify-between gap-3 sm:flex-row">
                    <button type="button" id="previous-step"
                            class="rounded-lg border border-gray-300 px-6 py-2 font-medium text-gray-700 hover:bg-gray-50">
                        Kembali
                    </button>

                    <button type="submit"
                            class="rounded-lg bg-brand-dark px-8 py-2 font-medium text-white hover:bg-brand-accent">
                        Simpan Perubahan
                    </button>
                </div>
            </section>
        </form>
    </div>
</div>

<div
    id="nutrition-validation-state"
    data-has-errors="{{ collect($errors->keys())->contains(fn ($key) => str_starts_with($key, 'nutrition_facts')) ? '1' : '0' }}"
    hidden>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const productStep = document.getElementById('product-step');
    const nutritionStep = document.getElementById('nutrition-step');
    const indicator1 = document.getElementById('step-indicator-1');
    const indicator2 = document.getElementById('step-indicator-2');

    function showStep(step) {
        const first = step === 1;

        productStep.classList.toggle('hidden', !first);
        nutritionStep.classList.toggle('hidden', first);

        indicator1.classList.toggle('text-brand-dark', first);
        indicator1.classList.toggle('text-gray-400', !first);
        indicator2.classList.toggle('text-brand-dark', !first);
        indicator2.classList.toggle('text-gray-400', first);

        indicator1.querySelector('span').classList.toggle('bg-brand-dark', first);
        indicator1.querySelector('span').classList.toggle('text-white', first);
        indicator1.querySelector('span').classList.toggle('bg-gray-200', !first);

        indicator2.querySelector('span').classList.toggle('bg-brand-dark', !first);
        indicator2.querySelector('span').classList.toggle('text-white', !first);
        indicator2.querySelector('span').classList.toggle('bg-gray-200', first);

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    document.getElementById('next-step').addEventListener('click', function () {
        const fields = productStep.querySelectorAll('[required]');

        for (const field of fields) {
            if (!field.checkValidity()) {
                field.reportValidity();
                return;
            }
        }

        showStep(2);
    });

    document.getElementById('previous-step').addEventListener('click', () => showStep(1));

    // Toggle Nutrition Facts
    const toggle = document.getElementById('nutrition-toggle');
    const toggleLabel = document.getElementById('nutrition-toggle-label');
    const wrapper = document.getElementById('nutrition-fields-wrapper');

    function syncNutritionToggle() {
        const enabled = toggle.checked;

        toggleLabel.textContent = enabled ? 'ON' : 'OFF';
        toggleLabel.classList.toggle('text-brand-dark', enabled);
        toggleLabel.classList.toggle('text-gray-600', !enabled);
        wrapper.classList.toggle('opacity-50', !enabled);
        wrapper.disabled = !enabled;
    }

    toggle.addEventListener('change', syncNutritionToggle);
    syncNutritionToggle();

    // Kembalikan ke langkah kedua jika validasi Nutrition Facts gagal
    const validationState = document.getElementById('nutrition-validation-state');

    if (validationState && validationState.dataset.hasErrors === '1') {
        showStep(2);
    }

    // Preview dan drag & drop gambar
    const dropZone = document.getElementById('drop-zone');
    const imageInput = document.getElementById('image');
    const fileNameDisplay = document.getElementById('file-name-display');
    const previewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const removeButton = document.getElementById('remove-image');
    let previewUrl = null;

    function clearPreview() {
        if (previewUrl) URL.revokeObjectURL(previewUrl);
        previewUrl = null;
        imagePreview.src = '';
        previewContainer.classList.add('hidden');
    }

    function processFile(file) {
        if (!file) return;

        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            alert('Format gambar harus PNG, JPG, JPEG, atau WEBP.');
            imageInput.value = '';
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran gambar maksimal 2 MB.');
            imageInput.value = '';
            return;
        }

        const transfer = new DataTransfer();
        transfer.items.add(file);
        imageInput.files = transfer.files;

        clearPreview();
        previewUrl = URL.createObjectURL(file);
        imagePreview.src = previewUrl;
        previewContainer.classList.remove('hidden');
        fileNameDisplay.textContent = 'File baru terpilih: ' + file.name;
        fileNameDisplay.classList.remove('hidden');
    }

    imageInput.addEventListener('change', () => processFile(imageInput.files[0]));

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(name => {
        dropZone.addEventListener(name, event => {
            event.preventDefault();
            event.stopPropagation();
        });
    });

    ['dragenter', 'dragover'].forEach(name => {
        dropZone.addEventListener(name, () => {
            dropZone.classList.add('border-brand-dark', 'bg-blue-50');
        });
    });

    ['dragleave', 'drop'].forEach(name => {
        dropZone.addEventListener(name, () => {
            dropZone.classList.remove('border-brand-dark', 'bg-blue-50');
        });
    });

    dropZone.addEventListener('drop', event => {
        processFile(event.dataTransfer.files[0]);
    });

    removeButton.addEventListener('click', () => {
        imageInput.value = '';
        clearPreview();
        fileNameDisplay.textContent = '';
        fileNameDisplay.classList.add('hidden');
    });
});
</script>
@endsection
