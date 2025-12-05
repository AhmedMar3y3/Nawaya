<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة ضريبية - {{ $subscription->invoice_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', 'DejaVu Sans', sans-serif;
            direction: rtl;
            color: #1f2937;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
        }
        .invoice-header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        .invoice-header-left {
            display: table-cell;
            vertical-align: middle;
            width: 50%;
        }
        .invoice-header-right {
            display: table-cell;
            vertical-align: middle;
            width: 50%;
            text-align: right;
        }
        .invoice-header h1 {
            color: #1f2937;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            margin: 0 0 5px 0;
        }
        .invoice-header p {
            color: #6b7280;
            font-size: 14px;
            margin: 0;
        }
        .invoice-logo {
            max-width: 150px;
            max-height: 80px;
            object-fit: contain;
        }
        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .info-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 15px;
        }
        .info-column h3 {
            color: #374151;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .info-column div {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.8;
        }
        .info-column .name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .invoice-details {
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }
        .invoice-details > div {
            display: table-cell;
            width: 50%;
            padding: 0 15px;
        }
        .invoice-details .label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .invoice-details .value {
            color: #1f2937;
            font-weight: 600;
        }
        .package-info {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .package-info .label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .package-info .value {
            color: #1f2937;
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background: #f9fafb;
        }
        th {
            padding: 12px;
            text-align: right;
            color: #374151;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 2px solid #e5e7eb;
        }
        th.center {
            text-align: center;
        }
        td {
            padding: 15px 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #1f2937;
        }
        td.center {
            text-align: center;
        }
        .workshop-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        .package-title {
            font-size: 13px;
            color: #6b7280;
        }
        .summary {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }
        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .summary-label {
            display: table-cell;
            width: 70%;
            color: #6b7280;
            font-size: 14px;
        }
        .summary-value {
            display: table-cell;
            width: 30%;
            text-align: left;
            color: #1f2937;
            font-weight: 600;
        }
        .total-row {
            background: #7c3aed;
            color: white;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
        }
        .total-row .summary-label,
        .total-row .summary-value {
            color: white;
            font-weight: 700;
        }
        .total-row .summary-value {
            font-size: 18px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }
        .footer div {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .footer .company-name {
            font-weight: 600;
            color: #1f2937;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
                <h1>فاتورة ضريبية</h1>
                <p>Tax Invoice</p>
        </div>
        
        <!-- Company and Recipient Info -->
        <div class="info-section">
            <div class="info-column">
                <h3>من:</h3>
                <div>
                    <div class="name">{{ $company['name'] }}</div>
                    <div>{{ $company['address'] }}</div>
                    <div style="margin-top: 5px;">{{ $company['phone'] }}</div>
                </div>
            </div>
            <div class="info-column">
                <h3>إلى:</h3>
                <div>
                    <div class="name">{{ $subscription->user ? $subscription->user->full_name : ($subscription->full_name ?? '-') }}</div>
                    <div>{{ $subscription->user ? $subscription->user->email : '-' }}</div>
                    <div style="margin-top: 5px;">{{ $subscription->user ? $subscription->user->phone : ($subscription->phone ?? '-') }}</div>
                </div>
            </div>
        </div>
        
        <!-- Invoice Details -->
        <div class="invoice-details">
            <div>
                <div class="label">تاريخ الفاتورة:</div>
                <div class="value">{{ \App\Helpers\FormatArabicDates::formatArabicDate($subscription->created_at) }}</div>
            </div>
            <div>
                <div class="label">رقم الفاتورة:</div>
                <div class="value">{{ $subscription->invoice_id }}</div>
            </div>
        </div>
        
        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th>الوصف</th>
                    <th class="center">صافي المبلغ</th>
                    <th class="center">الضريبة (5%)</th>
                    <th class="center">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="workshop-title">{{ $subscription->workshop ? $subscription->workshop->title : '-' }}</div>
                        <div class="package-title">الباقة: {{ $package_title }}</div>
                    </td>
                    <td class="center">{{ number_format($subtotal, 2) }}</td>
                    <td class="center">{{ number_format($vat, 2) }}</td>
                    <td class="center" style="font-weight: 600;">{{ number_format($total, 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        <!-- Summary -->
        <div class="summary">
                <span class="summary-label">المجموع الفرعي:</span>
                <span class="summary-value">{{ number_format($subtotal, 2) }} د.إ</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">ضريبة القيمة المضافة (5%):</span>
                <span class="summary-value">{{ number_format($vat, 2) }} د.إ</span>
            </div>
            <div class="summary-row total-row">
                <span class="summary-label">الإجمالي المستحق (درهم):</span>
                <span class="summary-value">{{ number_format($total, 2) }} د.إ</span>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div>شكرا لثقتكم بنا!</div>
            <div>الرقم الضريبي: <span style="font-weight: 600; color: #1f2937;">{{ $company['tax_number'] }}</span></div>
            <div class="company-name">Nawaya Events</div>
        </div>
    </div>
</body>
</html>

