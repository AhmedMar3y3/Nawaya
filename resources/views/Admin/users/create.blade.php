@extends('Admin.layout')

@section('styles')
<style>
    .create-user-container {
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
    
    .form-container {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    }
    
    .form-control {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #fff;
    }
    
    .form-control:focus {
        background: rgba(255,255,255,0.08);
        border-color: #38bdf8;
        box-shadow: 0 0 0 0.2rem rgba(56,189,248,0.25);
        color: #fff;
    }
    
    .form-select {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #fff;
    }
    
    .form-select:focus {
        background: rgba(255,255,255,0.08);
        border-color: #38bdf8;
        box-shadow: 0 0 0 0.2rem rgba(56,189,248,0.25);
        color: #fff;
    }
    
    .form-label {
        color: #94a3b8;
        font-weight: 500;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        color: white;
    }
    
    .btn-cancel {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 8px;
        padding: 0.75rem 2rem;
        color: #94a3b8;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .btn-cancel:hover {
        background: rgba(255,255,255,0.15);
        border-color: rgba(255,255,255,0.3);
        color: #fff;
        text-decoration: none;
    }
    
    .alert {
        border: none;
        border-radius: 10px;
        padding: 1rem 1.5rem;
        margin-bottom: 1rem;
    }
    
    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border-left: 4px solid #10b981;
    }
    
    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border-left: 4px solid #ef4444;
    }
</style>
@endsection

@section('main')
<div class="create-user-container" dir="rtl">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="page-title">إضافة مستخدم جديد</h1>
                <p class="page-subtitle">إنشاء حساب مستخدم جديد في النظام</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.users.index') }}" class="btn-cancel">
                    <i class="fa fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Form Container -->
    <div class="form-container">
        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">الاسم</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">رقم الهاتف</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="age" class="form-label">العمر</label>
                        <input type="number" class="form-control @error('age') is-invalid @enderror" id="age" name="age" value="{{ old('age') }}" min="1" max="120" required>
                        @error('age')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="gender" class="form-label">الجنس</label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                            <option value="">اختر الجنس</option>
                            @foreach($filterOptions['genders'] as $gender)
                                <option value="{{ $gender->value }}" {{ old('gender') === $gender->value ? 'selected' : '' }}>
                                    {{ $gender->getLocalizedName() }}
                                </option>
                            @endforeach
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="section" class="form-label">القسم</label>
                        <select class="form-select @error('section') is-invalid @enderror" id="section" name="section" required>
                            <option value="">اختر القسم</option>
                            @foreach($filterOptions['sections'] as $section)
                                <option value="{{ $section->value }}" {{ old('section') === $section->value ? 'selected' : '' }}>
                                    {{ $section->getLocalizedName() }}
                                </option>
                            @endforeach
                        </select>
                        @error('section')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="start_level" class="form-label">المستوى</label>
                        <select class="form-select @error('start_level') is-invalid @enderror" id="start_level" name="start_level" required>
                            <option value="">اختر المستوى</option>
                            @foreach($filterOptions['levels'] as $level)
                                <option value="{{ $level->value }}" {{ old('start_level') === $level->value ? 'selected' : '' }}>
                                    {{ $level->getLocalizedName() }}
                                </option>
                            @endforeach
                        </select>
                        @error('start_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="city_id" class="form-label">المدينة</label>
                        <select class="form-select @error('city_id') is-invalid @enderror" id="city_id" name="city_id">
                            <option value="">اختر المدينة</option>
                            @foreach($filterOptions['cities'] as $city)
                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('city_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="school_id" class="form-label">المدرسة</label>
                        <select class="form-select @error('school_id') is-invalid @enderror" id="school_id" name="school_id">
                            <option value="">اختر المدرسة</option>
                            @foreach($filterOptions['schools'] as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }} - {{ $school->city->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="exam_date" class="form-label">تاريخ الامتحان</label>
                        <input type="date" class="form-control @error('exam_date') is-invalid @enderror" id="exam_date" name="exam_date" value="{{ old('exam_date') }}">
                        @error('exam_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="image" class="form-label">صورة المستخدم</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="1" {{ old('is_verified') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_verified">مؤكد</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">نشط</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_notify" name="is_notify" value="1" {{ old('is_notify') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_notify">تفعيل الإشعارات</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="completed_info" name="completed_info" value="1" {{ old('completed_info') ? 'checked' : '' }}>
                            <label class="form-check-label" for="completed_info">مكتمل الملف الشخصي</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="questionnaire_taken" name="questionnaire_taken" value="1" {{ old('questionnaire_taken') ? 'checked' : '' }}>
                            <label class="form-check-label" for="questionnaire_taken">أخذ الاستبيان</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="btn-cancel">إلغاء</a>
                <button type="submit" class="btn-submit">
                    <i class="fa fa-save"></i>
                    حفظ المستخدم
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
