<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/catalog/categories/{category}/move', [CategoryController::class, 'move'])->name('catalog.categories.move');
        Route::get('/catalog/categories/tree/{parent?}', [CategoryController::class, 'getTree'])->name('catalog.categories.tree');
        Route::get('/catalog/products/search', [ProductController::class, 'search'])->name('catalog.products.search');
    });
});

