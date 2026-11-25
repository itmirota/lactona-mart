<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;

class StorefrontController extends Controller
{
    public function home(): View
    {
        $products = Product::with(['category', 'currentDiscounts'])
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact('products'));
    }

    public function products(): View
    {
        $products = Product::with(['category', 'currentDiscounts'])
            ->latest()
            ->paginate(12);

        return view('produk', compact('products'));
    }

    public function detail(?Product $product = null): View
    {
        if (! $product || ! $product->exists) {
            $product = Product::with([
                'category',
                'currentDiscounts',
                'variantTypes.variants',
            ])->first();
        } else {
            $product->loadMissing([
                'category',
                'currentDiscounts',
                'variantTypes.variants',
            ]);
        }

        $nutritionFactSections = Product::nutritionFactSections();

        return view('detail', compact('nutritionFactSections', 'product'));
    }
}
