<?php

use App\Http\Controllers\AssetsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(
    [
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ]
)->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/assets/images/{path}', [AssetsController::class, 'getImage'])->where('path','.+')->name('assets.images');

    Route::prefix('catalog')->name('catalog.')->group(function () {
        Route::resource('suppliers', SupplierController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
    });
});
