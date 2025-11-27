@extends('Admin.layout')

@push('head')
<style>
    /* Critical CSS - Load immediately to prevent white flash */
    html, body {
        background: #0F172A !important;
    }
</style>
<script>
    // Set background immediately before page loads to prevent white flash
    (function() {
        document.documentElement.style.backgroundColor = '#0F172A';
        document.body.style.backgroundColor = '#0F172A';
    })();
</script>
@endpush

@section('styles')
<style>
    /* Prevent white flash - apply immediately */
    html {
        background: #0F172A !important;
    }
    
    body {
        background: #0F172A !important;
        opacity: 0;
        transition: opacity 0.2s ease-in;
    }
    
    body.loaded {
        opacity: 1;
    }
    
    /* Loading overlay to prevent white flash */
    .page-loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #0F172A;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.3s ease-out;
    }
    
    .page-loading-overlay.hidden {
        opacity: 0;
        pointer-events: none;
    }
    
    .products-container {
        padding: 2rem 0;
        background: transparent;
        min-height: 100vh;
    }
    
    #sectionTabContent {
        background: transparent;
        min-height: 300px;
    }
    
    #sectionTabContent .tab-pane {
        background: transparent;
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
    
    /* Main Section Tabs (Products/Orders) */
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
        font-size: 1rem;
        font-weight: 600;
    }
    
    .nav-tabs .nav-link:hover {
        color: #fff;
        background: rgba(255,255,255,0.1);
    }
    
    .nav-tabs .nav-link.active {
        background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
        color: #fff;
    }
    
    /* Fix white flash/lag on tab switching */
    .tab-content {
        background: transparent;
        min-height: 200px;
    }
    
    .tab-pane {
        background: transparent;
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
    }
    
    .tab-pane.active {
        opacity: 1;
    }
    
    .tab-pane.fade {
        transition: opacity 0.15s linear;
    }
    
    .tab-pane.fade:not(.show) {
        opacity: 0;
    }
    
    .tab-pane.fade.show {
        opacity: 1;
    }
    
    /* Sub-tabs (Active/Deleted) - Smaller and Different Design */
    .sub-tabs-container {
        border-radius: 10px;
        padding: 0.15rem;
        margin-bottom: 1rem;
    }
    
    .sub-tabs-container .nav-tabs {
        background: transparent;
        padding: 0.15rem;
        border: none;
    }
    
    .sub-tabs-container .nav-link {
        padding: 0.4rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        margin: 0 0.15rem;
        border-radius: 6px;
        color: #64748b;
        background: transparent;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }
    
    .sub-tabs-container .nav-link:hover {
        color: #94a3b8;
        background: rgba(255,255,255,0.05);
        border-color: rgba(255,255,255,0.1);
    }
    
    .sub-tabs-container .nav-link.active {
        background: rgba(139, 92, 246, 0.2);
        color: #a78bfa;
        border-color: rgba(139, 92, 246, 0.4);
        box-shadow: 0 2px 6px rgba(139, 92, 246, 0.15);
    }
    
    /* Prevent white flash in sub-tabs content */
    #productsTabContent {
        background: transparent;
        min-height: 100px;
    }
    
    #productsTabContent .tab-pane {
        background: transparent;
    }
    
    .search-export-section {
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .search-input {
        flex: 1;
        min-width: 200px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: #fff;
        padding: 0.5rem 0.875rem;
        font-size: 0.9rem;
    }
    
    .search-input:focus {
        background: rgba(255,255,255,0.08);
        border-color: #38bdf8;
        box-shadow: 0 0 0 0.2rem rgba(56,189,248,0.25);
        color: #fff;
        outline: none;
    }
    
    .btn-create {
        background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1.25rem;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
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
    
    .products-table {
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
    
    .product-image-cell {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.05);
    }
    
    .product-thumbnail {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .badge-platform {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .badge-user {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
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
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }
    
    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
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
    
    /* Enhanced Modal Styles - Thinner and Better UX */
    .modal-dialog {
        max-width: 500px;
    }
    
    .modal-content {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 16px;
        color: #fff;
        direction: rtl;
        text-align: right;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }
    
    .modal-header {
        border-bottom: 1px solid rgba(255,255,255,0.08);
        padding: 1.25rem 1.5rem;
        background: rgba(255,255,255,0.02);
        border-radius: 16px 16px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .modal-title {
        color: #fff;
        font-weight: 700;
        font-size: 1.25rem;
        margin: 0;
    }
    
    .modal-body {
        padding: 1.5rem;
        max-height: 70vh;
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
    
    .modal-footer {
        border-top: 1px solid rgba(255,255,255,0.08);
        padding: 1rem 1.5rem;
        background: rgba(255,255,255,0.02);
        border-radius: 0 0 16px 16px;
        display: flex;
        gap: 0.75rem;
        justify-content: flex-start;
    }
    
    .btn-close {
        filter: invert(1);
        opacity: 0.8;
    }
    
    .btn-close:hover {
        opacity: 1;
    }
    
    /* Enhanced Form Styles */
    .form-control, .form-select {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #fff;
        border-radius: 8px;
        padding: 0.625rem 0.875rem;
        transition: all 0.2s ease;
        direction: rtl;
        text-align: right;
        font-size: 0.9rem;
    }
    
    .form-control:focus, .form-select:focus {
        background: rgba(255,255,255,0.08);
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56,189,248,0.15);
        color: #fff;
        outline: none;
    }
    
    .form-control::placeholder {
        color: #64748b;
        text-align: right;
        opacity: 0.7;
    }
    
    .form-select option {
        background: #1E293B;
        color: #fff;
        direction: rtl;
        padding: 0.5rem;
    }
    
    .form-label {
        color: #cbd5e1;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.875rem;
    }
    
    .form-label .text-danger {
        color: #ef4444;
        margin-right: 0.25rem;
    }
    
    .row {
        margin-bottom: 1rem;
    }
    
    .row:last-child {
        margin-bottom: 0;
    }
    
    /* Enhanced Button Styles */
    .btn-primary {
        background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
        border: none;
        border-radius: 8px;
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        min-width: 100px;
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.4);
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
    }
    
    .btn-primary:active {
        transform: translateY(0);
    }
    
    .btn-secondary {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 8px;
        padding: 0.625rem 1.5rem;
        color: #94a3b8;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        min-width: 100px;
    }
    
    .btn-secondary:hover {
        background: rgba(255,255,255,0.12);
        border-color: rgba(255,255,255,0.25);
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
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #94a3b8;
    }
    
    /* Enhanced Image Preview */
    .image-preview-container {
        margin-top: 0.75rem;
    }
    
    .image-preview-placeholder {
        width: 100%;
        min-height: 150px;
        border: 2px dashed rgba(255,255,255,0.15);
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        background: rgba(255,255,255,0.02);
        color: #64748b;
        transition: all 0.2s ease;
    }
    
    .image-preview-placeholder.has-image {
        border: 1px solid rgba(255,255,255,0.1);
        padding: 0.5rem;
        background: rgba(255,255,255,0.03);
    }
    
    .image-preview-placeholder.has-image img {
        width: 100%;
        max-height: 200px;
        object-fit: contain;
        border-radius: 8px;
    }
    
    /* Improved File Upload UI */
    .file-upload-area {
        border: 2px dashed rgba(255,255,255,0.2);
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        background: rgba(255,255,255,0.02);
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }
    
    .file-upload-area:hover {
        border-color: #38bdf8;
        background: rgba(56, 189, 248, 0.05);
    }
    
    .file-upload-area.has-image {
        border: none;
        padding: 0;
        background: transparent;
        cursor: default;
    }
    
    .file-upload-icon {
        font-size: 2rem;
        color: #64748b;
        margin-bottom: 0.5rem;
    }
    
    .file-upload-text {
        color: #94a3b8;
        font-size: 0.875rem;
        margin: 0;
    }
    
    .file-input-hidden {
        position: absolute;
        width: 0;
        height: 0;
        opacity: 0;
        overflow: hidden;
    }
    
    .image-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
        justify-content: center;
    }
    
    .btn-upload-image {
        background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
        border: none;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        color: white;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-upload-image:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(56, 189, 248, 0.3);
    }
    
    .btn-remove-image {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        color: white;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-remove-image:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
    }
    
    .image-preview-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    
    .image-preview-wrapper .remove-overlay {
        position: absolute;
        top: 0.5rem;
        left: 0.5rem;
        background: rgba(0,0,0,0.7);
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        z-index: 10;
    }
    
    .image-preview-wrapper .remove-overlay:hover {
        background: rgba(239, 68, 68, 0.9);
        transform: scale(1.1);
    }
    
    .image-preview-wrapper .remove-overlay i {
        color: white;
        font-size: 0.875rem;
    }
    
    /* Info Box for Show Modal */
    .info-box {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        min-height: 42px;
        display: flex;
        align-items: center;
        color: #fff;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('main')
<!-- Loading Overlay to prevent white flash -->
<div class="page-loading-overlay" id="pageLoadingOverlay">
    <div style="color: #94a3b8; font-size: 1rem;">
        <i class="fa fa-spinner fa-spin" style="margin-left: 0.5rem;"></i>
        جاري التحميل...
    </div>
</div>

<div class="products-container" dir="rtl">

    <!-- Main Section Tabs (Products/Orders) -->
    <div class="tabs-container">
        <ul class="nav nav-tabs" id="sectionTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $section === 'products' ? 'active' : '' }}" 
                        id="products-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#products-section" 
                        type="button" 
                        role="tab"
                        onclick="switchSection('products')">
                    المنتجات
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $section === 'orders' ? 'active' : '' }}" 
                        id="orders-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#orders-section" 
                        type="button" 
                        role="tab"
                        onclick="switchSection('orders')">
                    الطلبات
                </button>
            </li>
        </ul>
    </div>

    <!-- Products Section -->
    <div class="tab-content" id="sectionTabContent">
        <div class="tab-pane fade {{ $section === 'products' ? 'show active' : '' }}" 
             id="products-section" 
             role="tabpanel">
            
            <!-- Products Sub-tabs (Active/Deleted) - Smaller Design -->
            <div class="sub-tabs-container">
                <ul class="nav nav-tabs" id="productsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $tab === 'active' ? 'active' : '' }}" 
                                id="active-products-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#active-products" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('active'); return false;">
                            المنتجات الموجودة
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $tab === 'deleted' ? 'active' : '' }}" 
                                id="deleted-products-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#deleted-products" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('deleted'); return false;">
                            المنتجات المحذوفة
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Search and Create Section -->
            <div class="search-export-section">
                <input type="text" 
                       id="searchInput" 
                       class="search-input" 
                       placeholder="البحث بالعنوان أو السعر..."
                       value="{{ request('search') }}"
                       onkeyup="handleSearch(event)">
                <button type="button" class="btn-create" onclick="openCreateModal()">
                    <i class="fa fa-plus"></i>
                    إضافة منتج جديد
                </button>
            </div>

            <!-- Products Tab Content -->
            <div class="tab-content" id="productsTabContent">
                <!-- Active Products Tab -->
                <div class="tab-pane fade {{ $tab === 'active' ? 'show active' : '' }}" 
                     id="active-products" 
                     role="tabpanel">
                    @include('Admin.products.partials.products-table', ['products' => $activeProducts, 'isDeleted' => false])
                </div>

                <!-- Deleted Products Tab -->
                <div class="tab-pane fade {{ $tab === 'deleted' ? 'show active' : '' }}" 
                     id="deleted-products" 
                     role="tabpanel">
                    @include('Admin.products.partials.products-table', ['products' => $deletedProducts, 'isDeleted' => true])
                </div>
            </div>
        </div>

        <!-- Orders Section (Placeholder for future) -->
        <div class="tab-pane fade {{ $section === 'orders' ? 'show active' : '' }}" 
             id="orders-section" 
             role="tabpanel">
            <div class="empty-state">
                <i class="fa fa-shopping-cart" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                <h4>قريباً</h4>
                <p>سيتم إضافة إدارة الطلبات قريباً</p>
            </div>
        </div>
    </div>

    <!-- Main Modal - Thinner Design -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">معلومات المنتج</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const baseUrl = '{{ route("admin.products.index") }}';

function switchSection(section) {
    // Only reload for main tabs (Products/Orders) since Orders needs different data
    if (section === 'orders') {
        showLoadingOverlay();
        setTimeout(() => {
            window.location.href = `${baseUrl}?section=${section}&tab={{ $tab }}&search={{ request('search') }}`;
        }, 100);
    } else {
        // For products section, just update URL without reload if already on products
        if ('{{ $section }}' === 'products') {
            // Already on products, no need to reload
            return;
        }
        showLoadingOverlay();
        setTimeout(() => {
            window.location.href = `${baseUrl}?section=${section}&tab={{ $tab }}&search={{ request('search') }}`;
        }, 100);
    }
}

function switchTab(tab) {
    // Bootstrap tabs will handle the visual switch automatically via data-bs-toggle
    // URL will be updated by the event listener, no page reload needed
    // Both active and deleted products are already loaded in the page
}

function handleSearch(event) {
    if (event.key === 'Enter') {
        const search = event.target.value;
        window.location.href = `${baseUrl}?section={{ $section }}&tab={{ $tab }}&search=${search}`;
    }
}

function openCreateModal() {
    fetch('{{ route("admin.products.create") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const formHtml = `
                    <form action="{{ route('admin.products.store') }}" method="POST" id="createProductForm" enctype="multipart/form-data" dir="rtl">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <label for="title" class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control" 
                                       id="title" 
                                       name="title" 
                                       placeholder="أدخل اسم المنتج"
                                       required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="price" class="form-label">السعر (د.إ) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       step="0.01"
                                       min="0"
                                       class="form-control" 
                                       id="price" 
                                       name="price" 
                                       placeholder="0.00"
                                       required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="owner_type" class="form-label">نوع المالك <span class="text-danger">*</span></label>
                                <select class="form-select" id="owner_type" name="owner_type" required onchange="toggleOwnerFields()">
                                    <option value="">اختر نوع المالك</option>
                                    <option value="platform">المنصة</option>
                                    <option value="user">مستخدم</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" id="owner_id_field" style="display: none;">
                            <div class="col-12">
                                <label for="owner_id" class="form-label">المستخدم <span class="text-danger">*</span></label>
                                <select class="form-select" id="owner_id" name="owner_id">
                                    <option value="">اختر المستخدم</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" id="owner_per_field" style="display: none;">
                            <div class="col-12">
                                <label for="owner_per" class="form-label">نسبة المستخدم (%) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       step="0.01"
                                       min="0"
                                       max="100"
                                       class="form-control" 
                                       id="owner_per" 
                                       name="owner_per" 
                                       placeholder="0-100">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">صورة المنتج <span class="text-danger">*</span></label>
                                <input type="file" 
                                       id="image" 
                                       name="image" 
                                       class="file-input-hidden" 
                                       accept="image/*" 
                                       required 
                                       onchange="handleImageSelect(this, 'createImagePreview', 'createImageFile')">
                                <div class="file-upload-area" id="createImageUploadArea" onclick="document.getElementById('image').click()">
                                    <div class="file-upload-icon">
                                        <i class="fa fa-cloud-upload-alt"></i>
                                    </div>
                                    <p class="file-upload-text">انقر أو اسحب الصورة هنا</p>
                                    <p class="file-upload-text" style="font-size: 0.75rem; margin-top: 0.25rem; opacity: 0.7;">PNG, JPG, GIF حتى 2MB</p>
                                </div>
                                <div class="image-preview-container" id="createImagePreviewContainer" style="display: none;">
                                    <div class="image-preview-wrapper">
                                        <div class="remove-overlay" onclick="removeImage('createImagePreview', 'createImageFile', 'createImageUploadArea', 'image')" title="إزالة الصورة">
                                            <i class="fa fa-times"></i>
                                        </div>
                                        <div class="image-preview-placeholder has-image" id="createImagePreview">
                                            <!-- Image will be inserted here -->
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="createImageFile" name="remove_image" value="0">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary">حفظ</button>
                        </div>
                    </form>
                `;
                
                document.getElementById('modalContent').innerHTML = formHtml;
                document.getElementById('productModalLabel').textContent = 'إضافة منتج جديد';
                
                const modal = new bootstrap.Modal(document.getElementById('productModal'));
                modal.show();
            }
        });
}

function openEditModal(productId) {
    fetch(`{{ route('admin.products.index') }}/${productId}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const product = data.product;
                const formHtml = `
                    <form action="{{ route('admin.products.index') }}/${productId}" method="POST" id="editProductForm" enctype="multipart/form-data" dir="rtl">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12">
                                <label for="edit_title" class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control" 
                                       id="edit_title" 
                                       name="title" 
                                       value="${product.title}"
                                       placeholder="أدخل اسم المنتج"
                                       required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="edit_price" class="form-label">السعر (د.إ) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       step="0.01"
                                       min="0"
                                       class="form-control" 
                                       id="edit_price" 
                                       name="price" 
                                       value="${product.price}"
                                       placeholder="0.00"
                                       required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="edit_owner_type" class="form-label">نوع المالك <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_owner_type" name="owner_type" required onchange="toggleEditOwnerFields()">
                                    <option value="platform" ${product.owner_type === 'platform' ? 'selected' : ''}>المنصة</option>
                                    <option value="user" ${product.owner_type === 'user' ? 'selected' : ''}>مستخدم</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" id="edit_owner_id_field" style="display: ${product.owner_type === 'user' ? 'block' : 'none'};">
                            <div class="col-12">
                                <label for="edit_owner_id" class="form-label">المستخدم <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_owner_id" name="owner_id">
                                    <option value="">اختر المستخدم</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" ${product.owner_id == {{ $user->id }} ? 'selected' : ''}>{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" id="edit_owner_per_field" style="display: ${product.owner_type === 'user' ? 'block' : 'none'};">
                            <div class="col-12">
                                <label for="edit_owner_per" class="form-label">نسبة المستخدم (%) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       step="0.01"
                                       min="0"
                                       max="100"
                                       class="form-control" 
                                       id="edit_owner_per" 
                                       name="owner_per" 
                                       value="${product.owner_per || ''}"
                                       placeholder="0-100">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">صورة المنتج</label>
                                <input type="file" 
                                       id="edit_image" 
                                       name="image" 
                                       class="file-input-hidden" 
                                       accept="image/*" 
                                       onchange="handleImageSelect(this, 'editImagePreview', 'editImageFile')">
                                <div class="file-upload-area" id="editImageUploadArea" onclick="document.getElementById('edit_image').click()" style="display: none;">
                                    <div class="file-upload-icon">
                                        <i class="fa fa-cloud-upload-alt"></i>
                                    </div>
                                    <p class="file-upload-text">انقر أو اسحب الصورة هنا</p>
                                    <p class="file-upload-text" style="font-size: 0.75rem; margin-top: 0.25rem; opacity: 0.7;">PNG, JPG, GIF حتى 2MB</p>
                                </div>
                                <div class="image-preview-container" id="editImagePreviewContainer">
                                    <div class="image-preview-wrapper">
                                        <div class="remove-overlay" onclick="removeImage('editImagePreview', 'editImageFile', 'editImageUploadArea', 'edit_image')" title="إزالة الصورة">
                                            <i class="fa fa-times"></i>
                                        </div>
                                        <div class="image-preview-placeholder has-image" id="editImagePreview">
                                            <img src="${product.image}" alt="Current Image" onerror="handleImageError('editImagePreview', 'editImageUploadArea')">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="editImageFile" name="remove_image" value="0">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary">تحديث</button>
                        </div>
                    </form>
                `;
                
                document.getElementById('modalContent').innerHTML = formHtml;
                document.getElementById('productModalLabel').textContent = 'تعديل المنتج';
                
                // Initialize edit modal - hide upload area if image exists
                const editPreview = document.getElementById('editImagePreview');
                const editUploadArea = document.getElementById('editImageUploadArea');
                if (editPreview && editPreview.querySelector('img') && editUploadArea) {
                    editUploadArea.style.display = 'none';
                }
                
                const modal = new bootstrap.Modal(document.getElementById('productModal'));
                modal.show();
            }
        });
}

function openShowModal(productId) {
    fetch(`{{ route('admin.products.index') }}/${productId}/show`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const product = data.product;
                const content = `
                    <div class="row">
                        <div class="col-12 text-center mb-3">
                            <img src="${product.image}" 
                                 alt="${product.title}" 
                                 style="max-width: 100%; max-height: 180px; border-radius: 8px; object-fit: contain;"
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">اسم المنتج</label>
                            <div class="info-box">${product.title}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">السعر</label>
                            <div class="info-box">${product.price} د.إ</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">نوع المالك</label>
                            <div class="info-box">${product.owner_type === 'platform' ? 'المنصة' : 'مستخدم'}</div>
                        </div>
                    </div>
                    ${product.owner ? `
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">المالك</label>
                            <div class="info-box">${product.owner.full_name}</div>
                        </div>
                    </div>
                    ` : ''}
                    ${product.owner_type === 'user' ? `
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">نسبة المستخدم</label>
                            <div class="info-box">${product.owner_per}%</div>
                        </div>
                        <div class="col-6">
                            <label class="form-label">نسبة المنصة</label>
                            <div class="info-box">${100 - product.owner_per}%</div>
                        </div>
                    </div>
                    ` : ''}
                `;
                
                document.getElementById('modalContent').innerHTML = content;
                document.getElementById('productModalLabel').textContent = 'تفاصيل المنتج';
                
                const modal = new bootstrap.Modal(document.getElementById('productModal'));
                modal.show();
            }
        });
}

function toggleOwnerFields() {
    const ownerType = document.getElementById('owner_type').value;
    const ownerIdField = document.getElementById('owner_id_field');
    const ownerPerField = document.getElementById('owner_per_field');
    
    if (ownerType === 'user') {
        ownerIdField.style.display = 'block';
        ownerPerField.style.display = 'block';
        document.getElementById('owner_id').required = true;
        document.getElementById('owner_per').required = true;
    } else {
        ownerIdField.style.display = 'none';
        ownerPerField.style.display = 'none';
        document.getElementById('owner_id').required = false;
        document.getElementById('owner_per').required = false;
        document.getElementById('owner_id').value = '';
        document.getElementById('owner_per').value = '';
    }
}

function toggleEditOwnerFields() {
    const ownerType = document.getElementById('edit_owner_type').value;
    const ownerIdField = document.getElementById('edit_owner_id_field');
    const ownerPerField = document.getElementById('edit_owner_per_field');
    
    if (ownerType === 'user') {
        ownerIdField.style.display = 'block';
        ownerPerField.style.display = 'block';
        document.getElementById('edit_owner_id').required = true;
        document.getElementById('edit_owner_per').required = true;
    } else {
        ownerIdField.style.display = 'none';
        ownerPerField.style.display = 'none';
        document.getElementById('edit_owner_id').required = false;
        document.getElementById('edit_owner_per').required = false;
        document.getElementById('edit_owner_id').value = '';
        document.getElementById('edit_owner_per').value = '';
    }
}

function handleImageSelect(input, previewId, hiddenInputId) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            const previewContainer = document.getElementById(previewId + 'Container');
            const uploadArea = document.getElementById(previewId.replace('Preview', 'UploadArea'));
            
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            preview.classList.add('has-image');
            
            if (previewContainer) {
                previewContainer.style.display = 'block';
            }
            if (uploadArea) {
                uploadArea.style.display = 'none';
            }
            
            // Reset remove flag
            const hiddenInput = document.getElementById(hiddenInputId);
            if (hiddenInput) {
                hiddenInput.value = '0';
            }
        };
        reader.readAsDataURL(file);
    }
}

function removeImage(previewId, hiddenInputId, uploadAreaId, fileInputId) {
    const preview = document.getElementById(previewId);
    const previewContainer = document.getElementById(previewId + 'Container');
    const uploadArea = document.getElementById(uploadAreaId);
    const fileInput = document.getElementById(fileInputId);
    const hiddenInput = document.getElementById(hiddenInputId);
    
    // Clear preview
    preview.innerHTML = '';
    preview.classList.remove('has-image');
    
    // Show upload area
    if (uploadArea) {
        uploadArea.style.display = 'block';
    }
    if (previewContainer) {
        previewContainer.style.display = 'none';
    }
    
    // Clear file input
    if (fileInput) {
        fileInput.value = '';
    }
    
    // Set remove flag (for edit mode)
    if (hiddenInput) {
        hiddenInput.value = '1';
    }
}

function handleImageError(previewId, uploadAreaId) {
    const preview = document.getElementById(previewId);
    const uploadArea = document.getElementById(uploadAreaId);
    
    preview.classList.remove('has-image');
    preview.innerHTML = `
        <i class="fa fa-image" style="font-size: 2.5rem; opacity: 0.3;"></i>
        <span style="font-size: 0.85rem;">خطأ في تحميل الصورة</span>
    `;
    
    if (uploadArea) {
        uploadArea.style.display = 'block';
    }
}

function deleteProduct(productId) {
    if (confirm('هل أنت متأكد من حذف هذا المنتج؟')) {
        fetch(`{{ route('admin.products.index') }}/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showLoadingOverlay();
                setTimeout(() => {
                    location.reload();
                }, 100);
            } else {
                alert(data.message || 'حدث خطأ أثناء حذف المنتج');
            }
        });
    }
}

function restoreProduct(productId) {
    if (confirm('هل أنت متأكد من استعادة هذا المنتج؟')) {
        fetch(`{{ route('admin.products.index') }}/${productId}/restore`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showLoadingOverlay();
                setTimeout(() => {
                    location.reload();
                }, 100);
            } else {
                alert(data.message || 'حدث خطأ أثناء استعادة المنتج');
            }
        });
    }
}

function permanentlyDeleteProduct(productId) {
    if (confirm('هل أنت متأكد من حذف هذا المنتج نهائياً؟ لا يمكن التراجع عن هذا الإجراء.')) {
        fetch(`{{ route('admin.products.index') }}/${productId}/permanent`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showLoadingOverlay();
                setTimeout(() => {
                    location.reload();
                }, 100);
            } else {
                alert(data.message || 'حدث خطأ أثناء حذف المنتج');
            }
        });
    }
}

// Functions to handle loading overlay
function showLoadingOverlay() {
    const overlay = document.getElementById('pageLoadingOverlay');
    if (overlay) {
        overlay.classList.remove('hidden');
    }
}

function hideLoadingOverlay() {
    const overlay = document.getElementById('pageLoadingOverlay');
    if (overlay) {
        overlay.classList.add('hidden');
    }
}

// Handle Bootstrap tab events for smooth switching without page reload
document.addEventListener('DOMContentLoaded', function() {
    // Hide loading overlay and show body
    hideLoadingOverlay();
    document.body.classList.add('loaded');
    
    // Handle page visibility to prevent flash on back/forward
    if (document.visibilityState === 'visible') {
        hideLoadingOverlay();
    }
    // Handle sub-tabs (Active/Deleted) - no page reload needed
    const productsTabs = document.querySelectorAll('#productsTabs button[data-bs-toggle="tab"]');
    productsTabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(event) {
            const targetTab = event.target.getAttribute('data-bs-target');
            let tabValue = 'active';
            if (targetTab === '#deleted-products') {
                tabValue = 'deleted';
            }
            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('tab', tabValue);
            url.searchParams.delete('page');
            window.history.pushState({}, '', url);
        });
    });
    
    // Handle form submissions
    const modal = document.getElementById('productModal');
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
                        showLoadingOverlay();
                        setTimeout(() => {
                            location.reload();
                        }, 100);
                    } else {
                        alert(data.message || 'حدث خطأ');
                    }
                })
                .catch(error => {
                    alert('حدث خطأ أثناء معالجة الطلب');
                });
            }
        });
    }
});
</script>
@endsection

