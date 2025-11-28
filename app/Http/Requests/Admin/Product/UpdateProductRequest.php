<?php

namespace App\Http\Requests\Admin\Product;

use App\Http\Requests\BaseRequest;
use App\Enums\Boutique\OwnerType;

class UpdateProductRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'title'      => ['required', 'string', 'max:255'],
            'price'      => ['required', 'numeric', 'min:0'],
            'image'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'owner_type' => ['required', 'string', 'in:' . implode(',', array_column(OwnerType::cases(), 'value'))],
            'owner_id'   => ['required_if:owner_type,' . OwnerType::USER->value, 'nullable', 'integer', 'exists:users,id'],
            'owner_per'  => ['required_if:owner_type,' . OwnerType::USER->value, 'nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->owner_type === OwnerType::PLATFORM->value) {
            $this->merge([
                'owner_id' => null,
                'owner_per' => 0,
            ]);
        }
    }
}

