<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Blog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        $totalUsers = User::count();
        $newUsersToday = User::whereDate('created_at', today())->count();
        
        return response()->json([
            'total_users' => $totalUsers,
            'new_users_today' => $newUsersToday,
            'online_now' => rand(50, 200), // Simulé
            'last_hour' => rand(20, 100), // Simulé
            'emails_sent' => rand(1000, 5000), // Simulé
            'emails_today' => rand(50, 200), // Simulé
            'avg_session_time' => '32 min' // Simulé
        ]);
    }

    public function recentUsers()
    {
        $users = User::orderBy('created_at', 'desc')
                    ->take(10)
                    ->get(['id', 'name', 'email', 'role', 'status', 'created_at']);
        
        return response()->json($users);
    }

    public function activities()
    {
        // Simuler des activités
        $activities = [
            [
                'id' => 1,
                'user' => ['name' => 'Admin'],
                'description' => 'Nouvel utilisateur créé',
                'created_at' => now()->subMinutes(15)
            ],
            [
                'id' => 2,
                'user' => ['name' => 'Jane Smith'],
                'description' => 'Article publié',
                'created_at' => now()->subMinutes(30)
            ]
        ];
        
        return response()->json($activities);
    }
}