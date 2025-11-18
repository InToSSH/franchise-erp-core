<?php

use App\Domain\Sales\Mail\OrderToSupplierMail;
use App\Domain\Sales\Models\Order;
use App\Http\Controllers\AssetsController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
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
        Route::put('orders/{order}/approve', [OrderController::class, 'approve'])->name('orders.approve');
        Route::put('orders/{order}/set-for-approval', [OrderController::class, 'setForApproval'])->name('orders.set-for-approval');
        Route::put('orders/{order}/return-to-draft', [OrderController::class, 'returnToDraft'])->name('orders.return-to-draft');
        Route::put('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::get('orders/{order}/test-mail', function(Order $order) {
            $suppliers = $order->items()
                ->with('product.supplier')
                ->get()
                ->pluck('product.supplier')
                ->unique('id');

            foreach ($suppliers as $supplier) {
                $items = $order->items()
                    ->with('product')
                    ->whereHas('product', fn($q) => $q->where('supplier_id', $supplier->id))
                    ->get();

                return new OrderToSupplierMail($order, $supplier, $items);
            }
        })->name('orders.test-mail');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('branches', BranchController::class);
        Route::resource('users', UserController::class);
    });
});
