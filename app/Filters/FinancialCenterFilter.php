<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class FinancialCenterFilter
{
    protected $builder;
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $name => $value) {
            if (method_exists($this, $name) && $value !== null && $value !== '' && $value !== 'all') {
                $this->$name($value);
            }
        }

        return $this->builder;
    }

    protected function workshop_filter($value): void
    {
        if (is_numeric($value)) {
            $this->builder->where('id', $value);
        }
    }

    protected function search($value): void
    {
        $this->builder->where(function ($query) use ($value) {
            $query->where('title', 'like', "%{$value}%")
                ->orWhere('teacher', 'like', "%{$value}%");
        });
    }

    protected function teacher($value): void
    {
        $this->builder->where('teacher', 'like', "%{$value}%");
    }
}
