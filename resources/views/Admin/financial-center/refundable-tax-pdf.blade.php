<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الضريبة المستردة على المصروفات</title>
    <style>
        @font-face {
            font-family: 'Arial';
            src: local('Arial');
        }
        body {
            font-family: 'Arial', 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 20px;
            background: #fff;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
            font-weight: bold;
        }
        .info-section {
            margin-bottom: 30px;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            width: 40%;
        }
        .info-value {
            color: #000;
            width: 60%;
            text-align: left;
        }
        .summary-section {
            margin-top: 30px;
            padding: 20px;
            background: #f9f9f9;
            border: 2px solid #333;
            border-radius: 5px;
        }
        .summary-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            text-align: center;
        }
        .summary-amount {
            font-size: 32px;
            font-weight: bold;
            color: #8b5cf6;
            text-align: center;
            margin: 10px 0;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير الضريبة المستردة على المصروفات</h1>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">الورشة / المصروف:</span>
            <span class="info-value">{{ $workshop_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">من تاريخ:</span>
            <span class="info-value">{{ $date_from ? \App\Helpers\FormatArabicDates::formatArabicDate(\Carbon\Carbon::parse($date_from)) : 'الكل' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">إلى تاريخ:</span>
            <span class="info-value">{{ $date_to ? \App\Helpers\FormatArabicDates::formatArabicDate(\Carbon\Carbon::parse($date_to)) : 'الكل' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">عدد المصروفات المشمولة بالضريبة:</span>
            <span class="info-value">{{ $expenses_count }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">إجمالي مبلغ المصروفات:</span>
            <span class="info-value">{{ number_format($total_amount, 2) }} درهم</span>
        </div>
    </div>

    <div class="summary-section">
        <div class="summary-title">الضريبة المستردة للفترة المحددة</div>
        <div class="summary-amount">{{ number_format($refundable_tax, 2) }} درهم</div>
    </div>

    <div class="footer">
        <p>تم إنشاء التقرير في: {{ \App\Helpers\FormatArabicDates::formatArabicDate(\Carbon\Carbon::now()) }}</p>
        <p>© 2025 جميع الحقوق محفوظة لوكالة نكسس</p>
    </div>
</body>
</html>


