@extends('Admin.layout')

@section('styles')
<style>
    .reviews-container {
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
    
    .search-export-section {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .search-input {
        flex: 1;
        min-width: 250px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: #fff;
        padding: 0.75rem 1rem;
    }
    
    .search-input:focus {
        background: rgba(255,255,255,0.08);
        border-color: #38bdf8;
        box-shadow: 0 0 0 0.2rem rgba(56,189,248,0.25);
        color: #fff;
        outline: none;
    }
    
    .reviews-table {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    }
    
    .table {
        margin: 0;
        color: #fff;
    }
    
    .table thead th {
        background: rgba(255,255,255,0.05);
        border: none;
        color: #94a3b8;
        font-weight: 600;
        padding: 1.5rem 1rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table tbody tr {
        border-bottom: 1px solid rgba(255,255,255,0.05);
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background: rgba(255,255,255,0.02);
    }
    
    .table tbody td {
        padding: 1.5rem 1rem;
        border: none;
        vertical-align: middle;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    
    .btn-action {
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        color: white;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        cursor: pointer;
    }
    
    .btn-view {
        background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
    }
    
    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
        color: white;
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }
    
    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        color: white;
    }
    
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }
    
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked+.toggle-slider {
        background-color: #10b981;
    }
    
    input:checked+.toggle-slider:before {
        transform: translateX(26px);
    }
    
    .toggle-label {
        color: #94a3b8;
        font-size: 0.9rem;
        margin-left: 0.5rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #94a3b8;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        display: block;
    }
    
    .empty-state h4 {
        color: #fff;
        margin-bottom: 0.5rem;
    }
    
    .stars {
        color: #fbbf24;
        font-size: 1.2rem;
    }
    
    .pagination-wrapper {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 2rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .pagination-info {
        color: #94a3b8;
    }
    
    .pagination-text {
        font-size: 0.9rem;
    }
    
    .pagination-text strong {
        color: #fff;
    }
    
    .custom-pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 0.5rem;
    }
    
    .page-item {
        margin: 0;
    }
    
    .page-link {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #94a3b8;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .page-link:hover {
        background: rgba(255,255,255,0.1);
        color: #fff;
        text-decoration: none;
    }
    
    .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
        color: #fff;
        border-color: transparent;
    }
    
    .modal-dialog {
        max-width: 700px;
    }
    
    .modal-content {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 20px;
        color: #fff;
        direction: rtl;
        text-align: right;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        overflow: hidden;
    }
    
    .modal-header {
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding: 1.75rem 2rem;
        background: linear-gradient(135deg, rgba(56,189,248,0.1) 0%, rgba(14,165,233,0.05) 100%);
        border-radius: 20px 20px 0 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .modal-header::before {
        content: '';
        width: 4px;
        height: 40px;
        background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
        border-radius: 2px;
    }
    
    .modal-title {
        color: #fff;
        font-weight: 700;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }
    
    .modal-title i {
        color: #fbbf24;
        font-size: 1.75rem;
    }
    
    .modal-body {
        padding: 2rem;
        max-height: 75vh;
        overflow-y: auto;
    }
    
    .modal-body::-webkit-scrollbar {
        width: 6px;
    }
    
    .modal-body::-webkit-scrollbar-track {
        background: rgba(255,255,255,0.05);
        border-radius: 10px;
    }
    
    .modal-body::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
    }
    
    .modal-body::-webkit-scrollbar-thumb:hover {
        background: rgba(255,255,255,0.3);
    }
    
    .info-section {
        margin-bottom: 1.5rem;
    }
    
    .info-section-title {
        color: #38bdf8;
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(56,189,248,0.2);
    }
    
    .info-section-title i {
        font-size: 1.1rem;
    }
    
    .info-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        background: rgba(255,255,255,0.05);
        border-color: rgba(56,189,248,0.3);
        transform: translateX(-3px);
    }
    
    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 0.75rem;
    }
    
    .info-row:last-child {
        margin-bottom: 0;
    }
    
    .info-label {
        color: #94a3b8;
        font-weight: 600;
        font-size: 0.9rem;
        min-width: 120px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-label i {
        color: #64748b;
        font-size: 0.85rem;
    }
    
    .info-value {
        color: #fff;
        font-weight: 500;
        flex: 1;
        word-break: break-word;
    }
    
    .rating-display {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: rgba(251,191,36,0.1);
        border-radius: 10px;
        border: 1px solid rgba(251,191,36,0.2);
    }
    
    .rating-stars {
        font-size: 1.5rem;
        color: #fbbf24;
        letter-spacing: 2px;
    }
    
    .rating-number {
        color: #fbbf24;
        font-weight: 700;
        font-size: 1.1rem;
        margin-right: 0.5rem;
    }
    
    .review-text-card {
        background: linear-gradient(135deg, rgba(56,189,248,0.08) 0%, rgba(14,165,233,0.05) 100%);
        border: 1px solid rgba(56,189,248,0.2);
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 1rem;
        position: relative;
    }
    
    .review-text-card::before {
        content: '"';
        position: absolute;
        top: -10px;
        right: 20px;
        font-size: 4rem;
        color: rgba(56,189,248,0.2);
        font-family: serif;
        line-height: 1;
    }
    
    .review-text-label {
        color: #38bdf8;
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .review-text-content {
        color: #e2e8f0;
        font-size: 1rem;
        line-height: 1.8;
        font-style: italic;
    }
    
    .workshop-badge {
        display: inline-block;
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: #fff;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .user-badge {
        display: inline-block;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }
</style>
@endsection

@section('main')
<div class="reviews-container" dir="rtl">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-star"></i>
            إدارة التقييمات
        </h1>
        <p class="page-subtitle">عرض وإدارة تقييمات المستخدمين للورش</p>
    </div>

    <!-- Search Section -->
    <div class="search-export-section">
        <input type="text" 
               id="searchInput" 
               class="search-input" 
               placeholder="البحث بالاسم أو البريد الإلكتروني أو عنوان الورشة..."
               value="{{ request('search') }}"
               onkeyup="handleSearch(event)">
    </div>

    <!-- Reviews Table -->
    <div class="reviews-table">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>اسم المستخدم</th>
                        <th>عنوان الورشة</th>
                        <th>التقييم</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                    <tr>
                        <td>{{ $review->user->full_name ?? '-' }}</td>
                        <td>{{ $review->workshop->title ?? '-' }}</td>
                        <td>
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                @endfor
                            </div>
                        </td>
                        <td>{{ $review->created_at ? \App\Helpers\FormatArabicDates::formatArabicDate($review->created_at) : '-' }}</td>
                        <td>
                            <div class="action-buttons">
                                <button type="button" 
                                        class="btn-action btn-view" 
                                        onclick="openShowModal({{ $review->id }})"
                                        title="عرض التفاصيل">
                                    <i class="fa fa-eye"></i>
                                    عرض
                                </button>
                                <label class="toggle-label">{{ $review->is_active ? 'مفعل' : 'غير مفعل' }}</label>
                                <label class="toggle-switch">
                                    <input type="checkbox" {{ $review->is_active ? 'checked' : '' }}
                                        onchange="toggleReviewStatus({{ $review->id }}, this.checked)">
                                    <span class="toggle-slider"></span>
                                </label>
                                <button type="button" 
                                        class="btn-action btn-delete" 
                                        onclick="deleteReview({{ $review->id }})"
                                        title="حذف">
                                    <i class="fa fa-trash"></i>
                                    حذف
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fa fa-star"></i>
                                <h4>لا توجد تقييمات</h4>
                                <p>لم يتم العثور على تقييمات مطابقة للبحث</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($reviews->hasPages() || $reviews->total() > 0)
    <div class="pagination-wrapper">
        <div class="pagination-info">
            <span class="pagination-text">
                عرض 
                <strong>{{ $reviews->firstItem() ?? 0 }}</strong>
                إلى 
                <strong>{{ $reviews->lastItem() ?? 0 }}</strong>
                من أصل 
                <strong>{{ $reviews->total() }}</strong>
                تقييم
            </span>
        </div>
        
        @if($reviews->hasPages())
        <div class="pagination-container">
            <ul class="custom-pagination">
                {{-- Previous Page Link --}}
                @if ($reviews->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fa fa-chevron-right"></i>
                            السابق
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $reviews->appends(request()->except('page'))->previousPageUrl() }}" rel="prev">
                            <i class="fa fa-chevron-right"></i>
                            السابق
                        </a>
                    </li>
                @endif

                @php
                    $start = max(1, $reviews->currentPage() - 2);
                    $end = min($reviews->lastPage(), $reviews->currentPage() + 2);
                @endphp

                @if($start > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ $reviews->url(1) }}">1</a>
                    </li>
                    @if($start > 2)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                @endif

                @for($i = $start; $i <= $end; $i++)
                    <li class="page-item {{ $i == $reviews->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $reviews->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                @if($end < $reviews->lastPage())
                    @if($end < $reviews->lastPage() - 1)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link" href="{{ $reviews->url($reviews->lastPage()) }}">{{ $reviews->lastPage() }}</a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if ($reviews->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $reviews->appends(request()->except('page'))->nextPageUrl() }}" rel="next">
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

<!-- Show Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true" dir="rtl">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">
                    <i class="fas fa-star"></i>
                    تفاصيل التقييم
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reviewModalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function handleSearch(event) {
        if (event.key === 'Enter' || event.keyCode === 13) {
            const searchValue = event.target.value;
            const url = new URL(window.location.href);
            if (searchValue) {
                url.searchParams.set('search', searchValue);
            } else {
                url.searchParams.delete('search');
            }
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }
    }

    function openShowModal(reviewId) {
        fetch(`{{ route('admin.reviews.show', ':id') }}`.replace(':id', reviewId), {
            headers: {
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                const review = data.data;
                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    starsHtml += `<i class="fas fa-star${i <= review.rating ? '' : '-o'}"></i>`;
                }
                
                const modalContent = `
                    <!-- Rating Section -->
                    <div class="info-section">
                        <div class="info-section-title">
                            <i class="fas fa-star"></i>
                            التقييم
                        </div>
                        <div class="rating-display">
                            <span class="rating-number">${review.rating}/5</span>
                            <div class="rating-stars">${starsHtml}</div>
                        </div>
                    </div>

                    <!-- User Information Section -->
                    <div class="info-section">
                        <div class="info-section-title">
                            <i class="fas fa-user"></i>
                            معلومات المستخدم
                        </div>
                        <div class="info-card">
                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-user-circle"></i>
                                    الاسم:
                                </span>
                                <span class="info-value">
                                    <span class="user-badge">${review.user.full_name}</span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-envelope"></i>
                                    البريد الإلكتروني:
                                </span>
                                <span class="info-value">${review.user.email}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-phone"></i>
                                    رقم الهاتف:
                                </span>
                                <span class="info-value">${review.user.phone}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Workshop Information Section -->
                    <div class="info-section">
                        <div class="info-section-title">
                            <i class="fas fa-chalkboard-teacher"></i>
                            معلومات الورشة
                        </div>
                        <div class="info-card">
                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-book"></i>
                                    العنوان:
                                </span>
                                <span class="info-value">
                                    <span class="workshop-badge">${review.workshop.title}</span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-user-tie"></i>
                                    المدرب:
                                </span>
                                <span class="info-value">${review.workshop.teacher}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-tag"></i>
                                    النوع:
                                </span>
                                <span class="info-value">${review.workshop.type}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Review Comment Section -->
                    ${review.review && review.review !== '-' ? `
                    <div class="info-section">
                        <div class="info-section-title">
                            <i class="fas fa-comment-alt"></i>
                            التعليق
                        </div>
                        <div class="review-text-card">
                            <div class="review-text-label">
                                <i class="fas fa-quote-right"></i>
                                نص التقييم
                            </div>
                            <div class="review-text-content">${review.review}</div>
                        </div>
                    </div>
                    ` : ''}

                    <!-- Date Section -->
                    <div class="info-section">
                        <div class="info-section-title">
                            <i class="fas fa-calendar-alt"></i>
                            معلومات إضافية
                        </div>
                        <div class="info-card">
                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-clock"></i>
                                    تاريخ الإنشاء:
                                </span>
                                <span class="info-value">${review.created_at}</span>
                            </div>
                        </div>
                    </div>
                `;
                
                document.getElementById('reviewModalContent').innerHTML = modalContent;
                const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
                modal.show();
            } else {
                alert(data.msg || 'حدث خطأ أثناء تحميل التفاصيل');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تحميل التفاصيل');
        });
    }

    function toggleReviewStatus(reviewId, isActive) {
        const checkbox = event.target;
        const originalState = !isActive;

        fetch(`{{ route('admin.reviews.toggle', ':id') }}`.replace(':id', reviewId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const toggleLabel = checkbox.closest('.action-buttons').querySelector('.toggle-label');
                if (toggleLabel) {
                    toggleLabel.textContent = isActive ? 'مفعل' : 'غير مفعل';
                }
            } else {
                checkbox.checked = originalState;
                alert(data.msg || 'حدث خطأ أثناء تحديث حالة التقييم');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            checkbox.checked = originalState;
            alert('حدث خطأ أثناء تحديث حالة التقييم');
        });
    }

    function deleteReview(reviewId) {
        if (!confirm('هل أنت متأكد من حذف هذا التقييم؟')) {
            return;
        }

        fetch(`{{ route('admin.reviews.destroy', ':id') }}`.replace(':id', reviewId), {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.msg || 'حدث خطأ أثناء حذف التقييم');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حذف التقييم');
        });
    }
</script>
@endsection

