<?php

namespace Supaapps\LaravelApiKit\Services;

use Illuminate\Support\ServiceProvider;
use Supaapps\LaravelApiKit\Observers\AuditObserver;

class ObserversProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        foreach (config('supaapps-laravel-api-kit.audit.log_actions_for_models') as $model) {
            $model::observe(AuditObserver::class);
        }
        foreach (config('supaapps-laravel-api-kit.audit.add_user_id_for_models') as $model) {
            $model::observe(AuditObserver::class);
        }
    }
}
