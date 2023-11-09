<?php

namespace Tests\Stubs\Controllers;

use Tests\Stubs\SupaLaraExampleModel;
use Supaapps\LaravelApiKit\Controllers\BaseCrudController;
use Supaapps\LaravelApiKit\Controllers\CrudTraits\AllCrudTraits;

class SupaLaraPaginatedExampleController extends BaseCrudController
{
    use AllCrudTraits;

    public string $model = SupaLaraExampleModel::class;

    public bool $shouldPaginate = true;

    public ?string $searchField = 'id';
}
