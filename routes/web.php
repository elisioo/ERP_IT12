<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('menu');
})->name('menu');

Route::get('/dashboard', function () {
    return view('layout.inventory_app');
})->name('dashboard');

// Inventory Routes
Route::prefix('inventory')->group(function () {
    Route::get('/', function () {
        return view('dashboard.dashboard');
    })->name('inventory.dashboard');
    Route::get('/add', function () {
        return view('inventory.add');
    })->name('inventory.add');
    Route::get('/update', function () {
        return view('inventory.update');
    })->name('inventory.update');
    Route::get('/view', function () {
        return view('inventory.view');
    })->name('inventory.view');
});