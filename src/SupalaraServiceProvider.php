<?php

namespace Supaapps\Supalara;

use Illuminate\Support\ServiceProvider;
use Supaapps\Supalara\Console\Commands\CrudControllerMakeCommand;
use Supaapps\Supalara\Services\ObserversProvider;

class SupalaraServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
            $this->publishes([
                __DIR__.'/../config/supalara.php' => config_path('supalara.php'),
            ]);
            $this->registerCommands();
        }
    }

    public function register()
    {
        if (config('supalara.audit', false)) {
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
