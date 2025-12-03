<?php

namespace App\Http\Requests\Admin\Expense;

use App\Http\Requests\Admin\BaseAdminRequest;

class UpdateExpenseRequest extends BaseAdminRequest
{
    protected function prepareForValidation(): void
    {
        if (!$this->has('is_including_tax')) {
            $this->merge(['is_including_tax' => false]);
        }
    }

    public function rules(): array
    {
        return [
            'title'            => 'nullable|string|max:255',
            'workshop_id'      => 'nullable|exists:workshops,id',
            'invoice_number'   => 'nullable|string|max:255',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vendor'           => 'nullable|string|max:255',
            'amount'           => 'nullable|numeric|min:0',
            'notes'            => 'nullable|string',
            'is_including_tax' => 'nullable|boolean',
        ];
    }
}
