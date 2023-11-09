<?php

namespace Tests\Stubs\Controllers;

use Tests\Stubs\SupaLaraExampleModel;
use Supaapps\LaravelApiKit\Controllers\BaseCrudController;
use Supaapps\LaravelApiKit\Controllers\CrudTraits\CrudDestroyTrait;

class SupaLaraReadonlyExampleController extends BaseCrudController
{
    use CrudDestroyTrait;

    public string $model = SupaLaraExampleModel::class;

    public bool $isDeletable = true;

    public bool $readOnly = true;
}
