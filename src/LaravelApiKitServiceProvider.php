<?php

namespace Supaapps\LaravelApiKit;

use Illuminate\Support\ServiceProvider;
use Supaapps\LaravelApiKit\Services\ObserversProvider;

class LaravelApiKitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->publishes([
            __DIR__ . '/../config/supaapps-laravel-api-kit.php' => config_path('supaapps-laravel-api-kit.php'),
        ]);
    }

    public function register()
    {
        if (config('supaapps-laravel-api-kit.audit', false)) {
            $this->app->register(ObserversProvider::class);
        }
    }
}
