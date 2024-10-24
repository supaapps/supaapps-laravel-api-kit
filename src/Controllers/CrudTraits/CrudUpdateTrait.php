<?php

namespace Supaapps\LaravelApiKit\Controllers\CrudTraits;

use Illuminate\Http\Request;

trait CrudUpdateTrait
{
    use QueryBuilding;

    public function update(Request $request, mixed $id)
    {
        if ($this->readOnly) {
            abort('401', 'Can\'t update Read only model');
        }
        $model = $this->queryBuilder()
            ->findOrFail($id);
        $model->fill($request->only($model->getFillable()));
        $model->save();
        return $model;
    }
}
