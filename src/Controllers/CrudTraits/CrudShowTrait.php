<?php

namespace Supaapps\LaravelApiKit\Controllers\CrudTraits;

trait CrudShowTrait
{
    use QueryBuilding;

    public function show(mixed $id)
    {
        return $this->queryBuilder()
            ->findOrFail($id);
    }
}
