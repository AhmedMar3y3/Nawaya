<?php

namespace App\Http\Requests\Admin\Package;

use App\Http\Requests\BaseRequest;

class UpdatePackageRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'monthly_price'             => 'nullable|numeric|min:0',
            'yearly_price'              => 'nullable|numeric|min:0',
            'discount_percentage'       => 'nullable|integer|min:0|max:100',
            'has_discount'              => 'nullable|in:1',
            'description'               => 'nullable|string',
            'streak_restores_per_month' => 'nullable|integer|min:0',
            'free_trial_days'           => 'nullable|integer|min:0',
        ];
    }
}
