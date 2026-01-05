<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            if (!Schema::hasColumn('activities', 'title')) {
                $table->string('title')->nullable()->after('id');
            }
            if (!Schema::hasColumn('activities', 'icon')) {
                $table->string('icon')->nullable()->after('type');
            }
            if (!Schema::hasColumn('activities', 'bg_color')) {
                $table->string('bg_color')->nullable()->after('icon');
            }
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['title', 'icon', 'bg_color']);
        });
    }
};