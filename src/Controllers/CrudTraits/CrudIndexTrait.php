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

        // SEARCH BY COLUMN TYPES --------------
        $query->where(function ($query) use ($request) {
            if (!empty($this->getStringSearchFields()) && $request->has('search')) {
                foreach ($this->getStringSearchFields() as $field) {
                    $query->orWhere($field, 'LIKE', '%' . $request->get('search') . '%');
                }
            }

            if (!empty($this->getIntegerSearchFields()) && $request->has('search')) {
                foreach ($this->getIntegerSearchFields() as $field) {
                    $query->orWhere($field, $request->get('search'));
                }
            }

            if (!empty($this->getDateSearchFields()) && $request->has('search')) {
                foreach ($this->getDateSearchFields() as $field) {
                    $query->orWhereDate($field, $request->get('search'));
                }
            }
        });

        // FILTER BY COLUMNS -------------------
        foreach ($this->getFilters() as $key) {
            if ($request->has($key)) {
                $query->whereIn(Str::singular($key), $request->get($key));
            }
        }

        // FILTER BY DATES ---------------------
        foreach ($this->getDateFilters() as $key) {
            if (is_string($request->get("{$key}_min"))) {
                $query->whereDate($key, '>=', $request->get("{$key}_min"));
            }

            if (is_string($request->get("{$key}_max"))) {
                $query->whereDate($key, '<=', $request->get("{$key}_max"));
            }
        }

        // SORT COLUMNS ------------------------
        $availableSortColumns = $this->getOrderByColumns();
        $sortQuery = $request->get('sort', $this->getDefaultOrderByColumns());

        if (!empty($availableSortColumns) && is_array($sortQuery)) {
            foreach ($sortQuery as $value) {
                $value = explode(",", $value);
                $column = $value[0];
                $dir = $value[1] ?? 'asc';

                if (!in_array($column, $availableSortColumns)) {
                    continue;
                }

                $query->orderBy($column, $dir);
            }
        }

        if ($this->shouldPaginate) {
            return $query->paginate($request->get('per_page', 50));
        } else {
            return $query->get();
        }
    }

    private function getStringSearchFields(): array
    {
        return $this->stringSearchFields;
    }

    private function getIntegerSearchFields(): array
    {
        return $this->integerSearchFields;
    }

    private function getDateSearchFields(): array
    {
        return $this->dateSearchFields;
    }

    private function getFilters(): array
    {
        return $this->filters;
    }

    private function getDateFilters(): array
    {
        return $this->dateFilters;
    }

    private function getOrderByColumns(): array
    {
        return array_merge(
            $this->getStringSearchFields(),
            $this->getIntegerSearchFields(),
            $this->getDateSearchFields(),
        );
    }

    private function getDefaultOrderByColumns(): ?array
    {
        return $this->defaultOrderByColumns;
    }
}
