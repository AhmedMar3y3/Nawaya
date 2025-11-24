@extends('Admin.layout')

@section('styles')
<style>
    .subscription-detail-container {
        padding: 2rem 0;
    }
    
    .page-header {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    }
    
    .page-title {
        color: #fff;
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }
    
    .btn-back {
        padding: 0.75rem 1.5rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: #fff;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 1rem;
    }
    
    .btn-back:hover {
        background: rgba(255, 255, 255, 0.15);
    }
    
    .detail-card {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .card-title {
        color: #fff;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 1.5rem 0;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .info-item {
        padding: 1.5rem;
        background: rgba(15, 23, 42, 0.5);
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .info-label {
        color: #94a3b8;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .info-value {
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .user-card {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem;
        background: rgba(15, 23, 42, 0.5);
        border-radius: 10px;
    }
    
    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f43f5e, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 1.5rem;
    }
    
    .user-info h3 {
        color: #fff;
        margin: 0 0 0.25rem 0;
    }
    
    .user-info p {
        color: #94a3b8;
        margin: 0;
        font-size: 0.9rem;
    }
    
    .badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .badge-active {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
    }
    
    .badge-trial {
        background: linear-gradient(135deg, #38bdf8, #0284c7);
        color: #fff;
    }
    
    .badge-expired {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
    }
    
    .badge-cancelled {
        background: linear-gradient(135deg, #64748b, #475569);
        color: #fff;
    }
    
    .badge-monthly {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: #fff;
    }
    
    .badge-yearly {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
    }
    
    .timeline {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .timeline-item {
        padding: 1rem;
        background: rgba(15, 23, 42, 0.5);
        border-radius: 10px;
        border-right: 3px solid #38bdf8;
        margin-bottom: 1rem;
    }
    
    .timeline-date {
        color: #38bdf8;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .timeline-content {
        color: #e2e8f0;
    }
</style>
@endsection

@section('main')
<div class="subscription-detail-container" dir="rtl">
    <a href="{{ route('admin.subscriptions.index') }}" class="btn-back">
        <i class="fas fa-arrow-right me-2"></i>
        العودة
    </a>

    <!-- User Info -->
    <div class="detail-card">
        <h2 class="card-title">معلومات المستخدم</h2>
        <div class="user-card">
            <div class="user-avatar">
                {{ strtoupper(substr($subscription->user->name ?? 'U', 0, 1)) }}
            </div>
            <div class="user-info">
                <h3>{{ $subscription->user->name ?? 'مستخدم محذوف' }}</h3>
                <p>{{ $subscription->user->email ?? '-' }}</p>
                <p>{{ $subscription->user->phone ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Subscription Details -->
    <div class="detail-card">
        <h2 class="card-title">تفاصيل الاشتراك</h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">الحالة</div>
                <div class="info-value">
                    @if($subscription->status === 'active' && ($subscription->isActive() || $subscription->isInTrial()))
                        <span class="badge badge-active">نشط</span>
                    @elseif($subscription->status === 'cancelled')
                        <span class="badge badge-cancelled">ملغي</span>
                    @else
                        <span class="badge badge-expired">منتهي</span>
                    @endif
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">النوع</div>
                <div class="info-value">
                    @if($subscription->isInTrial())
                        <span class="badge badge-trial">تجربة مجانية</span>
                    @else
                        <span class="badge {{ $subscription->type === 'monthly' ? 'badge-monthly' : 'badge-yearly' }}">
                            {{ $subscription->type === 'monthly' ? 'شهري' : 'سنوي' }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">المبلغ</div>
                <div class="info-value">
                    @if($subscription->amount > 0)
                        {{ number_format($subscription->amount, 2) }} ريال
                    @else
                        مجاني
                    @endif
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">تاريخ البدء</div>
                <div class="info-value">
                    {{ $subscription->starts_at ? $subscription->starts_at->format('Y-m-d') : ($subscription->trial_starts_at ? $subscription->trial_starts_at->format('Y-m-d') : '-') }}
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">تاريخ الانتهاء</div>
                <div class="info-value">
                    @if($subscription->isInTrial())
                        {{ $subscription->trial_expires_at->format('Y-m-d') }}
                    @else
                        {{ $subscription->expires_at ? $subscription->expires_at->format('Y-m-d') : '-' }}
                    @endif
                </div>
            </div>

            @if($subscription->invoice_id)
            <div class="info-item">
                <div class="info-label">رقم الفاتورة</div>
                <div class="info-value" style="font-family: monospace; font-size: 0.9rem;">
                    {{ $subscription->invoice_id }}
                </div>
            </div>
            @endif

            @if($subscription->udid)
            <div class="info-item">
                <div class="info-label">معرف الجهاز (UDID)</div>
                <div class="info-value" style="font-family: monospace; font-size: 0.85rem; word-break: break-all;">
                    {{ $subscription->udid }}
                </div>
            </div>
            @endif

            <div class="info-item">
                <div class="info-label">تاريخ الإنشاء</div>
                <div class="info-value">
                    {{ $subscription->created_at->format('Y-m-d H:i') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription History -->
    @if($userSubscriptions->count() > 1)
    <div class="detail-card">
        <h2 class="card-title">سجل اشتراكات المستخدم</h2>
        <ul class="timeline">
            @foreach($userSubscriptions as $sub)
            <li class="timeline-item">
                <div class="timeline-date">{{ $sub->created_at->format('Y-m-d H:i') }}</div>
                <div class="timeline-content">
                    <strong>
                        @if($sub->isInTrial())
                            تجربة مجانية
                        @else
                            اشتراك {{ $sub->type === 'monthly' ? 'شهري' : 'سنوي' }}
                        @endif
                    </strong>
                    <span class="mx-2">|</span>
                    <span>
                        @if($sub->status === 'active' && ($sub->isActive() || $sub->isInTrial()))
                            <span class="badge badge-active">نشط</span>
                        @elseif($sub->status === 'cancelled')
                            <span class="badge badge-cancelled">ملغي</span>
                        @else
                            <span class="badge badge-expired">منتهي</span>
                        @endif
                    </span>
                    <span class="mx-2">|</span>
                    <span>
                        @if($sub->amount > 0)
                            {{ number_format($sub->amount, 2) }} ريال
                        @else
                            مجاني
                        @endif
                    </span>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection
