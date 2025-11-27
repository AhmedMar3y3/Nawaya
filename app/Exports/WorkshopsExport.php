<?php

namespace App\Exports;

use App\Models\Workshop;
use App\Filters\WorkshopFilter;
use App\Enums\Subscription\SubscriptionStatus;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WorkshopsExport implements FromCollection, WithHeadings, WithMapping
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
        $query = $this->onlyTrashed ? Workshop::onlyTrashed() : Workshop::query();

        // Create a request object with filters
        $request = new Request($this->filters);
        
        // Apply filters using WorkshopFilter
        $filter = new WorkshopFilter($request);
        $query = $filter->apply($query);

        // Load subscriptions count
        $query->withCount([
            'subscriptions as subscribers_count' => function ($q) {
                $q->where('status', SubscriptionStatus::ACTIVE->value);
            }
        ]);

        $query->with('country');
        $query->latest();

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'العنوان',
            'المدرب',
            'تاريخ البداية',
            'النوع',
            'عدد المشتركين',
            'الحالة',
            'تاريخ الإنشاء'
        ];
    }

    public function map($workshop): array
    {
        return [
            $workshop->title,
            $workshop->teacher,
            $workshop->start_date ? $workshop->start_date->format('Y-m-d') : '-',
            $workshop->type->getLocalizedName(),
            $workshop->subscribers_count ?? 0,
            $workshop->is_active ? 'نشط' : 'غير نشط',
            $workshop->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

