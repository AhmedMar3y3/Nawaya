<?php

namespace App\Http\Requests\Admin\User;

use App\Enums\Gender;
use App\Enums\Level;
use App\Enums\Section;
use App\Http\Requests\BaseRequest;

class StoreUserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name'                => ['required', 'string', 'max:255'],
            'phone'               => ['required', 'string', 'unique:users,phone', 'max:20'],
            'email'               => ['nullable', 'email', 'unique:users,email', 'max:255'],
            'age'                 => ['required', 'integer', 'min:1', 'max:120'],
            'gender'              => ['required', 'in:' . implode(',', array_column(Gender::cases(), 'value'))],
            'section'             => ['required', 'in:' . implode(',', array_column(Section::cases(), 'value'))],
            'city_id'             => ['nullable', 'exists:cities,id'],
            'school_id'           => ['nullable', 'exists:schools,id'],
            'exam_date'           => ['nullable', 'date', 'after_or_equal:today'],
            'image'               => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'start_level'         => ['required', 'in:' . implode(',', array_column(Level::cases(), 'value'))],
            'is_verified'         => ['boolean'],
            'is_active'           => ['boolean'],
            'is_notify'           => ['boolean'],
            'completed_info'      => ['boolean'],
            'questionnaire_taken' => ['boolean'],
        ];
    }

}
