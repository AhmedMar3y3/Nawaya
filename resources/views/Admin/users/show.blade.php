@extends('Admin.layout')

@section('styles')
<style>
    .user-detail-container {
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
    
    .user-profile-card {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    }
    
    .user-avatar-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255,255,255,0.1);
        margin-bottom: 1rem;
    }
    
    .user-name {
        color: #fff;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .user-info {
        color: #94a3b8;
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        margin: 0.25rem;
    }
    
    .status-active {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }
    
    .status-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
    
    .status-verified {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
        border: 1px solid rgba(139, 92, 246, 0.2);
    }
    
    .status-unverified {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }
    
    .info-card {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    }
    
    .info-title {
        color: #fff;
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        color: #94a3b8;
        font-weight: 500;
    }
    
    .info-value {
        color: #fff;
        font-weight: 600;
    }
    
    .btn-action {
        background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0.25rem;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .btn-action.edit {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }
    
    .btn-action.edit:hover {
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }
    
    .btn-action.toggle {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    .btn-action.toggle:hover {
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    
    .btn-action.delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }
    
    .btn-action.delete:hover {
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    .btn-back {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        color: #94a3b8;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-back:hover {
        background: rgba(255,255,255,0.15);
        border-color: rgba(255,255,255,0.3);
        color: #fff;
        text-decoration: none;
    }
</style>
@endsection

@section('main')
<div class="user-detail-container" dir="rtl">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="page-title">تفاصيل المستخدم</h1>
                <p class="page-subtitle">عرض جميع معلومات المستخدم</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.users.index') }}" class="btn-back">
                    <i class="fa fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="user-profile-card">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <img src="{{ $user->image ? asset($user->image) : asset('defaults/profile.webp') }}" 
                     alt="User Avatar" class="user-avatar-large">
            </div>
            <div class="col-md-6">
                <h2 class="user-name">{{ $user->name }}</h2>
                <p class="user-info"><i class="fa fa-phone"></i> {{ $user->phone }}</p>
                @if($user->email)
                    <p class="user-info"><i class="fa fa-envelope"></i> {{ $user->email }}</p>
                @endif
                <p class="user-info"><i class="fa fa-calendar"></i> العمر: {{ $user->age }} سنة</p>
                <p class="user-info"><i class="fa fa-clock"></i> تاريخ التسجيل: {{ $user->created_at->format('Y/m/d H:i') }}</p>
            </div>
            <div class="col-md-3">
                <div class="d-flex flex-wrap">
                    <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $user->is_active ? 'نشط' : 'معطل' }}
                    </span>
                    <span class="status-badge {{ $user->is_verified ? 'status-verified' : 'status-unverified' }}">
                        {{ $user->is_verified ? 'مؤكد' : 'غير مؤكد' }}
                    </span>
                    @if($user->completed_info)
                        <span class="status-badge status-verified">مكتمل الملف</span>
                    @endif
                    @if($user->questionnaire_taken)
                        <span class="status-badge status-verified">أخذ الاستبيان</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="text-center mb-4">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action edit">
            <i class="fa fa-edit"></i>
            تعديل المستخدم
        </a>
        <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn-action toggle">
                <i class="fa fa-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                {{ $user->is_active ? 'إيقاف' : 'تفعيل' }}
            </button>
        </form>
        <button type="button" class="btn-action delete" onclick="deleteUser({{ $user->id }})">
            <i class="fa fa-trash"></i>
            حذف المستخدم
        </button>
    </div>

    <!-- User Information Cards -->
    <div class="row">
        <div class="col-md-6">
            <div class="info-card">
                <h3 class="info-title">
                    <i class="fa fa-user"></i>
                    المعلومات الشخصية
                </h3>
                <div class="info-item">
                    <span class="info-label">الاسم</span>
                    <span class="info-value">{{ $user->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">رقم الهاتف</span>
                    <span class="info-value">{{ $user->phone }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">البريد الإلكتروني</span>
                    <span class="info-value">{{ $user->email ?: 'غير محدد' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">العمر</span>
                    <span class="info-value">{{ $user->age }} سنة</span>
                </div>
                <div class="info-item">
                    <span class="info-label">الجنس</span>
                    <span class="info-value">{{ $user->gender->getLocalizedName() }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="info-card">
                <h3 class="info-title">
                    <i class="fa fa-graduation-cap"></i>
                    المعلومات الأكاديمية
                </h3>
                <div class="info-item">
                    <span class="info-label">القسم</span>
                    <span class="info-value">{{ $user->section->getLocalizedName() }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">المستوى</span>
                    <span class="info-value">{{ $user->start_level->getLocalizedName() }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">المدينة</span>
                    <span class="info-value">{{ $user->city?->name ?: 'غير محدد' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">المدرسة</span>
                    <span class="info-value">{{ $user->school?->name ?: 'غير محدد' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">تاريخ الامتحان</span>
                    <span class="info-value">{{ $user->exam_date?->format('Y/m/d') ?: 'غير محدد' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="info-card">
                <h3 class="info-title">
                    <i class="fa fa-cog"></i>
                    إعدادات الحساب
                </h3>
                <div class="info-item">
                    <span class="info-label">حالة الحساب</span>
                    <span class="info-value">{{ $user->is_active ? 'نشط' : 'معطل' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">حالة التأكيد</span>
                    <span class="info-value">{{ $user->is_verified ? 'مؤكد' : 'غير مؤكد' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">الإشعارات</span>
                    <span class="info-value">{{ $user->is_notify ? 'مفعلة' : 'معطلة' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">إكمال الملف الشخصي</span>
                    <span class="info-value">{{ $user->completed_info ? 'مكتمل' : 'غير مكتمل' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">أخذ الاستبيان</span>
                    <span class="info-value">{{ $user->questionnaire_taken ? 'نعم' : 'لا' }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="info-card">
                <h3 class="info-title">
                    <i class="fa fa-chart-line"></i>
                    النشاط والإحصائيات
                </h3>
                <div class="info-item">
                    <span class="info-label">عدد الأجهزة</span>
                    <span class="info-value">{{ $activity['devices_count'] }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">الأجهزة النشطة</span>
                    <span class="info-value">{{ $activity['active_devices_count'] }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">محاولات الاستبيان</span>
                    <span class="info-value">{{ $activity['questionnaire_attempts_count'] }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">آخر نشاط</span>
                    <span class="info-value">{{ $activity['last_activity']?->format('Y/m/d H:i') ?: 'غير محدد' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">آخر محاولة استبيان</span>
                    <span class="info-value">{{ $activity['last_questionnaire_attempt']?->format('Y/m/d H:i') ?: 'غير محدد' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Questionnaire Attempts -->
    @if($user->questionnaireAttempts->count() > 0)
    <div class="info-card">
        <h3 class="info-title">
            <i class="fa fa-clipboard-list"></i>
            محاولات الاستبيان
        </h3>
        <div class="table-responsive">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th>تاريخ المحاولة</th>
                        <th>النتيجة</th>
                        <th>المستوى المكتشف</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->questionnaireAttempts as $attempt)
                    <tr>
                        <td>{{ $attempt->created_at->format('Y/m/d H:i') }}</td>
                        <td>{{ $attempt->total_score }}/{{ $attempt->max_score }}</td>
                        <td>{{ $attempt->detected_level }}</td>
                        <td>
                            <span class="status-badge {{ $attempt->completed ? 'status-verified' : 'status-unverified' }}">
                                {{ $attempt->completed ? 'مكتمل' : 'غير مكتمل' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="mb-4">
                        <i class="fa fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="mb-3">تأكيد حذف المستخدم</h5>
                    <p class="mb-4">هل أنت متأكد من حذف المستخدم <strong>{{ $user->name }}</strong>؟</p>
                    <div class="alert alert-warning">
                        <strong>تحذير:</strong> هذا الإجراء لا يمكن التراجع عنه. سيتم حذف جميع البيانات المرتبطة بهذا المستخدم.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash"></i>
                        تأكيد الحذف
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function deleteUser(id) {
    const modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
    modal.show();
}
</script>
@endsection