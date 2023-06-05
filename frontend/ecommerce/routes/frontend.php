<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ProductController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('login', [AuthController::class, 'login'])->name('index');
Route::post('login', [AuthController::class, 'do_login'])->name('login');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'do_register'])->name('do_register');
Route::get('forgot', [AuthController::class, 'forgot'])->name('forgot');
Route::post('forgot', [AuthController::class, 'do_forgot'])->name('do_forgot');
Route::get('password/reset/{token}', [AuthController::class, 'reset'])->name('password.reset');
Route::post('password/reset', [AuthController::class, 'do_reset'])->name('password.update');
Route::get('password/change', [AuthController::class, 'change'])->name('password.change');

Route::get('products', [ProductController::class, 'index'])->name('products.index');
Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::middleware(['auth'])->group(function () {

    Route::get('cart', [CartController::class, 'index'])->name('cart');
    Route::post('cart', [CartController::class, 'store'])->name('cart.store');
    Route::post('cart/update', [CartController::class, 'update'])->name('cart.update');

    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('checkout/shipping', [CheckoutController::class, 'shipping'])->name('checkout.shipping');
    Route::post('checkout/check', [CheckoutController::class, 'check'])->name('checkout.check');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('checkout/{id}', [CheckoutController::class, 'show'])->name('checkout.show');


    Route::get('logout', [AuthController::class, 'do_logout'])->name('logout');
});
Route::post('payments/midtrans-notification', [PaymentCallbackController::class, 'receive']);
