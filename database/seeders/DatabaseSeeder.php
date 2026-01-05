<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Blog;
namespace Database\Seeders;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'utilisateur admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        // Créer un utilisateur régulier
        User::create([
            'name' => 'User Test',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'status' => 'active'
        ]);

        echo "Users created:\n";
        echo "- admin@example.com / password123\n";
        echo "- user@example.com / password123\n";
    }
}