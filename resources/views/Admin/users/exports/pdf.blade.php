<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير المستخدمين</title>
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
            font-size: 9px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
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
    <h1>{{ $tab === 'deleted' ? 'تقرير المستخدمين المحذوفين' : 'تقرير المستخدمين النشطين' }}</h1>
    
    <table>
        <thead>
            <tr>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>رقم الهاتف</th>
                <th>عدد الاشتراكات النشطة</th>
                <th>تاريخ الإنشاء</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user['full_name'] ?? '-' }}</td>
                <td>{{ $user['email'] ?? '-' }}</td>
                <td>{{ $user['phone'] ?? '-' }}</td>
                <td>{{ $user['active_subscriptions_count'] ?? 0 }}</td>
                <td>{{ $user['created_at'] ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">لا توجد بيانات</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

