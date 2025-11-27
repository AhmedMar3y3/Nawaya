<?php

namespace App\Http\Requests\Admin\Workshop;

use Illuminate\Foundation\Http\FormRequest;

class WorkshopFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'in:online,onsite,online_onsite,recorded'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
            'tab' => ['nullable', 'string', 'in:active,deleted'],
        ];
    }
}

