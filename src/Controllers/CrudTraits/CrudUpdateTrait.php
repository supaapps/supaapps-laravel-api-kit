<?php

namespace Supaapps\LaravelApiKit\Controllers\CrudTraits;

use Illuminate\Http\Request;

trait CrudUpdateTrait
{
    public function update(Request $request, $id)
    {
        if ($this->readOnly) {
            abort('401', 'Can\'t update Read only model');
        }
        $model = $this->model::findOrFail($id);
        $model->fill($request->only($model->getFillable()));
        $model->save();
        return $model;
    }
}
