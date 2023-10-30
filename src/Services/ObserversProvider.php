<?php

namespace Supaapps\Supalara\Services;

use Illuminate\Support\ServiceProvider;
use Supaapps\Supalara\Observers\AuditObserver;

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
        foreach (config('supalara.audit.log_actions_for_models') as $model) {
            $model::observe(AuditObserver::class);
        }
        foreach (config('supalara.audit.add_user_id_for_models') as $model) {
            $model::observe(AuditObserver::class);
        }
    }
}
