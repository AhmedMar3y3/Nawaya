<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class LessonFilter
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
        $this->applySearch()
             ->applyDifficulty()
             ->applyKind()
             ->applyOrdering();

        return $this->query;
    }

    protected function applySearch(): self
    {
        if ($this->request->filled('search')) {
            $searchTerm = $this->request->get('search');
            $this->query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('key', 'like', "%{$searchTerm}%");
            });
        }

        return $this;
    }

    protected function applyDifficulty(): self
    {
        if ($this->request->filled('difficulty')) {
            $this->query->where('difficulty', $this->request->difficulty);
        }

        return $this;
    }

    protected function applyKind(): self
    {
        if ($this->request->filled('kind')) {
            $this->query->where('kind', $this->request->kind);
        }

        return $this;
    }

    protected function applyOrdering(): self
    {
        if ($this->request->filled('sort')) {
            switch ($this->request->sort) {
                case 'name_asc':
                    $this->query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $this->query->orderBy('name', 'desc');
                    break;
                case 'created_asc':
                    $this->query->orderBy('created_at', 'asc');
                    break;
                case 'created_desc':
                    $this->query->orderBy('created_at', 'desc');
                    break;
                default:
                    $this->query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $this->query->orderBy('created_at', 'desc');
        }

        return $this;
    }

    public function getActiveFilters(): array
    {
        return [
            'search'    => $this->request->get('search'),
            'difficulty' => $this->request->get('difficulty'),
            'kind'      => $this->request->get('kind'),
            'sort'      => $this->request->get('sort'),
        ];
    }

    public function hasActiveFilters(): bool
    {
        return !empty(array_filter($this->getActiveFilters()));
    }
}

