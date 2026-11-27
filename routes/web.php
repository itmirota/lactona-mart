<?php

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDiscountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/produk-kami', [StorefrontController::class, 'products'])->name('products.public');
Route::get('/keranjang/produk', [StorefrontController::class, 'cartProducts'])->name('cart.products');
Route::get('/detail/{product}', [StorefrontController::class, 'detail'])
    ->name('detail');;
Route::post('/checkout', [CheckoutController::class, 'store'])
    ->name('checkout.store');

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

        $activeProducts = Product::where('is_active', true)->count();
        $inactiveProducts = Product::where('is_active', false)->count();

        $recentProducts = Product::with('category')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'activeProducts',
            'inactiveProducts',
            'recentProducts'
        ));
    })->middleware('can:dashboard.view')->name('admin.dashboard');

    Route::get('products/search', [ProductController::class, 'search'])
        ->middleware('can:products.manage')
        ->name('admin.products.search');
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

    Route::prefix('orders')
        ->middleware('can:orders.manage')
        ->name('admin.orders.')
        ->group(function () {
            Route::get('/', [AdminOrderController::class, 'index'])
                ->name('index');
            Route::get('/{order}', [AdminOrderController::class, 'show'])
                ->name('show');
            Route::patch('/{order}/status', [AdminOrderController::class, 'updateStatus'])
                ->name('update-status');

            Route::middleware('role:superadmin')->group(function () {
                Route::delete('/{order}', [AdminOrderController::class, 'destroy'])
                    ->name('destroy');
            });
    });

    Route::prefix('users')->middleware('can:users.manage')->name('admin.users.')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::get('/create', [AdminUserController::class, 'create'])->name('create');
            Route::post('/', [AdminUserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
            Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
        });

    Route::prefix('activity-logs')
        ->middleware('can:activity-logs.view')
        ->name('admin.activity-logs.')
        ->group(function () {
            Route::get('/', [ActivityLogController::class, 'index'])
                ->name('index');
        });
});

require __DIR__.'/auth.php';
