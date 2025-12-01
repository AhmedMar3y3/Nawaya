<?php

namespace App\Http\Requests\Admin\FinancialCenter;

use App\Http\Requests\Admin\BaseAdminRequest;

class AddPaymentRequest extends BaseAdminRequest
{
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ];
    }
}
