<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;

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

// Test route
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API fonctionne!',
        'timestamp' => now()->toISOString()
    ]);
});

// Routes d'authentification
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
    Route::get('/check', [AuthController::class, 'checkAuth'])->middleware('auth:sanctum');
});

// Routes des blogs
Route::prefix('blogs')->group(function () {
    Route::get('/', [BlogController::class, 'index']);
    Route::post('/', [BlogController::class, 'store']);
    Route::get('/categories', [BlogController::class, 'getCategories']);
    Route::get('/stats', [BlogController::class, 'getStats']);
    Route::get('/{id}', [BlogController::class, 'show']);
    Route::put('/{id}', [BlogController::class, 'update']);
    Route::delete('/{id}', [BlogController::class, 'destroy']);
    Route::patch('/{id}/toggle-publish', [BlogController::class, 'togglePublish']);
});

// Test simple pour déboguer
Route::post('/test-login', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Log::info('Test login request', $request->all());
    
    return response()->json([
        'success' => true,
        'message' => 'Test login endpoint',
        'data' => $request->all()
    ]);
});