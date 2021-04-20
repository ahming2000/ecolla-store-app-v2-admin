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

Route::get('/item', [ItemsController::class, 'index'])->name('item.home');
Route::get('/item/create', [ItemsController::class, 'create'])->name('item.create');
Route::post('/item/create', [ItemsController::class, 'save'])->name('item.store');
Route::get('/item/edit/{item}', [ItemsController::class, 'edit'])->name('item.edit');
Route::post('/item/edit/{item}', [ItemsController::class, 'update'])->name('item.update');

Route::get('/order', [OrdersController::class, 'index'])->name('order.home');
Route::get('/order/{order}/item', [OrderItemsController::class, 'index']);

Route::get('/setting', [SettingsController::class, 'index'])->name('setting.home');
