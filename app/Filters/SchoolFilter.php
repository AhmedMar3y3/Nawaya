<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class SchoolFilter
{
    protected Builder $query;
    protected Request $request;

    public function __construct(Builder $query, Request $request)
    {
        $this->query   = $query;
        $this->request = $request;
    }

    public function apply(): Builder
    {
        $this->applySearch()->applyCityFilter()->applyOrdering();
        return $this->query;
    }

    protected function applySearch(): self
    {
        if ($this->request->filled('search')) {
            $searchTerm = $this->request->get('search');
            $this->query->where('name', 'like', "%{$searchTerm}%");
        }

        return $this;
    }

    protected function applyCityFilter(): self
    {
        if ($this->request->filled('city_id')) {
            $this->query->where('city_id', $this->request->get('city_id'));
        }

        return $this;
    }

    protected function applyOrdering(): self
    {
        $this->query->orderBy('name', 'asc');
        return $this;
    }

    public function getActiveFilters(): array
    {
        return [
            'search'  => $this->request->get('search'),
            'city_id' => $this->request->get('city_id'),
        ];
    }

    public function hasActiveFilters(): bool
    {
        return !empty(array_filter($this->getActiveFilters()));
    }
}

