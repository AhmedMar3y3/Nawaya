<?php

namespace App\Filters;

use App\Enums\Difficulty;
use App\Enums\QuestionKind;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class QuestionFilter
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
        $this->applySearch()->applyDifficulty()->applyKind()->applyOrdering();
        return $this->query;
    }

    protected function applySearch(): self
    {
        if ($this->request->filled('search')) {
            $searchTerm = $this->request->get('search');
            $this->query->where('question', 'like', "%{$searchTerm}%");
        }

        return $this;
    }

    protected function applyDifficulty(): self
    {
        if ($this->request->filled('difficulty')) {
            $difficulty = $this->request->get('difficulty');

            if (in_array($difficulty, array_column(Difficulty::cases(), 'value'))) {
                $this->query->where('difficulty', $difficulty);
            }
        }

        return $this;
    }

    protected function applyKind(): self
    {
        if ($this->request->filled('kind')) {
            $kind = $this->request->get('kind');

            if (in_array($kind, array_column(QuestionKind::cases(), 'value'))) {
                $this->query->where('kind', $kind);
            }
        }

        return $this;
    }

    protected function applyOrdering(): self
    {
        $this->query->orderBy('times_used', 'desc')->orderBy('id', 'desc');
        return $this;
    }

    public function getActiveFilters(): array
    {
        return [
            'search'     => $this->request->get('search'),
            'difficulty' => $this->request->get('difficulty'),
            'kind'       => $this->request->get('kind'),
        ];
    }

    public function hasActiveFilters(): bool
    {
        return ! empty(array_filter($this->getActiveFilters()));
    }
}
