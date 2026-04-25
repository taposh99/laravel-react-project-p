<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\DashboardController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/recent-products', [DashboardController::class, 'recentProducts']);
    
    // Products
    Route::apiResource('products', ProductController::class);
    Route::apiResource('brands', BrandController::class);
    
    // Categories
    Route::apiResource('categories', CategoryController::class);
});
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});