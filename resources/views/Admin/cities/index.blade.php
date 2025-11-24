@extends('Admin.layout')

@section('styles')
    <style>
        .cities-container {
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

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #fff;
            padding: 0.75rem 1rem;
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

        /* Cities Table */
        .cities-table-container {
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

        .badge-warning {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .badge-info {
            background: rgba(56, 189, 248, 0.2);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.3);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .btn-sm {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
        }

       /* Custom Pagination - Complete Override */
    .custom-pagination {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 0.5rem !important;
        margin-top: 2rem !important;
        list-style: none !important;
        padding: 0 !important;
        flex-wrap: wrap !important;
    }
    
    .custom-pagination .page-item {
        margin: 0 !important;
        padding: 0 !important;
        background: none !important;
        border: none !important;
        box-shadow: none !important;
    }
    
    .custom-pagination .page-link {
        background: rgba(255,255,255,0.05) !important;
        border: 1px solid rgba(255,255,255,0.1) !important;
        color: #94a3b8 !important;
        border-radius: 8px !important;
        padding: 0.75rem 1rem !important;
        transition: all 0.3s ease !important;
        text-decoration: none !important;
        min-width: 45px !important;
        text-align: center !important;
        display: block !important;
        font-size: 0.9rem !important;
        font-weight: 500 !important;
        line-height: 1 !important;
        margin: 0 !important;
        box-shadow: none !important;
        outline: none !important;
    }
    
    .custom-pagination .page-link:hover {
        background: rgba(255,255,255,0.1) !important;
        border-color: rgba(255,255,255,0.2) !important;
        color: #fff !important;
        text-decoration: none !important;
        transform: translateY(-1px) !important;
    }
    
    .custom-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        border-color: #10b981 !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3) !important;
    }
    
    .custom-pagination .page-item.disabled .page-link {
        background: rgba(255,255,255,0.02) !important;
        border-color: rgba(255,255,255,0.05) !important;
        color: #64748b !important;
        cursor: not-allowed !important;
        opacity: 0.5 !important;
    }
    
    .custom-pagination .page-item.disabled .page-link:hover {
        transform: none !important;
        background: rgba(255,255,255,0.02) !important;
        border-color: rgba(255,255,255,0.05) !important;
        color: #64748b !important;
    }
    
    .custom-pagination .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(56, 189, 248, 0.25) !important;
        outline: none !important;
    }
    
    /* Hide default Laravel pagination */
    .pagination:not(.custom-pagination) {
        display: none !important;
    }
    
    /* Pagination container */
    .pagination-container {
        display: flex !important;
        justify-content: center !important;
        width: 100% !important;
        margin-top: 2rem !important;
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
            animation: fadeIn 0.3s ease-out;
        }

        .modal-content {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 20px;
            padding: 2rem;
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
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-title {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }

        .close {
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .close:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .modal-body {
            margin-bottom: 1.5rem;
        }

        .modal-footer {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
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
    <div class="cities-container" dir="rtl">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-city"></i>
                إدارة المدن
            </h1>
            <p class="page-subtitle">إدارة المدن والمحافظات في النظام</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $statistics['total_cities'] }}</div>
                <div class="stat-label">إجمالي المدن</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $statistics['cities_with_users'] }}</div>
                <div class="stat-label">مدن بها مستخدمين</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $statistics['cities_with_schools'] }}</div>
                <div class="stat-label">مدن بها مدارس</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $statistics['cities_without_users'] }}</div>
                <div class="stat-label">مدن بدون مستخدمين</div>
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
            <form method="GET" action="{{ route('admin.cities.index') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="form-label">
                            <i class="fas fa-search"></i>
                            البحث في المدن
                        </label>
                        <input type="text" name="search" class="form-control"
                            value="{{ $filter->getActiveFilters()['search'] ?? '' }}" placeholder="ابحث عن مدينة...">
                    </div>
                    <div class="filter-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i>
                            تطبيق الفلاتر
                        </button>
                        <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo"></i>
                            إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Add City Button -->
        <div style="margin-bottom: 1.5rem;">
            <button class="btn btn-success" onclick="openModal()">
                <i class="fas fa-plus"></i>
                إضافة مدينة جديدة
            </button>
        </div>

        <!-- Cities Table -->
        <div class="cities-table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>المدينة</th>
                        <th>عدد المستخدمين</th>
                        <th>عدد المدارس</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cities as $city)
                        <tr>
                            <td>
                                <strong>{{ $city->name }}</strong>
                            </td>
                            <td>
                                @if ($city->users_count > 0)
                                    <span class="badge badge-success">{{ $city->users_count }}</span>
                                @else
                                    <span class="badge badge-warning">لا يوجد</span>
                                @endif
                            </td>
                            <td>
                                @if ($city->schools_count > 0)
                                    <span class="badge badge-info">{{ $city->schools_count }}</span>
                                @else
                                    <span class="badge badge-warning">لا يوجد</span>
                                @endif
                            </td>
                            <td>{{ $city->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-primary btn-sm"
                                        onclick="editCity({{ $city->id }}, '{{ $city->name }}')">
                                        <i class="fas fa-edit"></i>
                                        تعديل
                                    </button>
                                    <form method="POST" action="{{ route('admin.cities.destroy', $city) }}"
                                        style="display: inline;"
                                        onsubmit="return confirm('هل أنت متأكد من حذف هذه المدينة؟')">
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
                            <td colspan="5" style="text-align: center; padding: 2rem; color: #94a3b8;">
                                <i class="fas fa-city" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                                لا توجد مدن متاحة
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

              <!-- Custom Pagination -->
    @if($cities->hasPages())
    <div class="pagination-container">
        <ul class="custom-pagination">
            {{-- Previous Page Link --}}
            @if ($cities->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">السابق</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $cities->previousPageUrl() }}" rel="prev">السابق</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($cities->getUrlRange(1, $cities->lastPage()) as $page => $url)
                @if ($page == $cities->currentPage())
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
            @if ($cities->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $cities->nextPageUrl() }}" rel="next">التالي</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">التالي</span>
                </li>
            @endif
        </ul>
    </div>
    @endif
        </div>
    </div>

    <!-- City Modal -->
    <div id="cityModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">إضافة مدينة جديدة</h3>
                <button class="close" onclick="closeModal()">&times;</button>
            </div>
        <form id="cityForm" method="POST">
            @csrf
            <input type="hidden" name="_method" value="POST" id="methodInput">
            <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-city"></i>
                            اسم المدينة
                        </label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                            placeholder="أدخل اسم المدينة..." required>
                        @error('name')
                            <div class="invalid-feedback" style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </button>
                    <button type="submit" class="btn btn-success" id="submitBtn">
                        <i class="fas fa-save"></i>
                        حفظ المدينة
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let isEditMode = false;
        let editCityId = null;

        function openModal() {
            isEditMode = false;
            editCityId = null;
            document.getElementById('modalTitle').textContent = 'إضافة مدينة جديدة';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> حفظ المدينة';
            document.getElementById('cityForm').action = '{{ route('admin.cities.store') }}';
            document.getElementById('methodInput').value = 'POST';
            document.getElementById('name').value = '';
            document.getElementById('cityModal').classList.add('show');
        }

        function editCity(id, name) {
            isEditMode = true;
            editCityId = id;
            document.getElementById('modalTitle').textContent = 'تعديل المدينة';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> تحديث المدينة';
            document.getElementById('cityForm').action = `{{ url('/cities') }}/${id}`;
            document.getElementById('methodInput').value = 'PUT';
            document.getElementById('name').value = name;
            document.getElementById('cityModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('cityModal').classList.remove('show');
        }


        document.getElementById('cityModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        document.getElementById('cityForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
        });
    </script>
@endsection
