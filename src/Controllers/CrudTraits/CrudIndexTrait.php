<?php

namespace Supaapps\Supalara\Controllers\CrudTraits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait CrudIndexTrait
{
    public function index(Request $request)
    {
        $query = $this->model::query();

        if (!is_null($this->searchField) && $request->has('search')) {
            $query->where($this->searchField, 'LIKE', '%' . $request->get('search') . '%');
        }

        // SEARCH BY MULTIPLE FIELDS -----------
        if ($request->has('search') && is_array($this->searchFields)) {
            $query->where(function ($q) use ($request) {
                foreach ($this->searchFields as $field) {
                    $q->orWhere($field, 'LIKE', '%' . $request->get('search') . '%');
                }
            });
        }

        // FILTER BY COLUMNS -------------------
        foreach ($this->filters as $key) {
            if ($request->has($key)) {
                $query->whereIn(Str::singular($key), $request->get($key));
            }
        }

        // FILTER BY DATES ---------------------
        foreach ($this->dateFilters as $key) {
            if (is_string($request->get("{$key}_min"))) {
                $query->whereDate($key, '>=', $request->get("{$key}_min"));
            }

            if (is_string($request->get("{$key}_max"))) {
                $query->whereDate($key, '<=', $request->get("{$key}_max"));
            }
        }

        if ($this->shouldPaginate) {
            return $query->paginate($request->get('per_page', 50));
        } else {
            return $query->get();
        }
    }
}
