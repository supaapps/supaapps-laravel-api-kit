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
     * Search by columns
     *
     * @var array
     */
    public array $searchFields = [];

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
     * Default order by column when sorting query is not existing
     * ex: ['created_at,desc']
     *
     * @var array|null
     */
    public ?array $defaultOrderByColumns = null;
}
