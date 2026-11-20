<?php

use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDiscountController;
use App\Http\Controllers\StorefrontController;
use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/produk-kami', [StorefrontController::class, 'products'])->name('products.public');
Route::get('/detail/{product?}', [StorefrontController::class, 'detail'])->name('products.detail');

Route::prefix('lactonaAdmin')->group(function () {
    Route::get('/', function () {
        $totalProducts = Product::count();
        $totalCategories = CategoryProduct::count();

        $recentProducts = Product::with('category')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'recentProducts'
        ));
    })->name('admin.dashboard');
    Route::resource('products', ProductController::class)->names('admin.products');
    Route::resource('discounts', ProductDiscountController::class)
        ->except('show')
        ->names('admin.discounts');
    Route::resource('categoryProducts', CategoryProductController::class)->names('admin.categoryProducts');
});
