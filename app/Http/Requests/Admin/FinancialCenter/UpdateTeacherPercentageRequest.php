<?php

namespace App\Http\Requests\Admin\FinancialCenter;

use App\Http\Requests\Admin\BaseAdminRequest;

class UpdateTeacherPercentageRequest extends BaseAdminRequest
{
    public function rules(): array
    {
        return [
            'teacher_per' => 'required|numeric|min:0|max:100',
        ];
    }
}
