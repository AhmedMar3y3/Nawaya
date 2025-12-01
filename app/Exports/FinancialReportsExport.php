<?php

namespace App\Exports;

use App\Models\Workshop;
use App\Enums\Subscription\SubscriptionStatus;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialReportsExport implements FromCollection, WithHeadings, WithMapping
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Workshop::with(['payments', 'subscriptions']);

        // Apply filter if selected
        if (isset($this->filters['workshop_filter']) && $this->filters['workshop_filter'] !== 'all') {
            $query->where('id', $this->filters['workshop_filter']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'الورشة',
            'إجمالي الإيرادات',
            'صافي الربح',
            'نسبة المدربة (%)',
            'حصة المدربة',
            'حصة الشركة',
            'إجمالي المدفوع للمدربة',
            'المتبقي للمدربة',
        ];
    }

    public function map($workshop): array
    {
        // Calculate total revenue (sum of paid subscriptions)
        $totalRevenue = $workshop->subscriptions()
            ->where('status', SubscriptionStatus::PAID->value)
            ->sum('price');
        
        // Calculate net profit (total revenue - 5%)
        $netProfit = $totalRevenue * 0.95;
        
        // Get teacher percentage
        $teacherPer = $workshop->teacher_per ?? 0;
        
        // Calculate teacher's share
        $teacherShare = ($netProfit * $teacherPer) / 100;
        
        // Calculate company's share
        $companyShare = $netProfit - $teacherShare;
        
        // Calculate total paid to teacher
        $totalPaid = $workshop->payments()->sum('amount');
        
        // Calculate remaining for teacher
        $remaining = $teacherShare - $totalPaid;

        return [
            $workshop->title,
            number_format($totalRevenue, 2),
            number_format($netProfit, 2),
            $teacherPer,
            number_format($teacherShare, 2),
            number_format($companyShare, 2),
            number_format($totalPaid, 2),
            number_format($remaining, 2),
        ];
    }
}

