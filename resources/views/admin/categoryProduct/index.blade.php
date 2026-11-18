@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Kategori Produk</h1>
        <a href="{{ route('categoryProducts.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Tambah Produk</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="p-4">ID</th>
                    <th class="p-4">Nama Kategori Produk</th>
                    <th class="p-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($CategoryProducts as $CategoryProducts)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4">{{ $CategoryProducts->id }}</td>
                    <td class="p-4 font-semibold">{{ $CategoryProducts->name }}</td>
                    <td class="p-4 flex space-x-2">
                        <a href="{{ route('categoryProducts.edit', $CategoryProducts->id) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('categoryProducts.destroy', $CategoryProducts->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection