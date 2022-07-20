<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\Web\ProductController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('products', [ProductController::class, 'getAllProducts'])->name('products');
Route::get('sellers', [ProductController::class, 'getAllSellers'])->name('sellers');
Route::get('product/detail/{product_id}', [ProductController::class, 'getDetailsOfAProduct'])->name('product.detail');
Route::get('product/lists/{seller_id}', [ProductController::class, 'getProductListBySeller'])->name('product.list');
Route::get('seller/details/{product_id}', [ProductController::class, 'getSellerDetailOfAProduct'])->name('seller.detail');