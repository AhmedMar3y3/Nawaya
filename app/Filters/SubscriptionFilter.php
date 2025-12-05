<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class SubscriptionFilter
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
            $query->whereHas('user', function ($q) use ($value) {
                $q->where('full_name', 'like', "%{$value}%")
                  ->orWhere('email', 'like', "%{$value}%")
                  ->orWhere('phone', 'like', "%{$value}%");
            })
            ->orWhere('full_name', 'like', "%{$value}%")
            ->orWhere('phone', 'like', "%{$value}%")
            ->orWhereHas('workshop', function ($q) use ($value) {
                $q->where('title', 'like', "%{$value}%");
            });
        });
    }

    protected function workshop_id($value): void
    {
        $this->builder->where('workshop_id', $value);
    }

    protected function status($value): void
    {
        $this->builder->where('status', $value);
    }
}

