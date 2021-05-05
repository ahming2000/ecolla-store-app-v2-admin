<?php

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
    if(auth()){
        return redirect('/home');
    } else{
        return redirect('/login');
    }
});

Auth::routes();
Route::get('/home', [HomeController::class, 'homeDoGet'])->name('home');

Route::prefix('/item')->group(function (){
    Route::get('/', [ItemsController::class, 'index'])->name('item.index');
    Route::get('/create', [ItemsController::class, 'create'])->name('item.create');
    Route::get('/{item}', [ItemsController::class, 'show'])->name('item.show');
    Route::get('/{item}/edit', [ItemsController::class, 'edit'])->name('item.edit');
    Route::post('/', [ItemsController::class, 'store'])->name('item.store');
    Route::patch('/{item}', [ItemsController::class, 'update'])->name('item.update');
    Route::delete('/{item}', [ItemsController::class, 'destroy'])->name('item.destroy');
});



Route::get('/order', [OrdersController::class, 'index'])->name('order.home');
Route::get('/order/{order}', [OrdersController::class, 'edit']);
Route::post('/order/{order}', [OrdersController::class, 'update']);

Route::get('/order/{order}/item', [OrderItemsController::class, 'index']);

Route::get('/setting', [SettingsController::class, 'index'])->name('setting.home');
Route::patch('/setting/update/category', [SettingsController::class, 'updateCategory']);

Route::get('/changing-log', [SettingsController::class, 'changingLog']);
