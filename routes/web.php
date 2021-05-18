<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use \App\Http\Controllers\OrderItemsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (auth()) {
        return redirect('/home');
    } else {
        return redirect('/login');
    }
});

Auth::routes();
Route::get('/home', [HomeController::class, 'homeDoGet'])->name('home');

Route::prefix('/item')->group(function () {
    Route::get('/', [ItemsController::class, 'index'])->name('item.index');
    Route::get('/create', [ItemsController::class, 'create'])->name('item.create');
    Route::get('/{item}', [ItemsController::class, 'show'])->name('item.show');
    Route::get('/{item}/edit', [ItemsController::class, 'edit'])->name('item.edit');
    Route::post('/', [ItemsController::class, 'store'])->name('item.store');
    Route::patch('/{item}', [ItemsController::class, 'update'])->name('item.update');
    Route::delete('/{item}', [ItemsController::class, 'destroy'])->name('item.destroy');
});

Route::prefix('/order')->group(function () {
    Route::get('/', [OrdersController::class, 'index']);
    Route::get('/{order}', [OrdersController::class, 'show']);
    Route::get('/{order}/edit', [OrdersController::class, 'edit']);
    Route::get('/{order}/pdf', [OrdersController::class, 'createPDF']);

    Route::patch('/{order}', [OrdersController::class, 'update']);

    Route::prefix('/{order}/item')->group(function () {
        Route::get('/', [OrderItemsController::class, 'index']);
        Route::get('/create', [OrderItemsController::class, 'create']);

        Route::post('/', [OrderItemsController::class, 'store']);
        Route::patch('/{item}', [OrderItemsController::class, 'update']);
        Route::delete('/{item}', [OrderItemsController::class, 'destroy']);
    });
});

Route::prefix('/setting')->group(function () {
    Route::get('/', [SettingsController::class, 'index']);

    Route::patch('/update/category', [SettingsController::class, 'updateCategory']); // TODO - Convert to url below

    Route::patch('/item/{property}', [SettingsController::class, 'updateItemSettings']);
    Route::patch('/order/{property}', [SettingsController::class, 'updateOrderSettings']);
    Route::patch('/account/{property}', [SettingsController::class, 'updateAccountSettings']);
    Route::patch('/pagination/{type}', [SettingsController::class, 'UpdatePaginationSettings']);
});

Route::prefix('/account')->group(function () {
    Route::get('/', [AccountsController::class, 'index']);
    Route::get('/create', [AccountsController::class, 'create']);
    Route::get('/{user}', [AccountsController::class, 'show']);
    Route::get('/{user}/edit', [AccountsController::class, 'edit']);
    Route::post('/', [AccountsController::class, 'store']);
    Route::patch('/{user}', [AccountsController::class, 'update']);
    Route::delete('/{user}', [AccountsController::class, 'destroy']);
});

Route::get('/changing-log', [SettingsController::class, 'changingLog']); // TODO - Changing to another controller
