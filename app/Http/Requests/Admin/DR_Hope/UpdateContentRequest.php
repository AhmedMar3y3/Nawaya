<?php

namespace App\Http\Requests\Admin\DR_Hope;

use App\Enums\Settings\DrHope;
use App\Http\Requests\BaseRequest;

class UpdateContentRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'type'  => 'nullable|string|in:' . implode(',', array_column(DrHope::cases(), 'value')),

            'title' => [
                'nullable',
                'string',
                'max:255',
                'required_if:type,' . DrHope::INSTAGRAM->value . ',' . DrHope::VIDEO->value,
                'prohibited_if:type,' . DrHope::IMAGE->value,
            ],

            'image' => [
                'nullable',
                'image',
                'max:5000',
                'required_if:type,' . DrHope::IMAGE->value,
                'prohibited_if:type,' . DrHope::INSTAGRAM->value . ',' . DrHope::VIDEO->value,
            ],

            'link'  => [
                'nullable',
                'url',
                'max:255',
                'required_if:type,' . DrHope::INSTAGRAM->value . ',' . DrHope::VIDEO->value,
                'prohibited_if:type,' . DrHope::IMAGE->value,
            ],
        ];
    }
}
