<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\Documents\DocumentCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');


    Route::middleware(['auth:sanctum'])->group(function () {
        Route::put('/user/dark-mode', [UserController::class, 'setDarkMode'])->name('user.set-dark-mode');

        Route::post('/catalog/categories/{category}/move', [CategoryController::class, 'move'])->name('catalog.categories.move');
        Route::get('/catalog/categories/tree/{parent?}', [CategoryController::class, 'getTree'])->name('catalog.categories.tree');
        Route::get('/catalog/products/search', [ProductController::class, 'search'])->name('catalog.products.search');

        Route::post('/documents/categories/{category}/move', [DocumentCategoryController::class, 'move'])->name('documents.categories.move');
        Route::get('/documents/categories/tree/{parent?}', [DocumentCategoryController::class, 'getTree'])->name('documents.categories.tree');
    });
});

