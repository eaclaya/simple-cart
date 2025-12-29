<?php

use App\Http\Controllers\Admin\CartController as AdminCartController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('products', [ProductController::class, 'index'])->name('products.index');
Route::get('cart', [CartController::class, 'index'])->name('cart.index');

Route::middleware(['auth', 'verified'])->group(function () {
    // User cart routes
    Route::post('cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('products', [AdminProductController::class, 'index'])->name('admin.products');
    Route::get('carts', [AdminCartController::class, 'index'])->name('admin.carts');
    Route::get('orders', [OrderController::class, 'index'])->name('admin.orders');
});

require __DIR__.'/settings.php';
