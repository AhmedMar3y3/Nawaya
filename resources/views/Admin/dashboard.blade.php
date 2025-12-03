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
            min-height: 100vh;
        }

        .dashboard-container::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 80%, rgba(139, 92, 246, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(56, 189, 248, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(16, 185, 129, 0.1) 0%, transparent 50%);
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
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            margin-bottom: 3rem;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
            animation: slideInDown 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #38bdf8, #8b5cf6, #10b981, #f43f5e, #f59e0b);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .welcome-title {
            color: #fff;
            font-size: 3.5rem;
            font-weight: 900;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #38bdf8 0%, #8b5cf6 50%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: titleGlow 3s ease-in-out infinite alternate;
            line-height: 1.2;
        }

        @keyframes titleGlow {
            from {
                filter: drop-shadow(0 0 20px rgba(56, 189, 248, 0.4));
            }

            to {
                filter: drop-shadow(0 0 40px rgba(139, 92, 246, 0.6));
            }
        }

        .welcome-subtitle {
            color: #94a3b8;
            font-size: 1.3rem;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease-out 0.3s both;
            font-weight: 500;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            animation: fadeInUp 1s ease-out 0.5s both;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #38bdf8, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            font-weight: bold;
            box-shadow: 0 15px 30px rgba(56, 189, 248, 0.4), 0 0 0 4px rgba(56, 189, 248, 0.1);
            animation: pulse 2s ease-in-out infinite;
            position: relative;
        }

        .admin-avatar::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            padding: 4px;
            background: linear-gradient(135deg, #38bdf8, #8b5cf6, #10b981);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            animation: rotate 3s linear infinite;
        }

        @keyframes rotate {
            to {
                transform: rotate(360deg);
            }
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
            font-size: 1.5rem;
            font-weight: 700;
        }

        .admin-details p {
            color: #94a3b8;
            margin: 0.3rem 0 0 0;
            font-size: 1rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stats-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            animation: slideInUp 0.8s ease-out;
            animation-fill-mode: both;
            cursor: pointer;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--card-gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stats-card:hover::before {
            opacity: 1;
        }

        .stats-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .stats-card:nth-child(1) {
            animation-delay: 0.1s;
            --card-gradient: linear-gradient(90deg, #38bdf8, #0ea5e9);
        }

        .stats-card:nth-child(2) {
            animation-delay: 0.2s;
            --card-gradient: linear-gradient(90deg, #8b5cf6, #7c3aed);
        }

        .stats-card:nth-child(3) {
            animation-delay: 0.3s;
            --card-gradient: linear-gradient(90deg, #10b981, #059669);
        }

        .stats-card:nth-child(4) {
            animation-delay: 0.4s;
            --card-gradient: linear-gradient(90deg, #f43f5e, #e11d48);
        }

        .stats-card:nth-child(5) {
            animation-delay: 0.5s;
            --card-gradient: linear-gradient(90deg, #f59e0b, #d97706);
        }

        .stats-card:nth-child(6) {
            animation-delay: 0.6s;
            --card-gradient: linear-gradient(90deg, #ec4899, #db2777);
        }

        .stats-card:nth-child(7) {
            animation-delay: 0.7s;
            --card-gradient: linear-gradient(90deg, #06b6d4, #0891b2);
        }

        .stats-card:nth-child(8) {
            animation-delay: 0.8s;
            --card-gradient: linear-gradient(90deg, #14b8a6, #0d9488);
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stats-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .stats-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--icon-bg);
            box-shadow: 0 10px 20px var(--icon-shadow);
            transition: all 0.3s ease;
        }

        .stats-card:hover .stats-icon-wrapper {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 15px 30px var(--icon-shadow);
        }

        .stats-icon {
            font-size: 2rem;
            color: var(--icon-color);
            transition: all 0.3s ease;
        }

        .stats-card:nth-child(1) {
            --icon-color: #38bdf8;
            --icon-bg: rgba(56, 189, 248, 0.2);
            --icon-shadow: rgba(56, 189, 248, 0.3);
        }

        .stats-card:nth-child(2) {
            --icon-color: #8b5cf6;
            --icon-bg: rgba(139, 92, 246, 0.2);
            --icon-shadow: rgba(139, 92, 246, 0.3);
        }

        .stats-card:nth-child(3) {
            --icon-color: #10b981;
            --icon-bg: rgba(16, 185, 129, 0.2);
            --icon-shadow: rgba(16, 185, 129, 0.3);
        }

        .stats-card:nth-child(4) {
            --icon-color: #f43f5e;
            --icon-bg: rgba(244, 63, 94, 0.2);
            --icon-shadow: rgba(244, 63, 94, 0.3);
        }

        .stats-card:nth-child(5) {
            --icon-color: #f59e0b;
            --icon-bg: rgba(245, 158, 11, 0.2);
            --icon-shadow: rgba(245, 158, 11, 0.3);
        }

        .stats-card:nth-child(6) {
            --icon-color: #ec4899;
            --icon-bg: rgba(236, 72, 153, 0.2);
            --icon-shadow: rgba(236, 72, 153, 0.3);
        }

        .stats-card:nth-child(7) {
            --icon-color: #06b6d4;
            --icon-bg: rgba(6, 182, 212, 0.2);
            --icon-shadow: rgba(6, 182, 212, 0.3);
        }

        .stats-card:nth-child(8) {
            --icon-color: #14b8a6;
            --icon-bg: rgba(20, 184, 166, 0.2);
            --icon-shadow: rgba(20, 184, 166, 0.3);
        }

        .stats-title {
            color: #94a3b8;
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stats-value {
            color: #fff;
            font-size: 2.8rem;
            font-weight: 900;
            margin: 1rem 0;
            background: linear-gradient(135deg, #fff, #e2e8f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: countUp 1.5s ease-out;
            line-height: 1;
        }

        @keyframes countUp {
            from {
                opacity: 0;
                transform: scale(0.5) translateY(20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .stats-subtitle {
            color: #64748b;
            font-size: 0.9rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stats-trend {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.4rem 0.8rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        .trend-up {
            color: #10b981;
        }

        .trend-down {
            color: #f43f5e;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .action-btn {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            text-decoration: none;
            color: #fff;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.8rem;
            animation: fadeInScale 0.6s ease-out;
            animation-fill-mode: both;
        }

        .action-btn:nth-child(1) {
            animation-delay: 0.1s;
        }

        .action-btn:nth-child(2) {
            animation-delay: 0.2s;
        }

        .action-btn:nth-child(3) {
            animation-delay: 0.3s;
        }

        .action-btn:nth-child(4) {
            animation-delay: 0.4s;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .action-btn:hover {
            transform: translateY(-5px);
            border-color: rgba(56, 189, 248, 0.5);
            box-shadow: 0 15px 30px rgba(56, 189, 248, 0.2);
        }

        .action-icon {
            font-size: 2rem;
            color: #38bdf8;
        }

        .action-text {
            font-size: 0.95rem;
            font-weight: 600;
        }

        /* Recent Activity Section */
        .activity-section {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
            border-radius: 24px;
            padding: 2.5rem;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
            margin-bottom: 3rem;
            animation: slideInUp 1s ease-out 0.9s both;
        }

        .section-title {
            color: #fff;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 2rem;
            background: linear-gradient(135deg, #38bdf8, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 30px;
            background: linear-gradient(135deg, #38bdf8, #8b5cf6);
            border-radius: 2px;
        }

        .activity-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .activity-item:hover {
            transform: translateX(-5px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(56, 189, 248, 0.3);
        }

        .activity-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(56, 189, 248, 0.2);
            color: #38bdf8;
            font-size: 1.2rem;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .activity-description {
            color: #94a3b8;
            font-size: 0.85rem;
        }

        .activity-time {
            color: #64748b;
            font-size: 0.8rem;
        }

        /* Revenue Highlight */
        .revenue-section {
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.15) 0%, rgba(139, 92, 246, 0.15) 100%);
            border-radius: 24px;
            padding: 2.5rem;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(56, 189, 248, 0.3);
            box-shadow: 0 25px 50px rgba(56, 189, 248, 0.2);
            margin-bottom: 3rem;
            animation: slideInUp 1s ease-out 1.1s both;
            text-align: center;
        }

        .revenue-title {
            color: #94a3b8;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .revenue-amount {
            color: #fff;
            font-size: 3.5rem;
            font-weight: 900;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #38bdf8, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .revenue-subtitle {
            color: #64748b;
            font-size: 0.95rem;
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
            animation: fadeOut 1s ease-out 1.5s forwards;
        }

        .loading-spinner {
            width: 70px;
            height: 70px;
            border: 4px solid rgba(56, 189, 248, 0.2);
            border-top: 4px solid #38bdf8;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                visibility: hidden;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2.2rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stats-card {
                padding: 1.5rem;
            }

            .stats-value {
                font-size: 2.2rem;
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }

            .activity-item {
                flex-direction: column;
                text-align: center;
            }
        }

        /* Positive Vibes Emoji */
        .emoji {
            font-size: 1.5rem;
            display: inline-block;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
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
            <h1 class="welcome-title">
                {{ $greeting }}، {{ $admin->name }} <span class="emoji">👋</span>
            </h1>
            <p class="welcome-subtitle">
                مرحباً بك في لوحة التحكم الإدارية - نوايا <span class="emoji">✨</span>
            </p>

            <div class="admin-info">
                <div class="admin-avatar">
                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                </div>
                <div class="admin-details">
                    <h4>{{ $admin->name }}</h4>
                    <p>مدير النظام | كل يوم أفضل <span class="emoji">🚀</span></p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="{{ route('admin.users.index') }}" class="action-btn">
                <i class="fa fa-users action-icon"></i>
                <span class="action-text">إدارة المستخدمين</span>
            </a>
            <a href="{{ route('admin.workshops.index') }}" class="action-btn">
                <i class="fa fa-chalkboard-teacher action-icon"></i>
                <span class="action-text">إدارة الورش</span>
            </a>
            <a href="{{ route('admin.financial-center.index') }}" class="action-btn">
                <i class="fa fa-chart-line action-icon"></i>
                <span class="action-text">المركز المالي</span>
            </a>
            <a href="{{ route('admin.support-messages.index') }}" class="action-btn">
                <i class="fa fa-envelope action-icon"></i>
                <span class="action-text">رسائل الدعم</span>
            </a>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stats-card" onclick="window.location.href='{{ route('admin.users.index') }}'">
                <div class="stats-header">
                    <div>
                        <div class="stats-title">إجمالي المستخدمين</div>
                        <div class="stats-value">{{ number_format($totalUsers ?? 0) }}</div>
                        <div class="stats-subtitle">
                            <span class="trend-up">✓</span> نشط: {{ number_format($activeUsers ?? 0) }}
                        </div>
                    </div>
                    <div class="stats-icon-wrapper">
                        <i class="fa fa-users stats-icon"></i>
                    </div>
                </div>
                <div class="stats-trend trend-up">
                    <i class="fa fa-arrow-up"></i>
                    {{ number_format($recentUsers ?? 0) }} جديد هذا الأسبوع
                </div>
            </div>

            <div class="stats-card" onclick="window.location.href='{{ route('admin.workshops.index') }}'">
                <div class="stats-header">
                    <div>
                        <div class="stats-title">إجمالي الورش</div>
                        <div class="stats-value">{{ number_format($totalWorkshops ?? 0) }}</div>
                        <div class="stats-subtitle">
                            <span class="trend-up">✓</span> نشط: {{ number_format($activeWorkshops ?? 0) }}
                        </div>
                    </div>
                    <div class="stats-icon-wrapper">
                        <i class="fa fa-chalkboard-teacher stats-icon"></i>
                    </div>
                </div>
                <div class="stats-trend trend-up">
                    <i class="fa fa-arrow-up"></i>
                    {{ number_format($recentWorkshops ?? 0) }} جديد هذا الأسبوع
                </div>
            </div>

            <div class="stats-card"
                onclick="window.location.href='{{ route('admin.products.index', ['section' => 'orders']) }}'">
                <div class="stats-header">
                    <div>
                        <div class="stats-title">إجمالي الطلبات</div>
                        <div class="stats-value">{{ number_format($totalOrders ?? 0) }}</div>
                        <div class="stats-subtitle">
                            <span class="trend-up">✓</span> مكتمل: {{ number_format($completedOrders ?? 0) }}
                        </div>
                    </div>
                    <div class="stats-icon-wrapper">
                        <i class="fa fa-shopping-cart stats-icon"></i>
                    </div>
                </div>
                <div class="stats-trend trend-up">
                    <i class="fa fa-arrow-up"></i>
                    {{ number_format($recentOrders ?? 0) }} جديد هذا الأسبوع
                </div>
            </div>

            <div class="stats-card">
                <div class="stats-header">
                    <div>
                        <div class="stats-title">الاشتراكات</div>
                        <div class="stats-value">{{ number_format($totalSubscriptions ?? 0) }}</div>
                        <div class="stats-subtitle">
                            <span class="trend-up">✓</span> مدفوع: {{ number_format($paidSubscriptions ?? 0) }}
                        </div>
                    </div>
                    <div class="stats-icon-wrapper">
                        <i class="fa fa-credit-card stats-icon"></i>
                    </div>
                </div>
                <div class="stats-trend trend-up">
                    <i class="fa fa-arrow-up"></i>
                    {{ number_format($recentSubscriptions ?? 0) }} جديد هذا الأسبوع
                </div>
            </div>

            <div class="stats-card" onclick="window.location.href='{{ route('admin.products.index') }}'">
                <div class="stats-header">

                    <div class="stats-icon-wrapper">
                        <i class="fa fa-box stats-icon"></i>
                    </div>
                </div>
                <div class="stats-trend trend-up">
                    <i class="fa fa-arrow-up"></i>
                    {{ number_format($recentProducts ?? 0) }} جديد هذا الأسبوع
                </div>
            </div>

            <div class="stats-card" onclick="window.location.href='{{ route('admin.support-messages.index') }}'">
                <div class="stats-header">
                    <div>
                        <div class="stats-title">رسائل الدعم</div>
                        <div class="stats-value">{{ number_format($totalSupportMessages ?? 0) }}</div>
                        <div class="stats-subtitle">
                            <span class="trend-up">✓</span> إجمالي الرسائل
                        </div>
                    </div>
                    <div class="stats-icon-wrapper">
                        <i class="fa fa-envelope stats-icon"></i>
                    </div>
                </div>
                <div class="stats-trend trend-up">
                    <i class="fa fa-arrow-up"></i>
                    {{ number_format($recentSupportMessages ?? 0) }} جديد هذا الأسبوع
                </div>
            </div>

            <div class="stats-card" onclick="window.location.href='{{ route('admin.financial-center.index') }}'">
                <div class="stats-header">
                    <div>
                        <div class="stats-title">المركز المالي</div>
                        <div class="stats-value">💰</div>
                        <div class="stats-subtitle">
                            <span class="trend-up">✓</span> إدارة مالية شاملة
                        </div>
                    </div>
                    <div class="stats-icon-wrapper">
                        <i class="fa fa-chart-line stats-icon"></i>
                    </div>
                </div>
                <div class="stats-trend trend-up">
                    <i class="fa fa-arrow-right"></i>
                    عرض التقارير
                </div>
            </div>

            <div class="stats-card">
                <div class="stats-header">
                    <div>
                        <div class="stats-title">الطلبات المعلقة</div>
                        <div class="stats-value">{{ number_format($pendingOrders ?? 0) }}</div>
                        <div class="stats-subtitle">
                            @if (($pendingOrders ?? 0) > 0)
                                <span class="trend-down">!</span> تحتاج إلى مراجعة
                            @else
                                <span class="trend-up">✓</span> لا توجد طلبات معلقة
                            @endif
                        </div>
                    </div>
                    <div class="stats-icon-wrapper">
                        <i class="fa fa-clock stats-icon"></i>
                    </div>
                </div>
                <div class="stats-trend {{ ($pendingOrders ?? 0) > 0 ? 'trend-down' : 'trend-up' }}">
                    @if (($pendingOrders ?? 0) > 0)
                        <i class="fa fa-exclamation-circle"></i>
                        يحتاج إلى متابعة
                    @else
                        <i class="fa fa-check-circle"></i>
                        كل شيء جاهز
                    @endif
                </div>
            </div>
        </div>


        <!-- Recent Activity Section -->
        <div class="activity-section">
            <h2 class="section-title">النشاط الأخير <span class="emoji">📊</span></h2>

            <div>
                <h3 style="color: #94a3b8; font-size: 1.1rem; margin-bottom: 1rem; font-weight: 600;">المستخدمون الجدد</h3>
                @forelse($recentUsersList ?? [] as $user)
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">{{ $user->full_name }}</div>
                            <div class="activity-description">{{ $user->email }}</div>
                        </div>
                        <div class="activity-time">
                            {{ $user->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div class="activity-item">
                        <div class="activity-content">
                            <div class="activity-description" style="text-align: center; width: 100%;">لا يوجد مستخدمون
                                جدد</div>
                        </div>
                    </div>
                @endforelse
            </div>

            <div style="margin-top: 2rem;">
                <h3 style="color: #94a3b8; font-size: 1.1rem; margin-bottom: 1rem; font-weight: 600;">الطلبات الأخيرة</h3>
                @forelse($recentOrdersList ?? [] as $order)
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">
                                طلب #{{ $order->id }} - {{ $order->user->full_name ?? 'غير محدد' }}
                            </div>
                            <div class="activity-description">
                                المبلغ: {{ number_format($order->total_price, 2) }} درهم |
                                الحالة:
                                {{ $order->status === \App\Enums\Order\OrderStatus::COMPLETED->value ? 'مكتمل' : 'معلق' }}
                            </div>
                        </div>
                        <div class="activity-time">
                            {{ $order->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div class="activity-item">
                        <div class="activity-content">
                            <div class="activity-description" style="text-align: center; width: 100%;">لا توجد طلبات حديثة
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
