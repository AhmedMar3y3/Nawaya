<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ProductFilter
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
                ->orWhere('price', 'like', "%{$value}%");
        });
    }

    protected function owner_type($value): void
    {
        $this->builder->where('owner_type', $value);
    }
}

