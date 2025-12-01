<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الضريبة السنوية (9%)</title>
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
        
        .total-tax-section {
            text-align: center;
            margin: 40px 0;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px solid #1E293B;
        }
        
        .total-tax-section h2 {
            color: #64748b;
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 15px 0;
        }
        
        .total-tax-section .amount {
            color: #1E293B;
            font-size: 36px;
            font-weight: 700;
            margin: 10px 0;
        }
        
        .total-tax-section .based-on {
            color: #64748b;
            font-size: 14px;
            margin-top: 10px;
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
        <h1>تقرير الضريبة السنوية (9%)</h1>
        <p class="subtitle">تاريخ التقرير: {{ \App\Helpers\FormatArabicDates::formatArabicDate(\Carbon\Carbon::now()) }}</p>
    </div>

    <div class="total-tax-section">
        <h2>إجمالي الضريبة السنوية المستحقة</h2>
        <div class="amount">{{ number_format($annual_tax, 2) }} درهم</div>
        <p class="based-on">بناءً على إجمالي صافي ربح قدره {{ number_format($total_net_profit, 2) }} درهم</p>
    </div>

    <div class="breakdown-section">
        <h3>تفصيل صافي الربح</h3>
        <table class="breakdown-table">
            <thead>
                <tr>
                    <th style="width: 70%;">البند</th>
                    <th style="width: 30%;">المبلغ (درهم)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>صافي أرباح الورش</td>
                    <td>{{ number_format($workshop_net_profits, 2) }}</td>
                </tr>
                <tr>
                    <td>صافي أرباح البوتيك</td>
                    <td>{{ number_format($boutique_net_profits, 2) }}</td>
                </tr>
                <tr>
                    <td>إجمالي صافي الربح</td>
                    <td>{{ number_format($total_net_profit, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>تم إنشاء هذا التقرير تلقائياً من نظام نوايا</p>
    </div>
</body>
</html>

