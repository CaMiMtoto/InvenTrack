<?php

use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\PasswordChanged;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth', PasswordChanged::class, EnsureUserIsActive::class], 'prefix' => '/admin', 'as' => 'admin.'], function () {


    Route::group(['prefix' => "purchases", "as" => "purchases."], function () {
        Route::get('/', [App\Http\Controllers\PurchaseController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\PurchaseController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\PurchaseController::class, 'store'])->name('store');
        Route::get('/{purchase}', [App\Http\Controllers\PurchaseController::class, 'show'])->name('show');
        Route::delete('/{purchase}', [App\Http\Controllers\PurchaseController::class, 'destroy'])->name('destroy');
        Route::get('/{purchase}/edit', [App\Http\Controllers\PurchaseController::class, 'edit'])->name('edit');
        Route::put('/{purchase}', [App\Http\Controllers\PurchaseController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => "orders", "as" => "orders."], function () {
        Route::get('/', [App\Http\Controllers\OrderController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\OrderController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\OrderController::class, 'store'])->name('store');
        Route::get('/{saleOrder}/show', [App\Http\Controllers\OrderController::class, 'show'])->name('show');
        Route::delete('/{order}/destroy', [App\Http\Controllers\OrderController::class, 'destroy'])->name('destroy');
        Route::get('/{order}/edit', [App\Http\Controllers\OrderController::class, 'edit'])->name('edit');
        Route::put('/{order}/update', [App\Http\Controllers\OrderController::class, 'update'])->name('update');
        Route::get('/{order}/print', [App\Http\Controllers\OrderController::class, 'print'])->name('print');
        Route::put('/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('cancel');


    });



    Route::group(['prefix' => "settings", "as" => "settings."], function () {


    });


    Route::group(["prefix" => "system", "as" => "system."], function () {
        Route::get('/roles', [App\Http\Controllers\RolesController::class, 'index'])->name('roles.index');
        Route::post('/roles', [App\Http\Controllers\RolesController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}', [App\Http\Controllers\RolesController::class, 'show'])->name('roles.show');
        Route::delete('/roles/{role}', [App\Http\Controllers\RolesController::class, 'destroy'])->name('roles.destroy');


        Route::get('/users', [App\Http\Controllers\UsersController::class, 'index'])->name('users.index');
        Route::post('/users', [App\Http\Controllers\UsersController::class, 'store'])->name('users.store');
        Route::post('/users/{user}/toggle-activate', [App\Http\Controllers\UsersController::class, 'toggleActive'])->name('users.active-toggle');
        Route::delete('/users/{user}', [App\Http\Controllers\UsersController::class, 'destroy'])->name('users.destroy');
        Route::get('/users/{user}', [App\Http\Controllers\UsersController::class, 'show'])->name('users.show');
        Route::get('/permissions', [App\Http\Controllers\PermissionsController::class, 'index'])->name('permissions.index');

    });


    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');



    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {

        Route::get('/sales', [App\Http\Controllers\ReportsController::class, 'salesReport'])->name('sales');
        Route::get('/print-sales', [App\Http\Controllers\ReportsController::class, 'printSales'])->name('print-sales');
        Route::get('/export-sales', [App\Http\Controllers\ReportsController::class, 'exportSales'])->name('export-sales');
        Route::get('/export-purchases', [App\Http\Controllers\ReportsController::class, 'exportPurchases'])->name('purchases-export');
        Route::get('/stock', [App\Http\Controllers\ReportsController::class, 'stockReport'])->name('stock');


        Route::get('/payments', [App\Http\Controllers\ReportsController::class, 'paymentsReport'])->name('payments');
        Route::get('/print-payments', [App\Http\Controllers\ReportsController::class, 'printPayments'])->name('print-payments');


        Route::get('/purchase-orders/history', [App\Http\Controllers\PurchaseOrderController::class, 'history'])->name('purchase-orders.history');
        Route::get('/purchase-orders/history/export', [App\Http\Controllers\PurchaseOrderController::class, 'exportHistory'])->name('purchase-orders.history.export');
        Route::get('/items', [App\Http\Controllers\ReportsController::class, 'itemsReport'])->name('items');
        Route::get('/expenses', [App\Http\Controllers\ReportsController::class, 'expensesReport'])->name('expenses');

    });


});
