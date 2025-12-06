<?php

namespace App\Http\Resources\Admin\Subscription;

use Illuminate\Http\Request;
use App\Helpers\FormatArabicDates;
use Illuminate\Http\Resources\Json\JsonResource;

class UserBalanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $histories         = $this->balanceHistories;
        $existingHistories = $histories->filter(function ($history) {
            return $history->deleted_at === null;
        });
        $deletedHistories = $histories->filter(function ($history) {
            return $history->deleted_at !== null;
        });

        $totalAdded      = $existingHistories->where('type', 'add')->sum('amount');
        $totalSubtracted = $existingHistories->where('type', 'subtract')->sum('amount');

        return [
            'id'                => (int) $this->id,
            'user_name'         => $this->full_name,
            'user_phone'        => $this->phone,
            'balance'           => (float) $this->balance,
            'total_added'       => (float) $totalAdded,
            'total_subtracted'  => (float) $totalSubtracted,
            'histories'         => BalanceHistoryResource::collection($existingHistories->values()),
            'deleted_histories' => BalanceHistoryResource::collection($deletedHistories->values()),
        ];
    }
}

class BalanceHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => (int) $this->id,
            'description'    => $this->description ?? '-',
            'type'           => $this->type,
            'amount'         => (float) $this->amount,
            'created_at'     => FormatArabicDates::formatArabicDate($this->created_at),
            'workshop_title' => $this->workshop ? $this->workshop->title : null,
        ];
    }
}
