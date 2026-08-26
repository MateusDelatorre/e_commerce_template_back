<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Authenticated routes
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/profile', [AuthController::class, 'userProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Endereco CRUD routes
    Route::apiResource('enderecos', EnderecoController::class);

    // Admin & Developer only: Product mutations and image uploads
    Route::middleware('admin_or_developer')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::patch('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        Route::post('/products/{id}/image', [ProductController::class, 'uploadImage']);
    });
});
