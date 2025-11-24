@extends('Admin.layout')

@section('styles')
    <!-- CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>

    <style>
        .settings-container {
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

        /* Settings Cards */
        .settings-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .settings-card {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .settings-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        .card-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            background: transparent !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(56, 189, 248, 0.5), transparent);
        }

        .card-title {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
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

        /* CKEditor Styles */
        .ck-editor__editable {
            background: rgba(255, 255, 255, 0.05) !important;
            color: #fff !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 0 0 10px 10px !important;
            min-height: 300px !important;
        }

        .ck-editor__editable:focus {
            border-color: #38bdf8 !important;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.25) !important;
        }

        .ck.ck-editor {
            border-radius: 10px !important;
            overflow: hidden !important;
        }

        .ck.ck-toolbar {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-bottom: none !important;
            border-radius: 10px 10px 0 0 !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .ck.ck-toolbar__items {
            padding: 0.5rem !important;
        }

        .ck.ck-toolbar__separator {
            background: rgba(255, 255, 255, 0.1) !important;
        }

        .ck.ck-button {
            color: #94a3b8 !important;
        }

        .ck.ck-button:hover {
            background: rgba(56, 189, 248, 0.1) !important;
            color: #38bdf8 !important;
        }

        .ck.ck-button.ck-on {
            background: rgba(56, 189, 248, 0.2) !important;
            color: #38bdf8 !important;
        }

        .ck.ck-dropdown__panel {
            background: rgba(30, 41, 59, 0.95) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 8px !important;
        }

        .ck.ck-list__item {
            color: #94a3b8 !important;
        }

        .ck.ck-list__item:hover {
            background: rgba(56, 189, 248, 0.1) !important;
            color: #38bdf8 !important;
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

        /* Loading State */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
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
            .settings-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .settings-card {
                padding: 1.5rem;
            }

            .card-title {
                font-size: 1.3rem;
            }
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

        /* Tabs Styles */
        .settings-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 0;
        }

        .tab-button {
            background: transparent;
            border: none;
            color: #94a3b8;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tab-button:hover {
            color: #38bdf8;
            background: rgba(56, 189, 248, 0.05);
        }

        .tab-button.active {
            color: #38bdf8;
            border-bottom-color: #38bdf8;
            background: rgba(56, 189, 248, 0.1);
        }

        .tab-button i {
            font-size: 1.1rem;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .settings-tabs {
                flex-direction: column;
                gap: 0.5rem;
            }

            .tab-button {
                width: 100%;
                justify-content: center;
                padding: 0.875rem 1rem;
            }
        }
    </style>
@endsection

@section('main')
    <div class="settings-container" dir="rtl">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">إعدادات الموقع</h1>
            <p class="page-subtitle">إدارة محتوى التطبيق والنصوص الأساسية</p>
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        @endif

        <!-- Settings Tabs -->
        <div class="settings-tabs">
            <button type="button" class="tab-button active" data-tab="app-settings">
                <i class="fas fa-cog"></i>
                إعدادات التطبيق
            </button>
            <button type="button" class="tab-button" data-tab="system-settings">
                <i class="fas fa-sliders-h"></i>
                إعدادات النظام
            </button>
        </div>

        <!-- Settings Form -->
        <form action="{{ route('admin.settings.update') }}" method="POST" id="settingsForm">
            @csrf
            @method('PUT')

            <!-- App Settings Tab -->
            <div class="tab-content active" id="app-settings">
                <div class="settings-grid">

                <!-- Social Media Settings -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fab fa-telegram"></i>
                            قناة التليجرام
                        </h3>
                        <p class="card-subtitle">إدارة رابط قناة التليجرام الرسمية</p>
                    </div>

                    <div class="form-group">
                        <label for="telegram_channel" class="form-label">
                            <i class="fab fa-telegram"></i>
                            رابط قناة التليجرام
                        </label>
                        <input type="url" class="form-control @error('telegram_channel') is-invalid @enderror"
                            id="telegram_channel" name="telegram_channel" value="{{ $data['telegram_channel'] ?? '' }}"
                            placeholder="https://t.me/your_channel">
                        @error('telegram_channel')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            رابط قناة التليجرام الرسمية (مثال: https://t.me/your_channel)
                        </div>
                    </div>
                </div>

                <!-- About App Settings -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i>
                            معلومات التطبيق
                        </h3>
                        <p class="card-subtitle">إدارة محتوى صفحة معلومات التطبيق</p>
                    </div>

                    <div class="form-group">
                        <label for="about_app" class="form-label">
                            <i class="fas fa-file-alt"></i>
                            محتوى صفحة معلومات التطبيق
                        </label>
                        <textarea id="about_app" name="about_app" class="form-control @error('about_app') is-invalid @enderror" rows="10">{{ $data['about_app'] ?? '' }}</textarea>
                        @error('about_app')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            يمكنك استخدام محرر النصوص لإضافة تنسيق HTML للمحتوى
                        </div>
                    </div>
                </div>

                <!-- Privacy Policy Settings -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-shield-alt"></i>
                            سياسة الخصوصية
                        </h3>
                        <p class="card-subtitle">إدارة محتوى سياسة الخصوصية</p>
                    </div>

                    <div class="form-group">
                        <label for="privacy_policy" class="form-label">
                            <i class="fas fa-file-contract"></i>
                            محتوى سياسة الخصوصية
                        </label>
                        <textarea id="privacy_policy" name="privacy_policy" class="form-control @error('privacy_policy') is-invalid @enderror"
                            rows="10">{{ $data['privacy_policy'] ?? '' }}</textarea>
                        @error('privacy_policy')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            يمكنك استخدام محرر النصوص لإضافة تنسيق HTML للمحتوى
                        </div>
                    </div>
                </div>

                <!-- Terms of Use Settings -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file-signature"></i>
                            شروط الاستخدام
                        </h3>
                        <p class="card-subtitle">إدارة محتوى شروط الاستخدام</p>
                    </div>

                    <div class="form-group">
                        <label for="terms_of_use" class="form-label">
                            <i class="fas fa-gavel"></i>
                            محتوى شروط الاستخدام
                        </label>
                        <textarea id="terms_of_use" name="terms_of_use" class="form-control @error('terms_of_use') is-invalid @enderror"
                            rows="10">{{ $data['terms_of_use'] ?? '' }}</textarea>
                        @error('terms_of_use')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            يمكنك استخدام محرر النصوص لإضافة تنسيق HTML للمحتوى
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- System Settings Tab -->
            <div class="tab-content" id="system-settings">
                <div class="settings-grid">
                <!-- Referral Settings -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users"></i>
                            إعدادات الإحالة
                        </h3>
                        <p class="card-subtitle">إدارة إعدادات نظام الإحالة</p>
                    </div>

                    <div class="form-group">
                        <label for="referral_ord_users" class="form-label">
                            <i class="fas fa-user-plus"></i>
                            عدد المستخدمين العاديين للإحالة
                        </label>
                        <input type="number" class="form-control @error('referral_ord_users') is-invalid @enderror"
                            id="referral_ord_users" name="referral_ord_users"
                            value="{{ $data['referral_ord_users'] ?? '' }}" placeholder="0" min="0">
                        @error('referral_ord_users')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            عدد المستخدمين العاديين المطلوب للإحالة (يجب أن يكون رقم صحيح أكبر من أو يساوي 0)
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="referral_subs_users" class="form-label">
                            <i class="fas fa-crown"></i>
                            عدد المستخدمين المشتركين للإحالة
                        </label>
                        <input type="number" class="form-control @error('referral_subs_users') is-invalid @enderror"
                            id="referral_subs_users" name="referral_subs_users"
                            value="{{ $data['referral_subs_users'] ?? '' }}" placeholder="0" min="0">
                        @error('referral_subs_users')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            عدد المستخدمين المشتركين المطلوب للإحالة (يجب أن يكون رقم صحيح أكبر من أو يساوي 0)
                        </div>
                    </div>
                </div>

                <!-- Streak Settings -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-fire"></i>
                            إعدادات السلسلة
                        </h3>
                        <p class="card-subtitle">إدارة إعدادات نظام السلسلة والاستعادة</p>
                    </div>

                    <div class="form-group">
                        <label for="max_free_restores" class="form-label">
                            <i class="fas fa-redo"></i>
                            عدد استعادة السلسلة المجانية
                        </label>
                        <input type="number" class="form-control @error('max_free_restores') is-invalid @enderror"
                            id="max_free_restores" name="max_free_restores"
                            value="{{ $data['max_free_restores'] ?? '1' }}" placeholder="1" min="0"
                            max="10">
                        @error('max_free_restores')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            عدد مرات استعادة السلسلة المجانية للمستخدمين (من 0 إلى 10)
                        </div>
                    </div>
                </div>

                <!-- Percentage Settings -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-percentage"></i>
                            إعدادات النسب
                        </h3>
                        <p class="card-subtitle">إدارة نسب صعوبة الأسئلة في الاختبارات النهائية</p>
                    </div>

                    <div class="form-group">
                        <label for="per_of_easy" class="form-label">
                            <i class="fas fa-smile"></i>
                            نسبة الأسئلة السهلة
                        </label>
                        <input type="number" class="form-control @error('per_of_easy') is-invalid @enderror"
                            id="per_of_easy" name="per_of_easy"
                            value="{{ old('per_of_easy', $data['per_of_easy'] ?? '') }}" placeholder="0" min="0" max="100" step="1">
                        @error('per_of_easy')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            نسبة الأسئلة السهلة (قيمة صحيحة بين 0 و 100)
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="per_of_medium" class="form-label">
                            <i class="fas fa-meh"></i>
                            نسبة الأسئلة المتوسطة
                        </label>
                        <input type="number" class="form-control @error('per_of_medium') is-invalid @enderror"
                            id="per_of_medium" name="per_of_medium"
                            value="{{ old('per_of_medium', $data['per_of_medium'] ?? '') }}" placeholder="0" min="0" max="100" step="1">
                        @error('per_of_medium')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            نسبة الأسئلة المتوسطة (قيمة صحيحة بين 0 و 100)
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="per_of_hard" class="form-label">
                            <i class="fas fa-frown"></i>
                            نسبة الأسئلة الصعبة
                        </label>
                        <input type="number" class="form-control @error('per_of_hard') is-invalid @enderror"
                            id="per_of_hard" name="per_of_hard"
                            value="{{ old('per_of_hard', $data['per_of_hard'] ?? '') }}" placeholder="0" min="0" max="100" step="1">
                        @error('per_of_hard')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            نسبة الأسئلة الصعبة (قيمة صحيحة بين 0 و 100). ملاحظة: يجب أن يكون مجموع النسب الثلاث (سهلة + متوسطة + صعبة) يساوي 100.
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="text-center" style="margin-top: 2rem;">
                <button type="submit" class="btn-save" id="saveBtn">
                    <i class="fas fa-save"></i>
                    حفظ الإعدادات
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab Switching
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    // Remove active class from all buttons and contents
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));

                    // Add active class to clicked button and corresponding content
                    this.classList.add('active');
                    document.getElementById(targetTab).classList.add('active');
                });
            });

            const editors = {};

            const editorIds = ['about_app', 'privacy_policy', 'terms_of_use'];

            editorIds.forEach(function(id) {
                ClassicEditor
                    .create(document.querySelector('#' + id), {
                        language: 'ar',
                        direction: 'rtl',
                        toolbar: {
                            items: [
                                'heading', '|',
                                'bold', 'italic', 'underline', 'strikethrough', '|',
                                'bulletedList', 'numberedList', '|',
                                'outdent', 'indent', '|',
                                'blockQuote', 'insertTable', '|',
                                'link', '|',
                                'undo', 'redo'
                            ],
                            shouldNotGroupWhenFull: true
                        },
                        heading: {
                            options: [{
                                    model: 'paragraph',
                                    title: 'Paragraph',
                                    class: 'ck-heading_paragraph'
                                },
                                {
                                    model: 'heading1',
                                    view: 'h1',
                                    title: 'Heading 1',
                                    class: 'ck-heading_heading1'
                                },
                                {
                                    model: 'heading2',
                                    view: 'h2',
                                    title: 'Heading 2',
                                    class: 'ck-heading_heading2'
                                },
                                {
                                    model: 'heading3',
                                    view: 'h3',
                                    title: 'Heading 3',
                                    class: 'ck-heading_heading3'
                                }
                            ]
                        },
                        link: {
                            addTargetToExternalLinks: true
                        },
                        table: {
                            contentToolbar: [
                                'tableColumn',
                                'tableRow',
                                'mergeTableCells'
                            ]
                        }
                    })
                    .then(editor => {
                        editors[id] = editor;

                        editor.setData(document.querySelector('#' + id).value);

                        editor.model.document.on('change:data', () => {
                            document.querySelector('#' + id).value = editor.getData();
                        });
                    })
                    .catch(error => {
                        console.error('Error initializing CKEditor for ' + id + ':', error);
                    });
            });

            const form = document.getElementById('settingsForm');
            const saveBtn = document.getElementById('saveBtn');

            form.addEventListener('submit', function(e) {
                Object.keys(editors).forEach(function(id) {
                    if (editors[id]) {
                        document.querySelector('#' + id).value = editors[id].getData();
                    }
                });

                saveBtn.disabled = true;
                saveBtn.innerHTML = '<span class="spinner"></span> جاري الحفظ...';
                form.classList.add('loading');
            });

            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-save"></i> حفظ الإعدادات';
            form.classList.remove('loading');
        });
    </script>
@endsection
