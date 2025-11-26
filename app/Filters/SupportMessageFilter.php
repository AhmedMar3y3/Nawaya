<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class SupportMessageFilter
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
        
        // Default sorting if not specified
        if (!$this->request->has('sort')) {
            $this->builder->orderBy('created_at', 'desc');
        }

        return $this->builder;
    }

    protected function search($value): void
    {
        $this->builder->where(function ($query) use ($value) {
            $query->where('message', 'like', "%{$value}%")
                ->orWhereHas('user', function ($q) use ($value) {
                    $q->where('full_name', 'like', "%{$value}%")
                      ->orWhere('email', 'like', "%{$value}%")
                      ->orWhere('phone', 'like', "%{$value}%");
                });
        });
    }

    protected function date_from($value): void
    {
        $this->builder->whereDate('created_at', '>=', $value);
    }

    protected function date_to($value): void
    {
        $this->builder->whereDate('created_at', '<=', $value);
    }

    protected function sort($value): void
    {
        switch ($value) {
            case 'created_asc':
                $this->builder->orderBy('created_at', 'asc');
                break;
            case 'created_desc':
            default:
                $this->builder->orderBy('created_at', 'desc');
                break;
        }
    }
}
