<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('prehlad');
    }
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');
    Route::view('/vyrobky', 'products')->name('vyrobky');
    Route::view('/zakazky', 'orders')->name('zakazky');
    Route::view('/prehlad', 'overview')->name('prehlad');
    Route::view('/pouzivatelia', 'users')->name('pouzivatelia');
});

require __DIR__.'/auth.php';
