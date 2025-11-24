<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class UserFilter
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
                ->orWhere('phone', 'like', "%{$value}%")
                ->orWhere('email', 'like', "%{$value}%");
        });
    }

    protected function status($value): void
    {
        if ($value === 'active') {
            $this->builder->where('is_active', true);
        } elseif ($value === 'inactive') {
            $this->builder->where('is_active', false);
        }
    }

    protected function verified($value): void
    {
        if ($value === 'verified') {
            $this->builder->where('is_verified', true);
        } elseif ($value === 'unverified') {
            $this->builder->where('is_verified', false);
        }
    }

    protected function gender($value): void
    {
        $this->builder->where('gender', $value);
    }

    protected function section($value): void
    {
        $this->builder->where('section', $value);
    }

    protected function start_level($value): void
    {
        $this->builder->where('start_level', $value);
    }

    protected function profile_completed($value): void
    {
        if ($value === 'completed') {
            $this->builder->where('completed_info', true);
        } elseif ($value === 'incomplete') {
            $this->builder->where('completed_info', false);
        }
    }

    protected function questionnaire_taken($value): void
    {
        if ($value === 'taken') {
            $this->builder->where('questionnaire_taken', true);
        } elseif ($value === 'not_taken') {
            $this->builder->where('questionnaire_taken', false);
        }
    }

    protected function city_id($value): void
    {
        $this->builder->where('city_id', $value);
    }

    protected function school_id($value): void
    {
        $this->builder->where('school_id', $value);
    }

    protected function date_from($value): void
    {
        $this->builder->whereDate('created_at', '>=', $value);
    }

    protected function date_to($value): void
    {
        $this->builder->whereDate('created_at', '<=', $value);
    }

    protected function age_from($value): void
    {
        $this->builder->where('age', '>=', $value);
    }

    protected function age_to($value): void
    {
        $this->builder->where('age', '<=', $value);
    }

    protected function has_devices($value): void
    {
        if ($value === 'yes') {
            $this->builder->whereHas('devices');
        } elseif ($value === 'no') {
            $this->builder->whereDoesntHave('devices');
        }
    }

    protected function has_questionnaire_attempts($value): void
    {
        if ($value === 'yes') {
            $this->builder->whereHas('questionnaireAttempts');
        } elseif ($value === 'no') {
            $this->builder->whereDoesntHave('questionnaireAttempts');
        }
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
            case 'created_asc':
                $this->builder->orderBy('created_at', 'asc');
                break;
            case 'created_desc':
                $this->builder->orderBy('created_at', 'desc');
                break;
            case 'age_asc':
                $this->builder->orderBy('age', 'asc');
                break;
            case 'age_desc':
                $this->builder->orderBy('age', 'desc');
                break;
            default:
                $this->builder->orderBy('created_at', 'desc');
        }
    }
}
