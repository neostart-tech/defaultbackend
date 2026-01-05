<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier si l'admin existe déjà
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'avatar' => 'https://ui-avatars.com/api/?name=Admin+User&background=0D8ABC&color=fff',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
            echo "Admin user created.\n";
        } else {
            echo "Admin user already exists.\n";
        }

        // Vérifier si l'editor existe déjà
        if (!User::where('email', 'editor@example.com')->exists()) {
            User::create([
                'name' => 'Editor User',
                'email' => 'editor@example.com',
                'password' => Hash::make('password'),
                'role' => 'editor',
                'status' => 'active',
                'avatar' => 'https://ui-avatars.com/api/?name=Editor+User&background=10B981&color=fff',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
            echo "Editor user created.\n";
        } else {
            echo "Editor user already exists.\n";
        }

        // Créer des utilisateurs normaux (sans doublons)
        for ($i = 1; $i <= 10; $i++) {
            $email = "user{$i}@example.com";
            
            if (!User::where('email', $email)->exists()) {
                User::create([
                    'name' => 'User ' . $i,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'status' => 'active',
                    'avatar' => 'https://ui-avatars.com/api/?name=User+' . $i . '&background=random',
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]);
                echo "User {$i} created.\n";
            } else {
                echo "User {$i} already exists.\n";
            }
        }
    }
}