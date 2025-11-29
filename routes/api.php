<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TodoController;
use App\Models\User;
use App\Models\Todo;

// Health check (tidak butuh token)
Route::get('/health', fn() => ['status' => 'ok']);

// Auth (public, tidak butuh token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Route yang butuh token Sanctum
Route::middleware('auth:sanctum')->group(function () {
    
    // CRUD todos (protected)
    Route::apiResource('todos', TodoController::class);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // ===================================================
    // ROUTE KHUSUS ADMIN (role:admin)
    // ===================================================
    Route::middleware('role:admin')->group(function () {

        // 1. List semua user
        Route::get('/admin/users', function () {
            return User::select('id','name','email','role','created_at')->get();
        });

        // 2. List semua todos
        Route::get('/admin/todos', function () {
            return Todo::with('user')->get();
        });

    });

});