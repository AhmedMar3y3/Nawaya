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
            if (method_exists($this, $name) && ! empty($value)) {
                $this->$name($value);
            }
        }

        return $this->builder;
    }

    protected function search($value): void
    {
        $this->builder->whereHas('user', function ($query) use ($value) {
            $query->where('name', 'like', "%{$value}%")
                ->orWhere('email', 'like', "%{$value}%")
                ->orWhere('phone', 'like', "%{$value}%");
        });
    }

    protected function status($value): void
    {
        if ($value === 'active') {
            $this->builder->where('status', 'active')
                ->where('expires_at', '>', now());
        } elseif ($value === 'expired') {
            $this->builder->where(function ($query) {
                $query->where('status', 'expired')
                    ->orWhere('expires_at', '<', now());
            });
        } elseif ($value === 'cancelled') {
            $this->builder->where('status', 'cancelled');
        } else {
            $this->builder->where('status', $value);
        }
    }

    protected function type($value): void
    {
        $this->builder->where('type', $value);
    }

    protected function is_trial($value): void
    {
        if ($value === 'yes') {
            $this->builder->whereNotNull('trial_starts_at');
        } elseif ($value === 'no') {
            $this->builder->whereNull('trial_starts_at');
        }
    }

    protected function user_id($value): void
    {
        $this->builder->where('user_id', $value);
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
                $this->builder->orderBy('created_at', 'desc');
                break;
            case 'expires_asc':
                $this->builder->orderBy('expires_at', 'asc');
                break;
            case 'expires_desc':
                $this->builder->orderBy('expires_at', 'desc');
                break;
            default:
                $this->builder->orderBy('created_at', 'desc');
        }
    }
}
