<?php

namespace Supaapps\Supalara\Controllers;


class BaseCrudController extends Controller
{
    public bool $shouldPaginate = false;
    public string $model;
    public bool $isDeletable = false;
    public bool $readOnly = false;
    public ?string $searchField = null;
    public array $searchFields = [];
    public array $filters = [];
    public array $dateFilters = [];
}
