<?php

namespace Supaapps\Supalara\Controllers;


class BaseCrudController extends Controller
{
    public bool $shouldPaginate = false;
    public string $model;
    public bool $isDeletable = false;
    public bool $readOnly = false;
    public ?string $searchField = null;

    protected function getSearchFields(): array
    {
        return [];
    }

    protected function getFilters(): array
    {
        return [];
    }

    protected function getDateFilters(): array
    {
        return [];
    }

    protected function getSortColumns(): array
    {
        return $this->getSearchFields();
    }
}
