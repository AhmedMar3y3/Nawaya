<?php

namespace App\Exports;

use App\Models\User;
use App\Filters\UserFilter;
use App\Enums\Subscription\SubscriptionStatus;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    protected array $filters;
    protected bool $onlyTrashed;

    public function __construct(array $filters = [], bool $onlyTrashed = false)
    {
        $this->filters = $filters;
        $this->onlyTrashed = $onlyTrashed;
    }

    public function collection()
    {
        $query = $this->onlyTrashed ? User::onlyTrashed() : User::query();

        // Create a request object with filters
        $request = new Request($this->filters);
        
        // Apply filters using UserFilter
        $filter = new UserFilter($request);
        $query = $filter->apply($query);

        // Load subscriptions count
        $query->withCount([
            'subscriptions as active_subscriptions_count' => function ($q) {
                $q->where('status', SubscriptionStatus::ACTIVE->value);
            }
        ]);

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'الاسم',
            'البريد الإلكتروني',
            'رقم الهاتف',
            'عدد الاشتراكات النشطة',
            'تاريخ الإنشاء'
        ];
    }

    public function map($user): array
    {
        return [
            $user->full_name,
            $user->email,
            $user->phone,
            $user->active_subscriptions_count ?? 0,
            $user->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

