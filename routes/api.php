<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);

Route::middleware('auth:api', 'throttle:10,1')->group(function () {
    Route::post('product/create', [ProductController::class, 'store'])->name('product.create');
    Route::post('product/edit/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('product/delete/{id}', [ProductController::class, 'destroy'])->name('product.delete');
    Route::get('product/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::get('products', [ProductController::class, 'index'])->name('product.index');
    Route::get('logout', [PassportAuthController::class, 'logout'])->name('logout');
});
