<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    // 1. READ: Menampilkan daftar produk
    public function index()
    {
        $CategoryProducts = CategoryProduct::all();
        return view('admin.CategoryProduct.index', compact('CategoryProducts'));
    }

    // 2. CREATE: Menampilkan form tambah produk
    public function create()
    {
        return view('admin.CategoryProduct.create');
    }

    // 3. Menyimpan data produk baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        categoryProduct::create([
            'name' => $request->name,
        ]);

        return redirect()->route('categoryProducts.index')->with('success', 'Produk berhasil ditambahkan!');;
    }

    // 4. edit
    public function edit(CategoryProduct $categoryProduct)
    {
        return view('admin.categoryProduct.edit', compact('categoryProduct'));
    }

    // 5. update
    public function update(Request $request, CategoryProduct $categoryProduct)
    {
        $categoryProduct->update([
            'name' => $request->name,
        ]);

        return redirect()->route('categoryProducts.index')->with('success', 'Produk berhasil diupdate!');
    }

    // 6. DELETE: Menghapus produk
    public function destroy(CategoryProduct $categoryProduct)
    {
        
        $categoryProduct->delete();

        return redirect()->route('categoryProducts.index')->with('success', 'Produk berhasil dihapus!');
    }
}
