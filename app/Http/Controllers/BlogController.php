<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    // Récupérer tous les blogs
    public function index(Request $request)
    {
        try {
            Log::info('GET /api/blogs', $request->all());
            
            $query = Blog::query();
            
            // Filtres
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('excerpt', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%");
                });
            }
            
            if ($request->has('category') && $request->category) {
                $query->where('category', $request->category);
            }
            
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }
            
            // Pagination
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);
            
            $blogs = $query->orderBy('created_at', 'desc')
                          ->paginate($perPage, ['*'], 'page', $page);
            
            return response()->json([
                'success' => true,
                'data' => $blogs->items(),
                'total' => $blogs->total(),
                'current_page' => $blogs->currentPage(),
                'per_page' => $blogs->perPage(),
                'last_page' => $blogs->lastPage(),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in BlogController@index: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }
    
    // Créer un blog
    public function store(Request $request)
    {
        try {
            Log::info('POST /api/blogs', $request->all());
            
            // Validation
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category' => 'required|string|max:100',
                'excerpt' => 'nullable|string|max:500',
                'image' => 'nullable|string',
                'author' => 'nullable|string|max:100',
                'status' => 'nullable|in:published,draft'
            ]);
            
            // Gérer les tags
            $tags = null;
            if ($request->has('tags')) {
                if (is_array($request->tags)) {
                    $tags = json_encode($request->tags);
                } elseif (is_string($request->tags)) {
                    $tags = json_encode(explode(',', $request->tags));
                }
            }
            
            $blog = Blog::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'category' => $validated['category'],
                'excerpt' => $validated['excerpt'] ?? '',
                'image' => $validated['image'] ?? null,
                'author' => $validated['author'] ?? 'Admin',
                'tags' => $tags,
                'status' => $validated['status'] ?? 'draft',
                'views' => 0
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Blog créé avec succès',
                'data' => $blog
            ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error in BlogController@store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Récupérer les catégories
    public function getCategories()
    {
        try {
            $categories = Blog::distinct()->pluck('category')->filter()->values();
            
            if ($categories->isEmpty()) {
                $categories = collect(['Technologie', 'Développement', 'Design', 'Business', 'Marketing', 'Autre']);
            }
            
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'data' => ['Technologie', 'Développement', 'Design', 'Business', 'Marketing', 'Autre']
            ]);
        }
    }
    
    // Récupérer les statistiques
    public function getStats()
    {
        try {
            $stats = [
                'total' => Blog::count(),
                'published' => Blog::where('status', 'published')->count(),
                'draft' => Blog::where('status', 'draft')->count(),
                'totalViews' => Blog::sum('views'),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'data' => [
                    'total' => 0,
                    'published' => 0,
                    'draft' => 0,
                    'totalViews' => 0
                ]
            ]);
        }
    }
    
    // Récupérer un blog spécifique
    public function show($id)
    {
        try {
            $blog = Blog::find($id);
            
            if (!$blog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Blog non trouvé'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $blog
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }
    
    // Mettre à jour un blog
    public function update(Request $request, $id)
    {
        try {
            $blog = Blog::find($id);
            
            if (!$blog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Blog non trouvé'
                ], 404);
            }
            
            // Validation
            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'content' => 'sometimes|string',
                'category' => 'sometimes|string|max:100',
                'excerpt' => 'nullable|string|max:500',
                'image' => 'nullable|string',
                'author' => 'nullable|string|max:100',
                'status' => 'sometimes|in:published,draft'
            ]);
            
            // Gérer les tags
            if ($request->has('tags')) {
                if (is_array($request->tags)) {
                    $blog->tags = json_encode($request->tags);
                } elseif (is_string($request->tags)) {
                    $blog->tags = json_encode(explode(',', $request->tags));
                }
            }
            
            $blog->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Blog mis à jour',
                'data' => $blog
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }
    
    // Supprimer un blog
    public function destroy($id)
    {
        try {
            $blog = Blog::find($id);
            
            if (!$blog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Blog non trouvé'
                ], 404);
            }
            
            $blog->delete();
            
            return response()->json([
    'success' => true,  
    'message' => 'Blog supprimé'
]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }
    
    // Changer le statut
    public function togglePublish($id)
    {
        try {
            $blog = Blog::find($id);
            
            if (!$blog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Blog non trouvé'
                ], 404);
            }
            
            $blog->status = $blog->status === 'published' ? 'draft' : 'published';
            $blog->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour',
                'data' => $blog
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }
}