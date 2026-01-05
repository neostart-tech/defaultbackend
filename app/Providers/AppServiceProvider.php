<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Route::aliasMiddleware('auth.api', \App\Http\Middleware\ApiAuth::class);
        Route::aliasMiddleware('admin', \App\Http\Middleware\AdminOnly::class);
    }
}