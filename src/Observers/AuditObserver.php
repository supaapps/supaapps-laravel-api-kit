<?php

namespace Supaapps\Supalara\Observers;

use Supaapps\Supalara\Actions\ActionModelCreate;
use Supaapps\Supalara\Actions\ActionModelDelete;
use Supaapps\Supalara\Actions\ActionModelUpdate;

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
