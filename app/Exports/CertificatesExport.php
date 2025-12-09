<?php

namespace App\Exports;

use App\Models\Certificate;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CertificatesExport implements FromCollection, WithHeadings, WithMapping
{
    protected int $workshopId;

    public function __construct(int $workshopId)
    {
        $this->workshopId = $workshopId;
    }

    public function collection()
    {
        return Certificate::with(['user', 'workshop', 'subscription'])
            ->where('workshop_id', $this->workshopId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'الاسم',
            'الهاتف',
            'الإيميل',
            'نوع الورشة',
            'تاريخ الاشتراك',
            'السعر',
            'المبلغ المدفوع',
            'تاريخ الإصدار',
            'الحالة',
        ];
    }

    public function map($certificate): array
    {
        return [
            $certificate->user->full_name ?? 'N/A',
            $certificate->user->phone ?? 'N/A',
            $certificate->user->email ?? 'N/A',
            $certificate->workshop->type->getLocalizedName(),
            $certificate->subscription ? $certificate->subscription->created_at->locale('ar')->translatedFormat('j F Y') : 'N/A',
            number_format($certificate->subscription ? ($certificate->subscription->price ?? 0) : 0, 2),
            number_format($certificate->subscription ? ($certificate->subscription->paid_amount ?? 0) : 0, 2),
            $certificate->issued_at ? $certificate->issued_at->locale('ar')->translatedFormat('j F Y') : 'N/A',
            $certificate->is_active ? 'مفعل' : 'غير مفعل',
        ];
    }
}

