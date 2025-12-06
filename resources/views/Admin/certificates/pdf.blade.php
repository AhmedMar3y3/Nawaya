<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شهادة إتمام</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', 'Arial', sans-serif;
            direction: rtl;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .certificate-container {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        
        .certificate-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .certificate {
            width: 90%;
            max-width: 1000px;
            background: #ffffff;
            border: 20px solid #d4af37;
            border-radius: 15px;
            padding: 60px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }
        
        .certificate::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 2px,
                    rgba(212, 175, 55, 0.1) 2px,
                    rgba(212, 175, 55, 0.1) 4px
                );
            pointer-events: none;
        }
        
        .certificate-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }
        
        .certificate-logo {
            font-size: 60px;
            margin-bottom: 20px;
        }
        
        .certificate-title {
            font-size: 48px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .certificate-subtitle {
            font-size: 24px;
            color: #667eea;
            font-weight: 600;
            margin-bottom: 30px;
        }
        
        .certificate-divider {
            width: 200px;
            height: 3px;
            background: linear-gradient(to right, transparent, #d4af37, transparent);
            margin: 30px auto;
        }
        
        .certificate-body {
            text-align: center;
            margin: 50px 0;
        }
        
        .certificate-text {
            font-size: 28px;
            color: #2d3748;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        
        .certificate-name {
            font-size: 42px;
            font-weight: 700;
            color: #667eea;
            margin: 20px 0;
            padding: 15px;
            border-bottom: 3px solid #d4af37;
            display: inline-block;
        }
        
        .certificate-workshop {
            font-size: 32px;
            font-weight: 600;
            color: #2d3748;
            margin: 20px 0;
        }
        
        .certificate-date {
            font-size: 20px;
            color: #718096;
            margin-top: 40px;
        }
        
        .certificate-footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        
        .certificate-signature {
            text-align: center;
            flex: 1;
        }
        
        .signature-line {
            width: 200px;
            height: 2px;
            background: #2d3748;
            margin: 50px auto 10px;
        }
        
        .signature-label {
            font-size: 18px;
            color: #718096;
        }
        
        .certificate-seal {
            position: absolute;
            bottom: 40px;
            left: 40px;
            width: 120px;
            height: 120px;
            border: 5px solid #d4af37;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.9);
            font-size: 14px;
            text-align: center;
            padding: 10px;
            color: #2d3748;
            font-weight: 600;
        }
        
        .certificate-number {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 14px;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate">
            <div class="certificate-number">
                رقم الشهادة: #{{ $certificate->id }}
            </div>
            
            <div class="certificate-header">
                <div class="certificate-logo">🏆</div>
                <h1 class="certificate-title">شهادة إتمام</h1>
                <p class="certificate-subtitle">Certificate of Completion</p>
            </div>
            
            <div class="certificate-divider"></div>
            
            <div class="certificate-body">
                <p class="certificate-text">
                    هذه الشهادة تثبت أن
                </p>
                
                <div class="certificate-name">
                    {{ $certificate->user->full_name ?? 'المستخدم' }}
                </div>
                
                <p class="certificate-text">
                    قد أكمل بنجاح ورشة
                </p>
                
                <div class="certificate-workshop">
                    {{ $certificate->workshop->title }}
                </div>
                
                <p class="certificate-date">
                    بتاريخ: {{ $certificate->issued_at ? $certificate->issued_at->locale('ar')->translatedFormat('j F Y') : date('Y-m-d') }}
                </p>
            </div>
            
            <div class="certificate-divider"></div>
            
            <div class="certificate-footer">
                <div class="certificate-signature">
                    <div class="signature-line"></div>
                    <div class="signature-label">التوقيع</div>
                </div>
                
                <div class="certificate-seal">
                    <div>
                        <div style="font-size: 16px; margin-bottom: 5px;">ختم</div>
                        <div style="font-size: 12px;">نوايا</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

