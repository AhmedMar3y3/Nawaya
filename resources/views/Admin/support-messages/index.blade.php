@extends('Admin.layout')

@section('styles')
<style>
    .messages-container {
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
    
    .search-section {
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
        direction: rtl;
    }
    
    .search-input:focus {
        background: rgba(255,255,255,0.08);
        border-color: #38bdf8;
        box-shadow: 0 0 0 0.2rem rgba(56,189,248,0.25);
        color: #fff;
        outline: none;
    }
    
    .messages-table {
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
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .user-name {
        color: #fff;
        font-weight: 600;
        font-size: 1rem;
        margin: 0;
    }
    
    .user-email {
        color: #94a3b8;
        font-size: 0.9rem;
        margin: 0.25rem 0 0 0;
    }
    
    .message-preview {
        color: #94a3b8;
        font-size: 0.9rem;
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
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
        text-decoration: none;
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }
    
    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        color: white;
    }
    
    .modal-content {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 20px;
        color: #fff;
        direction: rtl;
        text-align: right;
    }
    
    .modal-header {
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding: 1.5rem 2rem;
        background: rgba(255,255,255,0.03);
        border-radius: 20px 20px 0 0;
    }
    
    .modal-title {
        color: #fff;
        font-weight: 700;
        font-size: 1.5rem;
    }
    
    .modal-body {
        padding: 2rem;
    }
    
    .modal-footer {
        border-top: 1px solid rgba(255,255,255,0.1);
        padding: 1.5rem 2rem;
        background: rgba(255,255,255,0.03);
        border-radius: 0 0 20px 20px;
    }
    
    .btn-close {
        filter: invert(1);
        opacity: 0.8;
    }
    
    .btn-close:hover {
        opacity: 1;
    }
    
    .form-label {
        color: #94a3b8;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.95rem;
    }
    
    .info-box {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        min-height: 45px;
        display: flex;
        align-items: center;
    }
    
    .message-content {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 1rem;
        min-height: 100px;
        color: #fff;
        line-height: 1.6;
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    
    .btn-secondary {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 10px;
        padding: 0.75rem 2rem;
        color: #94a3b8;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background: rgba(255,255,255,0.15);
        border-color: rgba(255,255,255,0.3);
        color: #fff;
    }
    
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border-radius: 15px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
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
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
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
        background: rgba(255,255,255,0.1);
        border-color: rgba(255,255,255,0.2);
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
        background: rgba(255,255,255,0.02);
        border-color: rgba(255,255,255,0.05);
        color: #64748b;
        cursor: not-allowed;
        opacity: 0.5;
    }
    
    .custom-pagination .page-item.disabled .page-link:hover {
        transform: none;
        background: rgba(255,255,255,0.02);
        border-color: rgba(255,255,255,0.05);
        color: #64748b;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #94a3b8;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .empty-state h4 {
        color: #fff;
        margin-bottom: 0.5rem;
    }
    
    .whatsapp-link {
        color: #25D366;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .whatsapp-link:hover {
        color: #128C7E;
        text-decoration: underline;
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
<div class="messages-container" dir="rtl">

    <!-- Search Section -->
    <div class="search-section">
        <input type="text" 
               id="searchInput" 
               class="search-input" 
               placeholder="البحث في الرسائل أو اسم المستخدم أو البريد الإلكتروني أو رقم الهاتف..."
               value="{{ request('search') }}"
               onkeyup="handleSearch(event)">
    </div>

    <!-- Messages Table -->
    <div class="messages-table">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>المستخدم</th>
                        <th>الرسالة</th>
                        <th>تاريخ الإرسال</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                    <tr>
                        <td>
                            <div class="user-info">
                                <div>
                                    <h6 class="user-name">{{ $message->user->full_name ?? '-' }}</h6>
                                    <p class="user-email">{{ $message->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="message-preview" title="{{ $message->message }}">
                                {{ Str::limit($message->message, 100) }}
                            </div>
                        </td>
                        <td>{{ $message->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="action-buttons">
                                <button type="button" 
                                        class="btn-action btn-view" 
                                        onclick="openShowModal({{ $message->id }})"
                                        title="عرض التفاصيل">
                                    <i class="fa fa-eye"></i>
                                    عرض
                                </button>
                                <button type="button" 
                                        class="btn-action btn-delete" 
                                        onclick="deleteMessage({{ $message->id }})"
                                        title="حذف">
                                    <i class="fa fa-trash"></i>
                                    حذف
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="fa fa-envelope"></i>
                                <h4>لا توجد رسائل</h4>
                                <p>لم يتم العثور على رسائل مطابقة للبحث</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Info and Controls -->
    @if($messages->hasPages() || $messages->total() > 0)
    <div class="pagination-wrapper">
        <div class="pagination-info">
            <span class="pagination-text">
                عرض 
                <strong>{{ $messages->firstItem() ?? 0 }}</strong>
                إلى 
                <strong>{{ $messages->lastItem() ?? 0 }}</strong>
                من أصل 
                <strong>{{ $messages->total() }}</strong>
                رسالة
            </span>
        </div>
        
        @if($messages->hasPages())
        <div class="pagination-container">
            <ul class="custom-pagination">
                {{-- Previous Page Link --}}
                @if ($messages->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fa fa-chevron-right"></i>
                            السابق
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $messages->appends(request()->except('page'))->previousPageUrl() }}" rel="prev">
                            <i class="fa fa-chevron-right"></i>
                            السابق
                        </a>
                    </li>
                @endif

                @php
                    $currentPage = $messages->currentPage();
                    $lastPage = $messages->lastPage();
                    $pages = [];
                    
                    if ($lastPage <= 7) {
                        for ($i = 1; $i <= $lastPage; $i++) {
                            $pages[] = $i;
                        }
                    } else {
                        $pages[] = 1;
                        
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
                            for ($i = max(2, $currentPage - 1); $i <= min($lastPage - 1, $currentPage + 2); $i++) {
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
                            <a class="page-link" href="{{ $messages->appends(request()->except('page'))->url($page) }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($messages->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $messages->appends(request()->except('page'))->nextPageUrl() }}" rel="next">
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

<!-- Main Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true" dir="rtl">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">تفاصيل الرسالة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Show Modal Template (Hidden) -->
<div style="display: none;">
    <div id="showModalTemplate">
        <div dir="rtl">
            <h5 class="mb-4" style="color: #38bdf8; font-weight: 700;">معلومات المستخدم</h5>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">الاسم الكامل</label>
                    <div class="info-box">
                        <p class="text-white mb-0" id="show_user_name"></p>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">البريد الإلكتروني</label>
                    <div class="info-box">
                        <p class="text-white mb-0" id="show_user_email"></p>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">رقم الهاتف</label>
                    <div class="info-box">
                        <p class="text-white mb-0" id="show_user_phone"></p>
                    </div>
                </div>
                <div class="col-md-6 mb-3" id="show_country_row" style="display: none;">
                    <label class="form-label">الدولة</label>
                    <div class="info-box">
                        <p class="text-white mb-0" id="show_user_country"></p>
                    </div>
                </div>
            </div>
            
            <h5 class="mb-4 mt-4" style="color: #38bdf8; font-weight: 700;">محتوى الرسالة</h5>
            <div class="mb-4">
                <div class="message-content" id="show_message_content"></div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">تاريخ الإرسال</label>
                <div class="info-box">
                    <p class="text-white mb-0" id="show_created_at"></p>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const baseUrl = '{{ url("support-messages") }}';

function handleSearch(event) {
    if (event.key === 'Enter') {
        const search = event.target.value;
        const url = new URL(window.location);
        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
}

function openShowModal(messageId) {
    fetch(`${baseUrl}/${messageId}/show`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const template = document.getElementById('showModalTemplate');
                const content = template.innerHTML;
                document.getElementById('modalContent').innerHTML = content;
                document.getElementById('messageModalLabel').textContent = 'تفاصيل الرسالة';
                
                // Populate user data
                const user = data.message.user;
                document.getElementById('show_user_name').textContent = user.full_name || '-';
                document.getElementById('show_user_email').textContent = user.email || '-';
                
                if (user.phone) {
                    const phoneHtml = `<a href="https://wa.me/${user.phone.replace(/\D/g, '')}" target="_blank" class="whatsapp-link"><i class="fab fa-whatsapp"></i> ${user.phone}</a>`;
                    document.getElementById('show_user_phone').innerHTML = phoneHtml;
                } else {
                    document.getElementById('show_user_phone').textContent = '-';
                }
                
                if (user.country) {
                    document.getElementById('show_user_country').textContent = user.country.name || '-';
                    document.getElementById('show_country_row').style.display = 'block';
                } else {
                    document.getElementById('show_country_row').style.display = 'none';
                }
                
                // Populate message
                document.getElementById('show_message_content').textContent = data.message.message || '-';
                document.getElementById('show_created_at').textContent = data.message.created_at || '-';
                
                const modal = new bootstrap.Modal(document.getElementById('messageModal'));
                modal.show();
            }
        });
}

function deleteMessage(messageId) {
    if (confirm('هل أنت متأكد من حذف هذه الرسالة؟')) {
        fetch(`${baseUrl}/${messageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'حدث خطأ أثناء حذف الرسالة');
            }
        });
    }
}
</script>
@endsection




