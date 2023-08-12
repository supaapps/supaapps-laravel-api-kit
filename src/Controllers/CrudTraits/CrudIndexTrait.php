<?php

namespace Supaapps\Supalara\Controllers\CrudTraits;

use Illuminate\Http\Request;

trait CrudIndexTrait
{
    public function index(Request $request)
    {
        $query = $this->model::query();
        if (!is_null($this->searchField) && $request->has('search')) {
            $query->where($this->searchField, 'LIKE', '%' . $request->get('search') . '%');
        }
        if ($this->shouldPaginate) {
            return $query->paginate($request->get('per_page', 50));
        } else {
            return $query->get();
        }
    }
}
