<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ExpenseFilter
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
            if (method_exists($this, $name) && $value !== null && $value !== '' && $name !== 'tab') {
                $this->$name($value);
            }
        }

        if (! $this->request->has('sort')) {
            $this->builder->orderBy('created_at', 'desc');
        }

        return $this->builder;
    }

    protected function category($value): void
    {
        if ($value === 'general') {
            $this->builder->whereNull('workshop_id');
        } elseif ($value !== 'all' && is_numeric($value)) {
            $this->builder->where('workshop_id', $value);
        }
    }

    protected function search($value): void
    {
        $this->builder->where(function ($query) use ($value) {
            $query->where('title', 'like', "%{$value}%")
                ->orWhere('vendor', 'like', "%{$value}%")
                ->orWhere('invoice_number', 'like', "%{$value}%")
                ->orWhere('notes', 'like', "%{$value}%")
                ->orWhereHas('workshop', function ($q) use ($value) {
                    $q->where('title', 'like', "%{$value}%");
                });
        });
    }

    protected function vendor($value): void
    {
        $this->builder->where('vendor', 'like', "%{$value}%");
    }

    protected function date_from($value): void
    {
        $this->builder->whereDate('created_at', '>=', $value);
    }

    protected function date_to($value): void
    {
        $this->builder->whereDate('created_at', '<=', $value);
    }

    protected function amount_min($value): void
    {
        $this->builder->where('amount', '>=', $value);
    }

    protected function amount_max($value): void
    {
        $this->builder->where('amount', '<=', $value);
    }

    protected function sort($value): void
    {
        switch ($value) {
            case 'amount_asc':
                $this->builder->orderBy('amount', 'asc');
                break;
            case 'amount_desc':
                $this->builder->orderBy('amount', 'desc');
                break;
            case 'date_asc':
                $this->builder->orderBy('created_at', 'asc');
                break;
            case 'date_desc':
            default:
                $this->builder->orderBy('created_at', 'desc');
                break;
        }
    }
}
