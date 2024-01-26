<?php

namespace Supaapps\LaravelApiKit;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;
use Supaapps\LaravelApiKit\Services\ObserversProvider;
use Supaapps\LaravelApiKit\Console\Commands\InitLaravelApiKit;
use Supaapps\LaravelApiKit\Console\Commands\CrudControllerMakeCommand;

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

        $this->registerBlueprintMacro();

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

    private function registerBlueprintMacro(): void
    {
        Blueprint::macro(
            'auditIds',
            function (string $relatedTable = 'users', string $relatedCol = 'id') {
                $this->unsignedBigInteger('created_by_id')
                    ->nullable();
                $this->unsignedBigInteger('updated_by_id')
                    ->nullable();

                if (!is_null($relatedTable)) {
                    $this->foreign('created_by_id')
                        ->references($relatedCol)
                        ->on($relatedTable);
                    $this->foreign('updated_by_id')
                        ->references($relatedCol)
                        ->on($relatedTable);
                }
            }
        );
    }

    private function registerCommands(): void
    {
        $this->commands([
            InitLaravelApiKit::class,
            CrudControllerMakeCommand::class,
        ]);
    }
}
