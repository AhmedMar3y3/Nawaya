@extends('Admin.layout')

@section('styles')
    <!-- CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
    <style>
        .workshops-container {
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

        .search-export-section {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 250px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: #fff;
            padding: 0.75rem 1rem;
        }

        .search-input:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #38bdf8;
            box-shadow: 0 0 0 0.2rem rgba(56, 189, 248, 0.25);
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
            cursor: pointer;
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
            color: white;
            text-decoration: none;
        }

        .workshops-table {
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
            padding: 1.5rem 1rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.02);
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
        }

        .btn-edit {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-restore {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .btn-restore:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
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

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #475569;
        }

        .empty-state h4 {
            color: #fff;
            margin: 1rem 0;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .pagination-info {
            color: #94a3b8;
        }

        .pagination-container {
            display: flex;
            gap: 0.5rem;
        }

        .custom-pagination {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 0.5rem;
        }

        .page-item {
            margin: 0;
        }

        .page-link {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .page-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
            color: #fff;
            border-color: #38bdf8;
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
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

        .form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 8px;
            padding: 0.75rem;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #38bdf8;
            box-shadow: 0 0 0 0.2rem rgba(56, 189, 248, 0.25);
            color: #fff;
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .dynamic-section {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .dynamic-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            position: relative;
        }

        .btn-remove-item {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            background: #ef4444;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .btn-add-item {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            color: white;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.5rem;
        }

        .info-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 0.75rem;
            min-height: 2.5rem;
        }

        .workshop-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .workshop-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .dynamic-section-header {
            border-bottom: 2px solid rgba(56, 189, 248, 0.3);
            padding-bottom: 0.5rem;
        }

        .file-upload-wrapper {
            position: relative;
        }

        .file-input {
            opacity: 0;
            position: absolute;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
        }

        .file-upload-placeholder {
            background: rgba(255, 255, 255, 0.05);
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            color: #94a3b8;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .file-upload-wrapper:hover .file-upload-placeholder {
            background: rgba(255, 255, 255, 0.08);
            border-color: #38bdf8;
            color: #fff;
        }

        .file-input:focus+.file-upload-placeholder {
            border-color: #38bdf8;
            box-shadow: 0 0 0 0.2rem rgba(56, 189, 248, 0.25);
        }

        .ck-editor__editable {
            min-height: 200px;
            background: rgba(255, 255, 255, 0.05) !important;
            color: #fff !important;
        }

        .ck.ck-toolbar {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        .ck.ck-button {
            color: #fff !important;
        }
    </style>
@endsection

@section('main')
    <div class="workshops-container" dir="rtl">
        <!-- Tabs -->
        <div class="tabs-container">
            <ul class="nav nav-tabs" id="workshopsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tab === 'active' ? 'active' : '' }}" id="active-tab" data-bs-toggle="tab"
                        data-bs-target="#active-workshops" type="button" role="tab" onclick="switchTab('active')">
                        الورش النشطة
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tab === 'deleted' ? 'active' : '' }}" id="deleted-tab" data-bs-toggle="tab"
                        data-bs-target="#deleted-workshops" type="button" role="tab" onclick="switchTab('deleted')">
                        الورش المحذوفة
                    </button>
                </li>
            </ul>
        </div>

        <!-- Search and Export Section -->
        <div class="search-export-section">
            <input type="text" id="searchInput" class="search-input" placeholder="البحث بالعنوان أو المدرب..."
                value="{{ request('search') }}" onkeyup="handleSearch(event)">
            <button type="button" class="btn-create" onclick="openCreateModal()">
                <i class="fa fa-plus"></i>
                إضافة ورشة جديدة
            </button>
            <a href="{{ route('admin.workshops.export.excel', ['tab' => $tab, 'search' => request('search')]) }}"
                class="btn-export">
                <i class="fa fa-file-excel"></i>
                تصدير Excel
            </a>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="workshopsTabContent">
            <!-- Active Workshops Tab -->
            <div class="tab-pane fade {{ $tab === 'active' ? 'show active' : '' }}" id="active-workshops" role="tabpanel">
                @include('Admin.workshops.partials.workshops-table', [
                    'workshops' => $workshops,
                    'isDeleted' => false,
                ])
            </div>

            <!-- Deleted Workshops Tab -->
            <div class="tab-pane fade {{ $tab === 'deleted' ? 'show active' : '' }}" id="deleted-workshops" role="tabpanel">
                @include('Admin.workshops.partials.workshops-table', [
                    'workshops' => $workshops,
                    'isDeleted' => true,
                ])
            </div>
        </div>
    </div>

    <!-- Main Modal -->
    <div class="modal fade" id="workshopModal" tabindex="-1" aria-labelledby="workshopModalLabel" aria-hidden="true"
        dir="rtl">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="workshopModalLabel">معلومات الورشة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Templates (Hidden) - Will be included in a separate partial for better organization -->
    @include('Admin.workshops.partials.modal-templates', ['countries' => $countries])
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

        const baseUrl = '{{ route("admin.workshops.index") }}';

        function openCreateModal() {
            const template = document.getElementById('createModalTemplate');
            document.getElementById('modalContent').innerHTML = template.innerHTML;
            document.getElementById('workshopModalLabel').textContent = 'إضافة ورشة جديدة';
            const modal = new bootstrap.Modal(document.getElementById('workshopModal'));
            modal.show();

            // Wait for modal to be fully shown before initializing
            setTimeout(() => {
                initializeDynamicSections();
                // Add initial items
                addPackageItem();
                // Setup form submission
                setupFormSubmission('createWorkshopForm', '{{ route("admin.workshops.store") }}');

                // Attach event delegation for add buttons
                const modalElement = document.getElementById('workshopModal');
                if (modalElement) {
                    modalElement.removeEventListener('click', handleAddButtonClick);
                    modalElement.addEventListener('click', handleAddButtonClick);
                }
            }, 300);
        }

        function openShowModal(workshopId) {
            fetch(`${baseUrl}/${workshopId}/show`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(async response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        const text = await response.text();
                        console.error('Non-JSON response:', text);
                        throw new Error('الخادم أرجَع استجابة غير صحيحة');
                    }
                })
                .then(data => {
                    if (data.success) {
                        const template = document.getElementById('showModalTemplate');
                        const content = template.innerHTML;
                        document.getElementById('modalContent').innerHTML = content;
                        document.getElementById('workshopModalLabel').textContent = 'تفاصيل الورشة';

                        const workshop = data.workshop;
                        populateShowModal(workshop);

                        const modal = new bootstrap.Modal(document.getElementById('workshopModal'));
                        modal.show();
                    } else {
                        alert(data.message || 'حدث خطأ أثناء جلب بيانات الورشة');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء جلب بيانات الورشة: ' + error.message);
                });
        }

        function openEditModal(workshopId) {
            console.log('Opening edit modal for workshop:', workshopId);
            fetch(`${baseUrl}/${workshopId}/edit`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(async response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        const text = await response.text();
                        console.error('Non-JSON response:', text);
                        throw new Error('الخادم أرجَع استجابة غير صحيحة');
                    }
                })
                .then(data => {
                    if (data.success) {
                        const template = document.getElementById('editModalTemplate');
                        const content = template.innerHTML;
                        document.getElementById('modalContent').innerHTML = content;
                        document.getElementById('workshopModalLabel').textContent = 'تعديل الورشة';

                        const form = document.getElementById('editWorkshopForm');
                        form.action = `${baseUrl}/${workshopId}`;

                        const workshop = data.workshop;

                        const modal = new bootstrap.Modal(document.getElementById('workshopModal'));
                        modal.show();

                        // Wait for modal to be fully shown before initializing
                        setTimeout(() => {
                            initializeDynamicSections();
                            populateEditForm(workshop);
                            // Setup form submission
                            setupFormSubmission('editWorkshopForm', `${baseUrl}/${workshopId}`);

                            // Attach event listeners to all add buttons using data-action attributes
                            const addButtons = document.querySelectorAll('.btn-add-item[data-action]');
                            console.log('Found add buttons:', addButtons.length);
                            addButtons.forEach(btn => {
                                const action = btn.getAttribute('data-action');
                                console.log('Attaching listener to button with action:', action);
                                btn.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    console.log('Button clicked, action:', action);

                                    switch (action) {
                                        case 'add-package':
                                            console.log('Calling addPackageItem');
                                            addPackageItem();
                                            break;
                                        case 'add-attachment':
                                            console.log('Calling addAttachmentItem');
                                            addAttachmentItem();
                                            break;
                                        case 'add-file':
                                            console.log('Calling addFileItem');
                                            addFileItem();
                                            break;
                                        case 'add-recording':
                                            console.log('Calling addRecordingItem');
                                            addRecordingItem();
                                            break;
                                        default:
                                            console.error('Unknown action:', action);
                                    }
                                });
                            });

                            // Also ensure functions are globally accessible
                            window.addPackageItem = addPackageItem;
                            window.addAttachmentItem = addAttachmentItem;
                            window.addFileItem = addFileItem;
                            window.addRecordingItem = addRecordingItem;
                        }, 500);
                    } else {
                        alert(data.message || 'حدث خطأ أثناء جلب بيانات الورشة');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء جلب بيانات الورشة: ' + error.message);
                });
        }

        function deleteWorkshop(workshopId) {
            if (confirm('هل أنت متأكد من حذف هذه الورشة؟')) {
                fetch(`${baseUrl}/${workshopId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            window.location.reload();
                        } else {
                            alert(data.message);
                        }
                    });
            }
        }

        function restoreWorkshop(workshopId) {
            if (confirm('هل أنت متأكد من استعادة هذه الورشة؟')) {
                fetch(`${baseUrl}/${workshopId}/restore`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            window.location.reload();
                        } else {
                            alert(data.message);
                        }
                    });
            }
        }

        function permanentlyDeleteWorkshop(workshopId) {
            if (confirm('هل أنت متأكد من الحذف النهائي لهذه الورشة؟ لا يمكن التراجع عن هذا الإجراء.')) {
                fetch(`${baseUrl}/${workshopId}/permanent`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            window.location.reload();
                        } else {
                            alert(data.message);
                        }
                    });
            }
        }

        function toggleWorkshopStatus(workshopId) {
            fetch(`${baseUrl}/${workshopId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Status updated successfully
                    } else {
                        alert(data.message);
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.location.reload();
                });
        }

        // CKEditor instances
        let subjectEditor = null;
        let editSubjectEditor = null;

        // Dynamic form handling functions
        function initializeDynamicSections() {
            // Initialize CKEditor for subject_of_discussion
            initializeCKEditor();

            // Handle type change
            const typeSelect = document.getElementById('type') || document.getElementById('edit_type');
            if (typeSelect) {
                typeSelect.addEventListener('change', handleTypeChange);
                handleTypeChange({
                    target: typeSelect
                });
            }
        }

        function initializeCKEditor() {
            // Initialize for create form
            const subjectTextarea = document.getElementById('subject_of_discussion');
            if (subjectTextarea && typeof ClassicEditor !== 'undefined') {
                ClassicEditor.create(subjectTextarea, {
                    language: 'ar',
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList',
                        'blockQuote', 'insertTable', '|', 'undo', 'redo'
                    ]
                }).then(editor => {
                    subjectEditor = editor;
                    // Remove required attribute from textarea since CKEditor handles it
                    subjectTextarea.removeAttribute('required');
                }).catch(error => {
                    console.error('Error initializing CKEditor:', error);
                });
            }

            // Initialize for edit form
            const editSubjectTextarea = document.getElementById('edit_subject_of_discussion');
            if (editSubjectTextarea && typeof ClassicEditor !== 'undefined') {
                ClassicEditor.create(editSubjectTextarea, {
                    language: 'ar',
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList',
                        'blockQuote', 'insertTable', '|', 'undo', 'redo'
                    ]
                }).then(editor => {
                    editSubjectEditor = editor;
                    // Remove required attribute from textarea since CKEditor handles it
                    editSubjectTextarea.removeAttribute('required');
                }).catch(error => {
                    console.error('Error initializing CKEditor:', error);
                });
            }
        }

        function handleTypeChange(event) {
            const type = event.target.value;
            const isEdit = event.target.id.includes('edit');
            const prefix = isEdit ? 'edit_' : '';

            // Get the type-specific section
            const typeSection = document.getElementById(prefix + 'type_specific_section');
            const datetimeSection = document.getElementById(prefix + 'datetime_section');
            const zoomSection = document.getElementById(prefix + 'zoom_section');
            const locationSection = document.getElementById(prefix + 'location_section');
            const recordingsSection = document.getElementById(prefix + 'recordings_section') || document.getElementById(
                prefix + 'recordings_section');

            // Hide all sections first
            if (typeSection) typeSection.style.display = 'none';
            if (datetimeSection) datetimeSection.style.display = 'none';
            if (zoomSection) zoomSection.style.display = 'none';
            if (locationSection) locationSection.style.display = 'none';
            if (recordingsSection) recordingsSection.style.display = 'none';

            // Show relevant sections based on type
            if (type === 'online') {
                if (typeSection) typeSection.style.display = 'block';
                if (datetimeSection) datetimeSection.style.display = 'block';
                if (zoomSection) zoomSection.style.display = 'block';
            } else if (type === 'onsite') {
                if (typeSection) typeSection.style.display = 'block';
                if (datetimeSection) datetimeSection.style.display = 'block';
                if (locationSection) locationSection.style.display = 'block';
            } else if (type === 'online_onsite') {
                if (typeSection) typeSection.style.display = 'block';
                if (datetimeSection) datetimeSection.style.display = 'block';
                if (zoomSection) zoomSection.style.display = 'block';
                if (locationSection) locationSection.style.display = 'block';
            } else if (type === 'recorded') {
                if (recordingsSection) recordingsSection.style.display = 'block';
            }

            // Handle packages section - limit to 1 for recorded type
            const packagesContainer = document.getElementById(prefix + 'packages_container') || document.getElementById(
                'packages_container');
            if (packagesContainer && type === 'recorded') {
                // Remove extra packages if more than 1
                while (packagesContainer.children.length > 1) {
                    packagesContainer.removeChild(packagesContainer.lastChild);
                }
            }
        }

        function populateShowModal(workshop) {
            // Basic information
            document.getElementById('show_title').textContent = workshop.title || '-';
            document.getElementById('show_teacher').textContent = workshop.teacher || '-';
            document.getElementById('show_teacher_percentage').textContent = workshop.teacher_percentage || '-';
            document.getElementById('show_description').textContent = workshop.description || '-';
            document.getElementById('show_subject_of_discussion').innerHTML = workshop.subject_of_discussion || '-';
            document.getElementById('show_type').textContent = getTypeLabel(workshop.type);
            document.getElementById('show_is_active').textContent = workshop.is_active ? 'نشط' : 'غير نشط';
            document.getElementById('show_subscribers_count').textContent = workshop.subscribers_count || 0;

            // Get type-specific sections
            const typeSection = document.getElementById('show_type_specific_section');
            const datetimeSection = document.getElementById('show_datetime_section');
            const zoomSection = document.getElementById('show_zoom_section');
            const locationSection = document.getElementById('show_location_section');
            const recordingsSection = document.getElementById('show_recordings_section');

            // Hide all sections first
            if (typeSection) typeSection.style.display = 'none';
            if (datetimeSection) datetimeSection.style.display = 'none';
            if (zoomSection) zoomSection.style.display = 'none';
            if (locationSection) locationSection.style.display = 'none';
            if (recordingsSection) recordingsSection.style.display = 'none';

            // Show and populate type-specific fields based on workshop type
            const type = workshop.type;

            if (type === 'online') {
                if (typeSection) typeSection.style.display = 'block';
                if (datetimeSection) datetimeSection.style.display = 'block';
                if (zoomSection) zoomSection.style.display = 'block';

                // Date and time
                document.getElementById('show_start_date').textContent = workshop.start_date || '-';
                document.getElementById('show_start_time').textContent = workshop.start_time || '-';
                document.getElementById('show_end_date').textContent = workshop.end_date || '-';
                document.getElementById('show_end_time').textContent = workshop.end_time || '-';

                // Online link
                const onlineLinkEl = document.getElementById('show_online_link');
                if (workshop.online_link) {
                    onlineLinkEl.href = workshop.online_link;
                    onlineLinkEl.textContent = workshop.online_link;
                } else {
                    onlineLinkEl.textContent = '-';
                }
            } else if (type === 'onsite') {
                if (typeSection) typeSection.style.display = 'block';
                if (datetimeSection) datetimeSection.style.display = 'block';
                if (locationSection) locationSection.style.display = 'block';

                // Date and time
                document.getElementById('show_start_date').textContent = workshop.start_date || '-';
                document.getElementById('show_start_time').textContent = workshop.start_time || '-';
                document.getElementById('show_end_date').textContent = workshop.end_date || '-';
                document.getElementById('show_end_time').textContent = workshop.end_time || '-';

                // Location
                document.getElementById('show_city').textContent = workshop.city || '-';
                document.getElementById('show_country').textContent = workshop.country ? workshop.country.name : '-';
                document.getElementById('show_hotel').textContent = workshop.hotel || '-';
                document.getElementById('show_hall').textContent = workshop.hall || '-';
            } else if (type === 'online_onsite') {
                if (typeSection) typeSection.style.display = 'block';
                if (datetimeSection) datetimeSection.style.display = 'block';
                if (zoomSection) zoomSection.style.display = 'block';
                if (locationSection) locationSection.style.display = 'block';

                // Date and time
                document.getElementById('show_start_date').textContent = workshop.start_date || '-';
                document.getElementById('show_start_time').textContent = workshop.start_time || '-';
                document.getElementById('show_end_date').textContent = workshop.end_date || '-';
                document.getElementById('show_end_time').textContent = workshop.end_time || '-';

                // Online link
                const onlineLinkEl = document.getElementById('show_online_link');
                if (workshop.online_link) {
                    onlineLinkEl.href = workshop.online_link;
                    onlineLinkEl.textContent = workshop.online_link;
                } else {
                    onlineLinkEl.textContent = '-';
                }

                // Location
                document.getElementById('show_city').textContent = workshop.city || '-';
                document.getElementById('show_country').textContent = workshop.country ? workshop.country.name : '-';
                document.getElementById('show_hotel').textContent = workshop.hotel || '-';
                document.getElementById('show_hall').textContent = workshop.hall || '-';
            } else if (type === 'recorded') {
                if (recordingsSection) recordingsSection.style.display = 'block';
            }

            // Populate packages
            const showPackagesContainer = document.getElementById('show_packages_container');
            if (showPackagesContainer) {
                showPackagesContainer.innerHTML = '';
                if (workshop.packages && workshop.packages.length > 0) {
                    workshop.packages.forEach((pkg) => {
                        const item = document.createElement('div');
                        item.className = 'mb-3 p-3 border rounded';
                        item.innerHTML = `
                    <h6>${pkg.title || '-'}</h6>
                    <p class="mb-1"><strong>السعر:</strong> ${pkg.price || '-'}</p>
                    ${pkg.is_offer ? `<p class="mb-1"><strong>سعر العرض:</strong> ${pkg.offer_price || '-'}</p>` : ''}
                    ${pkg.offer_expiry_date ? `<p class="mb-1"><strong>تاريخ انتهاء العرض:</strong> ${pkg.offer_expiry_date}</p>` : ''}
                    ${pkg.features ? `<p class="mb-0"><strong>المميزات:</strong> ${pkg.features}</p>` : ''}
                `;
                        showPackagesContainer.appendChild(item);
                    });
                } else {
                    showPackagesContainer.innerHTML = '<p class="text-muted">لا توجد حزم</p>';
                }
            }

            // Populate attachments
            const showAttachmentsContainer = document.getElementById('show_attachments_container');
            if (showAttachmentsContainer) {
                showAttachmentsContainer.innerHTML = '';
                if (workshop.attachments && workshop.attachments.length > 0) {
                    workshop.attachments.forEach((att) => {
                        const item = document.createElement('div');
                        item.className = 'mb-3 p-3 border rounded';
                        item.innerHTML = `
                    <h6>${att.title || '-'}</h6>
                    <p class="mb-1"><strong>النوع:</strong> ${att.type === 'audio' ? 'ملف صوتي' : 'ملف فيديو'}</p>
                    ${att.file ? `<p class="mb-1"><strong>الملف:</strong> ${att.file}</p>` : ''}
                    ${att.notes ? `<p class="mb-0"><strong>ملاحظات:</strong> ${att.notes}</p>` : ''}
                `;
                        showAttachmentsContainer.appendChild(item);
                    });
                } else {
                    showAttachmentsContainer.innerHTML = '<p class="text-muted">لا توجد مرفقات</p>';
                }
            }

            // Populate files
            const showFilesContainer = document.getElementById('show_files_container');
            if (showFilesContainer) {
                showFilesContainer.innerHTML = '';
                if (workshop.files && workshop.files.length > 0) {
                    workshop.files.forEach((file) => {
                        const item = document.createElement('div');
                        item.className = 'mb-3 p-3 border rounded';
                        item.innerHTML = `
                    <h6>${file.title || '-'}</h6>
                    ${file.file ? `<p class="mb-0"><strong>الملف:</strong> ${file.file}</p>` : ''}
                `;
                        showFilesContainer.appendChild(item);
                    });
                } else {
                    showFilesContainer.innerHTML = '<p class="text-muted">لا توجد ملفات</p>';
                }
            }

            // Populate recordings
            const showRecordingsContainer = document.getElementById('show_recordings_container');
            if (showRecordingsContainer) {
                showRecordingsContainer.innerHTML = '';
                if (workshop.recordings && workshop.recordings.length > 0) {
                    workshop.recordings.forEach((rec) => {
                        const item = document.createElement('div');
                        item.className = 'mb-3 p-3 border rounded';
                        item.innerHTML = `
                    <h6>${rec.title || '-'}</h6>
                    ${rec.link ? `<p class="mb-0"><strong>الرابط:</strong> <a href="${rec.link}" target="_blank">${rec.link}</a></p>` : ''}
                `;
                        showRecordingsContainer.appendChild(item);
                    });
                } else {
                    showRecordingsContainer.innerHTML = '<p class="text-muted">لا توجد تسجيلات</p>';
                }
            }
        }

        function populateEditForm(workshop) {
            document.getElementById('edit_title').value = workshop.title || '';
            document.getElementById('edit_teacher').value = workshop.teacher || '';
            document.getElementById('edit_teacher_percentage').value = workshop.teacher_percentage || '';
            document.getElementById('edit_description').value = workshop.description || '';

            // Set type and trigger change to show/hide relevant fields
            const typeSelect = document.getElementById('edit_type');
            if (typeSelect) {
                typeSelect.value = workshop.type || '';
                handleTypeChange({
                    target: typeSelect
                });
            }

            // Set CKEditor content
            setTimeout(() => {
                if (editSubjectEditor) {
                    editSubjectEditor.setData(workshop.subject_of_discussion || '');
                } else {
                    document.getElementById('edit_subject_of_discussion').value = workshop.subject_of_discussion ||
                        '';
                }
            }, 500);

            // Set type-specific fields
            if (workshop.start_date) {
                const startDateEl = document.getElementById('edit_start_date');
                if (startDateEl) startDateEl.value = workshop.start_date;
            }
            if (workshop.end_date) {
                const endDateEl = document.getElementById('edit_end_date');
                if (endDateEl) endDateEl.value = workshop.end_date;
            }
            if (workshop.start_time) {
                const startTimeEl = document.getElementById('edit_start_time');
                if (startTimeEl) startTimeEl.value = workshop.start_time;
            }
            if (workshop.end_time) {
                const endTimeEl = document.getElementById('edit_end_time');
                if (endTimeEl) endTimeEl.value = workshop.end_time;
            }
            if (workshop.online_link) {
                const onlineLinkEl = document.getElementById('edit_online_link');
                if (onlineLinkEl) onlineLinkEl.value = workshop.online_link;
            }
            if (workshop.city) {
                const cityEl = document.getElementById('edit_city');
                if (cityEl) cityEl.value = workshop.city;
            }
            if (workshop.country_id) {
                const countryEl = document.getElementById('edit_country_id');
                if (countryEl) countryEl.value = workshop.country_id;
            }
            if (workshop.hotel) {
                const hotelEl = document.getElementById('edit_hotel');
                if (hotelEl) hotelEl.value = workshop.hotel;
            }
            if (workshop.hall) {
                const hallEl = document.getElementById('edit_hall');
                if (hallEl) hallEl.value = workshop.hall;
            }

            // Populate packages, attachments, files, recordings
            // داخل populateEditForm
            const packagesContainer = document.getElementById('edit_packages_container');
            if (packagesContainer) {
                // ← الحل السحري: ما تمسحش بالـ innerHTML
                while (packagesContainer.firstChild) {
                    packagesContainer.removeChild(packagesContainer.firstChild);
                }

                if (workshop.packages && workshop.packages.length > 0) {
                    workshop.packages.forEach((pkg, index) => {
                        const item = createPackageItem(index, pkg, true);
                        packagesContainer.appendChild(item);
                    });
                } else {
                    addPackageItem(); // أو خلّيه فاضي، حسب رغبتك
                }
            }

            // Populate attachments
            const attachmentsContainer = document.getElementById('edit_attachments_container');
            if (attachmentsContainer) {
                while (attachmentsContainer.firstChild) {
                    attachmentsContainer.removeChild(attachmentsContainer.firstChild);
                }
                if (workshop.attachments && workshop.attachments.length > 0) {
                    workshop.attachments.forEach((att, index) => {
                        const item = createAttachmentItem(index, att, true);
                        attachmentsContainer.appendChild(item);
                    });
                }
            }

            // Files
            const filesContainer = document.getElementById('edit_files_container');
            if (filesContainer) {
                while (filesContainer.firstChild) {
                    filesContainer.removeChild(filesContainer.firstChild);
                }
                if (workshop.files && workshop.files.length > 0) {
                    workshop.files.forEach((file, index) => {
                        const item = createFileItem(index, file, true);
                        filesContainer.appendChild(item);
                    });
                }
            }

            // Recordings
            const recordingsContainer = document.getElementById('edit_recordings_container');
            if (recordingsContainer && workshop.type === 'recorded') {
                while (recordingsContainer.firstChild) {
                    recordingsContainer.removeChild(recordingsContainer.firstChild);
                }
                if (workshop.recordings && workshop.recordings.length > 0) {
                    workshop.recordings.forEach((rec, index) => {
                        const item = createRecordingItem(index, rec, true);
                        recordingsContainer.appendChild(item);
                    });
                } else {
                    addRecordingItem();
                }
            }
        }

        function getTypeLabel(type) {
            const types = {
                'online': 'أونلاين',
                'onsite': 'حضوري',
                'online_onsite': 'أونلاين و حضوري',
                'recorded': 'مسجلة'
            };
            return types[type] || type;
        }

        // Event delegation handler for add buttons
        function handleAddButtonClick(event) {
            const target = event.target.closest('.btn-add-item');
            if (!target) return;

            // Prevent default and stop propagation
            event.preventDefault();
            event.stopPropagation();

            // Check for data-action attribute first (preferred method)
            const action = target.getAttribute('data-action');
            if (action) {
                switch (action) {
                    case 'add-package':
                        if (typeof addPackageItem === 'function') {
                            addPackageItem();
                        } else {
                            console.error('addPackageItem function not found');
                        }
                        return;
                    case 'add-attachment':
                        if (typeof addAttachmentItem === 'function') {
                            addAttachmentItem();
                        } else {
                            console.error('addAttachmentItem function not found');
                        }
                        return;
                    case 'add-file':
                        if (typeof addFileItem === 'function') {
                            addFileItem();
                        } else {
                            console.error('addFileItem function not found');
                        }
                        return;
                    case 'add-recording':
                        if (typeof addRecordingItem === 'function') {
                            addRecordingItem();
                        } else {
                            console.error('addRecordingItem function not found');
                        }
                        return;
                }
            }

            // Fallback: Check which button was clicked based on the container it's next to
            const container = target.previousElementSibling;
            if (!container) return;

            const containerId = container.id;

            if (containerId === 'packages_container' || containerId === 'edit_packages_container') {
                if (typeof addPackageItem === 'function') {
                    addPackageItem();
                } else {
                    console.error('addPackageItem function not found');
                }
            } else if (containerId === 'attachments_container' || containerId === 'edit_attachments_container') {
                if (typeof addAttachmentItem === 'function') {
                    addAttachmentItem();
                } else {
                    console.error('addAttachmentItem function not found');
                }
            } else if (containerId === 'files_container' || containerId === 'edit_files_container') {
                if (typeof addFileItem === 'function') {
                    addFileItem();
                } else {
                    console.error('addFileItem function not found');
                }
            } else if (containerId === 'recordings_container' || containerId === 'edit_recordings_container') {
                if (typeof addRecordingItem === 'function') {
                    addRecordingItem();
                } else {
                    console.error('addRecordingItem function not found');
                }
            }
        }

        // Dynamic item management functions
        function addPackageItem() {
            console.log('addPackageItem called');
            // Check which form is active by checking if edit form exists and is visible
            const editForm = document.getElementById('editWorkshopForm');
            const createForm = document.getElementById('createWorkshopForm');

            let container;
            let isEdit = false;

            if (editForm && editForm.offsetParent !== null) {
                // Edit form is visible
                container = document.getElementById('edit_packages_container');
                isEdit = true;
                console.log('Edit form is active, using edit_packages_container');
            } else if (createForm && createForm.offsetParent !== null) {
                // Create form is visible
                container = document.getElementById('packages_container');
                isEdit = false;
                console.log('Create form is active, using packages_container');
            } else {
                // Fallback: try edit first, then create
                container = document.getElementById('edit_packages_container') || document.getElementById(
                    'packages_container');
                isEdit = container && container.id.includes('edit');
            }

            if (!container) {
                console.error('Packages container not found');
                return;
            }
            console.log('Packages container found:', container.id, 'isEdit:', isEdit);

            // Check if type is recorded and limit to 1 package
            const typeSelect = document.getElementById('type') || document.getElementById('edit_type');
            if (typeSelect && typeSelect.value === 'recorded') {
                if (container.children.length >= 1) {
                    alert('الورشة المسجلة يمكن أن تحتوي على حزمة واحدة فقط');
                    return;
                }
            }

            const index = container.children.length;
            console.log('Creating package item, index:', index, 'isEdit:', isEdit);
            const item = createPackageItem(index, null, isEdit);
            if (item) {
                container.appendChild(item);
                console.log('Package item added successfully to:', container.id);
            } else {
                console.error('Failed to create package item');
            }
        }
        // Make globally accessible
        if (typeof window !== 'undefined') {
            window.addPackageItem = addPackageItem;
        }

        function removePackageItem(button) {
            button.closest('.dynamic-item').remove();
        }

        function createPackageItem(index, data = null, isEdit = false) {
            const existingId = data?.id ? data.id : '';
            const item = document.createElement('div');
            item.className = 'dynamic-item';
            item.innerHTML = `
        <button type="button" class="btn-remove-item" onclick="removePackageItem(this)">&times;</button>
       
        <input type="hidden" name="packages[${index}][id]" value="${existingId}">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">العنوان <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="packages[${index}][title]" value="${data?.title || ''}" required placeholder="أدخل عنوان الحزمة">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">السعر <span class="text-danger">*</span></label>
                <input type="number" step="0.01" class="form-control" name="packages[${index}][price]" value="${data?.price || ''}" required placeholder="0.00">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">
                    <input type="checkbox" name="packages[${index}][is_offer]" value="1" ${data?.is_offer ? 'checked' : ''} onchange="toggleOfferFields(this)">
                    عرض خاص
                </label>
            </div>
        </div>
        <div class="row offer-fields" style="display: ${data?.is_offer ? 'block' : 'none'};">
            <div class="col-md-6 mb-3">
                <label class="form-label">سعر العرض</label>
                <input type="number" step="0.01" class="form-control" name="packages[${index}][offer_price]" value="${data?.offer_price || ''}" placeholder="0.00">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">تاريخ انتهاء العرض</label>
                <input type="date" class="form-control" name="packages[${index}][offer_expiry_date]" value="${data?.offer_expiry_date || ''}">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">المميزات</label>
            <textarea class="form-control" name="packages[${index}][features]" rows="3" placeholder="أدخل مميزات الحزمة (كل ميزة في سطر)">${data?.features || ''}</textarea>
        </div>
    `;
    return item;
}

        function toggleOfferFields(checkbox) {
            const item = checkbox.closest('.dynamic-item');
            const offerFields = item.querySelectorAll('.offer-fields');
            offerFields.forEach(field => {
                field.style.display = checkbox.checked ? 'block' : 'none';
            });

            // If unchecked, clear offer_price and offer_expiry_date
            if (!checkbox.checked) {
                const offerPriceInput = item.querySelector('input[name*="[offer_price]"]');
                const offerExpiryInput = item.querySelector('input[name*="[offer_expiry_date]"]');
                if (offerPriceInput) offerPriceInput.value = '';
                if (offerExpiryInput) offerExpiryInput.value = '';
            }
        }

        function populatePackageItem(index, data, isEdit) {
            // This will be handled by createPackageItem
        }

        // Similar functions for attachments, files, and recordings
        function addAttachmentItem() {
            console.log('addAttachmentItem called');
            // Check which form is active
            const editForm = document.getElementById('editWorkshopForm');
            const createForm = document.getElementById('createWorkshopForm');

            let container;
            let isEdit = false;

            if (editForm && editForm.offsetParent !== null) {
                container = document.getElementById('edit_attachments_container');
                isEdit = true;
                console.log('Edit form is active, using edit_attachments_container');
            } else if (createForm && createForm.offsetParent !== null) {
                container = document.getElementById('attachments_container');
                isEdit = false;
                console.log('Create form is active, using attachments_container');
            } else {
                container = document.getElementById('edit_attachments_container') || document.getElementById(
                    'attachments_container');
                isEdit = container && container.id.includes('edit');
            }

            if (!container) {
                console.error('Attachments container not found');
                return;
            }
            console.log('Attachments container found:', container.id, 'isEdit:', isEdit);
            const index = container.children.length;
            console.log('Creating attachment item, index:', index, 'isEdit:', isEdit);
            const item = createAttachmentItem(index, null, isEdit);
            if (item) {
                container.appendChild(item);
                console.log('Attachment item added successfully to:', container.id);
            } else {
                console.error('Failed to create attachment item');
            }
        }
        // Make globally accessible
        if (typeof window !== 'undefined') {
            window.addAttachmentItem = addAttachmentItem;
        }

        function createAttachmentItem(index, data = null, isEdit = false) {
            const existingId = data?.id ? data.id : '';
            const item = document.createElement('div');
            item.className = 'dynamic-item';
            item.innerHTML = `
        <button type="button" class="btn-remove-item" onclick="removeAttachmentItem(this)">&times;</button>
       
        <input type="hidden" name="attachments[${index}][id]" value="${existingId}">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">النوع <span class="text-danger">*</span></label>
                <select class="form-select" name="attachments[${index}][type]" required>
                    <option value="">اختر النوع</option>
                    <option value="audio" ${data?.type === 'audio' ? 'selected' : ''}>ملف صوتي (MP3)</option>
                    <option value="video" ${data?.type === 'video' ? 'selected' : ''}>ملف فيديو (MP4)</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">العنوان <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="attachments[${index}][title]" value="${data?.title || ''}" required placeholder="أدخل عنوان المرفق">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">الملف ${existingId ? '' : '<span class="text-danger">*</span>'}</label>
                <div class="file-upload-wrapper">
                    <input type="file" class="form-control file-input" name="attachments[${index}][file]" ${existingId ? '' : 'required'} accept="audio/mpeg,audio/mp3,video/mp4,.mp3,.mp4" onchange="updateFilePlaceholder(this)">
                    <div class="file-upload-placeholder">
                        <i class="fa fa-cloud-upload me-2"></i>
                        <span class="file-name">${existingId ? 'اختر ملف جديد (اختياري)' : 'اختر ملف صوتي (MP3) أو فيديو (MP4)'}</span>
                    </div>
                    ${data?.file ? `<small class="text-muted d-block mt-2">الملف الحالي: ${data.file}</small>` : ''}
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">ملاحظات</label>
                <textarea class="form-control" name="attachments[${index}][notes]" rows="2" placeholder="أدخل ملاحظات (اختياري)">${data?.notes || ''}</textarea>
            </div>
        </div>
    `;
    return item;
}

        function removeAttachmentItem(button) {
            button.closest('.dynamic-item').remove();
        }

        function addFileItem() {
            console.log('addFileItem called');
            // Check which form is active
            const editForm = document.getElementById('editWorkshopForm');
            const createForm = document.getElementById('createWorkshopForm');

            let container;
            let isEdit = false;

            if (editForm && editForm.offsetParent !== null) {
                container = document.getElementById('edit_files_container');
                isEdit = true;
                console.log('Edit form is active, using edit_files_container');
            } else if (createForm && createForm.offsetParent !== null) {
                container = document.getElementById('files_container');
                isEdit = false;
                console.log('Create form is active, using files_container');
            } else {
                container = document.getElementById('edit_files_container') || document.getElementById('files_container');
                isEdit = container && container.id.includes('edit');
            }

            if (!container) {
                console.error('Files container not found');
                return;
            }
            console.log('Files container found:', container.id, 'isEdit:', isEdit);
            const index = container.children.length;
            console.log('Creating file item, index:', index, 'isEdit:', isEdit);
            const item = createFileItem(index, null, isEdit);
            if (item) {
                container.appendChild(item);
                console.log('File item added successfully to:', container.id);
            } else {
                console.error('Failed to create file item');
            }
        }
        // Make globally accessible
        if (typeof window !== 'undefined') {
            window.addFileItem = addFileItem;
        }

        function createFileItem(index, data = null, isEdit = false) {
            const existingId = data?.id ? data.id : '';
            const item = document.createElement('div');
            item.className = 'dynamic-item';
            item.innerHTML = `
        <button type="button" class="btn-remove-item" onclick="removeFileItem(this)">&times;</button>
       
        <input type="hidden" name="files[${index}][id]" value="${existingId}">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">العنوان <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="files[${index}][title]" value="${data?.title || ''}" required placeholder="أدخل عنوان الملف">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">الملف ${existingId ? '' : '<span class="text-danger">*</span>'}</label>
                <div class="file-upload-wrapper">
                    <input type="file" class="form-control file-input" name="files[${index}][file]" ${existingId ? '' : 'required'} onchange="updateFilePlaceholder(this)">
                    <div class="file-upload-placeholder">
                        <i class="fa fa-file-upload me-2"></i>
                        <span class="file-name">${existingId ? 'اختر ملف جديد (اختياري)' : 'اختر ملف'}</span>
                    </div>
                    ${data?.file ? `<small class="text-muted d-block mt-2">الملف الحالي: ${data.file}</small>` : ''}
                </div>
            </div>
        </div>
    `;
    return item;
}

        function removeFileItem(button) {
            button.closest('.dynamic-item').remove();
        }

        function addRecordingItem() {
            console.log('addRecordingItem called');
            // Check which form is active
            const editForm = document.getElementById('editWorkshopForm');
            const createForm = document.getElementById('createWorkshopForm');

            let container;
            let isEdit = false;

            if (editForm && editForm.offsetParent !== null) {
                container = document.getElementById('edit_recordings_container');
                isEdit = true;
                console.log('Edit form is active, using edit_recordings_container');
            } else if (createForm && createForm.offsetParent !== null) {
                container = document.getElementById('recordings_container');
                isEdit = false;
                console.log('Create form is active, using recordings_container');
            } else {
                container = document.getElementById('edit_recordings_container') || document.getElementById(
                    'recordings_container');
                isEdit = container && container.id.includes('edit');
            }

            if (!container) {
                console.error('Recordings container not found');
                return;
            }
            console.log('Recordings container found:', container.id, 'isEdit:', isEdit);
            const index = container.children.length;
            console.log('Creating recording item, index:', index, 'isEdit:', isEdit);
            const item = createRecordingItem(index, null, isEdit);
            if (item) {
                container.appendChild(item);
                console.log('Recording item added successfully to:', container.id);
            } else {
                console.error('Failed to create recording item');
            }
        }
        // Make globally accessible
        if (typeof window !== 'undefined') {
            window.addRecordingItem = addRecordingItem;
        }

        function createRecordingItem(index, data = null, isEdit = false) {
            const existingId = data?.id ? data.id : '';
            const item = document.createElement('div');
            item.className = 'dynamic-item';
            item.innerHTML = `
        <button type="button" class="btn-remove-item" onclick="removeRecordingItem(this)">&times;</button>
       
        <input type="hidden" name="recordings[${index}][id]" value="${existingId}">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">العنوان <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="recordings[${index}][title]" value="${data?.title || ''}" required placeholder="أدخل عنوان التسجيل">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">الرابط <span class="text-danger">*</span></label>
                <input type="url" class="form-control" name="recordings[${index}][link]" value="${data?.link || ''}" required placeholder="https://...">
            </div>
        </div>
    `;
    return item;
}

        function removeRecordingItem(button) {
            button.closest('.dynamic-item').remove();
        }

        function updateFilePlaceholder(input) {
            const wrapper = input.closest('.file-upload-wrapper');
            const placeholder = wrapper.querySelector('.file-name');
            if (input.files && input.files.length > 0) {
                placeholder.textContent = input.files[0].name;
                placeholder.style.color = '#10b981';
            } else {
                placeholder.textContent = input.accept ? 'اختر ملف صورة أو فيديو' : 'اختر ملف';
                placeholder.style.color = '#94a3b8';
            }
        }

        function setupFormSubmission(formId, actionUrl) {
            const form = document.getElementById(formId);
            if (!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validate CKEditor content
                const isEdit = formId.includes('edit');
                const editor = isEdit ? editSubjectEditor : subjectEditor;
                let subjectContent = '';

                if (editor) {
                    subjectContent = editor.getData();
                    // Remove HTML tags to check if there's actual content
                    const textContent = subjectContent.replace(/<[^>]*>/g, '').trim();
                    if (!textContent) {
                        alert('يرجى إدخال موضوع النقاش');
                        if (editor.editing.view.domElement) {
                            editor.editing.view.focus();
                        }
                        return false;
                    }
                } else {
                    const textarea = document.getElementById(isEdit ? 'edit_subject_of_discussion' :
                        'subject_of_discussion');
                    if (textarea) {
                        subjectContent = textarea.value.trim();
                        if (!subjectContent) {
                            alert('يرجى إدخال موضوع النقاش');
                            textarea.focus();
                            return false;
                        }
                    }
                }

                const formData = new FormData(form);

                // Set CKEditor content
                if (editor) {
                    formData.set('subject_of_discussion', subjectContent);
                }

                // For PUT requests, Laravel needs _method field
                if (formId.includes('edit')) {
                    formData.append('_method', 'PUT');
                }

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i> جاري الحفظ...';

                fetch(actionUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(async response => {
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            const text = await response.text();
                            console.error('Non-JSON response:', text);
                            throw new Error('الخادم أرجَع استجابة غير صحيحة. يرجى التحقق من السجلات.');
                        }
                    })
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            bootstrap.Modal.getInstance(document.getElementById('workshopModal')).hide();
                            window.location.reload();
                        } else {
                            let errorMsg = data.message || 'حدث خطأ';
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
                        alert('حدث خطأ أثناء حفظ البيانات: ' + error.message);
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
            });
        }

        // Ensure functions are globally accessible for onclick handlers
        window.addPackageItem = addPackageItem;
        window.addAttachmentItem = addAttachmentItem;
        window.addFileItem = addFileItem;
        window.addRecordingItem = addRecordingItem;
        window.removePackageItem = removePackageItem;
        window.removeAttachmentItem = removeAttachmentItem;
        window.removeFileItem = removeFileItem;
        window.removeRecordingItem = removeRecordingItem;
        window.toggleOfferFields = toggleOfferFields;
        window.updateFilePlaceholder = updateFilePlaceholder;
        window.handleTypeChange = handleTypeChange;
    </script>
@endsection
