<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/vyrobky', function () {
    return view('products');
})->name('vyrobky');

Route::get('/zakazky', function () {
    return view('orders');
})->name('zakazky');

Route::get('/prehlad', function () {
    return view('overview');
})->name('prehlad');
