@extends('Admin.layout')

@section('styles')
    <style>
        .profile-container {
            padding: 2rem 0;
        }

        .page-header {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        .page-title {
            color: #fff;
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }

        .page-subtitle {
            color: #94a3b8;
            font-size: 1.1rem;
            margin: 0;
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            animation: slideIn 0.3s ease-out;
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

        /* Profile Card */
        .profile-card {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            max-width: 800px;
            margin: 0 auto;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        .card-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .card-title {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin: 0;
        }

        .card-title i {
            color: #38bdf8;
            font-size: 1.2rem;
        }

        .card-subtitle {
            color: #94a3b8;
            font-size: 0.9rem;
            margin: 0.5rem 0 0 0;
        }

        /* Avatar Section */
        .avatar-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .avatar-container {
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .avatar-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(56, 189, 248, 0.3);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .avatar-preview:hover {
            border-color: #38bdf8;
            box-shadow: 0 4px 24px rgba(56, 189, 248, 0.4);
        }

        .avatar-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 3rem;
            font-weight: 700;
            border: 4px solid rgba(56, 189, 248, 0.3);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        }

        .avatar-upload-btn {
            margin-top: 1rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            color: #94a3b8;
            font-weight: 500;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .form-label i {
            color: #38bdf8;
            font-size: 0.9rem;
            width: 16px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #fff;
            padding: 0.875rem 1rem;
            transition: all 0.3s ease;
            font-size: 1rem;
            width: 100%;
            direction: rtl;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.25);
            color: #fff;
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(148, 163, 184, 0.7);
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            padding: 0.75rem 1rem;
            background: rgba(56, 189, 248, 0.1);
            border: 1px dashed rgba(56, 189, 248, 0.3);
            border-radius: 10px;
            color: #38bdf8;
            transition: all 0.3s ease;
            text-align: center;
        }

        .file-input-label:hover {
            background: rgba(56, 189, 248, 0.15);
            border-color: rgba(56, 189, 248, 0.5);
        }

        input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .form-text {
            color: #94a3b8;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .form-text i {
            color: #38bdf8;
        }

        /* Button Styles */
        .btn-save {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            justify-content: center;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            color: #fff;
            text-decoration: none;
        }

        .btn-save:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Validation Styles */
        .is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.25) !important;
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .invalid-feedback i {
            color: #ef4444;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-card {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .card-title {
                font-size: 1.3rem;
            }
        }
    </style>
@endsection

@section('main')
    <div class="profile-container" dir="rtl">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-user-cog"></i>
                إعدادات الملف الشخصي
            </h1>
            <p class="page-subtitle">إدارة معلومات حسابك الشخصي</p>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Profile Form -->
        <div class="profile-card">

            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                @csrf
                @method('PUT')

                <!-- Avatar Section -->
                <div class="avatar-section">
                    <div class="avatar-container">
                        @if($admin->avatar)
                            <img src="{{ $admin->avatar }}" alt="Avatar" id="avatarPreview" class="avatar-preview">
                        @else
                            <div class="avatar-placeholder" id="avatarPlaceholder">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                            <img src="" alt="Avatar" id="avatarPreview" class="avatar-preview" style="display: none;">
                        @endif
                    </div>
                    <div class="file-input-wrapper avatar-upload-btn">
                        <label for="avatar" class="file-input-label" id="avatarLabel">
                            <i class="fas fa-upload"></i>
                            <span id="avatarLabelText">اختر صورة جديدة</span>
                        </label>
                        <input type="file" class="form-control @error('avatar') is-invalid @enderror"
                            id="avatar" name="avatar" accept="image/*" onchange="previewAvatar(this)">
                    </div>
                    @error('avatar')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="form-text" style="text-align: center;">
                        <i class="fas fa-info-circle"></i>
                        اختر صورة شخصية (JPG, PNG, GIF - الحد الأقصى 5MB)
                    </div>
                </div>

                <!-- Name Field -->
                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-user"></i>
                        الاسم
                    </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                        id="name" name="name" value="{{ old('name', $admin->name) }}"
                        placeholder="أدخل اسمك">
                    @error('name')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        البريد الإلكتروني
                    </label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                        id="email" name="email" value="{{ old('email', $admin->email) }}"
                        placeholder="أدخل بريدك الإلكتروني">
                    @error('email')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password Section -->
                <div style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 2rem; margin-top: 2rem;">
                    <h4 style="color: #fff; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-lock" style="color: #38bdf8;"></i>
                        تغيير كلمة المرور
                    </h4>
                    <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 1.5rem;">
                        اترك الحقول التالية فارغة إذا كنت لا تريد تغيير كلمة المرور
                    </p>

                    <!-- New Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-key"></i>
                            كلمة المرور الجديدة
                        </label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password"
                            placeholder="أدخل كلمة المرور الجديدة">
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-key"></i>
                            تأكيد كلمة المرور
                        </label>
                        <input type="password" class="form-control"
                            id="password_confirmation" name="password_confirmation"
                            placeholder="أعد إدخال كلمة المرور الجديدة">
                    </div>
                </div>

                <!-- Submit Button -->
                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn-save" id="saveBtn">
                        <i class="fas fa-save"></i>
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('profileForm');
            const saveBtn = document.getElementById('saveBtn');

            form.addEventListener('submit', function(e) {
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> جاري الحفظ...';
            });

            // Avatar preview function
            window.previewAvatar = function(input) {
                const preview = document.getElementById('avatarPreview');
                const placeholder = document.getElementById('avatarPlaceholder');
                const labelText = document.getElementById('avatarLabelText');
                
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    const reader = new FileReader();
                    
                    // Update label text with file name
                    labelText.textContent = file.name;
                    
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                        if (placeholder) {
                            placeholder.style.display = 'none';
                        }
                    };
                    
                    reader.readAsDataURL(file);
                } else {
                    // If no file selected, show existing avatar or placeholder
                    if (preview.src && preview.src !== window.location.href) {
                        preview.style.display = 'block';
                        if (placeholder) {
                            placeholder.style.display = 'none';
                        }
                    } else {
                        preview.style.display = 'none';
                        if (placeholder) {
                            placeholder.style.display = 'flex';
                        }
                    }
                    labelText.textContent = 'اختر صورة جديدة';
                }
            };
        });
    </script>
@endsection

