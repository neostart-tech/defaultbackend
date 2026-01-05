<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer un utilisateur admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        // Créer quelques blogs de test
        Blog::create([
            'title' => 'Introduction à Nuxt 3',
            'excerpt' => 'Découvrez les nouvelles fonctionnalités de Nuxt 3',
            'content' => 'Nuxt 3 est la dernière version du framework Vue.js...',
            'category' => 'Technologie',
            'image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=400&h=300&fit=crop',
            'author' => 'John Doe',
            'tags' => ['nuxt', 'vue', 'frontend'],
            'status' => 'published',
            'views' => 1250,
            'user_id' => $admin->id
        ]);

        Blog::create([
            'title' => 'Guide du Tailwind CSS',
            'excerpt' => 'Maîtrisez les utilitaires de Tailwind CSS',
            'content' => 'Tailwind CSS est un framework CSS utility-first...',
            'category' => 'Développement',
            'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=300&fit=crop',
            'author' => 'Jane Smith',
            'tags' => ['tailwind', 'css', 'design'],
            'status' => 'draft',
            'views' => 0,
            'user_id' => $admin->id
        ]);

        // Créer d'autres utilisateurs
        User::factory(10)->create();
    }
}