<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\OrderController;

Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');

Route::get('/', function () {
    return view('menu');
})->name('menu');

Route::get('/dashboard', function () {
    return view('inventory.dashboard', ['page' => 'dashboard']);
})->name('dashboard');


Route::get('/orders', function () {
    return view('inventory.order', ['page' => 'orders']);
})->name('orders');

// Route::get('/menus', function () {
//     return view('inventory.menus', ['page' => 'menus']);
// })->name('menus');

// Route::get('/menus/add', function () {
//     return view('inventory.addMenu', ['page' => 'menus']);
// })->name('menus.add');

// Route::get('/expenses', function () {
//     return view('inventory.expenses', ['page' => 'expenses']);
// })->name('expenses');

// Route::get('/expenses/add', function () {
//     return view('inventory.addExpenses', ['page' => 'expenses']);
// })->name('expenses.add');

Route::get('/inventory', function () {
    return view('inventory.inventory', ['page' => 'inventory']);
})->name('inventory');



Route::prefix('expenses')->group(function () {
    Route::get('/', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/add', [ExpenseController::class, 'create'])->name('expenses.add');
    Route::post('/store', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
});


Route::prefix('menus')->group(function () {
    Route::get('/', [MenuController::class, 'index'])->name('menus.index');
    Route::get('/add', [MenuController::class, 'create'])->name('menus.create');
    Route::post('/store', [MenuController::class, 'store'])->name('menus.store');
    Route::get('/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
    Route::post('/{menu}/rate', [MenuController::class, 'rate'])->name('menus.rate');
});



Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/store', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/{order}', [OrderController::class, 'show'])->name('orders.details');
    Route::get('/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    Route::delete('/bulk-delete', [OrderController::class, 'bulkDelete'])->name('orders.bulkDelete');
});

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

Route::get('/employee/attendance', [AttendanceController::class, 'attendance'])->name('employee.attendance');
Route::put('/attendance/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
Route::post('/employee/add', [AttendanceController::class, 'store'])->name('employee.add');
Route::post('/employee/store', [AttendanceController::class, 'store'])->name('employee.store');

Route::get('/attendance', [AttendanceController::class, 'attendance'])->name('attendance.index');
Route::delete('/employee/{id}', [AttendanceController::class, 'destroy'])->name('employee.delete');

