<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الاشتراكات</title>
    <style>
        * {
            direction: rtl;
            text-align: right;
        }
        
        body {
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 20px;
            font-size: 10px;
            font-family: 'DejaVu Sans', 'Arial Unicode MS', 'Tahoma', sans-serif;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 8px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: right;
        }
        
        th {
            background-color: #1E293B;
            color: white;
            font-weight: 600;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tr:nth-child(odd) {
            background-color: #ffffff;
        }
        
        h1 {
            color: #1E293B;
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>{{ $tab === 'deleted' ? 'تقرير الاشتراكات المحذوفة' : 'تقرير الاشتراكات' }}</h1>
    
    <table>
        <thead>
            <tr>
                <th>اسم المستخدم</th>
                <th>البريد الإلكتروني</th>
                <th>رقم الهاتف</th>
                <th>عنوان الورشة</th>
                <th>المبلغ المدفوع</th>
                <th>حالة الاشتراك</th>
                <th>عنوان الباقة</th>
                <th>نوع الدفع</th>
                <th>هدية</th>
                <th>تاريخ الإنشاء</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subscriptions as $subscription)
            <tr>
                <td>{{ $subscription['user_name'] ?? '-' }}</td>
                <td>{{ $subscription['email'] ?? '-' }}</td>
                <td>{{ $subscription['phone'] ?? '-' }}</td>
                <td>{{ $subscription['workshop_title'] ?? '-' }}</td>
                <td>{{ number_format($subscription['paid_amount'] ?? 0, 2) }}</td>
                <td>{{ $subscription['status'] ?? '-' }}</td>
                <td>{{ $subscription['package_title'] ?? '-' }}</td>
                <td>{{ $subscription['payment_type'] ?? '-' }}</td>
                <td>{{ $subscription['is_gift'] ?? 'لا' }}</td>
                <td>{{ $subscription['created_at'] ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center;">لا توجد بيانات</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if(count($subscriptions) > 0)
    @php
        $totalPaidAmount = collect($subscriptions)->sum('paid_amount');
        $totalPrice = collect($subscriptions)->sum('price');
        $difference = $totalPrice - $totalPaidAmount;
    @endphp
    <div style="margin-top: 30px; padding: 15px; background-color: #f5f5f5; border: 1px solid #ddd; border-radius: 5px;">
        <h2 style="color: #1E293B; font-size: 14px; margin-bottom: 15px; text-align: center;">الإحصائيات</h2>
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold; background-color: #fff;">إجمالي المبلغ المدفوع:</td>
                <td style="padding: 8px; border: 1px solid #ddd; background-color: #fff; text-align: left;">{{ number_format($totalPaidAmount, 2) }} د.إ</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold; background-color: #fff;">إجمالي السعر:</td>
                <td style="padding: 8px; border: 1px solid #ddd; background-color: #fff; text-align: left;">{{ number_format($totalPrice, 2) }} د.إ</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold; background-color: #fff;">الفرق:</td>
                <td style="padding: 8px; border: 1px solid #ddd; background-color: #fff; text-align: left; color: {{ $difference > 0 ? '#ef4444' : ($difference < 0 ? '#10b981' : '#000') }};">
                    {{ number_format(abs($difference), 2) }} د.إ
                    @if($difference > 0)
                        (مستحق)
                    @elseif($difference < 0)
                        (فائض)
                    @else
                        (متوازن)
                    @endif
                </td>
            </tr>
        </table>
    </div>
    @endif
</body>
</html>

