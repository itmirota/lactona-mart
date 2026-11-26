<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDiscountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorefrontController;
use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/produk-kami', [StorefrontController::class, 'products'])->name('products.public');
Route::get('/keranjang/produk', [StorefrontController::class, 'cartProducts'])->name('cart.products');
Route::get('/detail/{product?}', [StorefrontController::class, 'detail'])->name('products.detail');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'active', 'can:dashboard.view'])->name('dashboard');

Route::middleware(['auth', 'active', 'can:dashboard.view'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('lactonaAdmin')->middleware(['auth', 'active'])->group(function () {
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
    })->middleware('can:dashboard.view')->name('admin.dashboard');

    Route::resource('products', ProductController::class)
        ->except('show')
        ->middleware('can:products.manage')
        ->names('admin.products');
    Route::resource('discounts', ProductDiscountController::class)
        ->except('show')
        ->middleware('can:discounts.manage')
        ->names('admin.discounts');
    Route::resource('categoryProducts', CategoryProductController::class)
        ->except('show')
        ->middleware('can:categories.manage')
        ->names('admin.categoryProducts');

    Route::prefix('users')->middleware('can:users.manage')->name('admin.users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/create', [AdminUserController::class, 'create'])->name('create');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
