<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class WorkshopFilter
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
            if (method_exists($this, $name) && $value !== null && $value !== '') {
                $this->$name($value);
            }
        }

        return $this->builder;
    }

    protected function search($value): void
    {
        $this->builder->where(function ($query) use ($value) {
            $query->where('title', 'like', "%{$value}%")
                ->orWhere('teacher', 'like', "%{$value}%");
        });
    }

    protected function type($value): void
    {
        $this->builder->where('type', $value);
    }

    protected function status($value): void
    {
        if ($value === 'active') {
            $this->builder->where('is_active', true);
        } elseif ($value === 'inactive') {
            $this->builder->where('is_active', false);
        }
    }
}

