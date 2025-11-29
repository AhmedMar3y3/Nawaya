<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use App\Http\Resources\Workshop\WorkshopResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\Subscription\SubscriptionStatus;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id'          => $this->id,
            'full_name'   => $this->full_name,
            'email'       => $this->email,
            'phone'       => $this->phone,
        ];

        if ($this->relationLoaded('subscriptions')) {
            $activeSubscriptions = $this->subscriptions->map(function ($subscription) {
                return [
                    'id' => $subscription->id,
                    'workshop' => $subscription->workshop ? new WorkshopResource($subscription->workshop) : null,
                ];
            });
            $data['active_subscriptions'] = $activeSubscriptions;
        }

        return $data;
    }
}
