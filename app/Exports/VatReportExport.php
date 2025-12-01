<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class VatReportExport implements FromArray, WithHeadings, WithTitle
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $workshopName = 'الإجمالي';
        if ($this->data['workshop_id']) {
            $workshop = \App\Models\Workshop::find($this->data['workshop_id']);
            $workshopName = $workshop ? $workshop->title : 'غير محدد';
        }

        $result = [
            ['الورشة', $workshopName],
            ['من تاريخ', $this->data['date_from'] ? \App\Helpers\FormatArabicDates::formatArabicDate(\Carbon\Carbon::parse($this->data['date_from'])) : 'الكل'],
            ['إلى تاريخ', $this->data['date_to'] ? \App\Helpers\FormatArabicDates::formatArabicDate(\Carbon\Carbon::parse($this->data['date_to'])) : 'الكل'],
            ['', ''],
            ['ضريبة القيمة المضافة من الطلبات', number_format($this->data['orders_vat'], 2)],
            ['ضريبة القيمة المضافة من الاشتراكات', number_format($this->data['subscriptions_vat'], 2)],
            ['إجمالي ضريبة القيمة المضافة', number_format($this->data['total_vat'], 2)],
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
        return 'تقرير ضريبة القيمة المضافة';
    }
}

