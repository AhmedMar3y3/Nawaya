<?php

namespace App\Http\Requests\Admin\Expense;

use App\Http\Requests\Admin\BaseAdminRequest;

class UpdateExpenseRequest extends BaseAdminRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'workshop_id' => 'nullable|exists:workshops,id',
            'invoice_number' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vendor' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'is_including_tax' => 'boolean',
        ];
    }
}
