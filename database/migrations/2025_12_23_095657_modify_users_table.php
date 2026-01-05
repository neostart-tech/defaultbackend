<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vérifier et ajouter les colonnes manquantes
        Schema::table('users', function (Blueprint $table) {
            // Vérifier si role existe
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('password');
            } else {
                // S'il existe déjà, on le modifie
                $table->string('role')->default('user')->change();
            }
            
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('role');
            } else {
                $table->string('status')->default('active')->change();
            }
            
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('avatar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ne pas supprimer dans rollback pour éviter de perdre des données
            // $table->dropColumn(['role', 'status', 'avatar', 'last_login_at']);
        });
    }
};