<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('menu');
})->name('menu');

Route::get('/dashboard', function () {
    return view('inventory.dashboard', ['page' => 'dashboard']);
})->name('dashboard');


Route::get('/orders', function () {
    return view('inventory.order', ['page' => 'orders']);
})->name('orders');
// Route::prefix('inventory')->group(function () {
//     Route::get('/', function () {
//         return view('inventory.dashboard');
//     })->name('inventory.dashboard');
//     Route::get('/add', function () {
//         return view('inventory.add');
//     })->name('inventory.add');
//     Route::get('/update', function () {
//         return view('inventory.update');
//     })->name('inventory.update');
//     Route::get('/view', function () {
//         return view('inventory.view');
//     })->name('inventory.view');
// });