<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>404 - الصفحة غير موجودة</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .error-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            direction: rtl;
        }
        .error-container h1 {
            font-size: 6rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
        .error-container h2 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }
        .error-container p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 0.3rem;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div>
            <h1>404</h1>
            <h2>الصفحة غير موجودة</h2>
            <p>عذرًا، الصفحة التي تبحث عنها غير موجودة أو تمت إزالتها.</p>
            <a href="{{ url()->previous() }}" class="btn btn-primary btn-lg">العودة إلى الصفحة السابقة</a>
        </div>
    </div>
</body>
</html>