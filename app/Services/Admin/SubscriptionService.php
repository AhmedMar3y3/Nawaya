<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Setting;
use App\Models\Workshop;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\WorkshopPackage;
use App\Models\WorkshopTransfer;
use Illuminate\Support\Collection;
use App\Models\UserBalanceHistory;
use App\Filters\SubscriptionFilter;
use Illuminate\Pagination\LengthAwarePaginator;

class SubscriptionService
{
    public function getSubscriptionsWithFilters(Request $request, int $perPage = 15, bool $onlyTrashed = false): LengthAwarePaginator
    {
        $query  = $onlyTrashed ? Subscription::onlyTrashed() : Subscription::query();
        $filter = new SubscriptionFilter($request);
        $query  = $filter->apply($query);
        $query->with(['user', 'workshop', 'workshop.packages', 'country', 'gifter']);
        $query->latest();
        return $query->paginate($perPage);
    }

    public function getSubscriptionsForExport(Request $request, bool $onlyTrashed = false, int $limit = 1000): array
    {
        $query  = $onlyTrashed ? Subscription::onlyTrashed() : Subscription::query();
        $filter = new SubscriptionFilter($request);
        $query  = $filter->apply($query);

        $query->where('status', \App\Enums\Subscription\SubscriptionStatus::PAID);

        $query->with(['user', 'workshop', 'workshop.packages', 'country']);
        $query->latest();
        return $query->limit($limit)->get()->map(function ($subscription) {

            return [
                'id'             => $subscription->id,
                'user_name'      => $subscription->user ? $subscription->user->full_name : ($subscription->full_name ?? '-'),
                'email'          => $subscription->user ? $subscription->user->email : '-',
                'phone'          => $subscription->user ? $subscription->user->phone : ($subscription->phone ?? '-'),
                'workshop_title' => $subscription->workshop ? $subscription->workshop->title : '-',
                'paid_amount'    => $subscription->paid_amount ?? 0,
                'price'          => $subscription->price ?? 0,
                'status'         => __('enums.subscription_statuses.' . $subscription->status->value, [], 'ar'),
                'package_title'  => $subscription->package ? $subscription->package->title : ($subscription->workshop ? $subscription->workshop->title : '-'),
                'payment_type'   => $subscription->payment_type ? __('enums.payment_types.' . $subscription->payment_type->value, [], 'ar') : '-',
                'is_gift'        => $subscription->is_gift ? 'نعم' : 'لا',
                'created_at'     => $subscription->created_at ? $subscription->created_at->format('Y-m-d') : '-',
            ];
        })->toArray();
    }

    public function getSubscriptionById(int $id): Subscription
    {
        return Subscription::with(['user', 'workshop', 'workshop.packages', 'country', 'gifter'])->findOrFail($id);
    }

    public function getUserDetailsWithSubscriptions(int $subscriptionId): array
    {
        $subscription = $this->getSubscriptionById($subscriptionId);

        if (! $subscription->user_id) {
            throw new \Exception('هذا الاشتراك لا يحتوي على مستخدم');
        }

        $user = User::with([
            'country',
            'subscriptions' => function ($query) {
                $query->where('status', \App\Enums\Subscription\SubscriptionStatus::PAID)
                    ->with(['workshop', 'package', 'country'])
                    ->latest();
            },
        ])->findOrFail($subscription->user_id);

        $paidSubscriptions = $user->subscriptions;

        $totalPaid  = $paidSubscriptions->sum('paid_amount');
        $totalPrice = $paidSubscriptions->sum('price');
        $totalDebt  = $paidSubscriptions->sum(function ($sub) {
            return max(0, $sub->price - $sub->paid_amount);
        });

        return [
            'user'          => [
                'id'         => $user->id,
                'full_name'  => $user->full_name,
                'email'      => $user->email,
                'phone'      => $user->phone,
                'balance'    => $user->balance ?? 0,
                'is_active'  => $user->is_active,
                'country'    => $user->country ? $user->country->name : null,
                'created_at' => $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : null,
            ],
            'subscriptions' => $paidSubscriptions->map(function ($sub) {
                return [
                    'id'             => $sub->id,
                    'workshop_title' => $sub->workshop ? $sub->workshop->title : '-',
                    'package_title'  => $sub->package ? $sub->package->title : ($sub->workshop ? $sub->workshop->title : '-'),
                    'price'          => $sub->price ?? 0,
                    'paid_amount'    => $sub->paid_amount ?? 0,
                    'debt'           => max(0, ($sub->price ?? 0) - ($sub->paid_amount ?? 0)),
                    'payment_type'   => $sub->payment_type ? __('enums.payment_types.' . $sub->payment_type->value, [], 'ar') : '-',
                    'invoice_id'     => $sub->invoice_id,
                    'is_gift'        => $sub->is_gift,
                    'is_refunded'    => $sub->is_refunded,
                    'refund_type'    => $sub->refund_type ? __('enums.refund_types.' . $sub->refund_type->value, [], 'ar') : null,
                    'created_at'     => $sub->created_at ? $sub->created_at->format('Y-m-d H:i:s') : '-',
                    'updated_at'     => $sub->updated_at ? $sub->updated_at->format('Y-m-d H:i:s') : '-',
                ];
            })->toArray(),
            'statistics'    => [
                'total_subscriptions' => $paidSubscriptions->count(),
                'total_paid'          => $totalPaid,
                'total_price'         => $totalPrice,
                'total_debt'          => $totalDebt,
                'balance'             => $user->balance ?? 0,
            ],
        ];
    }

    public function createSubscription(array $data): Subscription
    {
        $price            = $this->calculateSubscriptionPrice($data);
        $invoiceId        = $this->generateInvoiceId();
        $balanceUsed      = isset($data['balance_used']) && $data['balance_used'] > 0 ? (float) $data['balance_used'] : 0;
        $subscriptionData = [
            'user_id'         => $data['user_id'] ?? null,
            'workshop_id'     => $data['workshop_id'],
            'package_id'      => $data['package_id'] ?? null,
            'price'           => $price,
            'paid_amount'     => $data['paid_amount'],
            'status'          => \App\Enums\Subscription\SubscriptionStatus::PAID->value,
            'payment_type'    => $data['payment_type'],
            'invoice_id'      => $invoiceId,
            'transferer_name' => $data['transferer_name'] ?? null,
            'notes'           => $data['notes'] ?? null,
        ];

        if (empty($data['user_id'])) {
            $subscriptionData['full_name']  = $data['full_name'];
            $subscriptionData['phone']      = $data['phone'];
            $subscriptionData['email']      = $data['email'] ?? null;
            $subscriptionData['country_id'] = $data['country_id'] ?? null;
        }

        $subscription = Subscription::create($subscriptionData);

        if ($balanceUsed > 0 && ! empty($data['user_id'])) {
            $user     = User::findOrFail($data['user_id']);
            $workshop = Workshop::findOrFail($data['workshop_id']);

            $user->balance = max(0, $user->balance - $balanceUsed);
            $user->save();

            UserBalanceHistory::create([
                'user_id'     => $user->id,
                'workshop_id' => $workshop->id,
                'description' => 'تم استخدام المبلغ في اشتراك ورشة: ' . $workshop->title,
                'type'        => 'subtract',
                'amount'      => $balanceUsed,
            ]);
        }

        return $subscription;
    }

    protected function calculateSubscriptionPrice(array $data): float
    {
        if (empty($data['package_id'])) {
            return (float) $data['paid_amount'];
        }

        $package = WorkshopPackage::findOrFail($data['package_id']);

        if ($package->is_offer && $package->offer_price && $package->offer_expiry_date) {
            $expiryDate = \Carbon\Carbon::parse($package->offer_expiry_date);
            if ($expiryDate->isFuture()) {
                return (float) $package->offer_price;
            }
        }

        return (float) $package->price;
    }

    protected function generateInvoiceId(): string
    {
        return 'INV-' . strtoupper(uniqid());
    }

    public function updateSubscription(int $id, array $data): Subscription
    {
        $subscription = Subscription::findOrFail($id);

        $newPackageId = $data['package_id'] ?? null;
        $price        = $subscription->price;

        if (isset($data['package_id']) && $subscription->package_id != $newPackageId) {
            $price = $this->calculateSubscriptionPrice([
                'package_id'  => $newPackageId,
                'paid_amount' => $data['paid_amount'],
            ]);
        }

        $subscription->update([
            'package_id'      => $newPackageId,
            'price'           => $price,
            'paid_amount'     => $data['paid_amount'],
            'payment_type'    => $data['payment_type'],
            'transferer_name' => $data['transferer_name'] ?? null,
            'notes'           => $data['notes'] ?? null,
        ]);

        return $subscription->fresh();
    }

    public function searchUsers(string $search): Collection
    {
        if (strlen($search) < 2) {
            return collect([]);
        }

        return User::where(function ($query) use ($search) {
            $query->where('full_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        })
            ->where('is_active', true)
            ->limit(10)
            ->get(['id', 'full_name', 'email', 'phone', 'balance']);
    }

    public function getPackagesByWorkshop(int $workshopId): array
    {
        if ($workshopId <= 0) {
            return [];
        }

        $packages = WorkshopPackage::where('workshop_id', $workshopId)
            ->whereNull('deleted_at')
            ->orderBy('price', 'asc')
            ->get(['id', 'title', 'price', 'is_offer', 'offer_price', 'offer_expiry_date']);

        return $packages->map(function ($package) {
            return [
                'id'                => $package->id,
                'title'             => $package->title,
                'price'             => (float) $package->price,
                'is_offer'          => (bool) $package->is_offer,
                'offer_price'       => $package->offer_price ? (float) $package->offer_price : null,
                'offer_expiry_date' => $package->offer_expiry_date ? $package->offer_expiry_date->format('Y-m-d') : null,
            ];
        })->values()->toArray();
    }

    public function getAllPackagesFormatted(): array
    {
        return WorkshopPackage::whereNull('deleted_at')
            ->orderBy('workshop_id')
            ->orderBy('price', 'asc')
            ->get(['id', 'workshop_id', 'title', 'price', 'is_offer', 'offer_price', 'offer_expiry_date'])
            ->map(function ($pkg) {
                return [
                    'id'                => (int) $pkg->id,
                    'workshop_id'       => (int) $pkg->workshop_id,
                    'title'             => $pkg->title,
                    'price'             => (float) $pkg->price,
                    'is_offer'          => (bool) $pkg->is_offer,
                    'offer_price'       => $pkg->offer_price ? (float) $pkg->offer_price : null,
                    'offer_expiry_date' => $pkg->offer_expiry_date ? $pkg->offer_expiry_date->format('Y-m-d') : null,
                ];
            })
            ->values()
            ->toArray();
    }

    public function getInvoiceData(int $subscriptionId): array
    {
        $subscription    = $this->getSubscriptionById($subscriptionId);
        $settings        = Setting::pluck('value', 'key');
        $packageTitle    = $this->getPackageTitle($subscription);
        $vatCalculations = $this->calculateVAT($subscription->paid_amount);

        return [
            'subscription'  => $subscription,
            'package_title' => $packageTitle,
            'subtotal'      => $vatCalculations['subtotal'],
            'vat'           => $vatCalculations['vat'],
            'total'         => $subscription->paid_amount,
            'company'       => $this->getCompanyData($settings),
        ];
    }

    protected function getPackageTitle(Subscription $subscription): string
    {
        if ($subscription->package) {
            return $subscription->package->title;
        }

        if ($subscription->workshop) {
            return $subscription->workshop->title;
        }

        return '-';
    }

    protected function calculateVAT(float $totalAmount): array
    {
        $vatRate  = 0.05;
        $subtotal = $totalAmount / (1 + $vatRate);
        $vat      = $totalAmount - $subtotal;

        return [
            'subtotal' => round($subtotal, 2),
            'vat'      => round($vat, 2),
        ];
    }

    protected function getCompanyData(Collection $settings): array
    {
        return [
            'name'       => 'مؤسسة نوايا للفعاليات',
            'address'    => $settings['address'],
            'phone'      => $settings['phone_number'] ?? '+971 4 123 4567',
            'tax_number' => $settings['tax_number'] ?? '100000000000003',
            'logo'       => $settings['logo'] ?? asset('defaults/nawaya.png'),
        ];
    }

    public function getIndexData(): array
    {
        return [
            'workshops' => Workshop::get(['id', 'title']),
            'statuses'  => \App\Enums\Subscription\SubscriptionStatus::cases(),
            'countries' => \App\Models\Country::get(['id', 'name']),
            'packages'  => $this->getAllPackagesFormatted(),
        ];
    }

    public function getProcessingSubscriptions(): Collection
    {
        return Subscription::where('status', \App\Enums\Subscription\SubscriptionStatus::PROCESSING)
            ->with(['user', 'workshop', 'package'])
            ->latest()
            ->get();
    }

    public function approveSubscription(int $subscriptionId): Subscription
    {
        $subscription = Subscription::where('status', \App\Enums\Subscription\SubscriptionStatus::PROCESSING)
            ->findOrFail($subscriptionId);

        $subscription->update([
            'status' => \App\Enums\Subscription\SubscriptionStatus::PAID,
        ]);

        return $subscription->fresh();
    }

    public function rejectSubscription(int $subscriptionId): Subscription
    {
        $subscription = Subscription::where('status', \App\Enums\Subscription\SubscriptionStatus::PROCESSING)
            ->findOrFail($subscriptionId);

        $subscription->update([
            'status' => \App\Enums\Subscription\SubscriptionStatus::FAILED,
        ]);

        return $subscription->fresh();
    }

    public function deleteSubscription(int $id): bool
    {
        $subscription = Subscription::findOrFail($id);
        return $subscription->delete();
    }

    public function restoreSubscription(int $id): bool
    {
        $subscription = Subscription::onlyTrashed()->findOrFail($id);
        return $subscription->restore();
    }

    public function permanentlyDeleteSubscription(int $id): bool
    {
        $subscription = Subscription::onlyTrashed()->findOrFail($id);
        return $subscription->forceDelete();
    }

    public function transferToInternalBalance(int $subscriptionId): Subscription
    {
        $subscription = $this->getSubscriptionById($subscriptionId);

        if (! $subscription->user_id) {
            throw new \Exception('لا يمكن تحويل الرصيد: الاشتراك لا يحتوي على مستخدم');
        }

        $user     = User::findOrFail($subscription->user_id);
        $workshop = $subscription->workshop;
        $amount   = $subscription->paid_amount;

        $user->balance = ($user->balance ?? 0) + $amount;
        $user->save();

        UserBalanceHistory::create([
            'user_id'     => $user->id,
            'workshop_id' => $workshop->id,
            'description' => 'تم استرداد مبلغ ' . number_format($amount, 2) . ' د.إ من اشتراك ورشة: ' . ($workshop?->title ?? 'غير محدد'),
            'type'        => 'add',
            'amount'      => $amount,
        ]);

        $subscription->status = \App\Enums\Subscription\SubscriptionStatus::USER_BALANCE;
        $subscription->save();

        return $subscription->fresh();
    }

    public function processRefund(int $subscriptionId, array $data): Subscription
    {
        $subscription = $this->getSubscriptionById($subscriptionId);

        $subscription->update([
            'status'       => \App\Enums\Subscription\SubscriptionStatus::REFUNDED,
            'is_refunded'  => true,
            'refund_type'  => $data['refund_type'],
            'refund_notes' => $data['refund_notes'] ?? null,
        ]);

        return $subscription->fresh();
    }

    public function transferSubscription(int $subscriptionId, array $data): Subscription
    {
        $subscription = $this->getSubscriptionById($subscriptionId);

        if (! $subscription->user_id) {
            throw new \Exception('لا يمكن تحويل الاشتراك: الاشتراك لا يحتوي على مستخدم');
        }

        $user          = User::findOrFail($subscription->user_id);
        $oldWorkshop   = $subscription->workshop;
        $newWorkshopId = $data['workshop_id'];
        $newPackageId  = $data['package_id'] ?? null;

        $newPrice   = $this->calculateNewWorkshopPrice($newWorkshopId, $newPackageId);
        $oldPrice   = $subscription->paid_amount;
        $difference = $newPrice - $oldPrice;

        $balanceUsed          = isset($data['balance_used']) && $data['balance_used'] > 0 ? (float) $data['balance_used'] : 0;
        $additionalPaidAmount = isset($data['paid_amount']) && $data['paid_amount'] > 0 ? (float) $data['paid_amount'] : 0;

        $balanceToUse = 0;
        if ($difference < 0) {
            $amountToAdd   = abs($difference);
            $user->balance = ($user->balance ?? 0) + $amountToAdd;
            $user->save();

            UserBalanceHistory::create([
                'user_id'     => $user->id,
                'workshop_id' => $newWorkshopId,
                'description' => 'تم إضافة المبلغ من تحويل اشتراك من ورشة: ' . ($oldWorkshop?->title ?? 'غير محدد') . ' إلى ورشة: ' . (Workshop::find($newWorkshopId)?->title ?? 'غير محدد'),
                'type'        => 'add',
                'amount'      => $amountToAdd,
            ]);

            $finalPaidAmount    = $newPrice;
            $transferPaidAmount = $newPrice;
        } else if ($difference > 0) {
            $remainingAmount = $difference;

            if ($balanceUsed > 0) {
                $availableBalance = $user->balance ?? 0;
                $balanceToUse     = min($balanceUsed, $availableBalance, $remainingAmount);

                if ($balanceToUse > 0) {
                    $user->balance = max(0, $availableBalance - $balanceToUse);
                    $user->save();

                    UserBalanceHistory::create([
                        'user_id'     => $user->id,
                        'workshop_id' => $newWorkshopId,
                        'description' => 'تم استخدام المبلغ في تحويل اشتراك من ورشة: ' . ($oldWorkshop?->title ?? 'غير محدد') . ' إلى ورشة: ' . (Workshop::find($newWorkshopId)?->title ?? 'غير محدد'),
                        'type'        => 'subtract',
                        'amount'      => $balanceToUse,
                    ]);

                    $remainingAmount -= $balanceToUse;
                }
            }

            if ($additionalPaidAmount > 0) {
                $remainingAmount -= $additionalPaidAmount;
            }

            $finalPaidAmount    = $oldPrice + $balanceToUse + $additionalPaidAmount;
            $transferPaidAmount = $oldPrice + $balanceToUse + $additionalPaidAmount;
        } else {
            $finalPaidAmount    = $oldPrice;
            $transferPaidAmount = $oldPrice;
        }

        \App\Models\WorkshopTransfer::create([
            'subscription_id'        => $subscription->id,
            'workshop_transfer_from' => $subscription->workshop_id,
            'workshop_transfer_to'   => $newWorkshopId,
            'old_price'              => $oldPrice,
            'new_price'              => $newPrice,
            'paid_amount'            => $transferPaidAmount,
            'notes'                  => $data['notes'] ?? null,
        ]);

        $subscription->update([
            'workshop_id' => $newWorkshopId,
            'package_id'  => $newPackageId,
            'price'       => $newPrice,
            'paid_amount' => $finalPaidAmount,
        ]);

        return $subscription->fresh();
    }

    public function getWorkshopSubscriptionsStats(int $workshopId): array
    {
        $workshop = Workshop::findOrFail($workshopId);

        $subscriptions = Subscription::where('workshop_id', $workshopId)
            ->where('status', \App\Enums\Subscription\SubscriptionStatus::PAID)
            ->with(['package'])
            ->get();

        $totalIncome = $subscriptions->sum('price');
        $totalCount  = $subscriptions->count();

        $packageStats           = [];
        $noPackageSubscriptions = [];

        foreach ($subscriptions as $subscription) {
            if ($subscription->package_id && $subscription->package) {
                $packageId = $subscription->package_id;
                if (! isset($packageStats[$packageId])) {
                    $packageStats[$packageId] = [
                        'title'  => $subscription->package->title,
                        'count'  => 0,
                        'income' => 0,
                    ];
                }
                $packageStats[$packageId]['count']++;
                $packageStats[$packageId]['income'] += $subscription->price;
            } else {
                $noPackageSubscriptions[] = $subscription;
            }
        }

        if (count($noPackageSubscriptions) > 0) {
            $packageStats[0] = [
                'title'  => 'بدون باقة (سعر أساسي / مسجلة)',
                'count'  => count($noPackageSubscriptions),
                'income' => collect($noPackageSubscriptions)->sum('price'),
            ];
        }

        return [
            'workshop_title' => $workshop->title,
            'total_amount'   => $totalIncome,
            'total_count'    => $totalCount,
            'packages'       => array_values($packageStats),
        ];
    }

    public function getWorkshopSubscriptionsStatsForExport(int $workshopId): array
    {
        $stats    = $this->getWorkshopSubscriptionsStats($workshopId);
        $workshop = Workshop::findOrFail($workshopId);

        return [
            'workshop'       => $workshop,
            'workshop_title' => $stats['workshop_title'],
            'total_amount'   => $stats['total_amount'],
            'total_count'    => $stats['total_count'],
            'packages'       => $stats['packages'],
        ];
    }

    protected function calculateNewWorkshopPrice(int $workshopId, ?int $packageId = null): float
    {
        if ($packageId) {
            $package = WorkshopPackage::where('workshop_id', $workshopId)
                ->where('id', $packageId)
                ->whereNull('deleted_at')
                ->firstOrFail();

            if ($package->is_offer && $package->offer_price && $package->offer_expiry_date) {
                $expiryDate = \Carbon\Carbon::parse($package->offer_expiry_date);
                if ($expiryDate->isFuture()) {
                    return (float) $package->offer_price;
                }
            }

            return (float) $package->price;
        }

        $minPackage = WorkshopPackage::where('workshop_id', $workshopId)
            ->whereNull('deleted_at')
            ->orderBy('price', 'asc')
            ->first();

        if ($minPackage) {
            if ($minPackage->is_offer && $minPackage->offer_price && $minPackage->offer_expiry_date) {
                $expiryDate = \Carbon\Carbon::parse($minPackage->offer_expiry_date);
                if ($expiryDate->isFuture()) {
                    return (float) $minPackage->offer_price;
                }
            }
            return (float) $minPackage->price;
        }

        return 0;
    }

    public function getTransfers(): Collection
    {
        return WorkshopTransfer::with([
            'subscription.user',
            'workshopFrom',
            'workshopTo',
        ])
            ->latest()
            ->get();
    }

    public function getRefundedSubscriptions(): Collection
    {
        return Subscription::where(function ($query) {
            $query->where('is_refunded', true)
                ->orWhere('status', \App\Enums\Subscription\SubscriptionStatus::REFUNDED);
        })
            ->with(['user', 'workshop'])
            ->latest('updated_at')
            ->get();
    }

    public function reactivateSubscription(int $subscriptionId): Subscription
    {
        $subscription = $this->getSubscriptionById($subscriptionId);

        if (! $subscription->is_refunded && $subscription->status !== \App\Enums\Subscription\SubscriptionStatus::REFUNDED) {
            throw new \Exception('هذا الاشتراك غير مسترد');
        }

        $subscription->update([
            'status'       => \App\Enums\Subscription\SubscriptionStatus::PAID,
            'is_refunded'  => false,
            'refund_type'  => null,
            'refund_notes' => null,
        ]);

        return $subscription->fresh();
    }

    public function getBalanceSubscriptions(): Collection
    {
        return Subscription::where('payment_type', \App\Enums\Payment\PaymentType::USER_BALANCE)
            ->where('status', \App\Enums\Subscription\SubscriptionStatus::PAID)
            ->with(['user', 'workshop'])
            ->latest('created_at')
            ->get();
    }

    public function getDebtSubscriptions(?int $workshopId = null): Collection
    {
        $query = Subscription::where('status', \App\Enums\Subscription\SubscriptionStatus::PAID)->whereRaw('paid_amount < price')
            ->with(['user', 'workshop']);

        if ($workshopId) {
            $query->where('workshop_id', $workshopId);
        }

        return $query->latest('created_at')->get();
    }

    public function getUsersWithBalances(): Collection
    {
        return User::where('balance', '>', 0)
            ->with([
                'balanceHistories' => function ($query) {
                    $query->withTrashed()->with('workshop')->orderBy('created_at', 'desc');
                },
            ])
            ->orderBy('balance', 'desc')
            ->get();
    }

    public function deleteBalanceHistory(int $historyId): UserBalanceHistory
    {
        $history = UserBalanceHistory::findOrFail($historyId);
        $history->delete();
        return $history;
    }

    public function restoreBalanceHistory(int $historyId): UserBalanceHistory
    {
        $history = UserBalanceHistory::onlyTrashed()->findOrFail($historyId);
        $history->restore();
        return $history;
    }

    public function permanentlyDeleteBalanceHistory(int $historyId): void
    {
        $history = UserBalanceHistory::onlyTrashed()->findOrFail($historyId);
        $history->forceDelete();
    }

    public function getGiftSubscriptions(bool $onlyTrashed = false): Collection
    {
        $query = Subscription::where('status', \App\Enums\Subscription\SubscriptionStatus::PAID)
            ->where('is_gift', true)
            ->where('is_gift_approved', false)
            ->with(['gifter', 'user', 'workshop']);

        if ($onlyTrashed) {
            $query->onlyTrashed();
        }

        return $query->latest('created_at')->get();
    }

    public function deleteGiftSubscription(int $subscriptionId): Subscription
    {
        $subscription = $this->getSubscriptionById($subscriptionId);

        if (! $subscription->is_gift) {
            throw new \Exception('هذا الاشتراك ليس هدية');
        }

        $subscription->delete();
        return $subscription;
    }

    public function restoreGiftSubscription(int $subscriptionId): Subscription
    {
        $subscription = Subscription::onlyTrashed()->findOrFail($subscriptionId);

        if (! $subscription->is_gift) {
            throw new \Exception('هذا الاشتراك ليس هدية');
        }

        $subscription->restore();
        return $subscription;
    }

    public function permanentlyDeleteGiftSubscription(int $subscriptionId): void
    {
        $subscription = Subscription::onlyTrashed()->findOrFail($subscriptionId);

        if (! $subscription->is_gift) {
            throw new \Exception('هذا الاشتراك ليس هدية');
        }

        $subscription->forceDelete();
    }

    public function approveGiftSubscription(int $subscriptionId): Subscription
    {
        $subscription = $this->getSubscriptionById($subscriptionId);

        if (! $subscription->is_gift) {
            throw new \Exception('هذا الاشتراك ليس هدية');
        }

        if ($subscription->user_id === null) {
            throw new \Exception('لا يمكن الموافقة على الهدية: المستلم ليس لديه حساب في النظام');
        }

        $subscription->update([
            'is_gift_approved' => true,
        ]);

        return $subscription->fresh(['user', 'workshop', 'workshop.packages', 'country', 'gifter']);
    }

    public function transferGiftSubscription(int $subscriptionId): Subscription
    {
        $subscription = $this->getSubscriptionById($subscriptionId);

        if (! $subscription->is_gift) {
            throw new \Exception('هذا الاشتراك ليس هدية');
        }

        if (! $subscription->gift_user_id) {
            throw new \Exception('لا يمكن تحويل الهدية: المرسل غير موجود');
        }

        $subscription->update([
            'user_id'          => $subscription->gift_user_id,
            'is_gift'          => false,
            'is_gift_approved' => false,
            'gift_user_id'     => null,
            'full_name'        => null,
            'phone'            => null,
            'country_id'       => null,
            'message'          => null,
        ]);

        return $subscription->fresh(['user', 'workshop', 'workshop.packages', 'country', 'gifter']);
    }
}
