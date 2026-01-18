<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Favorite\FavoriteController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Product\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    //products

    Route::post('/products', [ProductController::class, 'addProduct']);
    Route::get('/products', [ProductController::class, 'getAllProducts']);
    Route::post('/products/{id}', [ProductController::class, 'updateProduct']);
    Route::delete('/products/{id}', [ProductController::class, 'deleteProduct']);


    //favorites

    Route::post('/products/{id}/favorites', [FavoriteController::class, 'toggleFavorite']);
    Route::get('/favorites', [FavoriteController::class, 'getFavorites']);

    //carts

    // Add or Update quantity (POST)
    Route::post('/products/{id}/carts', [CartController::class, 'addToCart']);

    // Remove entirely from cart (DELETE)
    Route::delete('/products/{id}/carts', [CartController::class, 'removeFromCart']);

    Route::get('/carts',[CartController::class,'getCarts']);

    //order

    Route::post('/checkout',[OrderController::class,'checkout']);
    Route::get('/orders', [OrderController::class, 'getOrders']);      // History
    Route::get('/orders/{id}', [OrderController::class, 'getOrderDetails']);   // Details
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
