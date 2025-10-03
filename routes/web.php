<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
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
        Route::get('/{purchaseOrder}/print', [App\Http\Controllers\PurchaseController::class, 'print'])->name('print');

    });

    Route::group(['prefix' => "orders", "as" => "orders."], function () {
        Route::get('/', [App\Http\Controllers\OrderController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\OrderController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\OrderController::class, 'store'])->name('store');
        Route::get('/{saleOrder}/show', [App\Http\Controllers\OrderController::class, 'show'])->name('show');
        Route::delete('/{order}/destroy', [App\Http\Controllers\OrderController::class, 'destroy'])->name('destroy');
        Route::get('/{order}/edit', [App\Http\Controllers\OrderController::class, 'edit'])->name('edit');
        Route::patch('/{order}/update-status', [App\Http\Controllers\OrderController::class, 'updateStatus'])->name('update-status');
        Route::get('/{order}/print', [App\Http\Controllers\OrderController::class, 'print'])->name('print');
        Route::put('/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('cancel');
    });

    Route::group(['prefix' => "deliveries", "as" => "deliveries."], function () {
        Route::get('/pending', [App\Http\Controllers\OrderController::class, 'pendingDeliveries'])->name('pending');
        Route::post('/assign', [App\Http\Controllers\DeliveryController::class, 'bulkAssign'])->name('bulk-assign');
        Route::get('/', [App\Http\Controllers\DeliveryController::class, 'index'])->name('index');
        Route::get('/assigned-to-me', [App\Http\Controllers\DeliveryController::class, 'myDeliveries'])->name('assigned-to-me');
    });
    Route::get('/sales/{saleOrder}/deliveries', [App\Http\Controllers\SaleDeliveryController::class, 'index'])->name('sale-deliveries.index');
    Route::post('/sales/{saleOrder}/deliveries/store', [App\Http\Controllers\SaleDeliveryController::class, 'store'])->name('sale-deliveries.store');
    Route::get('/sales/deliveries/{saleDelivery}/print', [App\Http\Controllers\SaleDeliveryController::class, 'print'])->name('sale-deliveries.print');

    Route::get('/stock/movements', [StockTransactionController::class, 'index'])->name('stock-transaction.index');
    Route::get('/stock/adjustments', [StockTransactionController::class, 'adjustments'])->name('stock-transaction.adjustments');
    Route::post('/stock/adjustments', [StockTransactionController::class, 'adjustStock'])->name('stock-transaction.adjust-stock');

    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses/store', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}/show', [ExpenseController::class, 'show'])->name('expenses.show');
    Route::delete('/expenses/{expense}/destroy', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    //product management routes
    Route::group(["prefix" => "products", "as" => "products."], function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/{product}/show', [ProductController::class, 'show'])->name('show');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::delete('/{product}/destroy', [ProductController::class, 'destroy'])->name('destroy');
        Route::get('/export-excel', [ProductController::class, 'exportExcel'])->name('excel-export');
// routes/web.php
        Route::post('/upload-temp-images', [ProductController::class, 'uploadTempImages'])->name('uploadTempImages');

        // categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/{category}/show', [CategoryController::class, 'show'])->name('categories.show');
        Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
        Route::delete('/categories/{category}/destroy', [CategoryController::class, 'destroy'])->name('categories.destroy');

    });


    Route::group(["prefix" => "settings", "as" => "settings."], function () {
        //supplier routes
        Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/suppliers/{supplier}/show', [SupplierController::class, 'show'])->name('suppliers.show');
        Route::post('/suppliers/store', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
        //Customers routes
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}/show', [CustomerController::class, 'show'])->name('customers.show');
        Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

        Route::prefix('payment-methods')->group(function () {
            Route::get('/', [App\Http\Controllers\PaymentMethodController::class, 'index'])->name('payment-methods.index');
            Route::post('/store', [App\Http\Controllers\PaymentMethodController::class, 'store'])->name('payment-methods.store');
            Route::get('/{paymentMethod}/show', [App\Http\Controllers\PaymentMethodController::class, 'show'])->name('payment-methods.show');
            Route::delete('/{paymentMethod}/destroy', [App\Http\Controllers\PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
        });

        Route::prefix('expense-categories')->group(function () {
            Route::get('/', [App\Http\Controllers\ExpenseCategoryController::class, 'index'])->name('expense-categories.index');
            Route::post('/store', [App\Http\Controllers\ExpenseCategoryController::class, 'store'])->name('expense-categories.store');
            Route::get('/{expenseCategory}/show', [App\Http\Controllers\ExpenseCategoryController::class, 'show'])->name('expense-categories.show');
            Route::delete('/{expenseCategory}/destroy', [App\Http\Controllers\ExpenseCategoryController::class, 'destroy'])->name('expense-categories.destroy');
        });

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
