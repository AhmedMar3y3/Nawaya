<?php

namespace App\Http\Resources\Package;

use App\Models\Package;
use App\Models\FreeTrialUsage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    protected $user;

    public function __construct($resource, $user = null)
    {
        parent::__construct($resource);
        $this->user = $user;
    }

    public function toArray(Request $request): array
    {
        /** @var Package $package */
        $package = $this->resource;

        $packages = [];

        // Check if user's device has used trial
        $isTrialUsed = false;
        if ($this->user) {
            $udid = $this->user->getPrimaryDeviceUdid();
            if ($udid) {
                $isTrialUsed = FreeTrialUsage::hasUsedTrial($udid);
            }
        }

        // Add trial package if available and not used
        if ($package->free_trial_days > 0) {
            $packages[] = [
                'title'          => 'تجربة مجانية',
                'type'           => 'trial',
                'price'          => 0.0,
                'price_per_week' => 0.0,
                'period'         => "{$package->free_trial_days} أيام",
                'trial_days'     => (int) $package->free_trial_days,
                'is_available'   => !$isTrialUsed,
            ];
        }

        $monthlyPricePerWeek = $package->monthly_price / 4;
        $packages[]          = [
            'title'          => 'الخطة الشهرية',
            'type'           => 'monthly',
            'price'          => (float) $package->monthly_price,
            'price_per_week' => round($monthlyPricePerWeek, 2),
            'monthly_price'  => (float) $package->monthly_price,
            'period'         => 'شهري',
        ];

        $yearlyPricePerWeek = $package->yearly_price / 52;
        $packages[]         = [
            'title'          => 'الخطة السنوية',
            'type'           => 'yearly',
            'price'          => (float) $package->yearly_price,
            'price_per_week' => round($yearlyPricePerWeek, 2),
            'yearly_price'   => (float) $package->yearly_price,
            'period'         => 'سنوي',
        ];

        if ($package->has_discount && $package->discount_percentage > 0) {
            $discountedYearlyPrice  = $package->yearly_price * (1 - ($package->discount_percentage / 100));
            $discountedPricePerWeek = $discountedYearlyPrice / 52;

            $packages[] = [
                'title'               => 'عرض خاص',
                'type'                => 'yearly',
                'discount_percentage' => (int) $package->discount_percentage,
                'original_price'      => (float) $package->yearly_price,
                'price'               => round($discountedYearlyPrice, 2),
                'price_per_week'      => round($discountedPricePerWeek, 2),
                'yearly_price'        => round($discountedYearlyPrice, 2),
                'period'              => 'سنوي',
                'is_special_offer'    => true,
                'special_offer_text'  => "خصم {$package->discount_percentage}% عرض خاص",
            ];
        }

        $response = [
            'packages'                  => $packages,
            'description'               => $package->description,
            'streak_restores_per_month' => $package->streak_restores_per_month,
            'free_trial_days'           => $package->free_trial_days,
        ];

        return $response;
    }
}
