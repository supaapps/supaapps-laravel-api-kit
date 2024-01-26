<?php

namespace Supaapps\LaravelApiKit;

use Illuminate\Support\ServiceProvider;
use Supaapps\LaravelApiKit\Services\ObserversProvider;
use Supaapps\LaravelApiKit\Console\Commands\CrudControllerMakeCommand;

class LaravelApiKitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
            $this->publishes([
                __DIR__ . '/../config/supaapps-laravel-api-kit.php'
                    => config_path('supaapps-laravel-api-kit.php'),
            ]);
            $this->registerCommands();
        }
    }

    public function register()
    {
        if (config('supaapps-laravel-api-kit.audit', false)) {
            $this->app->register(ObserversProvider::class);
        }
    }

    private function registerCommands(): void
    {
        $this->commands([
            CrudControllerMakeCommand::class,
        ]);
    }
}
