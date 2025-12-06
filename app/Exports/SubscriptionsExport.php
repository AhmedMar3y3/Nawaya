<?php

namespace App\Exports;

use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Filters\SubscriptionFilter;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class SubscriptionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected array $filters;
    protected bool $onlyTrashed;

    public function __construct(array $filters = [], bool $onlyTrashed = false)
    {
        $this->filters     = $filters;
        $this->onlyTrashed = $onlyTrashed;
    }

    public function collection()
    {
        $query   = $this->onlyTrashed ? Subscription::onlyTrashed() : Subscription::query();
        $request = new Request($this->filters);
        $filter  = new SubscriptionFilter($request);
        $query   = $filter->apply($query);
        $query->where('status', \App\Enums\Subscription\SubscriptionStatus::PAID); 
        $query->with(['user', 'workshop', 'workshop.packages', 'country']);

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'اسم المستخدم',
            'البريد الإلكتروني',
            'رقم الهاتف',
            'عنوان الورشة',
            'المبلغ المدفوع',
            'حالة الاشتراك',
            'عنوان الباقة',
            'نوع الدفع',
            'هل هي هدية',
            'تاريخ الإنشاء',
        ];
    }

    public function map($subscription): array
    {
        $packageTitle = '-';
        if ($subscription->workshop && $subscription->workshop->packages) {
            $matchingPackage = $subscription->workshop->packages->first(function ($package) use ($subscription) {
                return abs($package->price - $subscription->price) < 0.01 ||
                    ($package->is_offer && abs($package->offer_price - $subscription->price) < 0.01);
            });
            if ($matchingPackage) {
                $packageTitle = $matchingPackage->title;
            } else {
                $packageTitle = $subscription->workshop->title;
            }
        }

        return [
            $subscription->user ? $subscription->user->full_name : ($subscription->full_name ?? '-'),
            $subscription->user ? $subscription->user->email : '-',
            $subscription->user ? $subscription->user->phone : ($subscription->phone ?? '-'),
            $subscription->workshop ? $subscription->workshop->title : '-',
            number_format($subscription->paid_amount ?? 0, 2),
            __('enums.subscription_statuses.' . $subscription->status->value, [], 'ar'),
            $packageTitle,
            $subscription->payment_type ? __('enums.payment_types.' . $subscription->payment_type->value, [], 'ar') : '-',
            $subscription->is_gift ? 'نعم' : 'لا',
            $subscription->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
