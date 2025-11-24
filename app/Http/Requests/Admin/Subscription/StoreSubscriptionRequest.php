<?php

namespace App\Http\Requests\Admin\Subscription;

use App\Http\Requests\BaseRequest;

class StoreSubscriptionRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'in:monthly,yearly'],
            'starts_at' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }
}

