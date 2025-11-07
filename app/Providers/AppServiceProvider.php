<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ClassementService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // ✅ IMPORTANT : Enregistrer le service
        $this->app->singleton(ClassementService::class, function ($app) {
            return new ClassementService();
        });
    }

    public function boot(): void
    {
        //
    }
    
}
