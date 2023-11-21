<?php

namespace Tests\Stubs\Controllers;

use Tests\Stubs\SupaLaraExampleModel;
use Supaapps\LaravelApiKit\Controllers\BaseCrudController;
use Supaapps\LaravelApiKit\Controllers\CrudTraits\CrudDestroyTrait;

class SupaLaraNonDeletableExampleController extends BaseCrudController
{
    use CrudDestroyTrait;

    public string $model = SupaLaraExampleModel::class;

    public bool $isDeletable = false;

    public bool $readOnly = false;
}
