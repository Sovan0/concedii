<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\HolidayController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/registration', [Auth::class, 'registration'])->name('registration');
Route::post('/registration', [Auth::class, 'registrationPost'])->name('registration.post');
Route::get('/login', [Auth::class, 'login'])->name('login');
Route::post('/login', [Auth::class, 'loginPost'])->name('login.post');
Route::get('/logout', [Auth::class, 'logout'])->name('logout');

//Route::group(['prefix' => 'holidays'], function() {
//    Route::get('/index', [HolidayController::class, 'index'])->name('holidays.index');
//    Route::get('/create', [HolidayController::class, 'create'])->name('holidays.create');
//    Route::post('/', [HolidayController::class, 'store'])->name('holidays.store');
//});

Route::get('/holiday/create', [HolidayController::class, 'create'])->name('holiday.create');
Route::post('/holiday', [HolidayController::class, 'store'])->name('holiday.store');
Route::get('/holiday/{holiday}/edit', [HolidayController::class, 'edit'])->name('holiday.edit');
Route::put('/holiday/{holiday}/update', [HolidayController::class, 'update'])->name('holiday.update');
Route::delete('/holiday/{holiday}/delete', [HolidayController::class, 'delete'])->name('holiday.delete');

//Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
//Route::post('/product', [ProductController::class, 'store'])->name('product.store');
//Route::get('/product/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
//Route::put('/product/{product}/update', [ProductController::class, 'update'])->name('product.update');
//Route::delete('/product/{product}/delete', [ProductController::class, 'delete'])->name('product.delete');
