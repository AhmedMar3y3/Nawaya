<?php

namespace App\Http\Resources\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user            = $request->user();
        $isAuthenticated = $user !== null;
        $isSubscribed    = $isAuthenticated ? $this->isSubscribedByUser($user->id) : false;

        return [
            'id'                      => $this->id,
            'title'                   => $this->title,
            'type'                    => $this->type,
            'online_link'             => $isSubscribed ? $this->online_link : null,
            'start_date'              => $this->start_date->format('Y-m-d'),
            'start_time'              => $this->start_time,
            'time_remaining_to_start' => $this->getTimeRemainingToStart(),
            'is_subscribed'           => $isSubscribed,
            'requires_authentication' => ! $isAuthenticated,
        ];
    }

    public function getTimeRemainingToStart(): ?string
    {
        $now = now();
        if ($now->greaterThanOrEqualTo($this->start_date->setTimeFromTimeString($this->start_time))) {
            return null;
        }

        $diff = $now->diff($this->start_date->setTimeFromTimeString($this->start_time));

        $parts = [];
        if ($diff->d > 0) {
            $parts[] = $diff->d . ' days';
        }
        if ($diff->h > 0) {
            $parts[] = $diff->h . ' hours';
        }
        if ($diff->i > 0) {
            $parts[] = $diff->i . ' minutes';
        }
        if ($diff->s > 0) {
            $parts[] = $diff->s . ' seconds';
        }

        return implode(', ', $parts);
    }
}
