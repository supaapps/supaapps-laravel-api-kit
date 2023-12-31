<?php

namespace Supaapps\LaravelApiKit\Controllers\CrudTraits;

use Illuminate\Http\Request;

trait CrudShowTrait
{
    public function show($id)
    {
        return $this->model::findOrFail($id);
    }
}
