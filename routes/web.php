<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AttendanceController;
// Bulk delete orders
Route::post('/orders/bulk-delete', [OrderController::class, 'bulkDelete'])->name('orders.bulkDelete');

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

Route::get('/orders/order-details', function () {
    return view('inventory.orderDetails', ['page' => 'orders']);
})->name('orders.details');

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

Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

Route::resource('menus', MenuController::class);

Route::resource('expenses', ExpenseController::class);

Route::get('/expenses/add', [ExpenseController::class, 'create'])->name('expenses.add');

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

