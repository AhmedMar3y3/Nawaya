<?php

namespace App\Http\Requests\API\Profile;

use App\Enums\Gender;
use App\Enums\Section;
use App\Http\Requests\BaseRequest;

class UpdateProfileRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
            'name'      => 'nullable|string|max:255',
            'email'     => 'nullable|email|unique:users,email,' . $this->user()->id,
            'gender'    => 'nullable|string|in:' . implode(',', array_column(Gender::cases(), 'value')),
            'section'   => 'nullable|in:' . implode(',', array_column(Section::cases(), 'value')),
            'city_id'   => 'nullable|integer|exists:cities,id',
            'school_id' => 'nullable|integer|exists:schools,id',
            'age'       => 'nullable|integer|min:10|max:30',
        ];
    }
}
