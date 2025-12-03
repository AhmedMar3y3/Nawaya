<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RefundableTaxExport implements FromArray, WithHeadings, WithTitle
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $workshopName = 'الإجمالي (يشمل الورش والمصروفات العامة)';
        if ($this->data['workshop_id'] && $this->data['workshop_id'] !== 'all') {
            $workshop = \App\Models\Workshop::find($this->data['workshop_id']);
            $workshopName = $workshop ? $workshop->title : 'غير محدد';
        }

        $result = [
            ['الورشة / المصروف', $workshopName],
            ['من تاريخ', $this->data['date_from'] ? \App\Helpers\FormatArabicDates::formatArabicDate(\Carbon\Carbon::parse($this->data['date_from'])) : 'الكل'],
            ['إلى تاريخ', $this->data['date_to'] ? \App\Helpers\FormatArabicDates::formatArabicDate(\Carbon\Carbon::parse($this->data['date_to'])) : 'الكل'],
            ['', ''],
            ['عدد المصروفات المشمولة بالضريبة', $this->data['expenses_count']],
            ['إجمالي مبلغ المصروفات', number_format($this->data['total_amount'], 2)],
            ['الضريبة المستردة (5%)', number_format($this->data['refundable_tax'], 2)],
        ];

        return $result;
    }

    public function headings(): array
    {
        return [
            'البند',
            'المبلغ (درهم)',
        ];
    }

    public function title(): string
    {
        return 'تقرير الضريبة المستردة';
    }

}

