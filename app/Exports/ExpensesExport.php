<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping
{
    protected string $tab;
    protected string $category;

    public function __construct(string $tab = 'active', string $category = 'all')
    {
        $this->tab = $tab;
        $this->category = $category;
    }

    public function collection()
    {
        $query = $this->tab === 'trash' ? Expense::onlyTrashed() : Expense::query();
        
        if ($this->category !== 'all') {
            if ($this->category === 'general') {
                $query->whereNull('workshop_id');
            } else {
                $query->where('workshop_id', $this->category);
            }
        }
        
        return $query->with('workshop')->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'العنوان',
            'الورشة / عام',
            'المورد',
            'المبلغ',
            'رقم الفاتورة',
            'الفاتورة تشمل الضريبة',
            'التاريخ',
            'ملاحظات',
        ];
    }

    public function map($expense): array
    {
        return [
            $expense->title,
            $expense->workshop ? $expense->workshop->title : 'عام',
            $expense->vendor,
            number_format($expense->amount, 2),
            $expense->invoice_number ?? '-',
            $expense->is_including_tax ? 'نعم' : 'لا',
            $expense->created_at->format('Y-m-d'),
            $expense->notes ?? '-',
        ];
    }
}

