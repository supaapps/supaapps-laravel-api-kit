<?php

namespace Tests\Stubs;

use Supaapps\Supalara\Controllers\BaseCrudController;
use Supaapps\Supalara\Controllers\CrudTraits\AllCrudTraits;

class SupaLaraPaginatedExampleController extends BaseCrudController
{
    use AllCrudTraits;

    public string $model = SupaLaraExampleModel::class;

    public bool $shouldPaginate = true;

    public ?string $searchField = 'id';
}