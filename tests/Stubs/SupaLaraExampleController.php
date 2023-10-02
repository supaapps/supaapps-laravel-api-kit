<?php

namespace Tests\Stubs;

use Supaapps\Supalara\Controllers\BaseCrudController;
use Supaapps\Supalara\Controllers\CrudTraits\AllCrudTraits;

class SupaLaraExampleController extends BaseCrudController
{
    use AllCrudTraits;

    public string $model = SupaLaraExampleModel::class;

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
