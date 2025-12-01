<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class AnnualTaxExport implements FromArray, WithHeadings, WithTitle
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return [
            [
                'صافي أرباح الورش',
                number_format($this->data['workshop_net_profits'], 2),
            ],
            [
                'صافي أرباح البوتيك',
                number_format($this->data['boutique_net_profits'], 2),
            ],
            [
                'إجمالي صافي الربح',
                number_format($this->data['total_net_profit'], 2),
            ],
            [
                'الضريبة السنوية (9%)',
                number_format($this->data['annual_tax'], 2),
            ],
        ];
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
        return 'تقرير الضريبة السنوية';
    }
}
