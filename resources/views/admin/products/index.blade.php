@extends('layouts.admin')

@section('title', 'Produk')
@section('page-heading', 'Manajemen Produk')
@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Bagian Header & Tombol Tambah -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Produk</h1>
        <a href="{{ route('admin.products.create') }}" class="bg-brand-dark hover:bg-brand-accent text-white font-medium px-5 py-2.5 rounded-lg flex items-center transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Produk
        </a>
    </div>

    <!-- Alert Sukses / Notifikasi -->
    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabel Data -->
    <div class="bg-white shadow-sm border border-gray-100 rounded-xl overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600 uppercase tracking-wider">
                    <th class="p-4 font-semibold">ID</th>
                    <th class="p-4 font-semibold">Gambar</th>
                    <th class="p-4 font-semibold">Kategori</th>
                    <th class="p-4 font-semibold">Nama Produk</th>
                    <th class="p-4 font-semibold">Varian</th>
                    <th class="p-4 font-semibold">Harga</th>
                    <th class="p-4 font-semibold hidden md:table-cell">Keterangan</th>
                    <th class="p-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4 text-gray-500">{{ $loop->iteration }}</td>
                    
                    <!-- Kolom Gambar -->
                    <td class="p-4">
                        @if($product->image)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-md border border-gray-200 shadow-sm">
                        @else
                            <div class="w-12 h-12 bg-gray-100 rounded-md border border-gray-200 flex items-center justify-center text-xs text-gray-400">
                                Kosong
                            </div>
                        @endif
                    </td>
                    
                    <!-- Kolom Kategori (Mengambil nama kategori dari relasi model) -->
                    <td class="p-4">
                        <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full text-xs font-medium border border-blue-100">
                            {{ $product->category ? $product->category->name : 'Tanpa Kategori' }}
                        </span>
                    </td>
                    
                    <!-- Kolom Nama -->
                    <td class="p-4 font-medium text-gray-800">{{ $product->name }}</td>

                    <!-- Kolom Variant -->
                    <td class="p-4 font-medium text-gray-800">
                        @if($product->variantTypes->isNotEmpty())
                            @foreach($product->variantTypes as $type)
                            @foreach($type->variants as $variant)
                            @if($variant->is_active)
                                <span class="bg-green-100 text-green-800 px-2.5 py-1 rounded-full text-xs font-medium border border-green-200">
                                    {{ $variant->name }}
                                </span>
                            @endif
                            @endforeach
                            @endforeach
                        @else
                            <span class="bg-gray-100 text-gray-500 px-2.5 py-1 rounded-full text-xs font-medium border border-gray-200">
                                Tidak ada varian
                            </span>
                        @endif
                    </td>

                    <!-- Kolom Harga -->
                    <td class="p-4 text-gray-600">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    
                    <!-- Kolom Keterangan (Dibatasi 40 karakter agar tabel tidak memanjang) -->
                    <td class="p-4 text-gray-500 hidden md:table-cell">
                        {{ Str::limit($product->description, 40, '...') }}
                    </td>
                    
                    <!-- Kolom Aksi (Edit & Hapus) -->
                    <td class="p-4">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-1.5 rounded-md transition-colors" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Data tidak dapat dikembalikan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 p-1.5 rounded-md transition-colors" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <!-- Tampilan jika database masih kosong -->
                <tr>
                    <td colspan="8" class="p-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <p>Belum ada data produk.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
