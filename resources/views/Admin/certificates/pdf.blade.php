<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شهادة حضور</title>
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
            margin: 0;
            padding: 0;
        }
        
        .certificate-container {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e3c72 0%, #6a4c93 100%);
            position: relative;
            overflow: hidden;
        }
        
        .certificate-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(255,255,255,0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(255,255,255,0.08) 0%, transparent 50%),
                repeating-linear-gradient(
                    45deg,
                    transparent,
                    transparent 10px,
                    rgba(255,255,255,0.03) 10px,
                    rgba(255,255,255,0.03) 20px
                );
            pointer-events: none;
        }
        
        .certificate-content {
            width: 90%;
            max-width: 1000px;
            position: relative;
            z-index: 1;
            text-align: center;
            color: #ffffff;
            padding: 60px 40px;
        }
        
        .logo-section {
            position: absolute;
            top: 40px;
            right: 40px;
            text-align: right;
            z-index: 2;
        }
        
        .logo-text {
            font-size: 32px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 5px;
            font-style: italic;
            letter-spacing: 2px;
        }
        
        .logo-name {
            font-size: 18px;
            color: #ffffff;
            margin-top: 5px;
            font-weight: 400;
        }
        
        .birds {
            font-size: 24px;
            margin-top: -10px;
            margin-right: 5px;
            line-height: 1;
        }
        
        .certificate-title {
            font-size: 64px;
            font-weight: 700;
            color: #ffffff;
            margin: 80px 0 60px 0;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
            letter-spacing: 2px;
        }
        
        .certificate-body {
            margin: 40px 0;
            line-height: 2.2;
        }
        
        .certificate-text {
            font-size: 28px;
            color: #ffffff;
            margin: 20px 0;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.2);
        }
        
        .user-name {
            font-size: 36px;
            font-weight: 700;
            color: #ffffff;
            margin: 25px 0;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.3);
        }
        
        .workshop-title {
            font-size: 32px;
            font-weight: 600;
            color: #ffffff;
            margin: 25px 0;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.2);
        }
        
        .teacher-section {
            margin: 30px 0;
        }
        
        .teacher-name {
            font-size: 28px;
            font-weight: 600;
            color: #ffffff;
            margin: 15px 0;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.2);
        }
        
        .teacher-title {
            font-size: 22px;
            color: #ffffff;
            margin: 10px 0;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.2);
            opacity: 0.95;
        }
        
        .workshop-date {
            font-size: 24px;
            color: #ffffff;
            margin: 30px 0 20px 0;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.2);
        }
        
        .workshop-type {
            font-size: 24px;
            color: #ffffff;
            margin: 15px 0;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.2);
        }
        
        .workshop-address {
            font-size: 22px;
            color: #ffffff;
            margin: 15px 0;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.2);
            opacity: 0.95;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-content">
            <div class="logo-section">
                <div class="logo-text">Dr. Hope</div>
                <div class="birds">🕊️🕊️🕊️</div>
                <div class="logo-name">أمل العتيبي<br>Amal Al Otaibi</div>
            </div>
            
            <h1 class="certificate-title">شهادة حضور</h1>
            
            <div class="certificate-body">
                <p class="certificate-text">نبارك لـ</p>
                
                <div class="user-name">
                    {{ $certificate->user->full_name ?? 'المستخدم' }}
                </div>
                
                <p class="certificate-text">حضورها دورة ورشة</p>
                
                <div class="workshop-title">
                    {{ $certificate->workshop->title }}
                </div>
                
                <div class="teacher-section">
                    <p class="certificate-text">تقديم</p>
                    <div class="teacher-name">
                        {{ $certificate->workshop->teacher }}
                    </div>
                    <div class="teacher-title">
                        مستشار فن إدارة الذات والحياة
                    </div>
                </div>
                
                @php
                    $workshop = $certificate->workshop;
                    $workshopDate = $workshop->start_date 
                        ? $workshop->start_date->locale('ar')->translatedFormat('j F Y')
                        : ($certificate->issued_at 
                            ? $certificate->issued_at->locale('ar')->translatedFormat('j F Y')
                            : now()->locale('ar')->translatedFormat('j F Y'));
                    
                    $workshopType = $workshop->type->getLocalizedName();
                    
                    $address = null;
                    if (in_array($workshop->type->value, ['onsite', 'online_onsite'])) {
                        $addressParts = [];
                        if ($workshop->hall) {
                            $addressParts[] = $workshop->hall;
                        }
                        if ($workshop->hotel) {
                            $addressParts[] = $workshop->hotel;
                        }
                        if ($workshop->city) {
                            $addressParts[] = $workshop->city;
                        }
                        if ($workshop->country && $workshop->country->name) {
                            $addressParts[] = $workshop->country->name;
                        }
                        if (!empty($addressParts)) {
                            $address = implode(' ، ', $addressParts);
                        }
                    }
                @endphp
                
                <div class="workshop-date">
                    {{ $workshopDate }}
                </div>
                
                <div class="workshop-type">
                    {{ $workshopType }}
                </div>
                
                @if($address)
                <div class="workshop-address">
                    {{ $address }}
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
