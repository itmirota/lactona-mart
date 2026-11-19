<?php

use App\Models\Product;
use App\Models\CategoryProduct;

use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/produk-kami', [StorefrontController::class, 'products'])->name('products.public');
Route::get('/detail', function () {return view('detail');});

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
    Route::resource('categoryProducts', CategoryProductController::class)->names('admin.categoryProducts');
});