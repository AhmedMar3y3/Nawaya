<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class CityFilter
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
        $this->applySearch()->applyOrdering();
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

    protected function applyOrdering(): self
    {
        $this->query->orderBy('name', 'asc');
        return $this;
    }

    public function getActiveFilters(): array
    {
        return [
            'search' => $this->request->get('search'),
        ];
    }

    public function hasActiveFilters(): bool
    {
        return !empty(array_filter($this->getActiveFilters()));
    }
}
