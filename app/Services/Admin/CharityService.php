<?php

namespace App\Services\Admin;

use App\Models\Charity;
use Illuminate\Support\Collection;

class CharityService
{
    public function getCharities(bool $onlyTrashed = false): Collection
    {
        $query = $onlyTrashed ? Charity::onlyTrashed() : Charity::query();
        
        return $query->with(['user', 'workshop', 'package'])
            ->where('status', \App\Enums\Subscription\SubscriptionStatus::PAID)
            ->whereRaw('used_seats < number_of_seats')
            ->latest()
            ->get();
    }

    public function getTotalAvailableAmount(): float
    {
        $charities = $this->getCharities(false);
        $totalAvailable = 0;

        foreach ($charities as $charity) {
            $pricePerSeat = $charity->price / $charity->number_of_seats;
            $usedSeatsPrice = $pricePerSeat * ($charity->used_seats ?? 0);
            $totalAvailable += ($charity->price - $usedSeatsPrice);
        }

        return $totalAvailable;
    }

    public function deleteCharity(int $charityId): Charity
    {
        $charity = Charity::findOrFail($charityId);
        
        if ($charity->used_seats > 0) {
            throw new \Exception('لا يمكن حذف اشتراك خيري تم استخدام بعض مقاعده');
        }
        
        $charity->delete();
        return $charity;
    }

    public function restoreCharity(int $charityId): Charity
    {
        $charity = Charity::onlyTrashed()->findOrFail($charityId);
        $charity->restore();
        return $charity;
    }

    public function permanentlyDeleteCharity(int $charityId): void
    {
        $charity = Charity::onlyTrashed()->findOrFail($charityId);
        
        if ($charity->used_seats > 0) {
            throw new \Exception('لا يمكن حذف اشتراك خيري تم استخدام بعض مقاعده نهائياً');
        }
        
        $charity->forceDelete();
    }

    public function assignSeat(int $charityId, int $userId, ?string $charityNotes = null): array
    {
        $charity = Charity::with(['user', 'workshop', 'package'])->findOrFail($charityId);
        
        if ($charity->status !== \App\Enums\Subscription\SubscriptionStatus::PAID) {
            throw new \Exception('الاشتراك الخيري غير مدفوع');
        }
        
        $availableSeats = $charity->number_of_seats - ($charity->used_seats ?? 0);
        if ($availableSeats <= 0) {
            throw new \Exception('لا توجد مقاعد متاحة في هذا الدعم');
        }
        
        $targetUser = \App\Models\User::findOrFail($userId);
        
        $existingSubscription = \App\Models\Subscription::where('user_id', $userId)
            ->where('workshop_id', $charity->workshop_id)
            ->where('status', \App\Enums\Subscription\SubscriptionStatus::PAID)
            ->first();
            
        if ($existingSubscription) {
            throw new \Exception('المستخدم لديه اشتراك مدفوع بالفعل في هذه الورشة');
        }
        
        $pricePerSeat = $charity->price / $charity->number_of_seats;
        
        $subscription = \App\Models\Subscription::create([
            'user_id'       => $userId,
            'workshop_id'   => $charity->workshop_id,
            'package_id'    => $charity->package_id,
            'price'         => $pricePerSeat,
            'paid_amount'   => $pricePerSeat,
            'status'        => \App\Enums\Subscription\SubscriptionStatus::PAID,
            'payment_type'  => \App\Enums\Payment\PaymentType::CHARITY,
            'charity_id'    => $charity->id,
            'charity_notes' => $charityNotes,
        ]);
        
        $charity->increment('used_seats');
        
        return [
            'subscription' => $subscription->fresh(['user', 'workshop', 'package']),
            'charity' => $charity->fresh(['user', 'workshop', 'package']),
        ];
    }

    public function returnSeats(int $charityId, int $seatsCount, string $action): Charity
    {
        $charity = Charity::with(['user', 'workshop', 'package'])->findOrFail($charityId);   
        $pricePerSeat = $charity->price / $charity->number_of_seats;
        $returnAmount = $pricePerSeat * $seatsCount;
        
        if ($action === 'keep_balance') {
            $user = $charity->user;
            $user->balance = ($user->balance ?? 0) + $returnAmount;
            $user->save();
            
            \App\Models\UserBalanceHistory::create([
                'user_id'     => $user->id,
                'workshop_id' => $charity->workshop_id,
                'description' => "استرجاع {$seatsCount} مقعد من صندوق الدعم لورشة: " . ($charity->workshop?->title ?? 'غير محدد'),
                'type'        => 'add',
                'amount'      => $returnAmount,
            ]);
            
            $charity->increment('user_balance', $seatsCount);
        } else if ($action === 'refund') {
            $charity->increment('refunded_seats', $seatsCount);
        }
        
        $charity->decrement('number_of_seats', $seatsCount);
        $charity->decrement('price', $returnAmount);
        return $charity->fresh(['user', 'workshop', 'package']);
    }
}

