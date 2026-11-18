@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Produk Baru</h1>
        <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-brand-dark flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <!-- Menampilkan pesan error validasi jika ada -->
    @if ($errors->any())
        <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-200">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Form Create -->
        <!-- Pastikan menambahkan enctype="multipart/form-data" karena ada upload gambar -->
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf <!-- CSRF Token Laravel (Wajib) -->

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Field Kategori Produk -->
                <div class="md:col-span-2">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori Produk <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark outline-none transition-colors">
                        <option value="" disabled selected>-- Pilih Kategori --</option>
                        
                        <!-- Melakukan perulangan (loop) untuk menampilkan semua nama kategori -->
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Field Nama Produk -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark outline-none transition-colors"
                           placeholder="Contoh: Lactona Ibu Rasa Cokelat">
                </div>

                <!-- Field Harga -->
                <div class="md:col-span-2">
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark outline-none transition-colors"
                           placeholder="Contoh: 59000">
                </div>

                <!-- Field Keterangan / Deskripsi -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Keterangan / Deskripsi</label>
                    <textarea name="description" id="description" rows="4" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark outline-none transition-colors"
                              placeholder="Deskripsi singkat produk...">{{ old('description') }}</textarea>
                </div>

                <!-- Field Gambar Produk -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar Produk</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-brand-dark transition-colors cursor-pointer" id="drop-zone" onclick="document.getElementById('image').click()">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <span class="relative cursor-pointer bg-white rounded-md font-medium text-brand-dark hover:text-brand-accent focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-brand-dark">
                                    <span>Upload file</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="updateFileName(this)">
                                </span>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500" id="file-name-display">PNG, JPG, WEBP max 2MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-8 flex justify-end">
                <a href="{{ route('products.index') }}" class="bg-white border border-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg mr-3 hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white font-medium py-2 px-8 rounded-lg transition-colors">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<!-- Script sederhana untuk menampilkan nama file gambar yang dipilih -->
<script>
    function updateFileName(input) {
        const fileNameDisplay = document.getElementById('file-name-display');
        if (input.files && input.files[0]) {
            fileNameDisplay.textContent = "File terpilih: " + input.files[0].name;
            fileNameDisplay.classList.add('text-brand-dark', 'font-semibold');
        } else {
            fileNameDisplay.textContent = "PNG, JPG, WEBP max 2MB";
            fileNameDisplay.classList.remove('text-brand-dark', 'font-semibold');
        }
    }
</script>
@endsection