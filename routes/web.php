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

Route::get('/orders/order-details', function () {
    return view('inventory.orderDetails', ['page' => 'orders']);
})->name('orders.details');

Route::get('/menus', function () {
    return view('inventory.menus', ['page' => 'menus']);
})->name('menus');

Route::get('/menus/add', function () {
    return view('inventory.addMenu', ['page' => 'menus']);
})->name('menus.add');

Route::get('/expenses', function () {
    return view('inventory.expenses', ['page' => 'expenses']);
})->name('expenses');

Route::get('/expenses/add', function () {
    return view('inventory.addExpenses', ['page' => 'expenses']);
})->name('expenses.add');

Route::get('/inventory', function () {
    return view('inventory.inventory', ['page' => 'inventory']);
})->name('inventory');

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