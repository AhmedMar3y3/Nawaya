<?php

namespace App\Http\Requests\API\Review;

use App\Enums\Subscription\SubscriptionStatus;
use App\Http\Requests\BaseRequest;
use App\Models\Subscription;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class StoreReviewRequest extends BaseRequest
{
    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'subscription_id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')
                    ->where('user_id', $userId)
                    ->where('status', SubscriptionStatus::PAID->value),
            ],
            'workshop_id'     => 'required|exists:workshops,id',
            'rating'          => 'required|integer|in:1,2,3,4,5',
            'review'          => 'nullable|string|max:255',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $subscriptionId = $this->input('subscription_id');
            $workshopId = $this->input('workshop_id');
            $userId = auth()->id();

            if (!$subscriptionId || !$workshopId || !$userId) {
                return;
            }

            $subscription = Subscription::where('id', $subscriptionId)
                ->where('user_id', $userId)
                ->where('workshop_id', $workshopId)
                ->where('status', SubscriptionStatus::PAID->value)
                ->first();

            if (!$subscription) {
                $validator->errors()->add('subscription_id', 'The selected subscription does not belong to you, does not match the workshop, or is not paid.');
            }
        });
    }
}
