<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\API\Subscription\CreateCharityRequest;
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
use App\Http\Requests\API\Subscription\ProcessCharityPaymentRequest;
use App\Http\Resources\Subscription\CharitySummaryResource;
use App\Models\Charity;

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

            $result = $this->subscriptionService->createSubscription(
                $user,
                $request->package_id,
                $subscriptionType,
                $request->recipient_name ?? null,
                $request->recipient_phone ?? null,
                $request->country_id ?? null,
                $request->message ?? null
            );

            $subscriptions = is_array($result) ? $result : [$result];
            
            foreach ($subscriptions as $subscription) {
                $subscription->load(['workshop.packages']);
            }

            $paymentSettings = $this->subscriptionService->getPaymentSettings();
            if ($subscriptionType === SubscriptionType::GIFT) {
                $paymentSettings['bank_transfer'] = false;
            }

            $bankAccountSettings = null;
            if ($paymentSettings['bank_transfer'] && $subscriptionType === SubscriptionType::MYSELF) {
                $bankAccountSettings = $this->subscriptionService->getBankAccountSettings();
            }

            $subscriptionsData = [];
            foreach ($subscriptions as $subscription) {
                $package = $subscription->workshop->packages->where('id', $request->package_id)->first();
                
                $subscriptionsData[] = [
                    'subscription_id'      => $subscription->id,
                    'subscription_details' => [
                        'workshop_id'    => $subscription->workshop_id,
                        'workshop_title' => $subscription->workshop->title,
                        'package_id'     => $request->package_id,
                        'package_title'  => $package->title ?? '',
                        'price'          => $subscription->price,
                    ],
                ];
            }

            $response = [
                'subscriptions'      => $subscriptionsData,
                'payment_options'    => $paymentSettings,
                'bank_account'       => $bankAccountSettings,
            ];

            if (count($subscriptions) === 1) {
                $response['subscription_id'] = $subscriptions[0]->id;
                $response['subscription_details'] = $subscriptionsData[0]['subscription_details'];
            }

            return $this->successWithDataResponse(SubscriptionSummaryResource::make($response));
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }

    public function processPayment(ProcessPaymentRequest $request)
    {
        try {
            $user = auth()->user();
            
            $subscriptionIds = $request->subscription_ids ?? [$request->subscription_id];
            $subscriptions = Subscription::whereIn('id', $subscriptionIds)->get();

            if ($subscriptions->count() !== count($subscriptionIds)) {
                return $this->failureResponse('بعض الاشتراكات غير موجودة');
            }

            foreach ($subscriptions as $subscription) {
                if ($subscription->is_gift) {
                    if ($subscription->gift_user_id !== $user->id) {
                        return $this->failureResponse('غير مصرح لك بالوصول إلى بعض الاشتراكات');
                    }
                } else {
                    if ($subscription->user_id !== $user->id) {
                        return $this->failureResponse('غير مصرح لك بالوصول إلى بعض الاشتراكات');
                    }
                }

                if ($subscription->status !== SubscriptionStatus::PENDING) {
                    return $this->failureResponse('بعض الاشتراكات غير صالحة للمعالجة');
                }

                if ($subscription->payment_type !== null) {
                    return $this->failureResponse('تم بدء معالجة الدفع لبعض الاشتراكات مسبقاً');
                }
            }

            $paymentType = PaymentType::from($request->payment_type);
            
            $subscriptionType = $subscriptions->first()->is_gift ? SubscriptionType::GIFT : SubscriptionType::MYSELF;
            $this->subscriptionService->validatePaymentType($request->payment_type, $subscriptionType);

            $result = DB::transaction(function () use ($subscriptions, $paymentType) {
                foreach ($subscriptions as $subscription) {
                    $subscription->update([
                        'payment_type' => $paymentType,
                    ]);
                }

                if ($paymentType === PaymentType::BANK_TRANSFER) {
                    foreach ($subscriptions as $subscription) {
                        $subscription->update([
                            'status' => SubscriptionStatus::PROCESSING,
                        ]);
                    }

                    return [
                        'type'            => 'bank',
                        'subscription_ids' => $subscriptions->pluck('id')->toArray(),
                    ];
                }

                foreach ($subscriptions as $subscription) {
                    if ($subscription->is_gift) {
                        $subscription->load('gifter.country');
                    } else {
                        $subscription->load('user.country');
                    }
                }

                if ($subscriptions->count() > 1) {
                    $paymentData = $this->foloosiPaymentService->createPaymentForMultipleSubscriptions($subscriptions);
                    
                    foreach ($subscriptions as $subscription) {
                        $subscription->update([
                            'invoice_id'  => $paymentData['invoice_id'],
                            'invoice_url' => $paymentData['invoice_url'],
                        ]);
                    }

                    return [
                        'type'            => 'online',
                        'subscription_ids' => $subscriptions->pluck('id')->toArray(),
                        'invoice_url'     => $paymentData['invoice_url'],
                        'invoice_id'      => $paymentData['invoice_id'],
                    ];
                } else {
                    $subscription = $subscriptions->first();
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
                }
            });

            if ($result['type'] === 'bank') {
                if (isset($result['subscription_id'])) {
                    return $this->successWithDataResponse([
                        'message'         => 'نعمل على معالجة اشتراكك الآن',
                        'subscription_id' => $result['subscription_id'],
                    ]);
                }

                return $this->successWithDataResponse([
                    'message'          => 'نعمل على معالجة اشتراكاتك الآن',
                    'subscription_ids' => $result['subscription_ids'],
                ]);
            }

            if (isset($result['subscription_id'])) {
                return $this->successWithDataResponse([
                    'invoice_url'     => $result['invoice_url'],
                    'invoice_id'      => $result['invoice_id'],
                    'subscription_id' => $result['subscription_id'],
                ]);
            }

            return $this->successWithDataResponse([
                'invoice_url'     => $result['invoice_url'],
                'invoice_id'      => $result['invoice_id'],
                'subscription_ids' => $result['subscription_ids'],
            ]);

        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }

    public function buyCharitySubscriptions(CreateCharityRequest $request)
    {
        try {
            $user = auth()->user();
            $data = $request->validated();
            
            $charity = $this->subscriptionService->createCharitySubscription(
                $user,
                $data['package_id'],
                $data['number_of_seats']
            );

            $charity->load(['workshop.packages']);

            $paymentSettings = $this->subscriptionService->getPaymentSettings();
            $bankAccountSettings = null;
            
            if ($paymentSettings['bank_transfer']) {
                $bankAccountSettings = $this->subscriptionService->getBankAccountSettings();
            }

            $package = $charity->workshop->packages->where('id', $data['package_id'])->first();

            $response = [
                'charity_id' => $charity->id,
                'charity_details' => [
                    'workshop_id'    => $charity->workshop_id,
                    'workshop_title' => $charity->workshop->title,
                    'package_id'     => $data['package_id'],
                    'package_title'  => $package->title ?? '',
                    'number_of_seats' => $charity->number_of_seats,
                    'price'          => $charity->price,
                ],
                'payment_options' => $paymentSettings,
                'bank_account'    => $bankAccountSettings,
            ];

            return $this->successWithDataResponse(CharitySummaryResource::make($response));
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }

    public function processCharityPayment(ProcessCharityPaymentRequest $request)
    {
        try {
            $user = auth()->user();
            $charity = Charity::findOrFail($request->charity_id);

            if ($charity->user_id !== $user->id) {
                return $this->failureResponse('غير مصرح لك بالوصول إلى هذا الاشتراك الخيري');
            }

            $paymentType = PaymentType::from($request->payment_type);
            $this->subscriptionService->validateCharityPaymentType($request->payment_type);

            $result = $this->subscriptionService->processCharityPayment($charity, $paymentType);

            if ($result['type'] === 'bank') {
                return $this->successWithDataResponse([
                    'message'    => 'نعمل على معالجة اشتراكك الخيري الآن',
                    'charity_id' => $charity->id,
                ]);
            }

            $charity->refresh();
            $paymentData = $this->foloosiPaymentService->createPaymentForCharity($charity);

            return $this->successWithDataResponse([
                'invoice_url' => $paymentData['invoice_url'],
                'invoice_id'  => $paymentData['invoice_id'],
                'charity_id'  => $charity->id,
            ]);
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }
}
