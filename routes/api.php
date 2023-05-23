<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('getDetail', [AuthController::class, 'getDetail']);
    Route::post('createProduct', [ProductController::class, 'store']);
    Route::put('updateProduct', [ProductController::class, 'edit']);
    Route::delete('productDelete/{id}', [ProductController::class, 'destroy']);
});

Route::get('getProduct', [ProductController::class, 'index']);
