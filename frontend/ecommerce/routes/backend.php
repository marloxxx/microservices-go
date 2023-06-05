<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\EventController;
use App\Http\Controllers\Backend\BookingController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\RequestController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\FacilityController;
use App\Http\Controllers\Backend\HomestayController;
use App\Http\Controllers\Backend\DashboardController;


Route::name('backend.')->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Products
    Route::resource('products', ProductController::class);

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('', [SettingController::class, 'index'])->name('index');
        Route::put('update_site', [SettingController::class, 'update_site'])->name('update_site');
        Route::put('update_user', [SettingController::class, 'update_user'])->name('update_user');
        Route::put('update_seo', [SettingController::class, 'update_seo'])->name('update_seo');
    });

    Route::get('logout', [AuthController::class, 'do_logout'])->name('logout');
});
