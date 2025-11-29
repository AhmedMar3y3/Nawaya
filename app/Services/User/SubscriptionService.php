<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\WorkshopPackage;
use Illuminate\Support\Facades\DB;
use App\Enums\Subscription\SubscriptionType;
use App\Enums\Subscription\SubscriptionStatus;

class SubscriptionService
{
    public function getPaymentSettings(): array
    {
        $settings = Setting::whereIn('key', [
            'online_payment',
            'bank_transfer',
        ])->pluck('value', 'key');

        return [
            'online_payment' => filter_var($settings['online_payment'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'bank_transfer'  => filter_var($settings['bank_transfer'] ?? true, FILTER_VALIDATE_BOOLEAN),
        ];
    }

    public function getBankAccountSettings(): array
    {
        $settings = Setting::whereIn('key', [
            'account_name',
            'bank_name',
            'IBAN_number',
            'account_number',
            'swift',
        ])->pluck('value', 'key');

        return [
            'account_name'   => $settings['account_name'] ?? null,
            'bank_name'      => $settings['bank_name'] ?? null,
            'IBAN_number'    => $settings['IBAN_number'] ?? null,
            'account_number' => $settings['account_number'] ?? null,
            'swift'          => $settings['swift'] ?? null,
        ];
    }

    public function createSubscription(
        User $user,
        int $packageId,
        SubscriptionType $subscriptionType,
        ?string $recipientName = null,
        ?string $recipientPhone = null,
        ?int $countryId = null,
        ?string $message = null
    ): Subscription {
        $package = WorkshopPackage::with('workshop')->findOrFail($packageId);

        $price = $package->price;
        if ($package->is_offer && $package->offer_price) {
            if (!$package->offer_expiry_date || $package->offer_expiry_date->isFuture()) {
                $price = $package->offer_price;
            }
        }

        DB::beginTransaction();

        try {
            if ($subscriptionType === SubscriptionType::GIFT) {
                $recipientUser = User::where('phone', $recipientPhone)->first();

                $subscription = Subscription::create([
                    'user_id'      => $recipientUser?->id,
                    'workshop_id'  => $package->workshop_id,
                    'price'        => $price,
                    'status'       => SubscriptionStatus::PENDING,
                    'is_gift'      => true,
                    'gift_user_id' => $user->id,
                    'full_name'    => $recipientName,
                    'phone'        => $recipientPhone,
                    'country_id'   => $countryId,
                    'message'      => $message,
                ]);
            } else {
                $subscription = Subscription::create([
                    'user_id'     => $user->id,
                    'workshop_id' => $package->workshop_id,
                    'price'       => $price,
                    'status'      => SubscriptionStatus::PENDING,
                    'is_gift'     => false,
                ]);
            }

            DB::commit();

            return $subscription->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function validatePaymentType(string $paymentType, SubscriptionType $subscriptionType): void
    {
        $paymentSettings = $this->getPaymentSettings();

        if ($paymentType === 'online' && !$paymentSettings['online_payment']) {
            throw new \Exception('الدفع الإلكتروني غير متاح حالياً');
        }

        if ($paymentType === 'bank_transfer') {
            if (!$paymentSettings['bank_transfer']) {
                throw new \Exception('التحويل البنكي غير متاح حالياً');
            }

            if ($subscriptionType === SubscriptionType::GIFT) {
                throw new \Exception('التحويل البنكي غير متاح للهدايا');
            }
        }
    }
}

