<?php

namespace App\Http\Controllers\User;

use App\Models\Subscription;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use App\Enums\Payment\PaymentType;
use App\Http\Controllers\Controller;
use App\Services\User\SubscriptionService;
use App\Services\User\FoloosiPaymentService;
use App\Enums\Subscription\SubscriptionType;
use App\Enums\Subscription\SubscriptionStatus;
use App\Http\Requests\API\Subscription\ProcessPaymentRequest;
use App\Http\Resources\Subscription\SubscriptionSummaryResource;
use App\Http\Requests\API\Subscription\CreateSubscriptionRequest;

class SubscriptionController extends Controller
{
    use HttpResponses;

    public function __construct(
        private readonly SubscriptionService $subscriptionService,
        private readonly FoloosiPaymentService $foloosiPaymentService
    ) {}

    public function create(CreateSubscriptionRequest $request)
    {
        try {
            $user             = auth()->user();
            $subscriptionType = SubscriptionType::from($request->subscription_type);

            $subscription = $this->subscriptionService->createSubscription(
                $user,
                $request->package_id,
                $subscriptionType,
                $request->recipient_name ?? null,
                $request->recipient_phone ?? null,
                $request->country_id ?? null,
                $request->message ?? null
            );

            $subscription->load(['workshop.packages']);

            $paymentSettings = $this->subscriptionService->getPaymentSettings();
            if ($subscriptionType === SubscriptionType::GIFT) {
                $paymentSettings['bank_transfer'] = false;
            }

            $bankAccountSettings = null;
            if ($paymentSettings['bank_transfer'] && $subscriptionType === SubscriptionType::MYSELF) {
                $bankAccountSettings = $this->subscriptionService->getBankAccountSettings();
            }

            $package = $subscription->workshop->packages->where('id', $request->package_id)->first();

            $response = [
                'subscription_id'      => $subscription->id,
                'subscription_details' => [
                    'workshop_id'    => $subscription->workshop_id,
                    'workshop_title' => $subscription->workshop->title,
                    'package_id'     => $request->package_id,
                    'package_title'  => $package->title ?? '',
                    'price'          => $subscription->price,
                ],
                'payment_options'      => $paymentSettings,
                'bank_account'         => $bankAccountSettings,
            ];

            return $this->successWithDataResponse(SubscriptionSummaryResource::make($response));
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }

    public function processPayment(ProcessPaymentRequest $request)
    {
        try {
            $user         = auth()->user();
            $subscription = Subscription::findOrFail($request->subscription_id);

            if ($subscription->is_gift) {
                if ($subscription->gift_user_id !== $user->id) {
                    return $this->failureResponse('غير مصرح لك بالوصول إلى هذه الاشتراك');
                }
            } else {
                if ($subscription->user_id !== $user->id) {
                    return $this->failureResponse('غير مصرح لك بالوصول إلى هذه الاشتراك');
                }
            }

            if ($subscription->status !== SubscriptionStatus::PENDING) {
                return $this->failureResponse('الاشتراك غير صالح للمعالجة');
            }

            if ($subscription->payment_type !== null) {
                return $this->failureResponse('تم بدء معالجة الدفع مسبقاً');
            }

            $paymentType      = PaymentType::from($request->payment_type);
            $subscriptionType = $subscription->is_gift ? SubscriptionType::GIFT : SubscriptionType::MYSELF;

            $this->subscriptionService->validatePaymentType($request->payment_type, $subscriptionType);

            $result = DB::transaction(function () use ($subscription, $paymentType) {
                $subscription->update([
                    'payment_type' => $paymentType,
                ]);

                if ($paymentType === PaymentType::BANK_TRANSFER) {
                    $subscription->update([
                        'status' => SubscriptionStatus::PROCESSING,
                    ]);

                    return [
                        'type'            => 'bank',
                        'subscription_id' => $subscription->id,
                    ];
                }

                if ($subscription->is_gift) {
                    $subscription->load('gifter.country');
                } else {
                    $subscription->load('user.country');
                }

                $paymentData = $this->foloosiPaymentService->createPaymentForSubscription($subscription);

                $subscription->update([
                    'invoice_id'  => $paymentData['invoice_id'],
                    'invoice_url' => $paymentData['invoice_url'],
                ]);

                return [
                    'type'            => 'online',
                    'subscription_id' => $subscription->id,
                    'invoice_url'     => $paymentData['invoice_url'],
                    'invoice_id'      => $paymentData['invoice_id'],
                ];
            });

            if ($result['type'] === 'bank') {
                return $this->successWithDataResponse([
                    'message'         => 'نعمل على معالجة اشتراكك الآن',
                    'subscription_id' => $result['subscription_id'],
                ]);
            }

            return $this->successWithDataResponse([
                'invoice_url'     => $result['invoice_url'],
                'invoice_id'      => $result['invoice_id'],
                'subscription_id' => $result['subscription_id'],
            ]);

        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }
}
