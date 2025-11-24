@extends('Admin.layout')

@section('styles')
<style>
    .subscriptions-container {
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
    
    .page-subtitle {
        color: #94a3b8;
        font-size: 1.1rem;
        margin: 0.5rem 0 0 0;
    }
    
    .filters-section {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .filter-group label {
        display: block;
        color: #94a3b8;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .filter-input,
    .filter-select {
        width: 100%;
        padding: 0.75rem;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: #fff;
        font-size: 0.95rem;
    }
    
    .filter-input:focus,
    .filter-select:focus {
        outline: none;
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1);
    }
    
    .btn-filter {
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #38bdf8, #0284c7);
        border: none;
        border-radius: 8px;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
    }
    
    .btn-reset {
        padding: 0.75rem 1.5rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }
    
    .btn-reset:hover {
        background: rgba(255, 255, 255, 0.15);
    }
    
    .table-section {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .table-title {
        color: #fff;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }
    
    .btn-create {
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        border-radius: 8px;
        color: #fff;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    .subscriptions-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 0.5rem;
    }
    
    .subscriptions-table thead th {
        color: #94a3b8;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
        text-align: right;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }
    
    .subscriptions-table tbody tr {
        background: rgba(15, 23, 42, 0.5);
        transition: all 0.3s ease;
    }
    
    .subscriptions-table tbody tr:hover {
        background: rgba(15, 23, 42, 0.8);
        transform: scale(1.01);
    }
    
    .subscriptions-table tbody td {
        color: #e2e8f0;
        padding: 1rem;
        text-align: right;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f43f5e, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    
    .user-details h4 {
        margin: 0;
        color: #fff;
        font-size: 0.95rem;
        font-weight: 600;
    }
    
    .user-details p {
        margin: 0;
        color: #94a3b8;
        font-size: 0.8rem;
    }
    
    .badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
    
    .action-btn {
        padding: 0.4rem 0.8rem;
        border: none;
        border-radius: 8px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        margin-left: 0.5rem;
    }
    
    .btn-view {
        background: rgba(56, 189, 248, 0.2);
        color: #38bdf8;
        border: 1px solid rgba(56, 189, 248, 0.3);
    }
    
    .btn-view:hover {
        background: rgba(56, 189, 248, 0.3);
    }
    
    .btn-delete {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .btn-delete:hover {
        background: rgba(239, 68, 68, 0.3);
    }
    
    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }
    
    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .pagination li a,
    .pagination li span {
        padding: 0.5rem 1rem;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: #94a3b8;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .pagination li a:hover {
        background: rgba(56, 189, 248, 0.2);
        border-color: #38bdf8;
        color: #38bdf8;
    }
    
    .pagination li.active span {
        background: linear-gradient(135deg, #38bdf8, #0284c7);
        border-color: transparent;
        color: #fff;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-icon {
        font-size: 4rem;
        color: #64748b;
        margin-bottom: 1rem;
    }
    
    .empty-title {
        color: #94a3b8;
        font-size: 1.3rem;
        margin: 0;
    }
</style>
@endsection

@section('main')
<div class="subscriptions-container" dir="rtl">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-crown me-2"></i>
            {{ $type === 'trial' ? 'التجارب المجانية' : 'الاشتراكات' }}
        </h1>
        <p class="page-subtitle">
            {{ $type === 'trial' ? 'إدارة التجارب المجانية' : 'إدارة اشتراكات المستخدمين' }}
        </p>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <form action="{{ route('admin.subscriptions.index', $type) }}" method="GET">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="search">بحث</label>
                    <input 
                        type="text" 
                        name="search" 
                        id="search" 
                        class="filter-input" 
                        placeholder="اسم المستخدم، البريد، أو الهاتف..."
                        value="{{ request('search') }}"
                    >
                </div>

                @if($type === 'subscription')
                <div class="filter-group">
                    <label for="status">الحالة</label>
                    <select name="status" id="status" class="filter-select">
                        <option value="">الكل</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>منتهي</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="type">النوع</label>
                    <select name="type" id="type" class="filter-select">
                        <option value="">الكل</option>
                        <option value="monthly" {{ request('type') === 'monthly' ? 'selected' : '' }}>شهري</option>
                        <option value="yearly" {{ request('type') === 'yearly' ? 'selected' : '' }}>سنوي</option>
                    </select>
                </div>
                @endif

                <div class="filter-group">
                    <label for="sort">ترتيب</label>
                    <select name="sort" id="sort" class="filter-select">
                        <option value="created_desc" {{ request('sort') === 'created_desc' ? 'selected' : '' }}>الأحدث أولاً</option>
                        <option value="created_asc" {{ request('sort') === 'created_asc' ? 'selected' : '' }}>الأقدم أولاً</option>
                        <option value="expires_asc" {{ request('sort') === 'expires_asc' ? 'selected' : '' }}>ينتهي قريباً</option>
                        <option value="expires_desc" {{ request('sort') === 'expires_desc' ? 'selected' : '' }}>ينتهي متأخراً</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search me-2"></i>
                    بحث
                </button>
                <a href="{{ route('admin.subscriptions.index', $type) }}" class="btn-reset">
                    <i class="fas fa-redo me-2"></i>
                    إعادة تعيين
                </a>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="table-section">
        <div class="table-header">
            <h2 class="table-title">
                <i class="fas fa-list me-2"></i>
                القائمة ({{ $subscriptions->total() }})
            </h2>
            @if($type === 'subscription')
            <a href="{{ route('admin.subscriptions.create') }}" class="btn-create">
                <i class="fas fa-plus"></i>
                إضافة اشتراك جديد
            </a>
            @endif
        </div>

        @if($subscriptions->count() > 0)
            <div class="table-responsive">
                <table class="subscriptions-table">
                    <thead>
                        <tr>
                            <th>المستخدم</th>
                            <th>النوع</th>
                            <th>الحالة</th>
                            <th>المبلغ</th>
                            <th>تاريخ البدء</th>
                            <th>تاريخ الانتهاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscriptions as $subscription)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($subscription->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="user-details">
                                        <h4>{{ $subscription->user->name ?? 'مستخدم محذوف' }}</h4>
                                        <p>{{ $subscription->user->email ?? $subscription->user->phone ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($subscription->isInTrial())
                                    <span class="badge badge-trial">تجربة مجانية</span>
                                @else
                                    <span class="badge {{ $subscription->type === 'monthly' ? 'badge-monthly' : 'badge-yearly' }}">
                                        {{ $subscription->type === 'monthly' ? 'شهري' : 'سنوي' }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($subscription->status === 'active' && ($subscription->isActive() || $subscription->isInTrial()))
                                    <span class="badge badge-active">نشط</span>
                                @elseif($subscription->status === 'cancelled')
                                    <span class="badge badge-cancelled">ملغي</span>
                                @else
                                    <span class="badge badge-expired">منتهي</span>
                                @endif
                            </td>
                            <td>
                                @if($subscription->amount > 0)
                                    <strong>{{ number_format($subscription->amount, 2) }}</strong> ريال
                                @else
                                    <span style="color: #94a3b8;">مجاني</span>
                                @endif
                            </td>
                            <td>
                                {{ $subscription->starts_at ? $subscription->starts_at->format('Y-m-d') : ($subscription->trial_starts_at ? $subscription->trial_starts_at->format('Y-m-d') : '-') }}
                            </td>
                            <td>
                                @if($subscription->isInTrial())
                                    {{ $subscription->trial_expires_at->format('Y-m-d') }}
                                @else
                                    {{ $subscription->expires_at ? $subscription->expires_at->format('Y-m-d') : '-' }}
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="action-btn btn-view">
                                    <i class="fas fa-eye me-1"></i>
                                    عرض
                                </a>
                                <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا الاشتراك؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete">
                                        <i class="fas fa-trash me-1"></i>
                                        حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $subscriptions->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3 class="empty-title">لا توجد {{ $type === 'trial' ? 'تجارب مجانية' : 'اشتراكات' }}</h3>
            </div>
        @endif
    </div>
</div>
@endsection
