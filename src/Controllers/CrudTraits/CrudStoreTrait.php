<?php

namespace Supaapps\Supalara\Controllers\CrudTraits;

use Illuminate\Http\Request;

trait CrudStoreTrait
{
    public function store(Request $request)
    {
        $model = new $this->model();
        $model->fill($request->only($model->getFillable()));
        $model->save();
        return $model;
    }
}
