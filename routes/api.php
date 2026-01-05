<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

// Routes existantes
Route::get('/test-db', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        return response()->json([
            'success' => true,
            'message' => 'Connexion DB OK',
            'db_name' => \Illuminate\Support\Facades\DB::connection()->getDatabaseName()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur DB',
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::post('/test-simple', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Log::info('Test simple POST', $request->all());
    
    return response()->json([
        'success' => true,
        'message' => 'Test POST fonctionnel',
        'data' => $request->all(),
        'timestamp' => now()
    ]);
});

// Dashboard routes (existant)
Route::prefix('dashboard')->group(function () {
    Route::get('/stats', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'users' => 150,
                'posts' => 45,
                'visits' => 1200
            ]
        ]);
    });
    
    Route::get('/recent-users', function () {
        return response()->json([
            'success' => true,
            'data' => [
                ['id' => 1, 'name' => 'User 1', 'email' => 'user1@test.com'],
                ['id' => 2, 'name' => 'User 2', 'email' => 'user2@test.com']
            ]
        ]);
    });
});

// Users routes (existant)
Route::prefix('users')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'success' => true,
            'data' => [
                ['id' => 1, 'name' => 'John Doe', 'email' => 'john@test.com'],
                ['id' => 2, 'name' => 'Jane Doe', 'email' => 'jane@test.com']
            ]
        ]);
    });
    
    Route::get('/{id}', function ($id) {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $id,
                'name' => 'User ' . $id,
                'email' => 'user' . $id . '@test.com'
            ]
        ]);
    });
});

// Health check (existant)
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is healthy',
        'timestamp' => now()->toISOString()
    ]);
});

// ============ AJOUTEZ CES ROUTES ============

// Blogs routes - NOUVELLES ROUTES
Route::prefix('blogs')->group(function () {
    // Test route
    Route::get('/test', function () {
        return response()->json([
            'success' => true,
            'message' => 'Blogs API is working!',
            'timestamp' => now()->toISOString()
        ]);
    });
    
    // CRUD operations
    Route::get('/', function (\Illuminate\Http\Request $request) {
        // Simulation de données pour tester
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
                ],
                [
                    'id' => 2,
                    'title' => 'Deuxième article',
                    'excerpt' => 'Un autre article de test',
                    'content' => 'Plus de contenu ici',
                    'category' => 'Design',
                    'image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=500',
                    'author' => 'User',
                    'tags' => ['design', 'web'],
                    'status' => 'draft',
                    'views' => 12,
                    'created_at' => now()->subDays(1)->toISOString(),
                    'updated_at' => now()->toISOString()
                ]
            ],
            'total' => 2,
            'current_page' => 1,
            'per_page' => 10,
            'last_page' => 1
        ]);
    });
    
    // Create blog
    Route::post('/', function (\Illuminate\Http\Request $request) {
        \Illuminate\Support\Facades\Log::info('POST /api/blogs', $request->all());
        
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
            'message' => 'Blog créé avec succès (simulation)',
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
                'total' => 2,
                'published' => 1,
                'draft' => 1,
                'totalViews' => 54
            ]
        ]);
    });
    
    // Get single blog
    Route::get('/{id}', function ($id) {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $id,
                'title' => 'Article ' . $id,
                'content' => 'Contenu de l\'article ' . $id,
                'category' => 'Test',
                'excerpt' => 'Extrait de l\'article ' . $id,
                'author' => 'Admin',
                'status' => 'published',
                'views' => 100,
                'created_at' => now()->subDays($id)->toISOString(),
                'updated_at' => now()->toISOString()
            ]
        ]);
    });
    
    // Update blog
    Route::put('/{id}', function ($id, \Illuminate\Http\Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'Blog mis à jour (simulation)',
            'data' => array_merge(['id' => $id], $request->all())
        ]);
    });
    
    // Delete blog
    Route::delete('/{id}', function ($id) {
        return response()->json([
            'success' => true,
            'message' => 'Blog supprimé (simulation)'
        ]);
    });
    
    // Toggle publish status
    Route::patch('/{id}/toggle-publish', function ($id) {
        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour (simulation)',
            'data' => [
                'id' => $id,
                'status' => 'published'
            ]
        ]);
    });
});