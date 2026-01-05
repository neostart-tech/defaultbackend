<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

Route::get('/', function () {
    return view('welcome');
});

// Routes API dans web.php (solution temporaire)
Route::prefix('api')->group(function () {
    
    // Route de test
    Route::get('/test', function () {
        return response()->json([
            'success' => true,
            'message' => 'API via web.php fonctionne!'
        ]);
    });
    
    // Blogs
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
    
    // Test POST
    Route::post('/test-post', function () {
        return response()->json([
            'success' => true,
            'message' => 'POST test réussi'
        ]);
    });
});
// Routes API dans web.php
Route::prefix('api')->group(function () {
    
    // Test route
    Route::get('/test', function () {
        return response()->json([
            'success' => true,
            'message' => 'API fonctionne via web.php',
            'timestamp' => now()->toISOString()
        ]);
    });
    
    // Blogs routes
    Route::prefix('blogs')->group(function () {
        // Test route
        Route::get('/test', function () {
            return response()->json([
                'success' => true,
                'message' => 'Blogs API is working!',
                'timestamp' => now()->toISOString()
            ]);
        });
        
        // Get all blogs
        Route::get('/', function (\Illuminate\Http\Request $request) {
            return response()->json([
                'success' => true,
                'data' => [
                    [
                        'id' => 1,
                        'title' => 'Premier article de test',
                        'excerpt' => 'Ceci est un article de test',
                        'content' => 'Contenu de test',
                        'category' => 'Technologie',
                        'image' => 'https://images.unsplash.com/photo-1516116216624-53e697fedbea?w=500',
                        'author' => 'Admin',
                        'tags' => ['test', 'technologie'],
                        'status' => 'published',
                        'views' => 42,
                        'created_at' => now()->subDays(2)->toISOString(),
                        'updated_at' => now()->subDays(1)->toISOString()
                    ]
                ],
                'total' => 1,
                'current_page' => 1,
                'per_page' => 10,
                'last_page' => 1
            ]);
        });
        
        // Create blog
        Route::post('/', function (\Illuminate\Http\Request $request) {
            // Validation simple
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category' => 'required|string|max:100'
            ]);
            
            // Simulation d'une création
            $blog = [
                'id' => rand(100, 999),
                'title' => $request->title,
                'content' => $request->content,
                'category' => $request->category,
                'excerpt' => $request->excerpt ?? '',
                'image' => $request->image ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=500',
                'author' => $request->author ?? 'Admin',
                'tags' => $request->tags ? (is_array($request->tags) ? $request->tags : explode(',', $request->tags)) : [],
                'status' => $request->status ?? 'draft',
                'views' => 0,
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString()
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Blog créé avec succès',
                'data' => $blog
            ], 201);
        });
        
        // Get categories
        Route::get('/categories', function () {
            return response()->json([
                'success' => true,
                'data' => ['Technologie', 'Développement', 'Design', 'Business', 'Marketing', 'Autre']
            ]);
        });
        
        // Get stats
        Route::get('/stats', function () {
            return response()->json([
                'success' => true,
                'data' => [
                    'total' => 1,
                    'published' => 1,
                    'draft' => 0,
                    'totalViews' => 42
                ]
            ]);
        });
    });
});