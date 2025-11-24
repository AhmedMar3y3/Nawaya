<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\BaseRequest;

class UserFilterRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'search'                     => ['nullable', 'string', 'max:255'],
            'status'                     => ['nullable', 'in:active,inactive'],
            'verified'                   => ['nullable', 'boolean'],
            'gender'                     => ['nullable', 'in:male,female'],
            'section'                    => ['nullable', 'string'],
            'start_level'                => ['nullable', 'string'],
            'profile_completed'          => ['nullable', 'boolean'],
            'questionnaire_taken'        => ['nullable', 'boolean'],
            'city_id'                    => ['nullable', 'integer', 'exists:cities,id'],
            'school_id'                  => ['nullable', 'integer', 'exists:schools,id'],
            'date_from'                  => ['nullable', 'date'],
            'date_to'                    => ['nullable', 'date'],
            'age_from'                   => ['nullable', 'integer', 'min:1'],
            'age_to'                     => ['nullable', 'integer', 'min:1'],
            'has_devices'                => ['nullable', 'boolean'],
            'has_questionnaire_attempts' => ['nullable', 'boolean'],
            'sort'                       => ['nullable', 'string'],
        ];
    }
}
