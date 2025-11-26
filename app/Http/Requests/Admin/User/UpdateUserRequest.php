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
        $userId = $this->route('id');

        return [
            'full_name'  => ['required', 'string', 'max:255'],
            'phone'      => ['required', 'string', 'unique:users,phone,' . $userId, 'max:20'],
            'email'      => ['nullable', 'email', 'unique:users,email,' . $userId, 'max:255'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'is_active'  => ['nullable', 'boolean'],
        ];
    }

}
