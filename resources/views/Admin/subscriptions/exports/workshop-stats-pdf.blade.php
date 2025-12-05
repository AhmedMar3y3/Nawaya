<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير إجمالي الاشتراكات</title>
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
            font-size: 12px;
            font-family: 'DejaVu Sans', 'Arial Unicode MS', 'Tahoma', sans-serif;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #1E293B;
            font-size: 20px;
            margin-bottom: 10px;
        }
        
        .summary {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .summary-label {
            color: #6c757d;
            font-size: 14px;
        }
        
        .summary-value {
            color: #1E293B;
            font-weight: 700;
            font-size: 16px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
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
        
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير إجمالي الاشتراكات</h1>
        <div style="color: #6c757d; font-size: 14px;">{{ $stats['workshop_title'] }}</div>
    </div>
    
    <div class="summary">
        <div class="summary-row">
            <div>
                <div class="summary-label">الإجمالي:</div>
                <div class="summary-value">{{ number_format($stats['total_amount'], 2) }} د.إ</div>
            </div>
            <div>
                <div class="summary-label">عدد المشتركين:</div>
                <div class="summary-value">{{ $stats['total_count'] }}</div>
            </div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>الباقة</th>
                <th class="text-center">عدد المشتركين</th>
                <th class="text-center">الإيرادات</th>
            </tr>
        </thead>
        <tbody>
            @if(count($stats['packages']) > 0)
                @foreach($stats['packages'] as $package)
                    <tr>
                        <td>{{ $package['title'] }}</td>
                        <td class="text-center">{{ $package['count'] }}</td>
                        <td class="text-center">{{ number_format($package['income'], 2) }} د.إ</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3" class="text-center">لا توجد بيانات</td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <div style="margin-top: 30px; text-align: center; color: #6c757d; font-size: 10px;">
        تم إنشاء التقرير في: {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>
</html>

