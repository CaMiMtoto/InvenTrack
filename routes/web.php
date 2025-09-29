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


    Route::prefix('reports')->group(function () {

    });


});
