<?php

namespace App\Http\Requests\Admin\User;

use App\Enums\Gender;
use App\Enums\Level;
use App\Enums\Section;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id ?? $this->route('user');

        return [
            'name'                => ['required', 'string', 'max:255'],
            'phone'               => ['required', 'string', 'unique:users,phone,' . $userId, 'max:20'],
            'email'               => ['nullable', 'email', 'unique:users,email,' . $userId, 'max:255'],
            'age'                 => ['required', 'integer', 'min:1', 'max:120'],
            'gender'              => ['required', 'in:' . implode(',', array_column(Gender::cases(), 'value'))],
            'section'             => ['required', 'in:' . implode(',', array_column(Section::cases(), 'value'))],
            'city_id'             => ['nullable', 'exists:cities,id'],
            'school_id'           => ['nullable', 'exists:schools,id'],
            'exam_date'           => ['nullable', 'date'],
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
