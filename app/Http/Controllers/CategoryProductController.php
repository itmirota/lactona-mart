<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Services\ActivityLogger;
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

        $categoryProduct = CategoryProduct::create([
            'name' => $request->name,
        ]);

        ActivityLogger::log(
            'created',
            'Kategori',
            "Menambahkan kategori {$categoryProduct->name}",
            $categoryProduct,
            [
                'name' => $categoryProduct->name,
            ]
        );

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

        $oldName = $categoryProduct->name;

        $categoryProduct->update([
            'name' => $request->name,
        ]);

        ActivityLogger::log(
            'updated',
            'Kategori',
            "Mengubah kategori {$oldName} menjadi {$categoryProduct->name}",
            $categoryProduct,
            [
                'old' => [
                    'name' => $oldName,
                ],
                'new' => [
                    'name' => $categoryProduct->name,
                ],
            ]
        );

        return redirect()->route('admin.categoryProducts.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryProduct $categoryProduct): RedirectResponse
    {
        ActivityLogger::log(
            'deleted',
            'Kategori',
            "Menghapus kategori {$categoryProduct->name}",
            $categoryProduct,
            [
                'name' => $categoryProduct->name,
            ]
        );

        $categoryProduct->delete();

        return redirect()->route('admin.categoryProducts.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
