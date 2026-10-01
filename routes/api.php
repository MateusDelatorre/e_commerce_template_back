<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\OfficialContactController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────
// Public routes (no authentication required)
// ──────────────────────────────────────────────

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Official Contacts (public)
Route::prefix('contact')->group(function () {
    Route::get('/whatsapp', [OfficialContactController::class, 'getWhatsApp']);
    Route::get('/email', [OfficialContactController::class, 'getEmail']);
});

// Public product browsing
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/new', [ProductController::class, 'newProducts']);
    Route::get('/offers', [ProductController::class, 'withOffers']);
    Route::get('/featured', [ProductController::class, 'featured']);
    Route::get('/search', [ProductController::class, 'search']);
    Route::get('/{id}', [ProductController::class, 'show']);
});

// ──────────────────────────────────────────────
// Authenticated routes (any logged-in user)
// ──────────────────────────────────────────────

Route::middleware('auth:api')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [UserController::class, 'profile']);

    // User profile management
    Route::put('/user', [UserController::class, 'update']);
    Route::put('/user/password', [UserController::class, 'updatePassword']);
    Route::delete('/user', [UserController::class, 'deleteAccount']);

    // Endereco CRUD
    Route::apiResource('enderecos', EnderecoController::class);

    // Orders (customer)
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/mine', [OrderController::class, 'myOrders']);

    // ──────────────────────────────────────────
    // Employee routes (employee + admin + owner + developer)
    // ──────────────────────────────────────────

    Route::middleware('employee')->group(function () {

        // Product catalog for staff
        Route::get('/admin/products', [ProductController::class, 'adminIndex']);

        // Product mutations
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::patch('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        Route::post('/products/{id}/image', [ProductController::class, 'uploadImage']);

        // Order management
        Route::get('/orders', [OrderController::class, 'allOrders']);
        Route::get('/orders/filter', [OrderController::class, 'filtered']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::patch('/orders/{id}', [OrderController::class, 'updateStatus']);
    });

    // ──────────────────────────────────────────
    // Admin routes (admin + owner + developer)
    // ──────────────────────────────────────────

    Route::middleware('admin_or_developer')->group(function () {

        // Employee management
        Route::post('/employees', [UserController::class, 'createEmployee']);
        Route::get('/employees', [UserController::class, 'listEmployees']);
        Route::put('/employees/{id}', [UserController::class, 'updateEmployee']);
        Route::delete('/employees/{id}', [UserController::class, 'deleteEmployee']);

        // Statistics dashboard
        Route::get('/statistics', [StatisticsController::class, 'dashboard']);

        // Official contact management
        Route::get('/official-contacts', [OfficialContactController::class, 'index']);
        Route::post('/official-contacts', [OfficialContactController::class, 'store']);
        Route::put('/official-contacts/{id}', [OfficialContactController::class, 'update']);
        Route::delete('/official-contacts/{id}', [OfficialContactController::class, 'destroy']);
    });
});
