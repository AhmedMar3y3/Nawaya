@extends('Admin.layout')

@section('styles')
    <style>
        .financial-center-container {
            padding: 2rem 0;
        }

        .page-header {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
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

        .tabs-container {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .nav-tabs {
            border: none;
            background: rgba(255, 255, 255, 0.05);
            padding: 0.5rem;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #94a3b8;
            padding: 1rem 2rem;
            border-radius: 10px;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
            color: #fff;
        }

        .filters-section {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            display: flex;
            gap: 1rem;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .filters-section .form-control {
            min-width: 200px;
        }

        .btn-export {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        }

        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            color: white;
            text-decoration: none;
        }

        .financial-table {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
        }

        .table {
            margin: 0;
            color: #fff;
        }

        .table thead th {
            background: rgba(255, 255, 255, 0.05);
            border: none;
            color: #94a3b8;
            font-weight: 600;
            padding: 1rem 0.75rem;
            font-size: 0.85rem;
            white-space: nowrap;
            text-align: right;
        }

        .table tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .table tbody td {
            padding: 1rem 0.75rem;
            border: none;
            vertical-align: middle;
            text-align: right;
            font-size: 0.9rem;
        }

        .table tbody td:first-child {
            text-align: right;
            font-weight: 500;
        }

        .teacher-per-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            color: #fff;
            padding: 0.4rem 0.5rem;
            width: 70px;
            text-align: center;
            font-size: 0.9rem;
        }

        .teacher-per-input:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #38bdf8;
            box-shadow: 0 0 0 0.2rem rgba(56, 189, 248, 0.25);
            color: #fff;
            outline: none;
        }

        .btn-manage-payments {
            background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            color: white;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-manage-payments:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
        }

        .negative-amount {
            color: #ef4444;
        }

        .modal-content {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border: none;
            border-radius: 15px;
            color: #fff;
        }

        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-title {
            color: #fff;
            font-weight: 700;
        }

        .btn-close {
            filter: invert(1);
        }

        .form-label {
            color: #94a3b8;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 8px;
            padding: 0.75rem;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #38bdf8;
            box-shadow: 0 0 0 0.2rem rgba(56, 189, 248, 0.25);
            color: #fff;
            outline: none;
        }

        .payment-log-table {
            width: 100%;
            margin-top: 1rem;
        }

        .payment-log-table th,
        .payment-log-table td {
            padding: 0.75rem;
            text-align: right;
        }

        .payment-log-table th {
            background: rgba(255, 255, 255, 0.05);
            color: #94a3b8;
        }

        .btn-delete-payment {
            background: #ef4444;
            border: none;
            border-radius: 6px;
            color: white;
            padding: 0.25rem 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-delete-payment:hover {
            background: #dc2626;
        }

        .btn-add-payment {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-add-payment:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-save {
            background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
        }

        .btn-cancel {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        /* Custom File Input Styling */
        .expense-file-input-wrapper {
            position: relative;
            display: block;
            width: 100%;
        }

        .expense-file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            cursor: pointer;
            padding: 1rem;
            background: rgba(56, 189, 248, 0.1);
            border: 2px dashed rgba(56, 189, 248, 0.3);
            border-radius: 10px;
            color: #38bdf8;
            transition: all 0.3s ease;
            text-align: center;
            font-weight: 500;
            min-height: 60px;
        }

        .expense-file-input-label:hover {
            background: rgba(56, 189, 248, 0.15);
            border-color: rgba(56, 189, 248, 0.5);
            transform: translateY(-2px);
        }

        .expense-file-input-label i {
            font-size: 1.25rem;
        }

        .expense-file-input-label.has-file {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
            color: #10b981;
        }

        .expense-file-input-label.has-file:hover {
            background: rgba(16, 185, 129, 0.15);
            border-color: rgba(16, 185, 129, 0.5);
        }

        #expenseImage {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            top: 0;
            left: 0;
        }

        .expense-image-preview-container {
            margin-top: 1rem;
            position: relative;
        }

        .expense-image-preview {
            width: 100%;
            max-width: 300px;
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .expense-image-preview-remove {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(239, 68, 68, 0.9);
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }

        .expense-image-preview-remove:hover {
            background: rgba(220, 38, 38, 1);
            transform: scale(1.1);
        }

        .btn-cancel:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .expense-card {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: all 0.3s ease;
        }

        .expense-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
        }

        .expense-card-icon {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .expense-card-content {
            flex: 1;
        }

        .dropdown-menu {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .dropdown-item {
            color: #94a3b8;
            padding: 0.75rem 1rem;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .btn-edit {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            border-radius: 6px;
            padding: 0.4rem 0.6rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-left: 0.25rem;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-restore {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 6px;
            padding: 0.4rem 0.6rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-left: 0.25rem;
        }

        .btn-restore:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .pagination-info {
            color: #94a3b8;
            font-size: 0.95rem;
        }

        .pagination-text {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .pagination-text strong {
            color: #fff;
            font-weight: 600;
        }

        .custom-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .custom-pagination .page-link {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 45px;
            justify-content: center;
        }

        .custom-pagination .page-link:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            transform: translateY(-2px);
        }

        .custom-pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-color: #10b981;
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .custom-pagination .page-item.disabled .page-link {
            background: rgba(255, 255, 255, 0.02);
            border-color: rgba(255, 255, 255, 0.05);
            color: #64748b;
            cursor: not-allowed;
            opacity: 0.5;
        }

        .custom-pagination .page-item.disabled .page-link:hover {
            transform: none;
            background: rgba(255, 255, 255, 0.02);
            border-color: rgba(255, 255, 255, 0.05);
            color: #64748b;
        }

        @media (max-width: 768px) {
            .pagination-wrapper {
                flex-direction: column;
                align-items: stretch;
            }

            .pagination-info {
                text-align: center;
                margin-bottom: 1rem;
            }

            .custom-pagination {
                flex-wrap: wrap;
                justify-content: center;
            }

            .custom-pagination .page-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
                min-width: 40px;
            }
        }
    </style>
@endsection

@section('main')
    <div class="financial-center-container" dir="rtl">

        <!-- Tabs -->
        <div class="tabs-container">
            <ul class="nav nav-tabs" id="financialTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tab === 'workshops' ? 'active' : '' }}" id="workshops-tab"
                        data-bs-toggle="tab" data-bs-target="#workshops" type="button" role="tab"
                        onclick="switchTab('workshops')">
                        التقارير المالية للورش
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tab === 'boutique' ? 'active' : '' }}" id="boutique-tab"
                        data-bs-toggle="tab" data-bs-target="#boutique" type="button" role="tab"
                        onclick="switchTab('boutique')">
                        ملخص إيرادات البوتيك
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tab === 'expenses' ? 'active' : '' }}" id="expenses-tab"
                        data-bs-toggle="tab" data-bs-target="#expenses" type="button" role="tab"
                        onclick="switchTab('expenses')">
                        المصروفات والضرائب
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="financialTabContent">
            <!-- Workshops Financial Reports Tab -->
            <div class="tab-pane fade {{ $tab === 'workshops' ? 'show active' : '' }}" id="workshops" role="tabpanel">
                <!-- Filters and Export -->
                <div class="filters-section">
                    <div>
                        <label style="color: #94a3b8; margin-left: 0.5rem; font-size: 0.9rem;">فلتر الورشة:</label>
                        <select class="form-control" id="workshopFilter"
                            style="display: inline-block; width: auto; min-width: 180px;">
                            <option value="all"
                                {{ request('workshop_filter') == 'all' || !request('workshop_filter') ? 'selected' : '' }}>
                                كل الورشات</option>
                            @foreach ($allWorkshops as $workshop)
                                <option value="{{ $workshop->id }}"
                                    {{ request('workshop_filter') == $workshop->id ? 'selected' : '' }}>
                                    {{ $workshop->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <a href="{{ route('admin.financial-center.export.excel', ['workshop_filter' => request('workshop_filter')]) }}"
                        class="btn-export">
                        <i class="fa fa-download"></i>
                        تصدير
                    </a>
                </div>

                <!-- Financial Table -->
                <div class="financial-table">
                    <div style="padding: 1rem 1.5rem; color: #94a3b8; font-size: 0.9rem;">
                        عرض بيانات {{ $workshops->total() }} ورشة
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="table" style="min-width: 100%;">
                            <thead>
                                <tr>
                                    <th style="min-width: 200px;">الورشة</th>
                                    <th style="min-width: 120px;">إجمالي الإيرادات</th>
                                    <th style="min-width: 120px;">صافي الربح</th>
                                    <th style="min-width: 100px;">نسبة المدربة (%)</th>
                                    <th style="min-width: 120px;">حصة المدربة</th>
                                    <th style="min-width: 120px;">حصة الشركة</th>
                                    <th style="min-width: 150px;">إجمالي المدفوع للمدربة</th>
                                    <th style="min-width: 120px;">المتبقي للمدربة</th>
                                    <th style="min-width: 130px;">إدارة الدفعات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($workshops as $workshop)
                                    <tr data-workshop-id="{{ $workshop['id'] }}">
                                        <td
                                            style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $workshop['title'] }}
                                        </td>
                                        <td>{{ number_format($workshop['total_revenue'], 2) }}</td>
                                        <td>{{ number_format($workshop['net_profit'], 2) }}</td>
                                        <td>
                                            <input type="number" class="teacher-per-input" step="0.01" min="0"
                                                max="100" value="{{ $workshop['teacher_per'] }}"
                                                data-workshop-id="{{ $workshop['id'] }}"
                                                onchange="updateTeacherPercentage({{ $workshop['id'] }}, this.value)">
                                        </td>
                                        <td>{{ number_format($workshop['teacher_share'], 2) }}</td>
                                        <td>{{ number_format($workshop['company_share'], 2) }}</td>
                                        <td>{{ number_format($workshop['total_paid'], 2) }}</td>
                                        <td>
                                            <span class="{{ $workshop['remaining'] < 0 ? 'negative-amount' : '' }}">
                                                {{ number_format($workshop['remaining'], 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn-manage-payments"
                                                onclick="openPaymentModal({{ $workshop['id'] }}, '{{ addslashes($workshop['title']) }}', {{ $workshop['teacher_per'] }})">
                                                إدارة الدفعات
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" style="text-align: center; padding: 3rem; color: #94a3b8;">
                                            لا توجد ورشات
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if ($workshops->hasPages() || $workshops->total() > 0)
                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            <span class="pagination-text">
                                عرض
                                <strong>{{ $workshops->firstItem() ?? 0 }}</strong>
                                إلى
                                <strong>{{ $workshops->lastItem() ?? 0 }}</strong>
                                من أصل
                                <strong>{{ $workshops->total() }}</strong>
                                ورشة
                            </span>
                        </div>

                        @if ($workshops->hasPages())
                            <div class="pagination-container">
                                <ul class="custom-pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($workshops->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fa fa-chevron-right"></i>
                                                السابق
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $workshops->appends(request()->except('page'))->previousPageUrl() }}"
                                                rel="prev">
                                                <i class="fa fa-chevron-right"></i>
                                                السابق
                                            </a>
                                        </li>
                                    @endif

                                    @php
                                        $currentPage = $workshops->currentPage();
                                        $lastPage = $workshops->lastPage();
                                        $pages = [];

                                        if ($lastPage <= 7) {
                                            for ($i = 1; $i <= $lastPage; $i++) {
                                                $pages[] = $i;
                                            }
                                        } else {
                                            $pages[] = 1;

                                            $start = max(2, $currentPage - 1);
                                            $end = min($lastPage - 1, $currentPage + 2);

                                            if ($currentPage <= 4) {
                                                for ($i = 2; $i <= min(5, $lastPage - 1); $i++) {
                                                    $pages[] = $i;
                                                }
                                                if ($lastPage > 6) {
                                                    $pages[] = 'dots';
                                                }
                                            } elseif ($currentPage >= $lastPage - 3) {
                                                if ($lastPage > 6) {
                                                    $pages[] = 'dots';
                                                }
                                                for ($i = max(2, $lastPage - 4); $i <= $lastPage - 1; $i++) {
                                                    $pages[] = $i;
                                                }
                                            } else {
                                                $pages[] = 'dots';
                                                for ($i = $start; $i <= $end; $i++) {
                                                    $pages[] = $i;
                                                }
                                                $pages[] = 'dots';
                                            }

                                            if ($lastPage > 1) {
                                                $pages[] = $lastPage;
                                            }
                                        }
                                    @endphp

                                    {{-- Page Numbers --}}
                                    @foreach ($pages as $page)
                                        @if ($page === 'dots')
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @elseif ($page == $currentPage)
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $workshops->appends(request()->except('page'))->url($page) }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($workshops->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $workshops->appends(request()->except('page'))->nextPageUrl() }}"
                                                rel="next">
                                                التالي
                                                <i class="fa fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                التالي
                                                <i class="fa fa-chevron-left"></i>
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Boutique Revenue Summary Tab -->
            <div class="tab-pane fade {{ $tab === 'boutique' ? 'show active' : '' }}" id="boutique" role="tabpanel">
                <div class="financial-table">
                    <div style="padding: 1rem 1.5rem; color: #94a3b8; font-size: 0.9rem;">
                        ملخص لعدد {{ $boutiqueSummary['completed_orders_count'] }} طلب مكتمل.
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="table" style="min-width: 100%;">
                            <thead>
                                <tr>
                                    <th style="min-width: 300px; text-align: right;">البند</th>
                                    <th style="min-width: 150px; text-align: right;">المبلغ (درهم)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: right;">إجمالي إيرادات البوتيك (شامل الضريبة)</td>
                                    <td style="text-align: right;">
                                        {{ number_format($boutiqueSummary['total_revenue'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">إجمالي ضريبة القيمة المضافة (5%)</td>
                                    <td style="text-align: right;">{{ number_format($boutiqueSummary['vat'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">صافي الإيرادات (بعد الضريبة)</td>
                                    <td style="text-align: right;">{{ number_format($boutiqueSummary['net_revenue'], 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">إجمالي حصص أصحاب المنتجات</td>
                                    <td style="text-align: right;"
                                        class="{{ $boutiqueSummary['total_owner_shares'] < 0 ? 'negative-amount' : '' }}">
                                        {{ number_format($boutiqueSummary['total_owner_shares'], 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">صافي ربح المنصة من البوتيك</td>
                                    <td style="text-align: right;">
                                        {{ number_format($boutiqueSummary['platform_profit'], 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Expenses and Taxes Tab -->
            <div class="tab-pane fade {{ $tab === 'expenses' ? 'show active' : '' }}" id="expenses" role="tabpanel">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
                    <!-- Expenses Card -->
                    <div class="expense-card" onclick="openExpensesModal()" style="cursor: pointer;">
                        <div class="expense-card-icon"
                            style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                            <i class="fa fa-file-invoice" style="font-size: 2rem;"></i>
                        </div>
                        <div class="expense-card-content">
                            <h6 style="color: #94a3b8; margin: 0 0 0.5rem 0; font-size: 0.9rem;">المصروفات</h6>
                            <h3 style="color: #fff; margin: 0; font-size: 1.5rem; font-weight: 700;">
                                {{ number_format($expensesTaxesSummary['expenses'], 2) }}
                            </h3>
                        </div>
                    </div>

                    <!-- Refundable Tax Card -->
                    <div class="expense-card" style="cursor: default;">
                        <div class="expense-card-icon"
                            style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                            <i class="fa fa-arrow-down" style="font-size: 2rem;"></i>
                        </div>
                        <div class="expense-card-content">
                            <h6 style="color: #94a3b8; margin: 0 0 0.5rem 0; font-size: 0.9rem;">الضريبة المستردة</h6>
                            <h3 style="color: #fff; margin: 0; font-size: 1.5rem; font-weight: 700;">
                                {{ number_format($expensesTaxesSummary['refundable_tax'], 2) }}
                            </h3>
                        </div>
                    </div>

                    <!-- VAT Card -->
                    <div class="expense-card" onclick="openVatReportModal()" style="cursor: pointer;">
                        <div class="expense-card-icon"
                            style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="fa fa-percent" style="font-size: 2rem;"></i>
                        </div>
                        <div class="expense-card-content">
                            <h6 style="color: #94a3b8; margin: 0 0 0.5rem 0; font-size: 0.9rem;">ضريبة القيمة المضافة</h6>
                            <h3 style="color: #fff; margin: 0; font-size: 1.5rem; font-weight: 700;">
                                {{ number_format($expensesTaxesSummary['vat'], 2) }}
                            </h3>
                        </div>
                    </div>

                    <!-- Annual Tax Card -->
                    <div class="expense-card" onclick="openAnnualTaxModal()" style="cursor: pointer;">
                        <div class="expense-card-icon"
                            style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="fa fa-calendar" style="font-size: 2rem;"></i>
                        </div>
                        <div class="expense-card-content">
                            <h6 style="color: #94a3b8; margin: 0 0 0.5rem 0; font-size: 0.9rem;">الضريبة السنوية (9%)</h6>
                            <h3 style="color: #fff; margin: 0; font-size: 1.5rem; font-weight: 700;">
                                {{ number_format($expensesTaxesSummary['annual_tax'], 2) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Management Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true"
        dir="rtl">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">إدارة دفعات المدربة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>الورشة: </strong><span id="modalWorkshopTitle"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">نسبة المدربة (%)</label>
                        <input type="number" class="form-control" id="modalTeacherPer" step="0.01" min="0"
                            max="100" style="width: 150px;">
                    </div>
                    <div class="mb-4">
                        <h6>سجل الدفعات (<span id="modalTotalPaid">0.00</span>)</h6>
                        <table class="payment-log-table">
                            <thead>
                                <tr>
                                    <th>حذف</th>
                                    <th>ملاحظات</th>
                                    <th>المبلغ</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody id="paymentLogBody">
                                <!-- Payments will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <h6>إضافة دفعة جديدة</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">المبلغ</label>
                                <input type="number" class="form-control" id="newPaymentAmount" step="0.01"
                                    min="0" value="0.00">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">التاريخ</label>
                                <input type="date" class="form-control" id="newPaymentDate">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">ملاحظات</label>
                                <input type="text" class="form-control" id="newPaymentNotes">
                            </div>
                        </div>
                        <button type="button" class="btn-add-payment" onclick="addPayment()">
                            <i class="fa fa-plus"></i>
                            إضافة
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-save" onclick="saveChanges()">
                        <i class="fa fa-save"></i>
                        حفظ التغييرات
                    </button>
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">إلغاء</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Annual Tax Report Modal -->
    <div class="modal fade" id="annualTaxModal" tabindex="-1" aria-labelledby="annualTaxModalLabel" aria-hidden="true"
        dir="rtl">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%); border: none;">
                <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <h5 class="modal-title" id="annualTaxModalLabel" style="color: #fff; font-weight: 700;">
                        تقرير الضريبة السنوية (9%)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <!-- Total Tax Section -->
                    <div style="text-align: center; margin-bottom: 2rem; padding: 2rem; background: rgba(255,255,255,0.05); border-radius: 12px;">
                        <h6 style="color: #94a3b8; margin-bottom: 1rem; font-size: 1rem;">إجمالي الضريبة السنوية المستحقة</h6>
                        <h2 style="color: #fff; font-size: 2.5rem; font-weight: 700; margin: 0;" id="annualTaxAmount">
                            {{ number_format($expensesTaxesSummary['annual_tax'], 2) }}
                        </h2>
                        <p style="color: #64748b; margin-top: 0.5rem; font-size: 0.9rem;" id="annualTaxBasedOn">
                            بناءً على إجمالي صافي ربح قدره {{ number_format($workshopNetProfits + $boutiqueSummary['platform_profit'], 2) }}
                        </p>
                    </div>

                    <!-- Net Profit Breakdown -->
                    <div style="margin-bottom: 2rem;">
                        <h6 style="color: #94a3b8; margin-bottom: 1rem; font-size: 1rem; font-weight: 600;">تفصيل صافي الربح</h6>
                        <div style="background: rgba(255,255,255,0.05); border-radius: 10px; padding: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <span style="color: #94a3b8;">صافي أرباح الورش</span>
                                <strong style="color: #fff; font-size: 1.1rem;" id="workshopNetProfits">
                                    {{ number_format($workshopNetProfits, 2) }}
                                </strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <span style="color: #94a3b8;">صافي أرباح البوتيك</span>
                                <strong style="color: #fff; font-size: 1.1rem;" id="boutiqueNetProfits">
                                    {{ number_format($boutiqueSummary['platform_profit'], 2) }}
                                </strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; background: rgba(56, 189, 248, 0.1); border-radius: 8px; margin-top: 0.5rem; padding: 1rem;">
                                <span style="color: #38bdf8; font-weight: 600;">إجمالي صافي الربح</span>
                                <strong style="color: #38bdf8; font-size: 1.3rem; font-weight: 700;" id="totalNetProfit">
                                    {{ number_format($workshopNetProfits + $boutiqueSummary['platform_profit'], 2) }}
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.1); padding: 1.5rem 2rem;">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal" style="margin-left: auto;">
                        إغلاق
                    </button>
                    <a href="{{ route('admin.financial-center.annual-tax.export.excel') }}" class="btn-export" style="text-decoration: none;">
                        <i class="fa fa-download"></i>
                        تصدير Excel
                    </a>
                    <a href="{{ route('admin.financial-center.annual-tax.export.pdf') }}" class="btn-save" style="text-decoration: none; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);" download="annual-tax-report.pdf">
                        <i class="fa fa-print"></i>
                        طباعة / حفظ PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- VAT Report Modal -->
    <div class="modal fade" id="vatReportModal" tabindex="-1" aria-labelledby="vatReportModalLabel" aria-hidden="true"
        dir="rtl">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%); border: none;">
                <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <h5 class="modal-title" id="vatReportModalLabel" style="color: #fff; font-weight: 700;">
                        تقرير ضريبة القيمة المضافة من الإيرادات
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <!-- Filters -->
                    <div style="margin-bottom: 2rem;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label style="color: #94a3b8; margin-bottom: 0.5rem; display: block;">فلتر حسب الورشة</label>
                                <select class="form-control" id="vatWorkshopFilter" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff;">
                                    <option value="">الإجمالي (يشمل الورش والبوتيك)</option>
                                    @if (isset($allWorkshops))
                                        @foreach ($allWorkshops as $workshop)
                                            <option value="{{ $workshop->id }}">{{ $workshop->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label style="color: #94a3b8; margin-bottom: 0.5rem; display: block;">من تاريخ</label>
                                <input type="date" class="form-control" id="vatDateFrom" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff;">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label style="color: #94a3b8; margin-bottom: 0.5rem; display: block;">إلى تاريخ</label>
                                <input type="date" class="form-control" id="vatDateTo" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff;">
                            </div>
                        </div>
                    </div>

                    <!-- Total VAT Section -->
                    <div style="text-align: center; margin-bottom: 2rem; padding: 2rem; background: rgba(255,255,255,0.05); border-radius: 12px;">
                        <h6 style="color: #94a3b8; margin-bottom: 1rem; font-size: 1rem;">اجمالي الضريبة المحصلة للفترة المحددة</h6>
                        <h2 style="color: #fff; font-size: 2.5rem; font-weight: 700; margin: 0;" id="vatTotalAmount">
                            0.00
                        </h2>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.1); padding: 1.5rem 2rem;">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal" style="margin-left: auto;">
                        إغلاق
                    </button>
                    <button type="button" class="btn-export" onclick="exportVatExcel()" style="text-decoration: none;">
                        <i class="fa fa-download"></i>
                        تصدير Excel
                    </button>
                    <button type="button" class="btn-save" onclick="exportVatPdf()" style="text-decoration: none; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                        <i class="fa fa-print"></i>
                        طباعة / حفظ PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses Management Modal -->
    <div class="modal fade" id="expensesModal" tabindex="-1" aria-labelledby="expensesModalLabel" aria-hidden="true"
        dir="rtl">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="expensesModalLabel">إدارة المصروفات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Add/Edit Expense Form -->
                    <div class="mb-4">
                        <h6 id="expenseFormTitle">إضافة مصروف جديد</h6>
                        <form id="expenseForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="expenseId" name="id">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">خاص بورشة (أو اتركه فارغاً لمصروف عام)</label>
                                    <select class="form-control" id="expenseWorkshopId" name="workshop_id">
                                        <option value="">-- مصروف عام</option>
                                        @if (isset($allWorkshops))
                                            @foreach ($allWorkshops as $workshop)
                                                <option value="{{ $workshop->id }}">{{ $workshop->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">عنوان الفاتورة <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="expenseTitle" name="title"
                                        required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">صورة الفاتورة (اختياري)</label>
                                    <div class="expense-file-input-wrapper">
                                        <label for="expenseImage" class="expense-file-input-label" id="expenseFileInputLabel">
                                            <i class="fa fa-cloud-upload-alt"></i>
                                            <span id="expenseFileInputText">اختر صورة الفاتورة</span>
                                        </label>
                                        <input type="file" id="expenseImage" name="image" accept="image/*">
                                    </div>
                                    <div class="expense-image-preview-container" id="expenseImagePreview" style="display: none;">
                                        <img id="expenseImagePreviewImg" src="" alt="Preview" class="expense-image-preview">
                                        <button type="button" class="expense-image-preview-remove" id="expenseImageRemoveBtn" title="إزالة الصورة">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">رقم الفاتورة (اختياري)</label>
                                    <input type="text" class="form-control" id="expenseInvoiceNumber"
                                        name="invoice_number">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">الشركة الموردة <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="expenseVendor" name="vendor"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="d-flex flex-wrap align-items-center gap-3">
                                    <div class="flex-grow-1" style="min-width:200px;">
                                        <label class="form-label">مبلغ الفاتورة <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="expenseAmount" name="amount"
                                            step="0.01" min="0" required>
                                    </div>
                                    <div style="width:100%; margin-top:0.75rem;">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">ملاحظات</label>
                                                <textarea class="form-control" id="expenseNotes" name="notes" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div style="min-width:180px;">
                                            <label class="form-label d-flex align-items-center mb-0" style="gap:1rem;">
                                                <input type="checkbox" id="expenseIsIncludingTax" name="is_including_tax"
                                                    value="1" checked>
                                                الفاتورة تشمل الضريبة
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn-save" id="expenseSubmitBtn">
                                    <i class="fa fa-save"></i>
                                    <span id="expenseSubmitText">إضافة المصروف</span>
                                </button>
                                <button type="button" class="btn-cancel" id="expenseCancelBtn" style="display: none;"
                                    onclick="cancelExpenseEdit()">
                                    إلغاء التعديل
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Expenses List -->
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3" style="gap: 1rem;">
                            <ul class="nav nav-tabs" id="expensesTabs" role="tablist"
                                style="background: rgba(255,255,255,0.05); padding: 0.25rem; border-radius: 8px; margin: 0; flex: 1;">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="active-expenses-tab" data-bs-toggle="tab"
                                        data-bs-target="#active-expenses" type="button" role="tab"
                                        onclick="switchExpensesTab('active')" style="padding: 0.4rem 1rem; font-size: 0.85rem;">
                                        المصروفات النشطة
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="trash-expenses-tab" data-bs-toggle="tab"
                                        data-bs-target="#trash-expenses" type="button" role="tab"
                                        onclick="switchExpensesTab('trash')" style="padding: 0.4rem 1rem; font-size: 0.85rem;">
                                        سلة المهملات
                                    </button>
                                </li>
                            </ul>
                            <div class="d-flex gap-2 align-items-center">
                                <select class="form-control" id="expenseCategoryFilter"
                                    style="width: auto; min-width: 180px; font-size: 0.85rem; padding: 0.4rem 0.75rem;" onchange="filterExpenses()">
                                    <option value="all">كل المصروفات</option>
                                    <option value="general">مصروفات عامة</option>
                                    @if (isset($allWorkshops))
                                        @foreach ($allWorkshops as $workshop)
                                            <option value="{{ $workshop->id }}">{{ $workshop->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="dropdown">
                                    <button class="btn-export dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" style="font-size: 0.85rem; padding: 0.4rem 0.75rem;">
                                        <i class="fa fa-download"></i>
                                        تصدير
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"
                                                onclick="exportExpenses('active')">تصدير المصروفات النشطة</a></li>
                                        <li><a class="dropdown-item" href="#"
                                                onclick="exportExpenses('trash')">تصدير سلة المهملات</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content mt-3" id="expensesTabContent">
                            <!-- Active Expenses Tab -->
                            <div class="tab-pane fade show active" id="active-expenses" role="tabpanel">
                                <div class="mb-3">
                                    <span id="expensesSummary" style="color: #94a3b8; font-size: 0.9rem;">إجمالي 0 مصروف | إجمالي المبلغ: 0.00</span>
                                </div>
                                <div style="overflow-x: auto;">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 200px;">العنوان</th>
                                                <th style="min-width: 200px;">الورشة / عام</th>
                                                <th style="min-width: 150px;">المورد</th>
                                                <th style="min-width: 100px;">المبلغ</th>
                                                <th style="min-width: 120px;">التاريخ</th>
                                                <th style="min-width: 100px;">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody id="expensesTableBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Trash Tab -->
                            <div class="tab-pane fade" id="trash-expenses" role="tabpanel">
                                <div class="mb-3">
                                    <span id="trashExpensesSummary" style="color: #94a3b8; font-size: 0.9rem;">إجمالي 0 مصروف | إجمالي المبلغ: 0.00</span>
                                </div>
                                <div style="overflow-x: auto;">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 200px;">العنوان</th>
                                                <th style="min-width: 200px;">الورشة / عام</th>
                                                <th style="min-width: 150px;">المورد</th>
                                                <th style="min-width: 100px;">المبلغ</th>
                                                <th style="min-width: 120px;">التاريخ</th>
                                                <th style="min-width: 100px;">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody id="trashExpensesTableBody">
                                            <!-- Trashed expenses will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let currentTab = '{{ $tab }}';
        let currentWorkshopId = null;
        let paymentModal = null;

        function switchTab(tab) {
            currentTab = tab;
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.location.href = url.toString();
        }

        function updateTeacherPercentage(workshopId, teacherPer) {
            fetch(`/financial-center/workshops/${workshopId}/teacher-per`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        teacher_per: parseFloat(teacherPer)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to update all calculations
                        window.location.reload();
                    } else {
                        alert(data.message || 'حدث خطأ أثناء تحديث نسبة المدربة');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء تحديث نسبة المدربة');
                });
        }

        function openPaymentModal(workshopId, workshopTitle, teacherPer) {
            currentWorkshopId = workshopId;
            document.getElementById('modalWorkshopTitle').textContent = workshopTitle;
            document.getElementById('modalTeacherPer').value = teacherPer;

            // Set today's date as default
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('newPaymentDate').value = today;

            // Load payments
            loadWorkshopPayments(workshopId);

            // Initialize modal if not already done
            if (!paymentModal) {
                paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            }
            paymentModal.show();
        }

        function loadWorkshopPayments(workshopId) {
            fetch(`/financial-center/workshops/${workshopId}/payments`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update teacher percentage in modal
                        document.getElementById('modalTeacherPer').value = data.workshop.teacher_per;

                        // Update total paid
                        document.getElementById('modalTotalPaid').textContent = parseFloat(data.total_paid).toFixed(2);

                        // Populate payment log
                        const tbody = document.getElementById('paymentLogBody');
                        tbody.innerHTML = '';

                        if (data.payments.length === 0) {
                            tbody.innerHTML =
                                '<tr><td colspan="4" style="text-align: center; color: #94a3b8;">لا توجد دفعات</td></tr>';
                        } else {
                            data.payments.forEach(payment => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>
                                        <button type="button" class="btn-delete-payment" onclick="deletePayment(${payment.id})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                    <td>${payment.notes || '-'}</td>
                                    <td>${parseFloat(payment.amount).toFixed(2)}</td>
                                    <td>${payment.date_formatted}</td>
                                `;
                                tbody.appendChild(row);
                            });
                        }
                    } else {
                        alert(data.message || 'حدث خطأ أثناء جلب بيانات الدفعات');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء جلب بيانات الدفعات');
                });
        }

        function addPayment() {
            const amount = parseFloat(document.getElementById('newPaymentAmount').value);
            const date = document.getElementById('newPaymentDate').value;
            const notes = document.getElementById('newPaymentNotes').value;

            if (!amount || amount <= 0) {
                alert('يرجى إدخال مبلغ صحيح');
                return;
            }

            if (!date) {
                alert('يرجى إدخال تاريخ');
                return;
            }

            fetch(`/financial-center/workshops/${currentWorkshopId}/payments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        amount: amount,
                        date: date,
                        notes: notes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload payments
                        loadWorkshopPayments(currentWorkshopId);
                        // Clear form
                        document.getElementById('newPaymentAmount').value = '0.00';
                        document.getElementById('newPaymentNotes').value = '';
                    } else {
                        alert(data.message || 'حدث خطأ أثناء إضافة الدفعة');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء إضافة الدفعة');
                });
        }

        function deletePayment(paymentId) {
            if (!confirm('هل أنت متأكد من حذف هذه الدفعة؟')) {
                return;
            }

            fetch(`/financial-center/payments/${paymentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload payments
                        loadWorkshopPayments(currentWorkshopId);
                    } else {
                        alert(data.message || 'حدث خطأ أثناء حذف الدفعة');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء حذف الدفعة');
                });
        }

        function saveChanges() {
            const teacherPer = parseFloat(document.getElementById('modalTeacherPer').value);

            // Update teacher percentage
            updateTeacherPercentage(currentWorkshopId, teacherPer);

            // Close modal
            if (paymentModal) {
                paymentModal.hide();
            }
        }

        // Filter workshops
        document.getElementById('workshopFilter').addEventListener('change', function() {
            const filterValue = this.value;
            const url = new URL(window.location);

            if (filterValue === 'all') {
                url.searchParams.delete('workshop_filter');
            } else {
                url.searchParams.set('workshop_filter', filterValue);
            }
            url.searchParams.delete('page'); // Reset to first page
            url.searchParams.set('tab', currentTab);

            window.location.href = url.toString();
        });

        // Annual Tax Modal
        let annualTaxModal = null;

        // VAT Report Modal
        let vatReportModal = null;

        function openVatReportModal() {
            if (!vatReportModal) {
                vatReportModal = new bootstrap.Modal(document.getElementById('vatReportModal'));
            }
            // Reset filters
            const vatWorkshopFilter = document.getElementById('vatWorkshopFilter');
            const vatDateFrom = document.getElementById('vatDateFrom');
            const vatDateTo = document.getElementById('vatDateTo');
            if (vatWorkshopFilter) vatWorkshopFilter.value = '';
            if (vatDateFrom) vatDateFrom.value = '';
            if (vatDateTo) vatDateTo.value = '';
            // Load VAT data
            loadVatReport();
            vatReportModal.show();
        }

        function loadVatReport() {
            const workshopId = document.getElementById('vatWorkshopFilter')?.value || '';
            const dateFrom = document.getElementById('vatDateFrom')?.value || '';
            const dateTo = document.getElementById('vatDateTo')?.value || '';

            const params = new URLSearchParams();
            if (workshopId) params.append('workshop_id', workshopId);
            if (dateFrom) params.append('date_from', dateFrom);
            if (dateTo) params.append('date_to', dateTo);

            fetch(`/financial-center/vat-report?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('VAT Report Data:', data);
                if (data.success && data.data) {
                    const vatTotalAmount = document.getElementById('vatTotalAmount');
                    if (vatTotalAmount) {
                        vatTotalAmount.textContent = parseFloat(data.data.total_vat || 0).toFixed(2);
                    }
                } else {
                    console.error('VAT Report Error:', data.message || 'Unknown error');
                    const vatTotalAmount = document.getElementById('vatTotalAmount');
                    if (vatTotalAmount) {
                        vatTotalAmount.textContent = '0.00';
                    }
                }
            })
            .catch(error => {
                console.error('Error loading VAT report:', error);
                const vatTotalAmount = document.getElementById('vatTotalAmount');
                if (vatTotalAmount) {
                    vatTotalAmount.textContent = '0.00';
                }
            });
        }

        function exportVatExcel() {
            const workshopId = document.getElementById('vatWorkshopFilter')?.value || '';
            const dateFrom = document.getElementById('vatDateFrom')?.value || '';
            const dateTo = document.getElementById('vatDateTo')?.value || '';

            const params = new URLSearchParams();
            if (workshopId) params.append('workshop_id', workshopId);
            if (dateFrom) params.append('date_from', dateFrom);
            if (dateTo) params.append('date_to', dateTo);

            window.location.href = `/financial-center/vat-report/export/excel?${params.toString()}`;
        }

        function exportVatPdf() {
            const workshopId = document.getElementById('vatWorkshopFilter')?.value || '';
            const dateFrom = document.getElementById('vatDateFrom')?.value || '';
            const dateTo = document.getElementById('vatDateTo')?.value || '';

            const params = new URLSearchParams();
            if (workshopId) params.append('workshop_id', workshopId);
            if (dateFrom) params.append('date_from', dateFrom);
            if (dateTo) params.append('date_to', dateTo);

            window.location.href = `/financial-center/vat-report/export/pdf?${params.toString()}`;
        }

        // Add event listeners for VAT filters
        document.addEventListener('DOMContentLoaded', function() {
            const vatWorkshopFilter = document.getElementById('vatWorkshopFilter');
            const vatDateFrom = document.getElementById('vatDateFrom');
            const vatDateTo = document.getElementById('vatDateTo');

            if (vatWorkshopFilter) {
                vatWorkshopFilter.addEventListener('change', loadVatReport);
            }
            if (vatDateFrom) {
                vatDateFrom.addEventListener('change', loadVatReport);
            }
            if (vatDateTo) {
                vatDateTo.addEventListener('change', loadVatReport);
            }
        });

        function openAnnualTaxModal() {
            if (!annualTaxModal) {
                annualTaxModal = new bootstrap.Modal(document.getElementById('annualTaxModal'));
            }
            // Load latest data
            fetch('/financial-center/annual-tax/details', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const taxData = data.data;
                    document.getElementById('annualTaxAmount').textContent = parseFloat(taxData.annual_tax).toFixed(2);
                    document.getElementById('annualTaxBasedOn').textContent = `بناءً على إجمالي صافي ربح قدره ${parseFloat(taxData.total_net_profit).toFixed(2)}`;
                    document.getElementById('workshopNetProfits').textContent = parseFloat(taxData.workshop_net_profits).toFixed(2);
                    document.getElementById('boutiqueNetProfits').textContent = parseFloat(taxData.boutique_net_profits).toFixed(2);
                    document.getElementById('totalNetProfit').textContent = parseFloat(taxData.total_net_profit).toFixed(2);
                }
            })
            .catch(error => {
                console.error('Error loading annual tax details:', error);
            });
            annualTaxModal.show();
        }

        // Expenses Management
        let expensesModal = null;
        let currentExpensesTab = 'active';
        let currentExpenseCategory = 'all';
        let editingExpenseId = null;

        function openExpensesModal() {
            if (!expensesModal) {
                expensesModal = new bootstrap.Modal(document.getElementById('expensesModal'));
            }
            resetExpenseForm();
            loadExpenses('active', 'all');
            expensesModal.show();
        }

        function switchExpensesTab(tab) {
            currentExpensesTab = tab;
            const category = document.getElementById('expenseCategoryFilter').value;
            loadExpenses(tab, category);
        }

        function filterExpenses() {
            const category = document.getElementById('expenseCategoryFilter').value;
            currentExpenseCategory = category;
            loadExpenses(currentExpensesTab, category);
        }

        function loadExpenses(tab, category) {
            fetch(`/expenses?tab=${tab}&category=${category}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const expensesData = data.data || {};
                        const expenses = expensesData.expenses || [];
                        const totalCount = expensesData.total_count || 0;
                        const totalAmount = expensesData.total_amount || 0;

                        const tbody = tab === 'active' ?
                            document.getElementById('expensesTableBody') :
                            document.getElementById('trashExpensesTableBody');
                        const summary = tab === 'active' ?
                            document.getElementById('expensesSummary') :
                            document.getElementById('trashExpensesSummary');

                        if (!tbody || !summary) return;

                        tbody.innerHTML = '';

                        if (expenses.length === 0) {
                            tbody.innerHTML =
                                '<tr><td colspan="6" style="text-align: center; padding: 3rem; color: #94a3b8;">لا توجد مصروفات</td></tr>';
                        } else {
                            expenses.forEach(expense => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>${expense.title || ''}</td>
                                    <td>${expense.workshop_title || 'عام'}</td>
                                    <td>${expense.vendor || ''}</td>
                                    <td>${parseFloat(expense.amount || 0).toFixed(2)}</td>
                                    <td>${expense.date_formatted || ''}</td>
                                    <td>
                                        <div class="action-buttons">
                                            ${tab === 'active' 
                                                ? `<button type="button" class="btn-edit" onclick="editExpense(${expense.id})" title="تعديل">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn-delete" onclick="deleteExpense(${expense.id})" title="حذف">
                                                        <i class="fa fa-trash"></i>
                                                    </button>`
                                                : `<button type="button" class="btn-restore" onclick="restoreExpense(${expense.id})" title="استعادة">
                                                        <i class="fa fa-undo"></i>
                                                    </button>
                                                    <button type="button" class="btn-delete" onclick="permanentlyDeleteExpense(${expense.id})" title="حذف نهائي">
                                                        <i class="fa fa-trash"></i>
                                                    </button>`
                                            }
                                        </div>
                                    </td>
                                `;
                                tbody.appendChild(row);
                            });
                        }

                        summary.textContent =
                            `إجمالي ${totalCount} مصروف | إجمالي المبلغ: ${parseFloat(totalAmount).toFixed(2)}`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء جلب بيانات المصروفات');
                });
        }

        function resetExpenseForm() {
            editingExpenseId = null;
            const expenseForm = document.getElementById('expenseForm');
            const expenseId = document.getElementById('expenseId');
            const expenseFormTitle = document.getElementById('expenseFormTitle');
            const expenseSubmitText = document.getElementById('expenseSubmitText');
            const expenseCancelBtn = document.getElementById('expenseCancelBtn');
            const expenseIsIncludingTax = document.getElementById('expenseIsIncludingTax');
            const expenseImagePreview = document.getElementById('expenseImagePreview');

            if (expenseForm) expenseForm.reset();
            if (expenseId) expenseId.value = '';
            if (expenseFormTitle) expenseFormTitle.textContent = 'إضافة مصروف جديد';
            if (expenseSubmitText) expenseSubmitText.textContent = 'إضافة المصروف';
            if (expenseCancelBtn) expenseCancelBtn.style.display = 'none';
            if (expenseIsIncludingTax) expenseIsIncludingTax.checked = true;
            if (expenseImagePreview) expenseImagePreview.style.display = 'none';
            // Reset file input label
            const expenseFileInputLabel = document.getElementById('expenseFileInputLabel');
            const expenseFileInputText = document.getElementById('expenseFileInputText');
            if (expenseFileInputLabel) {
                expenseFileInputLabel.classList.remove('has-file');
                if (expenseFileInputText) expenseFileInputText.textContent = 'اختر صورة الفاتورة';
            }
        }

        function editExpense(expenseId) {
            fetch(`/expenses/${expenseId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        editingExpenseId = expenseId;
                        const expense = data.data || data.expense || {};

                        const expenseIdEl = document.getElementById('expenseId');
                        const expenseTitle = document.getElementById('expenseTitle');
                        const expenseWorkshopId = document.getElementById('expenseWorkshopId');
                        const expenseInvoiceNumber = document.getElementById('expenseInvoiceNumber');
                        const expenseVendor = document.getElementById('expenseVendor');
                        const expenseAmount = document.getElementById('expenseAmount');
                        const expenseIsIncludingTax = document.getElementById('expenseIsIncludingTax');
                        const expenseNotes = document.getElementById('expenseNotes');
                        const expenseImagePreviewImg = document.getElementById('expenseImagePreviewImg');
                        const expenseImagePreview = document.getElementById('expenseImagePreview');
                        const expenseFormTitle = document.getElementById('expenseFormTitle');
                        const expenseSubmitText = document.getElementById('expenseSubmitText');
                        const expenseCancelBtn = document.getElementById('expenseCancelBtn');
                        const expenseForm = document.getElementById('expenseForm');

                        if (expenseIdEl) expenseIdEl.value = expense.id;
                        if (expenseTitle) expenseTitle.value = expense.title;
                        if (expenseWorkshopId) expenseWorkshopId.value = expense.workshop_id || '';
                        if (expenseInvoiceNumber) expenseInvoiceNumber.value = expense.invoice_number || '';
                        if (expenseVendor) expenseVendor.value = expense.vendor;
                        if (expenseAmount) expenseAmount.value = expense.amount;
                        if (expenseIsIncludingTax) expenseIsIncludingTax.checked = expense.is_including_tax;
                        if (expenseNotes) expenseNotes.value = expense.notes || '';

                        if (expense.image) {
                            if (expenseImagePreviewImg) expenseImagePreviewImg.src = expense.image;
                            if (expenseImagePreview) expenseImagePreview.style.display = 'block';
                            // Update file input label
                            if (expenseFileInputLabel) {
                                expenseFileInputLabel.classList.add('has-file');
                                if (expenseFileInputText) expenseFileInputText.textContent = 'صورة موجودة';
                            }
                        } else {
                            if (expenseImagePreview) expenseImagePreview.style.display = 'none';
                            // Reset file input label
                            if (expenseFileInputLabel) {
                                expenseFileInputLabel.classList.remove('has-file');
                                if (expenseFileInputText) expenseFileInputText.textContent = 'اختر صورة الفاتورة';
                            }
                        }

                        if (expenseFormTitle) expenseFormTitle.textContent = 'تعديل المصروف';
                        if (expenseSubmitText) expenseSubmitText.textContent = 'حفظ التعديلات';
                        if (expenseCancelBtn) expenseCancelBtn.style.display = 'inline-block';

                        // Scroll to form
                        if (expenseForm) expenseForm.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء جلب بيانات المصروف');
                });
        }

        function cancelExpenseEdit() {
            resetExpenseForm();
        }

        function deleteExpense(expenseId) {
            if (!confirm('هل أنت متأكد من حذف هذا المصروف؟')) {
                return;
            }

            fetch(`/expenses/${expenseId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const successMsg = document.createElement('div');
                        successMsg.className = 'alert alert-success';
                        successMsg.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; padding: 1rem; border-radius: 8px;';
                        successMsg.textContent = data.msg || 'تم حذف المصروف بنجاح';
                        document.body.appendChild(successMsg);
                        setTimeout(() => successMsg.remove(), 3000);
                        
                        // Reload expenses list
                        loadExpenses(currentExpensesTab, currentExpenseCategory);
                    } else {
                        alert(data.msg || data.message || 'حدث خطأ أثناء حذف المصروف');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء حذف المصروف');
                });
        }

        function restoreExpense(expenseId) {
            if (!confirm('هل أنت متأكد من استعادة هذا المصروف؟')) {
                return;
            }

            fetch(`/expenses/${expenseId}/restore`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const successMsg = document.createElement('div');
                        successMsg.className = 'alert alert-success';
                        successMsg.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; padding: 1rem; border-radius: 8px;';
                        successMsg.textContent = data.msg || 'تم استعادة المصروف بنجاح';
                        document.body.appendChild(successMsg);
                        setTimeout(() => successMsg.remove(), 3000);
                        
                        // Reload expenses list
                        loadExpenses('trash', currentExpenseCategory);
                    } else {
                        alert(data.msg || data.message || 'حدث خطأ أثناء استعادة المصروف');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء استعادة المصروف');
                });
        }

        function permanentlyDeleteExpense(expenseId) {
            if (!confirm('هل أنت متأكد من الحذف النهائي لهذا المصروف؟ لا يمكن التراجع عن هذا الإجراء.')) {
                return;
            }

            fetch(`/expenses/${expenseId}/permanent`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const successMsg = document.createElement('div');
                        successMsg.className = 'alert alert-success';
                        successMsg.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; padding: 1rem; border-radius: 8px;';
                        successMsg.textContent = data.msg || 'تم حذف المصروف نهائياً بنجاح';
                        document.body.appendChild(successMsg);
                        setTimeout(() => successMsg.remove(), 3000);
                        
                        // Reload expenses list
                        loadExpenses('trash', currentExpenseCategory);
                    } else {
                        alert(data.msg || data.message || 'حدث خطأ أثناء حذف المصروف');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء حذف المصروف');
                });
        }

        function exportExpenses(tab) {
            const category = document.getElementById('expenseCategoryFilter').value;
            window.location.href = `/expenses/export/excel?tab=${tab}&category=${category}`;
        }

        // Handle expense form submission
        const expenseForm = document.getElementById('expenseForm');
        if (expenseForm) {
            expenseForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const expenseId = document.getElementById('expenseId');
                const expenseIdValue = expenseId ? expenseId.value : '';
                const url = expenseIdValue ? `/expenses/${expenseIdValue}` : '/expenses';
                const method = expenseIdValue ? 'PUT' : 'POST';

                // Add _method for PUT requests
                if (method === 'PUT') {
                    formData.append('_method', 'PUT');
                }

                const submitBtn = document.getElementById('expenseSubmitBtn');
                if (!submitBtn) {
                    alert('حدث خطأ: لم يتم العثور على زر الحفظ');
                    return;
                }

                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> جاري الحفظ...';

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            const successMsg = document.createElement('div');
                            successMsg.className = 'alert alert-success';
                            successMsg.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; padding: 1rem; border-radius: 8px;';
                            successMsg.textContent = data.msg || data.message || 'تم حفظ المصروف بنجاح';
                            document.body.appendChild(successMsg);
                            setTimeout(() => successMsg.remove(), 3000);
                            
                            // Reset button state
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                            
                            resetExpenseForm();
                            loadExpenses(currentExpensesTab, currentExpenseCategory);
                        } else {
                            let errorMsg = data.msg || data.message || 'حدث خطأ';
                            if (data.errors) {
                                const errorList = Object.values(data.errors).flat();
                                errorMsg += '\n\n' + errorList.join('\n');
                            }
                            alert(errorMsg);
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ أثناء حفظ المصروف');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
            });
        }

        // Image preview and file input styling
        const expenseImage = document.getElementById('expenseImage');
        const expenseFileInputLabel = document.getElementById('expenseFileInputLabel');
        const expenseFileInputText = document.getElementById('expenseFileInputText');
        const expenseImagePreview = document.getElementById('expenseImagePreview');
        const expenseImagePreviewImg = document.getElementById('expenseImagePreviewImg');
        const expenseImageRemoveBtn = document.getElementById('expenseImageRemoveBtn');

        if (expenseImage && expenseFileInputLabel) {
            expenseImage.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Update label to show file is selected
                    expenseFileInputLabel.classList.add('has-file');
                    expenseFileInputText.textContent = file.name;
                    
                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (expenseImagePreviewImg) expenseImagePreviewImg.src = e.target.result;
                        if (expenseImagePreview) expenseImagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Reset if no file
                    expenseFileInputLabel.classList.remove('has-file');
                    expenseFileInputText.textContent = 'اختر صورة الفاتورة';
                    if (expenseImagePreview) expenseImagePreview.style.display = 'none';
                }
            });

            // Remove image button
            if (expenseImageRemoveBtn) {
                expenseImageRemoveBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Clear file input
                    expenseImage.value = '';
                    
                    // Reset label
                    expenseFileInputLabel.classList.remove('has-file');
                    expenseFileInputText.textContent = 'اختر صورة الفاتورة';
                    
                    // Hide preview
                    if (expenseImagePreview) expenseImagePreview.style.display = 'none';
                    if (expenseImagePreviewImg) expenseImagePreviewImg.src = '';
                });
            }
        }
    </script>
@endsection

