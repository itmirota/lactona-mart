<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categoryProducts = CategoryProduct::all();

        return view('admin.categoryProduct.index', [
            'categoryProducts' => $categoryProducts,
            'CategoryProducts' => $categoryProducts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.categoryProduct.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        CategoryProduct::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.categoryProducts.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoryProduct $categoryProduct): View
    {
        return view('admin.categoryProduct.edit', compact('categoryProduct'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoryProduct $categoryProduct): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $categoryProduct->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.categoryProducts.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryProduct $categoryProduct): RedirectResponse
    {
        $categoryProduct->delete();

        return redirect()->route('admin.categoryProducts.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
