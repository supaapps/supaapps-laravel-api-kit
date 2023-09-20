<?php

namespace Supaapps\Supalara\Controllers;

class BaseCrudController extends Controller
{
    /**
     * Indicates the index should return paginated response
     *
     * @var boolean
     */
    public bool $shouldPaginate = false;

    /**
     * The model for CRUD operations
     *
     * @var string
     */
    public string $model;

    /**
     * The model can be deleted
     *
     * @var boolean
     */
    public bool $isDeletable = false;

    /**
     * The model can't be updated
     *
     * @var boolean
     */
    public bool $readOnly = false;

    /**
     * Search by column
     *
     * @var string|null
     */
    public ?string $searchField = null;

    /**
     * Search by string columns
     *
     * @var array
     */
    public array $stringSearchFields = [];

    /**
     * Search by integer columns
     *
     * @var array
     */
    public array $integerSearchFields = [];

    /**
     * Search by date columns
     *
     * @var array
     */
    public array $dateSearchFields = [];

    /**
     * Filter by columns
     *
     * @var array
     */
    public array $filters = [];

    /**
     * Filter by dates
     *
     * @var array
     */
    public array $dateFilters = [];

    /**
     * Filter columns that is null or not null
     *
     * @var array|null
     */
    public ?array $isEmptyFilters = [];

    /**
     * Default order by column when sorting query is not existing
     * ex: ['created_at,desc']
     *
     * @var array|null
     */
    public ?array $defaultOrderByColumns = null;
}
