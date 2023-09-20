<?php

namespace Supaapps\Supalara\Controllers\CrudTraits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Supaapps\Supalara\Enums\Operator;
use Supaapps\Supalara\Exceptions\OperatorIsNotDefined;

trait CrudIndexTrait
{
    public function index(Request $request)
    {
        $query = $this->model::query();

        if (!is_null($this->searchField) && $request->has('search')) {
            $query->where($this->searchField, 'LIKE', '%' . $request->get('search') . '%');
        }

        // SEARCH BY MULTIPLE FIELDS -----------
        $searchFields = $this->getSearchFields();
        if (!empty($searchFields) && $request->has('search')) {
            $query->where(function ($q) use ($request, $searchFields) {
                foreach ($searchFields as $field => $operator) {
                    switch ($operator) {
                        case Operator::LIKE:
                            $q->orWhere($field, 'LIKE', '%' . $request->get('search') . '%');
                            break;
                        case Operator::DATE:
                            $q->orWhereDate($field, $request->get('search'));
                            break;
                        case Operator::EQUAL:
                            $q->orWhere($field, $request->get('search'));
                            break;
                        default:
                            throw new OperatorIsNotDefined("{$operator} is unknown operator");
                            break;
                    }
                }
            });
        }

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

    private function getSearchFields(): array
    {
        return $this->searchFields;
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
        return $this->getSearchFields();
    }

    private function getDefaultOrderByColumns(): ?array
    {
        return $this->defaultOrderByColumns;
    }
}
