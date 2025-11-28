@extends('Admin.layout')

@section('styles')
<style>
    .products-container {
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
    
    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }
    
    .file-input-label {
        display: block;
        padding: 0.625rem 1rem;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: #94a3b8;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }
    
    .file-input-label:hover {
        background: rgba(255,255,255,0.08);
        border-color: #38bdf8;
        color: #fff;
    }
    
    .file-input {
        position: absolute;
        left: -9999px;
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

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.95);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 1rem;
    }

    .loading-overlay.active {
        display: flex;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 4px solid rgba(56, 189, 248, 0.2);
        border-top-color: #38bdf8;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .loading-text {
        color: #94a3b8;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .products-container {
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .products-container.loaded {
        opacity: 1;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
        animation: fadeIn 0.3s ease;
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

    html, body {
        background: #0F172A !important;
        min-height: 100vh;
    }
</style>
@endsection

@section('main')
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
    <div class="loading-text">جاري التحميل...</div>
</div>

<div class="products-container" dir="rtl" id="productsContainer">

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
                                onclick="switchTab('active')">
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
                                onclick="switchTab('deleted')">
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
                    @if(isset($products) && $products)
                        @include('Admin.products.partials.products-table', ['products' => $products, 'isDeleted' => false])
                    @else
                        <div class="empty-state">
                            <i class="fa fa-box" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                            <h4>لا توجد منتجات</h4>
                        </div>
                    @endif
                </div>

                <!-- Deleted Products Tab -->
                <div class="tab-pane fade {{ $tab === 'deleted' ? 'show active' : '' }}" 
                     id="deleted-products" 
                     role="tabpanel">
                    @if(isset($products) && $products)
                        @include('Admin.products.partials.products-table', ['products' => $products, 'isDeleted' => true])
                    @else
                        <div class="empty-state">
                            <i class="fa fa-box" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                            <h4>لا توجد منتجات</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="tab-pane fade {{ $section === 'orders' ? 'show active' : '' }}" 
             id="orders-section" 
             role="tabpanel">
            
            <!-- Orders Sub-tabs (Pending/Completed/Trashed) -->
            <div class="sub-tabs-container">
                <ul class="nav nav-tabs" id="ordersTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $tab === 'pending' ? 'active' : '' }}" 
                                id="pending-orders-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#pending-orders" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('pending')">
                            الطلبات المعلقة
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $tab === 'completed' ? 'active' : '' }}" 
                                id="completed-orders-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#completed-orders" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('completed')">
                            الطلبات المكتملة
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $tab === 'trashed' ? 'active' : '' }}" 
                                id="trashed-orders-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#trashed-orders" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('trashed')">
                            الطلبات المحذوفة
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Orders Tab Content -->
            <div class="tab-content" id="ordersTabContent">
                <!-- Pending Orders Tab -->
                <div class="tab-pane fade {{ $tab === 'pending' ? 'show active' : '' }}" 
                     id="pending-orders" 
                     role="tabpanel">
                    @if(isset($orders) && $orders)
                        @include('Admin.products.partials.orders-table', ['orders' => $orders, 'tab' => 'pending'])
                    @else
                        <div class="empty-state">
                            <i class="fa fa-shopping-cart" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                            <h4>لا توجد طلبات</h4>
                        </div>
                    @endif
                </div>

                <!-- Completed Orders Tab -->
                <div class="tab-pane fade {{ $tab === 'completed' ? 'show active' : '' }}" 
                     id="completed-orders" 
                     role="tabpanel">
                    @if(isset($orders) && $orders)
                        @include('Admin.products.partials.orders-table', ['orders' => $orders, 'tab' => 'completed'])
                    @else
                        <div class="empty-state">
                            <i class="fa fa-shopping-cart" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                            <h4>لا توجد طلبات</h4>
                        </div>
                    @endif
                </div>

                <!-- Trashed Orders Tab -->
                <div class="tab-pane fade {{ $tab === 'trashed' ? 'show active' : '' }}" 
                     id="trashed-orders" 
                     role="tabpanel">
                    @if(isset($orders) && $orders)
                        @include('Admin.products.partials.orders-table', ['orders' => $orders, 'tab' => 'trashed'])
                    @else
                        <div class="empty-state">
                            <i class="fa fa-shopping-cart" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                            <h4>لا توجد طلبات</h4>
                        </div>
                    @endif
                </div>
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

    <!-- Order Items Modal -->
    <div class="modal fade" id="orderItemsModal" tabindex="-1" aria-labelledby="orderItemsModalLabel" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderItemsModalLabel">عناصر الطلب</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="orderItemsContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- User Details Modal -->
    <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userDetailsModalLabel">تفاصيل المستخدم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="userDetailsContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const baseUrlProducts = '{{ route("admin.products.index") }}';
const baseUrlOrders = '{{ route("admin.orders.index") }}';

function showLoading() {
    document.getElementById('loadingOverlay').classList.add('active');
    document.getElementById('productsContainer').classList.remove('loaded');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.remove('active');
    document.getElementById('productsContainer').classList.add('loaded');
}

function switchSection(section) {
    showLoading();
    if (section === 'orders') {
        window.location.href = `${baseUrlOrders}?section=${section}&tab=pending&search={{ request('search') }}`;
    } else {
        window.location.href = `${baseUrlProducts}?section=${section}&tab=active&search={{ request('search') }}`;
    }
}

function switchTab(tab) {
    showLoading();
    const section = '{{ $section }}';
    const search = '{{ request('search') }}';
    if (section === 'orders') {
        window.location.href = `${baseUrlOrders}?section=${section}&tab=${tab}&search=${search}`;
    } else {
        window.location.href = `${baseUrlProducts}?section=${section}&tab=${tab}&search=${search}`;
    }
}

function handleSearch(event) {
    if (event.key === 'Enter') {
        showLoading();
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
                                <label for="image" class="form-label">صورة المنتج <span class="text-danger">*</span></label>
                                <div class="file-input-wrapper">
                                    <label for="image" class="file-input-label">
                                        <i class="fa fa-upload"></i>
                                        <span>اختر الصورة</span>
                                    </label>
                                    <input type="file" 
                                           id="image" 
                                           name="image" 
                                           class="file-input" 
                                           accept="image/*" 
                                           required 
                                           onchange="previewImage(this, 'createImagePreview')">
                                </div>
                                <div class="image-preview-container">
                                    <div class="image-preview-placeholder" id="createImagePreview">
                                        <i class="fa fa-image" style="font-size: 2.5rem; opacity: 0.3;"></i>
                                        <span style="font-size: 0.85rem;">معاينة الصورة</span>
                                    </div>
                                </div>
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
                                <label for="edit_image" class="form-label">صورة المنتج (اختياري)</label>
                                <div class="file-input-wrapper">
                                    <label for="edit_image" class="file-input-label">
                                        <i class="fa fa-upload"></i>
                                        <span>اختر صورة جديدة</span>
                                    </label>
                                    <input type="file" 
                                           id="edit_image" 
                                           name="image" 
                                           class="file-input" 
                                           accept="image/*" 
                                           onchange="previewImage(this, 'editImagePreview')">
                                </div>
                                <div class="image-preview-container">
                                    <div class="image-preview-placeholder has-image" id="editImagePreview">
                                        <img src="${product.image}" alt="Current Image" onerror="this.parentElement.classList.remove('has-image'); this.parentElement.innerHTML='<i class=\\'fa fa-image\\' style=\\'font-size: 2.5rem; opacity: 0.3;\\'></i><span style=\\'font-size: 0.85rem;\\'>معاينة الصورة</span>';">
                                    </div>
                                </div>
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

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const label = input.previousElementSibling;
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const span = label.querySelector('span');
        if (span) {
            span.textContent = file.name.length > 20 ? file.name.substring(0, 20) + '...' : file.name;
        } else {
            label.innerHTML = `<i class="fa fa-upload"></i> <span>${file.name.length > 20 ? file.name.substring(0, 20) + '...' : file.name}</span>`;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            preview.classList.add('has-image');
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = `
            <i class="fa fa-image" style="font-size: 2.5rem; opacity: 0.3;"></i>
            <span style="font-size: 0.85rem;">معاينة الصورة</span>
        `;
        preview.classList.remove('has-image');
        const span = label.querySelector('span');
        if (span) {
            span.textContent = 'اختر الصورة';
        } else {
            label.innerHTML = '<i class="fa fa-upload"></i> <span>اختر الصورة</span>';
        }
    }
}

function deleteProduct(productId) {
    if (confirm('هل أنت متأكد من حذف هذا المنتج؟')) {
        showLoading();
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
                location.reload();
            } else {
                hideLoading();
                alert(data.message || 'حدث خطأ أثناء حذف المنتج');
            }
        })
        .catch(error => {
            hideLoading();
            alert('حدث خطأ أثناء حذف المنتج');
        });
    }
}

function restoreProduct(productId) {
    if (confirm('هل أنت متأكد من استعادة هذا المنتج؟')) {
        showLoading();
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
                location.reload();
            } else {
                hideLoading();
                alert(data.message || 'حدث خطأ أثناء استعادة المنتج');
            }
        })
        .catch(error => {
            hideLoading();
            alert('حدث خطأ أثناء استعادة المنتج');
        });
    }
}

function permanentlyDeleteProduct(productId) {
    if (confirm('هل أنت متأكد من حذف هذا المنتج نهائياً؟ لا يمكن التراجع عن هذا الإجراء.')) {
        showLoading();
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
                location.reload();
            } else {
                hideLoading();
                alert(data.message || 'حدث خطأ أثناء حذف المنتج');
            }
        })
        .catch(error => {
            hideLoading();
            alert('حدث خطأ أثناء حذف المنتج');
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    hideLoading();
    
    const modal = document.getElementById('productModal');
    if (modal) {
        modal.addEventListener('submit', function(e) {
            if (e.target.tagName === 'FORM') {
                e.preventDefault();
                const form = e.target;
                const formData = new FormData(form);
                const url = form.action;
                const method = form.method.toUpperCase();
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جاري المعالجة...';

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
                        showLoading();
                        location.reload();
                    } else {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                        alert(data.message || 'حدث خطأ');
                    }
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    alert('حدث خطأ أثناء معالجة الطلب');
                });
            }
        });
    }
});

function openOrderItemsModal(orderId) {
    fetch(`{{ route('admin.orders.items', ':id') }}`.replace(':id', orderId))
        .then(response => response.json())
        .then(data => {
            if (data.success && data.order && data.order.items) {
                const items = Array.isArray(data.order.items) ? data.order.items : [];
                const itemsHtml = `
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>اسم المنتج</th>
                                    <th>الكمية</th>
                                    <th>السعر</th>
                                    <th>الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${items.length > 0 ? items.map(item => `
                                    <tr>
                                        <td>${item.product_title || 'غير متوفر'}</td>
                                        <td>${item.quantity || 0}</td>
                                        <td>${item.price || 0} د.إ</td>
                                        <td>${item.total_price || 0} د.إ</td>
                                    </tr>
                                `).join('') : '<tr><td colspan="4" class="text-center">لا توجد عناصر</td></tr>'}
                            </tbody>
                        </table>
                    </div>
                `;
                document.getElementById('orderItemsContent').innerHTML = itemsHtml;
                const modal = new bootstrap.Modal(document.getElementById('orderItemsModal'));
                modal.show();
            } else {
                alert(data.message || 'حدث خطأ');
            }
        })
        .catch(error => {
            alert('حدث خطأ أثناء جلب بيانات الطلب');
        });
}

function openUserDetailsModal(orderId) {
    fetch(`{{ route('admin.orders.user', ':id') }}`.replace(':id', orderId))
        .then(response => response.json())
        .then(data => {
            if (data.success && data.user) {
                const user = data.user;
                const completedOrders = Array.isArray(user.completed_orders) ? user.completed_orders : [];
                const ordersHtml = completedOrders.length > 0 
                    ? completedOrders.map(order => `
                        <tr>
                            <td>${order.id || '-'}</td>
                            <td>${order.total_price || 0} د.إ</td>
                            <td>${order.payment_type === 'online' ? 'دفع إلكتروني' : 'تحويل بنكي'}</td>
                            <td>${order.items_count || 0}</td>
                            <td>${order.created_at || '-'}</td>
                        </tr>
                    `).join('')
                    : '<tr><td colspan="5" class="text-center">لا توجد طلبات مكتملة</td></tr>';

                const content = `
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">الاسم الكامل</label>
                            <div class="info-box">${user.full_name}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">البريد الإلكتروني</label>
                            <div class="info-box">${user.email || 'غير متوفر'}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">رقم الهاتف</label>
                            <div class="info-box">
                                ${user.phone ? `<a href="https://wa.me/${user.phone.replace(/[^0-9]/g, '')}" target="_blank" class="btn-action btn-view" style="text-decoration: none;"><i class="fab fa-whatsapp"></i> ${user.phone}</a>` : 'غير متوفر'}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">تاريخ التسجيل</label>
                            <div class="info-box">${user.created_at}</div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label">الطلبات المكتملة</label>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>رقم الطلب</th>
                                            <th>السعر الإجمالي</th>
                                            <th>نوع الدفع</th>
                                            <th>عدد العناصر</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${ordersHtml}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `;
                document.getElementById('userDetailsContent').innerHTML = content;
                const modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
                modal.show();
            } else {
                alert(data.message || 'حدث خطأ');
            }
        })
        .catch(error => {
            alert('حدث خطأ أثناء جلب بيانات المستخدم');
        });
}

function markOrderCompleted(orderId) {
    if (confirm('هل أنت متأكد من إكمال هذا الطلب؟')) {
        showLoading();
        fetch(`{{ route('admin.orders.complete', ':id') }}`.replace(':id', orderId), {
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
                hideLoading();
                alert(data.message || 'حدث خطأ');
            }
        })
        .catch(error => {
            hideLoading();
            alert('حدث خطأ أثناء تحديث حالة الطلب');
        });
    }
}

function deleteOrder(orderId) {
    if (confirm('هل أنت متأكد من حذف هذا الطلب؟')) {
        showLoading();
        fetch(`{{ route('admin.orders.destroy', ':id') }}`.replace(':id', orderId), {
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
                hideLoading();
                alert(data.message || 'حدث خطأ');
            }
        })
        .catch(error => {
            hideLoading();
            alert('حدث خطأ أثناء حذف الطلب');
        });
    }
}

function restoreOrder(orderId) {
    if (confirm('هل أنت متأكد من استعادة هذا الطلب؟')) {
        showLoading();
        fetch(`{{ route('admin.orders.restore', ':id') }}`.replace(':id', orderId), {
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
                hideLoading();
                alert(data.message || 'حدث خطأ');
            }
        })
        .catch(error => {
            hideLoading();
            alert('حدث خطأ أثناء استعادة الطلب');
        });
    }
}

function permanentlyDeleteOrder(orderId) {
    if (confirm('هل أنت متأكد من حذف هذا الطلب نهائياً؟ لا يمكن التراجع عن هذا الإجراء.')) {
        showLoading();
        fetch(`{{ route('admin.orders.permanent', ':id') }}`.replace(':id', orderId), {
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
                hideLoading();
                alert(data.message || 'حدث خطأ');
            }
        })
        .catch(error => {
            hideLoading();
            alert('حدث خطأ أثناء حذف الطلب');
        });
    }
}
</script>
@endsection

