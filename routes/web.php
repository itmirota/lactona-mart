<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryProductController;

Route::get('/', function () {
    return view('home');
});

Route::get('/produk-kami', function () {
    return view('produk');
});

Route::get('/detail', function () {
    return view('detail');
});

Route::resource('admin/products', ProductController::class);
Route::resource('admin/categoryProducts', CategoryProductController::class);
