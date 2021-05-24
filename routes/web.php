<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\ChangingLog\ChangingLogController;
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
    Route::get('/', [ItemsController::class, 'index'])->name('item.index')->middleware('access:item_view');
    Route::get('/create', [ItemsController::class, 'create'])->name('item.create')->middleware('access:item_create');
    Route::get('/{item}', [ItemsController::class, 'show'])->name('item.show')->middleware('access:item_view');
    Route::get('/{item}/edit', [ItemsController::class, 'edit'])->name('item.edit')->middleware('access:item_update');
    Route::post('/', [ItemsController::class, 'store'])->name('item.store');
    Route::patch('/{item}', [ItemsController::class, 'update'])->name('item.update');
    Route::delete('/{item}', [ItemsController::class, 'destroy'])->name('item.destroy');
});

Route::prefix('/order')->group(function () {
    Route::get('/', [OrdersController::class, 'index'])->middleware('access:order_view');
    Route::get('/{order}', [OrdersController::class, 'show'])->middleware('access:order_view');
    Route::get('/{order}/edit', [OrdersController::class, 'edit'])->middleware('access:order_update');
    Route::get('/{order}/pdf', [OrdersController::class, 'createPDF'])->middleware('access:order_invoice_download');

    Route::post('/{order}', [OrdersController::class, 'update']); //change to patch later

    Route::prefix('/{order}/item')->group(function () {
        Route::get('/', [OrderItemsController::class, 'index'])->middleware('access:order_item_view');
        Route::get('/create', [OrderItemsController::class, 'create'])->middleware('access:order_item_create');

        Route::post('/', [OrderItemsController::class, 'store']);
        Route::patch('/{item}', [OrderItemsController::class, 'update']);
        Route::delete('/{item}', [OrderItemsController::class, 'destroy']);
    });
});

Route::prefix('/setting')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->middleware('access:setting_view');

    Route::patch('/update/category', [SettingsController::class, 'updateCategory']); // TODO - Convert to url below

    Route::patch('/item/{property}', [SettingsController::class, 'updateItemSettings']);
    Route::patch('/order/{property}', [SettingsController::class, 'updateOrderSettings']);
    Route::patch('/account/{property}', [SettingsController::class, 'updateAccountSettings']);
    Route::patch('/pagination/{type}', [SettingsController::class, 'UpdatePaginationSettings']);
});

Route::prefix('/account')->group(function () {
    Route::get('/', [AccountsController::class, 'index']);
    Route::post('/', [AccountsController::class, 'store']);
    Route::post('/{user}', [AccountsController::class, 'manage']);
    Route::patch('/{user}', [AccountsController::class, 'update']);
});

Route::get('/changing-log', [ChangingLogController::class, 'index']);
