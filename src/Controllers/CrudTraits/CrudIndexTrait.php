<?php

namespace Supaapps\LaravelApiKit\Controllers\CrudTraits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait CrudIndexTrait
{
    public function indexQueryBuilder(Request $request): Builder
    {
        $query = $this->model::query();

        if (!is_null($this->searchField) && $request->has('search')) {
            $query->where($this->searchField, 'LIKE', '%' . $request->get('search') . '%');
        }

        // SEARCH BY COLUMN TYPES --------------
        $query->where(function ($query) use ($request) {
            if (!empty($this->getSearchSimilarFields()) && $request->has('search')) {
                foreach ($this->getSearchSimilarFields() as $field) {
                    $query->orWhere($field, 'LIKE', '%' . $request->get('search') . '%');
                }
            }

            if (!empty($this->getSearchExactFields()) && $request->has('search')) {
                foreach ($this->getSearchExactFields() as $field) {
                    $query->orWhere($field, $request->get('search'));
                }
            }

            if (!empty($this->getSearchDateFields()) && $request->has('search')) {
                foreach ($this->getSearchDateFields() as $field) {
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

        // FILTER NULL OR NOT NULL -------------
        if (!empty($request->get('is_empty'))) {
            foreach ($this->getIsEmptyFilters() as $column) {
                $query->whereNull(
                    $column,
                    'and',
                    !$request->boolean("is_empty.{$column}")
                );
            }
        }

        // SORT COLUMNS ------------------------
        $availableSortColumns = $this->getOrderByColumns();
        $sortQuery = $request->get('sort', $this->getDefaultOrderByColumns());

        if (!empty($availableSortColumns) && is_array($sortQuery)) {
            foreach ($sortQuery as $value) {
                $value = explode(',', $value);
                $column = $value[0];
                $dir = $value[1] ?? 'asc';

                if (!in_array($column, $availableSortColumns)) {
                    continue;
                }

                $query->orderBy($column, $dir);
            }
        }

        return $query;
    }

    public function index(Request $request)
    {
        if ($this->shouldPaginate) {
            return $this->indexQueryBuilder($request)
                ->paginate($request->get('per_page', 50));
        } else {
            return $this->indexQueryBuilder($request)
                ->get();
        }
    }

    private function getSearchSimilarFields(): array
    {
        return $this->searchSimilarFields;
    }

    private function getSearchExactFields(): array
    {
        return $this->searchExactFields;
    }

    private function getSearchDateFields(): array
    {
        return $this->searchDateFields;
    }

    private function getFilters(): array
    {
        return $this->filters;
    }

    private function getDateFilters(): array
    {
        return $this->dateFilters;
    }

    private function getIsEmptyFilters(): array
    {
        return $this->isEmptyFilters;
    }

    private function getOrderByColumns(): array
    {
        return array_merge(
            $this->getSearchSimilarFields(),
            $this->getSearchExactFields(),
            $this->getSearchDateFields(),
        );
    }

    private function getDefaultOrderByColumns(): ?array
    {
        return $this->defaultOrderByColumns;
    }
}
