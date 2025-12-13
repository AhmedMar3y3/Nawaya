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
        array | string | null $recipientName = null,
        array | string | null $recipientPhone = null,
        array | int | null $countryId = null,
        array | string | null $message = null
    ): Subscription | array {
        $package = WorkshopPackage::with('workshop')->findOrFail($packageId);

        $price = $package->price;
        if ($package->is_offer && $package->offer_price) {
            if (! $package->offer_expiry_date || $package->offer_expiry_date->isFuture()) {
                $price = $package->offer_price;
            }
        }

        DB::beginTransaction();

        try {
            if ($subscriptionType === SubscriptionType::GIFT) {
                $recipientNames  = is_array($recipientName) ? $recipientName : [$recipientName];
                $recipientPhones = is_array($recipientPhone) ? $recipientPhone : [$recipientPhone];
                $countryIds      = is_array($countryId) ? $countryId : [$countryId];
                $messages        = is_array($message) ? $message : [$message];

                if (! empty($existingPhones)) {
                    throw new \Exception('لا يمكن إنشاء عدة هدايا لنفس المستلم: ' . implode(', ', $existingPhones));
                }

                $subscriptions = [];

                foreach ($recipientPhones as $index => $phone) {
                    $recipientUser = User::where('phone', $phone)->first();

                    $subscription = Subscription::create([
                        'user_id'          => $recipientUser?->id,
                        'workshop_id'      => $package->workshop_id,
                        'package_id'       => $packageId,
                        'price'            => $price,
                        'paid_amount'      => $price,
                        'status'           => SubscriptionStatus::PENDING,
                        'is_gift'          => true,
                        'gift_user_id'     => $user->id,
                        'full_name'        => $recipientNames[$index] ?? null,
                        'phone'            => $phone,
                        'country_id'       => $countryIds[$index] ?? null,
                        'message'          => $messages[$index] ?? null,
                        'is_gift_approved' => $recipientUser !== null,
                    ]);

                    $subscriptions[] = $subscription->fresh();
                }

                DB::commit();

                return count($subscriptions) === 1 ? $subscriptions[0] : $subscriptions;
            } else {
                $subscription = Subscription::create([
                    'user_id'     => $user->id,
                    'workshop_id' => $package->workshop_id,
                    'package_id'  => $packageId,
                    'price'       => $price,
                    'paid_amount' => $price,
                    'status'      => SubscriptionStatus::PENDING,
                    'is_gift'     => false,
                ]);

                DB::commit();

                return $subscription->fresh();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function validatePaymentType(string $paymentType, SubscriptionType $subscriptionType): void
    {
        $paymentSettings = $this->getPaymentSettings();

        if ($paymentType === 'online' && ! $paymentSettings['online_payment']) {
            throw new \Exception('الدفع الإلكتروني غير متاح حالياً');
        }

        if ($paymentType === 'bank_transfer') {
            if (! $paymentSettings['bank_transfer']) {
                throw new \Exception('التحويل البنكي غير متاح حالياً');
            }

            if ($subscriptionType === SubscriptionType::GIFT) {
                throw new \Exception('التحويل البنكي غير متاح للهدايا');
            }
        }
    }

    public function createCharitySubscription(
        User $user,
        int $packageId,
        int $numberOfSeats
    ): \App\Models\Charity {
        $package = WorkshopPackage::with('workshop')->findOrFail($packageId);

        $pricePerSeat = $package->price;
        if ($package->is_offer && $package->offer_price) {
            if (! $package->offer_expiry_date || $package->offer_expiry_date->isFuture()) {
                $pricePerSeat = $package->offer_price;
            }
        }

        $totalPrice = $pricePerSeat * $numberOfSeats;

        DB::beginTransaction();

        try {
            $charity = \App\Models\Charity::create([
                'user_id'         => $user->id,
                'workshop_id'     => $package->workshop_id,
                'package_id'      => $packageId,
                'number_of_seats' => $numberOfSeats,
                'price'           => $totalPrice,
                'status'          => SubscriptionStatus::PENDING,
            ]);

            DB::commit();

            return $charity->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function validateCharityPaymentType(string $paymentType): void
    {
        $paymentSettings = $this->getPaymentSettings();

        if ($paymentType === 'online' && ! $paymentSettings['online_payment']) {
            throw new \Exception('الدفع الإلكتروني غير متاح حالياً');
        }

        if ($paymentType === 'bank_transfer' && ! $paymentSettings['bank_transfer']) {
            throw new \Exception('التحويل البنكي غير متاح حالياً');
        }
    }

    public function processCharityPayment(
        \App\Models\Charity $charity,
        \App\Enums\Payment\PaymentType $paymentType
    ): array {
        if ($charity->status !== SubscriptionStatus::PENDING) {
            throw new \Exception('الاشتراك الخيري غير صالح للمعالجة');
        }

        if ($charity->payment_type !== null) {
            throw new \Exception('تم بدء معالجة الدفع مسبقاً');
        }

        DB::beginTransaction();

        try {
            $charity->update([
                'payment_type' => $paymentType,
            ]);

            if ($paymentType === \App\Enums\Payment\PaymentType::BANK_TRANSFER) {
                $charity->update([
                    'status' => SubscriptionStatus::PROCESSING,
                ]);

                DB::commit();

                return [
                    'type' => 'bank',
                ];
            }

            $charity->load('user.country');

            DB::commit();

            return [
                'type' => 'online',
                'charity' => $charity,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
