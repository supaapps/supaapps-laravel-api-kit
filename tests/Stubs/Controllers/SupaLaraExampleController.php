<?php

namespace Tests\Stubs\Controllers;

use Tests\Stubs\SupaLaraExampleModel;
use Supaapps\LaravelApiKit\Controllers\BaseCrudController;
use Supaapps\LaravelApiKit\Controllers\CrudTraits\AllCrudTraits;

class SupaLaraExampleController extends BaseCrudController
{
    use AllCrudTraits;

    public string $model = SupaLaraExampleModel::class;

    public bool $isDeletable = true;

    public array $searchSimilarFields = [
        'label',
    ];

    public array $searchExactFields = [
        'id',
    ];

    public array $searchDateFields = [
        'created_at',
    ];

    public array $filters = [
        'ids',
    ];

    public array $dateFilters = [
        'created_at',
    ];

    public ?array $isEmptyFilters = [
        'label',
    ];

    public ?array $defaultOrderByColumns = ['id,desc'];
}
