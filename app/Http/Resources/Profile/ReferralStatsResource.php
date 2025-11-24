<?php

namespace App\Http\Resources\Profile;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferralStatsResource extends JsonResource
{
    private const DEFAULT_TOTAL_REFERRALS        = 10;
    private const DEFAULT_SUBSCRIPTION_REFERRALS = 5;

    public function toArray(Request $request): array
    {
        $totalReferralsLimit        = Settings::where('key', 'referral_ord_users')->value('value') ?? self::DEFAULT_TOTAL_REFERRALS;
        $subscriptionReferralsLimit = Settings::where('key', 'referral_subs_users')->value('value') ?? self::DEFAULT_SUBSCRIPTION_REFERRALS;

        return [
            'owned_referral_code' => $this->owned_referral_code,
            'stats'               => [
                'total_referrals'        => [
                    'owned' => min($this->total_referrals, $totalReferralsLimit),
                    'limit' => (int)$totalReferralsLimit,
                ],

                'subscription_referrals' => [
                    'owned' => min($this->subscription_referrals, $subscriptionReferralsLimit),
                    'limit' => (int)$subscriptionReferralsLimit,
                ],
            ],
        ];
    }
}
