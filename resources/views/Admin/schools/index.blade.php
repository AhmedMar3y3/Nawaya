@extends('Admin.layout')

@section('styles')
    <style>
        .schools-container {
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

        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        .stat-number {
            color: #38bdf8;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #94a3b8;
            font-size: 0.9rem;
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

        /* Filter Section */
        .filter-section {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .filter-row {
            display: flex;
            gap: 1rem;
            align-items: end;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .form-label {
            color: #e2e8f0;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #fff;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            width: 100%;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.25);
            color: #fff;
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(148, 163, 184, 0.7);
        }

        .form-select option {
            background: #1E293B;
            color: #fff;
        }

        .btn {
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
            color: #fff;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(56, 189, 248, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #fff;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        /* Schools Table */
        .schools-table-container {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            color: #e2e8f0;
        }

        .table th {
            background: rgba(255, 255, 255, 0.05);
            color: #38bdf8;
            font-weight: 600;
            padding: 1rem;
            text-align: right;
            border-bottom: 2px solid rgba(56, 189, 248, 0.3);
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .badge-info {
            background: rgba(56, 189, 248, 0.2);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.3);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            padding: 2rem;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: slideUp 0.3s ease-out;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .close {
            color: #94a3b8;
            font-size: 2rem;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
            background: none;
            border: none;
        }

        .close:hover {
            color: #fff;
        }

        .modal-body {
            margin-bottom: 1.5rem;
        }

        .modal-footer {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .page-item {
            list-style: none;
        }

        .page-link {
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: #e2e8f0;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: rgba(56, 189, 248, 0.2);
            border-color: #38bdf8;
            color: #38bdf8;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
            border-color: #38bdf8;
            color: #fff;
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Animations */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
            }

            .filter-group {
                min-width: 100%;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }

            .modal-content {
                width: 95%;
                padding: 1.5rem;
            }
        }
    </style>
@endsection

@section('main')
    <div class="schools-container" dir="rtl">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-school"></i>
                إدارة المدارس
            </h1>
            <p class="page-subtitle">إدارة المدارس والمؤسسات التعليمية في النظام</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $statistics['total_schools'] }}</div>
                <div class="stat-label">إجمالي المدارس</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $statistics['schools_with_users'] }}</div>
                <div class="stat-label">مدارس بها مستخدمين</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $statistics['schools_without_users'] }}</div>
                <div class="stat-label">مدارس بدون مستخدمين</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $statistics['most_popular_school']?->users_count ?? 0 }}</div>
                <div class="stat-label">أكثر مدرسة شعبية</div>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.schools.index') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="form-label">
                            <i class="fas fa-search"></i>
                            البحث في المدارس
                        </label>
                        <input type="text" name="search" class="form-control"
                            value="{{ $filter->getActiveFilters()['search'] ?? '' }}" placeholder="ابحث عن مدرسة...">
                    </div>
                    <div class="filter-group">
                        <label class="form-label">
                            <i class="fas fa-city"></i>
                            المدينة
                        </label>
                        <select name="city_id" class="form-select">
                            <option value="">جميع المدن</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}"
                                    {{ ($filter->getActiveFilters()['city_id'] ?? '') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i>
                            تطبيق الفلاتر
                        </button>
                        <a href="{{ route('admin.schools.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i>
                            إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Add School Button -->
        <div style="margin-bottom: 1.5rem;">
            <button class="btn btn-success" onclick="openModal()">
                <i class="fas fa-plus"></i>
                إضافة مدرسة جديدة
            </button>
        </div>

        <!-- Schools Table -->
        <div class="schools-table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المدرسة</th>
                        <th>المدينة</th>
                        <th>عدد المستخدمين</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schools as $school)
                        <tr>
                            <td>{{ $loop->iteration + ($schools->currentPage() - 1) * $schools->perPage() }}</td>
                            <td>{{ $school->name }}</td>
                            <td>
                                <span class="badge badge-info">{{ $school->city->name }}</span>
                            </td>
                            <td>
                                <span class="badge badge-success">{{ $school->users_count }}</span>
                            </td>
                            <td>{{ $school->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-primary btn-sm"
                                        onclick="editSchool({{ $school->id }}, '{{ $school->name }}', {{ $school->city_id }})">
                                        <i class="fas fa-edit"></i>
                                        تعديل
                                    </button>
                                    <form method="POST" action="{{ route('admin.schools.destroy', $school) }}"
                                        style="display: inline;"
                                        onsubmit="return confirm('هل أنت متأكد من حذف هذه المدرسة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem; color: #94a3b8;">
                                <i class="fas fa-school" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                لا توجد مدارس. قم بإضافة أول مدرسة للبدء.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            @if ($schools->hasPages())
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($schools->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">السابق</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $schools->previousPageUrl() }}" rel="prev">السابق</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($schools->getUrlRange(1, $schools->lastPage()) as $page => $url)
                        @if ($page == $schools->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($schools->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $schools->nextPageUrl() }}" rel="next">التالي</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">التالي</span>
                        </li>
                    @endif
                </ul>
            @endif
        </div>
    </div>

    <!-- School Modal -->
    <div id="schoolModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">إضافة مدرسة جديدة</h3>
                <button class="close" onclick="closeModal()">&times;</button>
            </div>
            <form id="schoolForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST" id="methodInput">
                <div class="modal-body">
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="name" class="form-label">
                            <i class="fas fa-school"></i>
                            اسم المدرسة
                        </label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="{{ old('name') }}" placeholder="أدخل اسم المدرسة..." required>
                    </div>
                    <div class="form-group">
                        <label for="city_id_modal" class="form-label">
                            <i class="fas fa-city"></i>
                            المدينة
                        </label>
                        <select id="city_id_modal" name="city_id" class="form-select" required>
                            <option value="">اختر المدينة</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </button>
                    <button type="submit" class="btn btn-success" id="submitBtn">
                        <i class="fas fa-save"></i>
                        حفظ المدرسة
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let isEditMode = false;
        let editSchoolId = null;

        function openModal() {
            isEditMode = false;
            editSchoolId = null;
            document.getElementById('modalTitle').textContent = 'إضافة مدرسة جديدة';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> حفظ المدرسة';
            document.getElementById('schoolForm').action = '{{ route('admin.schools.store') }}';
            document.getElementById('methodInput').value = 'POST';
            document.getElementById('name').value = '';
            document.getElementById('city_id_modal').value = '';
            document.getElementById('schoolModal').classList.add('show');
        }

        function editSchool(id, name, cityId) {
            isEditMode = true;
            editSchoolId = id;
            document.getElementById('modalTitle').textContent = 'تعديل المدرسة';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> تحديث المدرسة';
            document.getElementById('schoolForm').action = `{{ url('/schools') }}/${id}`;
            document.getElementById('methodInput').value = 'PUT';
            document.getElementById('name').value = name;
            document.getElementById('city_id_modal').value = cityId;
            document.getElementById('schoolModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('schoolModal').classList.remove('show');
        }

        document.getElementById('schoolModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        document.getElementById('schoolForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
        });
    </script>
@endsection