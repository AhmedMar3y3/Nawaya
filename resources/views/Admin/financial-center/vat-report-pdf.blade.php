<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير ضريبة القيمة المضافة من الإيرادات</title>
    <style>
        * {
            direction: rtl;
            text-align: right;
        }
        
        body {
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 0;
            font-size: 12px;
            background: #fff;
            font-family: 'DejaVu Sans', 'Arial Unicode MS', 'Tahoma', sans-serif;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1E293B;
        }
        
        .header h1 {
            color: #1E293B;
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 10px 0;
        }
        
        .header .subtitle {
            color: #64748b;
            font-size: 14px;
            margin: 0;
        }
        
        .filter-info {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .filter-info p {
            margin: 5px 0;
            color: #64748b;
            font-size: 12px;
        }
        
        .total-vat-section {
            text-align: center;
            margin: 40px 0;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px solid #1E293B;
        }
        
        .total-vat-section h2 {
            color: #64748b;
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 15px 0;
        }
        
        .total-vat-section .amount {
            color: #1E293B;
            font-size: 36px;
            font-weight: 700;
            margin: 10px 0;
        }
        
        .breakdown-section {
            margin: 40px 0;
        }
        
        .breakdown-section h3 {
            color: #1E293B;
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .breakdown-table th,
        .breakdown-table td {
            padding: 15px;
            text-align: right;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .breakdown-table th {
            background-color: #1E293B;
            color: white;
            font-weight: 600;
        }
        
        .breakdown-table tr:last-child {
            background-color: #f1f5f9;
            font-weight: 700;
        }
        
        .breakdown-table tr:last-child td {
            color: #1E293B;
            font-size: 14px;
        }
        
        .footer {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير ضريبة القيمة المضافة من الإيرادات</h1>
        <p class="subtitle">تاريخ التقرير: {{ \App\Helpers\FormatArabicDates::formatArabicDate(\Carbon\Carbon::now()) }}</p>
    </div>

    @if($workshop_name || $date_from || $date_to)
    <div class="filter-info">
        @if($workshop_name)
        <p><strong>الورشة:</strong> {{ $workshop_name }}</p>
        @endif
        @if($date_from)
        <p><strong>من تاريخ:</strong> {{ \App\Helpers\FormatArabicDates::formatArabicDate(\Carbon\Carbon::parse($date_from)) }}</p>
        @endif
        @if($date_to)
        <p><strong>إلى تاريخ:</strong> {{ \App\Helpers\FormatArabicDates::formatArabicDate(\Carbon\Carbon::parse($date_to)) }}</p>
        @endif
    </div>
    @endif

    <div class="total-vat-section">
        <h2>إجمالي الضريبة المحصلة للفترة المحددة</h2>
        <div class="amount">{{ number_format($total_vat, 2) }} درهم</div>
    </div>

    <div class="breakdown-section">
        <h3>تفصيل الضريبة</h3>
        <table class="breakdown-table">
            <thead>
                <tr>
                    <th style="width: 70%;">البند</th>
                    <th style="width: 30%;">المبلغ (درهم)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ضريبة القيمة المضافة من الطلبات</td>
                    <td>{{ number_format($orders_vat, 2) }}</td>
                </tr>
                <tr>
                    <td>ضريبة القيمة المضافة من الاشتراكات</td>
                    <td>{{ number_format($subscriptions_vat, 2) }}</td>
                </tr>
                <tr>
                    <td>إجمالي ضريبة القيمة المضافة</td>
                    <td>{{ number_format($total_vat, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>تم إنشاء هذا التقرير تلقائياً من نظام نوايا</p>
    </div>
</body>
</html>

