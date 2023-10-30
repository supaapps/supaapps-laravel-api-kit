<?php

namespace Supaapps\Supalara;

use Illuminate\Support\ServiceProvider;
use Supaapps\Supalara\Services\ObserversProvider;

class SupalaraServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->publishes([
            __DIR__ . '/../config/supalara.php' => config_path('supalara.php'),
        ]);
    }

    public function register()
    {
        if (config('supalara.audit', false)) {
            $this->app->register(ObserversProvider::class);
        }
    }
}
