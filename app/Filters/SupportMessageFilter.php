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
            if (method_exists($this, $name) && ! empty($value)) {
                $this->$name($value);
            }
        }

        return $this->builder;
    }

    protected function search($value): void
    {
        $this->builder->where(function ($query) use ($value) {
            $query->where('name', 'like', "%{$value}%")
                ->orWhere('email', 'like', "%{$value}%")
                ->orWhere('title', 'like', "%{$value}%")
                ->orWhere('message', 'like', "%{$value}%");
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
            case 'name_asc':
                $this->builder->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $this->builder->orderBy('name', 'desc');
                break;
            case 'email_asc':
                $this->builder->orderBy('email', 'asc');
                break;
            case 'email_desc':
                $this->builder->orderBy('email', 'desc');
                break;
            case 'title_asc':
                $this->builder->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $this->builder->orderBy('title', 'desc');
                break;
            case 'created_asc':
                $this->builder->orderBy('created_at', 'asc');
                break;
            case 'created_desc':
                $this->builder->orderBy('created_at', 'desc');
                break;
            default:
                $this->builder->orderBy('created_at', 'desc');
        }
    }
}
