<?php

use App\Http\Controllers\AssetsController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(
    [
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ]
)->group(function () {
    Route::get('/', [MainController::class, 'dashboard'])->name('dashboard');

    Route::get('/assets/images/{path}', [AssetsController::class, 'getImage'])->where('path','.+')->name('assets.images');

    Route::prefix('catalog')->name('catalog.')->group(function () {
        Route::resource('suppliers', SupplierController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
    });

    Route::prefix('sales')->name('sales.')->group(function () {
        Route::resource('orders', OrderController::class);
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('branches', BranchController::class);
        Route::resource('users', UserController::class);
    });
});
