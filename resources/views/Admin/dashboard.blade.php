@extends('Admin.layout')

@section('styles')
    <style>
        /* Global Styles */
        * {
            box-sizing: border-box;
        }

        body,
        .container {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%) !important;
            overflow-x: hidden;
        }

        /* Animated Background */
        .dashboard-container {
            position: relative;
            padding: 2rem 0;
        }

        .dashboard-container::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
            animation: backgroundShift 20s ease-in-out infinite;
            z-index: -1;
        }

        @keyframes backgroundShift {

            0%,
            100% {
                transform: translateX(0) translateY(0);
            }

            25% {
                transform: translateX(-20px) translateY(-10px);
            }

            50% {
                transform: translateX(20px) translateY(10px);
            }

            75% {
                transform: translateX(-10px) translateY(20px);
            }
        }

        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.9) 0%, rgba(15, 23, 42, 0.9) 100%);
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 3rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
            animation: slideInDown 1s ease-out;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #38bdf8, #8b5cf6, #f43f5e, #10b981);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .welcome-title {
            color: #fff;
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #38bdf8, #8b5cf6, #f43f5e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: titleGlow 2s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            from {
                filter: drop-shadow(0 0 20px rgba(56, 189, 248, 0.5));
            }

            to {
                filter: drop-shadow(0 0 30px rgba(139, 92, 246, 0.5));
            }
        }

        .welcome-subtitle {
            color: #94a3b8;
            font-size: 1.2rem;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease-out 0.5s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: fadeInUp 1s ease-out 0.7s both;
        }

        .admin-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #38bdf8, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            font-weight: bold;
            box-shadow: 0 10px 20px rgba(56, 189, 248, 0.3);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .admin-details h4 {
            color: #fff;
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .admin-details p {
            color: #94a3b8;
            margin: 0;
            font-size: 0.9rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stats-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.9) 0%, rgba(15, 23, 42, 0.9) 100%);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            animation: slideInUp 1s ease-out;
            animation-fill-mode: both;
        }

        .stats-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stats-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stats-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stats-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .stats-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .stats-card:nth-child(6) {
            animation-delay: 0.6s;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--card-color), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stats-card:hover::before {
            opacity: 1;
        }

        .stats-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .stats-card.users {
            --card-color: #38bdf8;
        }

        .stats-card.doctors {
            --card-color: #8b5cf6;
        }

        .stats-card.courses {
            --card-color: #10b981;
        }

        .stats-card.subscriptions {
            --card-color: #f43f5e;
        }

        .stats-card.subjects {
            --card-color: #f59e0b;
        }

        .stats-card.universities {
            --card-color: #ec4899;
        }

        .stats-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .stats-icon {
            font-size: 3rem;
            opacity: 0.8;
            transition: all 0.3s ease;
            filter: drop-shadow(0 0 10px currentColor);
        }

        .stats-card:hover .stats-icon {
            transform: scale(1.1) rotate(5deg);
            opacity: 1;
        }

        .stats-icon.users {
            color: #38bdf8;
        }

        .stats-icon.doctors {
            color: #8b5cf6;
        }

        .stats-icon.courses {
            color: #10b981;
        }

        .stats-icon.subscriptions {
            color: #f43f5e;
        }

        .stats-icon.subjects {
            color: #f59e0b;
        }

        .stats-icon.universities {
            color: #ec4899;
        }

        .stats-title {
            color: #94a3b8;
            font-size: 1rem;
            font-weight: 500;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stats-value {
            color: #fff;
            font-size: 3rem;
            font-weight: 800;
            margin: 0.5rem 0;
            background: linear-gradient(135deg, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: countUp 2s ease-out;
        }

        @keyframes countUp {
            from {
                opacity: 0;
                transform: scale(0.5);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .stats-subtitle {
            color: #64748b;
            font-size: 0.9rem;
            margin: 0;
        }

        .stats-trend {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            font-size: 0.8rem;
        }

        .trend-up {
            color: #10b981;
        }

        .trend-down {
            color: #f43f5e;
        }

        /* Activity Section */
        .activity-section {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.9) 0%, rgba(15, 23, 42, 0.9) 100%);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            margin-bottom: 3rem;
            animation: slideInUp 1s ease-out 0.8s both;
        }

        .section-title {
            color: #fff;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            background: linear-gradient(135deg, #38bdf8, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .activity-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            animation: fadeInScale 0.6s ease-out;
            animation-fill-mode: both;
        }

        .activity-item:nth-child(1) {
            animation-delay: 0.1s;
        }

        .activity-item:nth-child(2) {
            animation-delay: 0.2s;
        }

        .activity-item:nth-child(3) {
            animation-delay: 0.3s;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .activity-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(56, 189, 248, 0.3);
        }

        .activity-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #38bdf8;
        }

        .activity-title {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .activity-value {
            color: #38bdf8;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .activity-description {
            color: #94a3b8;
            font-size: 0.9rem;
        }

        /* Chart Section */
        .chart-section {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.9) 0%, rgba(15, 23, 42, 0.9) 100%);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            animation: slideInUp 1s ease-out 1s both;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .chart-title {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .chart-badge {
            background: linear-gradient(135deg, #38bdf8, #8b5cf6);
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.9rem;
            animation: pulse 2s ease-in-out infinite;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stats-card {
                padding: 1.5rem;
            }

            .stats-value {
                font-size: 2rem;
            }

            .activity-grid {
                grid-template-columns: 1fr;
            }

            .chart-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeOut 1s ease-out 2s forwards;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 3px solid rgba(56, 189, 248, 0.3);
            border-top: 3px solid #38bdf8;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                visibility: hidden;
            }
        }
    </style>
@endsection

@section('main')
    <div class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="dashboard-container" dir="rtl">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1 class="welcome-title">{{ $greeting }}، {{ $admin->name }}</h1>
            <p class="welcome-subtitle">مرحباً بك في لوحة التحكم الإدارية - قدراتو</p>

            <div class="admin-info">
                <div class="admin-avatar">
                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                </div>
                <div class="admin-details">
                    <h4>{{ $admin->name }}</h4>
                    <p>مدير النظام</p>
                </div>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="stats-grid">
            <div class="stats-card users">
                <div class="stats-header">
                    <div>
                        <h3 class="stats-title">المستخدمين</h3>
                        <div class="stats-value">{{ number_format($totalUsers) }}</div>
                        <p class="stats-subtitle">إجمالي المستخدمين المسجلين</p>
                    </div>
                    <div class="stats-icon users">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stats-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>{{ $activeUsers }} نشط</span>
                </div>
            </div>
        </div>
        <!-- Recent Activity Section -->
        <div class="activity-section">
            <h2 class="section-title">النشاط الأخير (آخر 7 أيام)</h2>
            <div class="activity-grid">
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="activity-title">مستخدمين جدد</div>
                    <div class="activity-value">{{ $recentUsers }}</div>
                    <div class="activity-description">انضموا خلال الأسبوع الماضي</div>
                </div>

            </div>

            <!-- Chart Section -->
            <div class="chart-section">
                <div class="chart-header">
                    <h3 class="chart-title">نمو المستخدمين</h3>
                    <span class="chart-badge">آخر 7 أيام</span>
                </div>
                <div id="usersChart" style="height: 400px;"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function animateNumber(element, target) {
                let current = 0;
                const increment = target / 100;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    element.textContent = Math.floor(current).toLocaleString();
                }, 20);
            }

            document.querySelectorAll('.stats-value').forEach(element => {
                const target = parseInt(element.textContent.replace(/,/g, ''));
                animateNumber(element, target);
            });

            const chartData = @json($last7DaysUsers);
            const dates = Object.keys(chartData);
            const values = Object.values(chartData);

            const chart = new ApexCharts(document.querySelector("#usersChart"), {
                series: [{
                    name: 'مستخدمين جدد',
                    data: values
                }],
                chart: {
                    height: 400,
                    type: 'area',
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'inherit',
                    background: 'transparent',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }
                },
                colors: ['#38bdf8'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                markers: {
                    size: 6,
                    colors: ['#38bdf8'],
                    strokeColors: '#fff',
                    strokeWidth: 2,
                    hover: {
                        size: 8
                    }
                },
                xaxis: {
                    categories: dates,
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return Math.round(val) + " مستخدم";
                        },
                        style: {
                            colors: '#94a3b8',
                            fontSize: '12px'
                        }
                    },
                    min: 0
                },
                grid: {
                    borderColor: 'rgba(255,255,255,0.1)',
                    strokeDashArray: 4
                },
                tooltip: {
                    theme: 'dark',
                    style: {
                        fontSize: '12px',
                        fontFamily: 'inherit'
                    }
                }
            });

            chart.render();

            document.querySelectorAll('.stats-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px) scale(1.02)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            document.querySelectorAll('.stats-card, .activity-item').forEach(element => {
                element.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });
        });
    </script>
@endsection
