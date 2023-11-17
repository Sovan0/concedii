<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\ProductController;
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

Route::get('/bar-chart', [ProductController::class, 'barChart'])->name('bar-chart');

Route::get('/registration', [Auth::class, 'registration'])->name('registration');
Route::post('/registration', [Auth::class, 'registrationPost'])->name('registration.post');
Route::get('/login', [Auth::class, 'login'])->name('login');
Route::post('/login', [Auth::class, 'loginPost'])->name('login.post');
Route::get('/logout', [Auth::class, 'logout'])->name('logout');

Route::get('/product', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/show-create-product', [ProductController::class, 'showCreateProduct'])->name('product.show-create-product');
Route::post('/product-create', [ProductController::class, 'productCreate'])->name('product.create-product');
Route::get('/product/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
Route::put('/product/{product}/update', [ProductController::class, 'update'])->name('product.update');
Route::delete('/product/{product}/delete', [ProductController::class, 'delete'])->name('product.delete');

Route::get('/filter', [ProductController::class, 'filtered'])->name('filtered-products');

Route::get('/test', [ProductController::class, 'getUserId']);
Route::get('/date', [ProductController::class, 'getDate']);
