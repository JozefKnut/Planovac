<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('overview');
    }
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');
    Route::view('/products', 'products')->name('products');
    Route::view('/orders', 'orders')->name('orders');
    Route::view('/overview', 'overview')->name('overview');
});

require __DIR__.'/auth.php';
