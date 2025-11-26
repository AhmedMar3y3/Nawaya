@extends('Admin.layout')

@section('styles')
<style>
    .users-container {
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
    
    .tabs-container {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border-radius: 15px;
        padding: 0;
        margin-bottom: 2rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        overflow: hidden;
    }
    
    .nav-tabs {
        border: none;
        background: rgba(255,255,255,0.05);
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
        background: rgba(255,255,255,0.1);
    }
    
    .nav-tabs .nav-link.active {
        background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
        color: #fff;
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
    
    .btn-create {
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
    }
    
    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .users-table {
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
        text-decoration: none;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }
    
    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
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
    
    .btn-restore {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .btn-restore:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        color: white;
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
    
    .form-control, .form-select {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #fff;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        direction: rtl;
        text-align: right;
    }
    
    .form-control:focus, .form-select:focus {
        background: rgba(255,255,255,0.08);
        border-color: #38bdf8;
        box-shadow: 0 0 0 0.2rem rgba(56,189,248,0.25);
        color: #fff;
        outline: none;
    }
    
    .form-control::placeholder {
        color: #64748b;
        text-align: right;
    }
    
    .form-select option {
        background: #1E293B;
        color: #fff;
        direction: rtl;
    }
    
    .form-label {
        color: #94a3b8;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.95rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
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
    
    /* Responsive Pagination */
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
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #94a3b8;
    }
    
    .workshop-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .workshop-item {
        padding: 0.75rem 1rem;
        background: rgba(255,255,255,0.05);
        border-radius: 10px;
        margin-bottom: 0.5rem;
        border-right: 3px solid #38bdf8;
        transition: all 0.3s ease;
    }
    
    .workshop-item:hover {
        background: rgba(255,255,255,0.08);
        transform: translateX(-5px);
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
</style>
@endsection

@section('main')
<div class="users-container" dir="rtl">
    <!-- Tabs -->
    <div class="tabs-container">
        <ul class="nav nav-tabs" id="usersTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $tab === 'active' ? 'active' : '' }}" 
                        id="active-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#active-users" 
                        type="button" 
                        role="tab"
                        onclick="switchTab('active')">
                    المستخدمين النشطين
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $tab === 'deleted' ? 'active' : '' }}" 
                        id="deleted-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#deleted-users" 
                        type="button" 
                        role="tab"
                        onclick="switchTab('deleted')">
                    المستخدمين المحذوفين
                </button>
            </li>
        </ul>
            </div>

    <!-- Search and Export Section -->
    <div class="search-export-section">
        <input type="text" 
               id="searchInput" 
               class="search-input" 
               placeholder="البحث بالاسم أو البريد الإلكتروني أو رقم الهاتف..."
               value="{{ request('search') }}"
               onkeyup="handleSearch(event)">
        <button type="button" class="btn-create" onclick="openCreateModal()">
                    <i class="fa fa-plus"></i>
                    إضافة مستخدم جديد
        </button>
        <a href="{{ route('admin.users.export.excel', ['tab' => $tab, 'search' => request('search')]) }}" class="btn-export">
            <i class="fa fa-file-excel"></i>
            تصدير Excel
        </a>
        <a href="{{ route('admin.users.export.pdf', ['tab' => $tab, 'search' => request('search')]) }}" class="btn-export">
            <i class="fa fa-file-pdf"></i>
            تصدير PDF
        </a>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="usersTabContent">
        <!-- Active Users Tab -->
        <div class="tab-pane fade {{ $tab === 'active' ? 'show active' : '' }}" 
             id="active-users" 
             role="tabpanel">
            @include('Admin.users.partials.users-table', ['users' => $users, 'isDeleted' => false])
        </div>

        <!-- Deleted Users Tab -->
        <div class="tab-pane fade {{ $tab === 'deleted' ? 'show active' : '' }}" 
             id="deleted-users" 
             role="tabpanel">
            @include('Admin.users.partials.users-table', ['users' => $users, 'isDeleted' => true])
            </div>
        </div>
    </div>

<!-- Main Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true" dir="rtl">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">معلومات المستخدم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <div class="modal-body" id="modalContent">
                <!-- Content will be loaded here -->
        </div>
        </div>
        </div>
    </div>

<!-- Modal Templates (Hidden) -->
<div style="display: none;">
    <!-- Create Modal Template -->
    <div id="createModalTemplate">
        <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm" dir="rtl">
            @csrf
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="full_name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           id="full_name" 
                           name="full_name" 
                           placeholder="أدخل الاسم الكامل"
                           required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email"
                           placeholder="example@email.com">
                </div>
                </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           id="phone" 
                           name="phone" 
                           placeholder="+966XXXXXXXXX"
                           required>
                </div>
                <div class="col-md-6">
                    <label for="country_id" class="form-label">الدولة</label>
                    <select class="form-select" id="country_id" name="country_id">
                        <option value="">اختر الدولة</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="is_active" class="form-label">الحالة</label>
                    <select class="form-select" id="is_active" name="is_active">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-2"></i>
                    حفظ
                </button>
                </div>
        </form>
                </div>
                
    <!-- Edit Modal Template -->
    <div id="editModalTemplate">
        <form id="editUserForm" method="POST" dir="rtl">
            @csrf
            @method('PUT')
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="edit_full_name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           id="edit_full_name" 
                           name="full_name" 
                           placeholder="أدخل الاسم الكامل"
                           required>
                </div>
                <div class="col-md-6">
                    <label for="edit_email" class="form-label">البريد الإلكتروني</label>
                    <input type="email" 
                           class="form-control" 
                           id="edit_email" 
                           name="email"
                           placeholder="example@email.com">
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="edit_phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           id="edit_phone" 
                           name="phone" 
                           placeholder="+966XXXXXXXXX"
                           required>
                </div>
                <div class="col-md-6">
                    <label for="edit_country_id" class="form-label">الدولة</label>
                    <select class="form-select" id="edit_country_id" name="country_id">
                        <option value="">اختر الدولة</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="edit_is_active" class="form-label">الحالة</label>
                    <select class="form-select" id="edit_is_active" name="is_active">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-2"></i>
                    حفظ
                </button>
            </div>
        </form>
    </div>

    <!-- Show Modal Template -->
    <div id="showModalTemplate">
        <div dir="rtl">
            <h5 class="mb-4" style="color: #38bdf8; font-weight: 700;">معلومات المستخدم</h5>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">الاسم الكامل</label>
                    <div class="info-box">
                        <p class="text-white mb-0" id="show_full_name"></p>
        </div>
    </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">البريد الإلكتروني</label>
                    <div class="info-box">
                        <p class="text-white mb-0" id="show_email"></p>
        </div>
        </div>
                                </div>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">رقم الهاتف</label>
                    <div class="info-box">
                        <p class="text-white mb-0" id="show_phone"></p>
                            </div>
                            </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">عدد الاشتراكات النشطة</label>
                    <div class="info-box">
                        <p class="text-white mb-0" id="show_subscriptions_count"></p>
                            </div>
                            </div>
                            </div>
            <div class="row mb-4" id="show_country_row" style="display: none;">
                <div class="col-md-6 mb-3">
                    <label class="form-label">الدولة</label>
                    <div class="info-box">
                        <p class="text-white mb-0" id="show_country"></p>
        </div>
    </div>
    </div>
            <div class="mb-4" id="show_workshops_section">
                <label class="form-label">ورش العمل المشترك بها</label>
                <ul class="workshop-list" id="show_workshops_list"></ul>
                <p class="text-white" id="show_no_workshops" style="display: none;">لا توجد اشتراكات نشطة</p>
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
let currentTab = '{{ $tab }}';

function switchTab(tab) {
    currentTab = tab;
    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    url.searchParams.delete('page');
    window.location.href = url.toString();
}

function handleSearch(event) {
    if (event.key === 'Enter') {
        const search = event.target.value;
        const url = new URL(window.location);
        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }
        url.searchParams.set('tab', currentTab);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
}

const baseUrl = '{{ url("users") }}';

function openCreateModal() {
    const template = document.getElementById('createModalTemplate');
    document.getElementById('modalContent').innerHTML = template.innerHTML;
    document.getElementById('userModalLabel').textContent = 'إضافة مستخدم جديد';
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();
}

function openShowModal(userId) {
    fetch(`${baseUrl}/${userId}/show`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const template = document.getElementById('showModalTemplate');
                const content = template.innerHTML;
                document.getElementById('modalContent').innerHTML = content;
                document.getElementById('userModalLabel').textContent = 'تفاصيل المستخدم';
                
                // Populate data
                const user = data.user;
                document.getElementById('show_full_name').textContent = user.full_name || '-';
                document.getElementById('show_email').textContent = user.email || '-';
                
                if (user.phone) {
                    const phoneHtml = `<a href="https://wa.me/${user.phone.replace(/\D/g, '')}" target="_blank" class="whatsapp-link"><i class="fab fa-whatsapp"></i> ${user.phone}</a>`;
                    document.getElementById('show_phone').innerHTML = phoneHtml;
    } else {
                    document.getElementById('show_phone').textContent = '-';
                }
                
                document.getElementById('show_subscriptions_count').textContent = user.active_subscriptions_count || 0;
                
                if (user.country) {
                    document.getElementById('show_country').textContent = user.country.name || '-';
                    document.getElementById('show_country_row').style.display = 'block';
                } else {
                    document.getElementById('show_country_row').style.display = 'none';
                }
                
                // Populate workshops
                const workshopsList = document.getElementById('show_workshops_list');
                const noWorkshops = document.getElementById('show_no_workshops');
                workshopsList.innerHTML = '';
                
                if (user.subscriptions && user.subscriptions.length > 0) {
                    user.subscriptions.forEach(subscription => {
                        if (subscription.workshop) {
                            const li = document.createElement('li');
                            li.className = 'workshop-item';
                            li.textContent = subscription.workshop.title;
                            workshopsList.appendChild(li);
                        }
                    });
                    if (workshopsList.children.length === 0) {
                        noWorkshops.style.display = 'block';
                    }
                } else {
                    noWorkshops.style.display = 'block';
                }
                
                const modal = new bootstrap.Modal(document.getElementById('userModal'));
                modal.show();
            }
        });
}

function openEditModal(userId) {
    fetch(`${baseUrl}/${userId}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const template = document.getElementById('editModalTemplate');
                const content = template.innerHTML;
                document.getElementById('modalContent').innerHTML = content;
                document.getElementById('userModalLabel').textContent = 'تعديل المستخدم';
                
                // Set form action and populate data
                const form = document.getElementById('editUserForm');
                form.action = `${baseUrl}/${userId}`;
                
                const user = data.user;
                document.getElementById('edit_full_name').value = user.full_name || '';
                document.getElementById('edit_email').value = user.email || '';
                document.getElementById('edit_phone').value = user.phone || '';
                document.getElementById('edit_country_id').value = user.country_id || '';
                document.getElementById('edit_is_active').value = user.is_active ? '1' : '0';
                
                const modal = new bootstrap.Modal(document.getElementById('userModal'));
                modal.show();
            }
        });
}

function deleteUser(userId) {
    if (confirm('هل أنت متأكد من حذف هذا المستخدم؟')) {
        fetch(`${baseUrl}/${userId}`, {
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
                alert(data.message || 'حدث خطأ أثناء حذف المستخدم');
            }
        });
    }
}

function restoreUser(userId) {
    if (confirm('هل أنت متأكد من استعادة هذا المستخدم؟')) {
        fetch(`${baseUrl}/${userId}/restore`, {
            method: 'POST',
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
                alert(data.message || 'حدث خطأ أثناء استعادة المستخدم');
            }
        });
    }
}

function permanentlyDeleteUser(userId) {
    if (confirm('هل أنت متأكد من الحذف النهائي لهذا المستخدم؟ لا يمكن التراجع عن هذا الإجراء!')) {
        fetch(`${baseUrl}/${userId}/permanent`, {
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
                alert(data.message || 'حدث خطأ أثناء حذف المستخدم');
            }
        });
    }
}

// Handle form submissions
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('userModal');
    if (modal) {
        modal.addEventListener('submit', function(e) {
            if (e.target.tagName === 'FORM') {
                e.preventDefault();
                const form = e.target;
                const formData = new FormData(form);
                const url = form.action;
                const method = form.method.toUpperCase();

                fetch(url, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(modal).hide();
                        location.reload();
                    } else {
                        alert(data.message || 'حدث خطأ');
                    }
                });
            }
        });
    }
});
</script>
@endsection
