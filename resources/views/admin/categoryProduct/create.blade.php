@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Baru</h1>
        <a href="{{ route('categoryProducts.index') }}" class="text-gray-600 hover:text-brand-dark flex items-center">
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
        <form action="{{ route('categoryProducts.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf <!-- CSRF Token Laravel (Wajib) -->

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Field Nama Produk -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-brand-dark focus:border-brand-dark outline-none transition-colors"
                           placeholder="Contoh: anak">
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-8 flex justify-end">
                <a href="{{ route('categoryProducts.index') }}" class="bg-white border border-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg mr-3 hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white font-medium py-2 px-8 rounded-lg transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection