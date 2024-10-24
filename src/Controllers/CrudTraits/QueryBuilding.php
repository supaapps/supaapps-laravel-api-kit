<?php

namespace Supaapps\LaravelApiKit\Controllers\CrudTraits;

use Illuminate\Database\Eloquent\Builder;

trait QueryBuilding
{
    protected function queryBuilder(): Builder
    {
        return $this->model::query()
            ->with($this->getWithRelationships());
    }

    protected function getWithRelationships(): array
    {
        return $this->with ?? [];
    }
}
