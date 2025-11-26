<?php

namespace App\Http\Requests\Admin\Partner;

use App\Http\Requests\BaseRequest;

class UpdatePartnerRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'image'       => 'nullable|image|max:5000',
            'link'        => 'nullable|url|max:255',
            'description' => 'nullable|string|max:255',
        ];
    }
}

