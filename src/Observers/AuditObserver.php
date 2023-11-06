<?php

namespace Supaapps\LaravelApiKit\Observers;

use Supaapps\LaravelApiKit\Actions\ActionModelCreate;
use Supaapps\LaravelApiKit\Actions\ActionModelDelete;
use Supaapps\LaravelApiKit\Actions\ActionModelUpdate;

class AuditObserver
{
    public function created($model)
    {
        ActionModelCreate::create($model);
    }

    public function updated($model)
    {
        ActionModelUpdate::create($model);
    }

    public function deleted($model)
    {
        ActionModelDelete::create($model);
    }
}
