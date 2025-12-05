@extends('Admin.layout')

@section('styles')
<style>
    .subscriptions-container {
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
        padding: 0.25rem;
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: #94a3b8;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        margin: 0 0.25rem;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }
    
    .nav-tabs .nav-link:hover {
        color: #fff;
        background: rgba(255,255,255,0.1);
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
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    }
    
    .filters-row {
        display: flex;
        gap: 1rem;
        align-items: end;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    
    .filter-group {
        flex: 1;
        min-width: 200px;
    }
    
    .filter-group label {
        display: block;
        color: #94a3b8;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .search-input,
    .filter-select {
        width: 100%;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: #fff;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
    }
    
    .search-input:focus,
    .filter-select:focus {
        background: rgba(255,255,255,0.08);
        border-color: #38bdf8;
        box-shadow: 0 0 0 0.2rem rgba(56,189,248,0.25);
        color: #fff;
        outline: none;
    }
    
    .search-input::placeholder {
        color: #64748b;
    }
    
    .filter-select option {
        background: #1E293B;
        color: #fff;
    }
    
    .actions-section {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 1rem;
    }
    
    .btn-action-top {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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
    
    .btn-action-top:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        color: white;
        text-decoration: none;
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
    
    .btn-settings {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        color: #fff;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }
    
    .btn-settings:hover {
        background: rgba(255,255,255,0.15);
        color: #fff;
        text-decoration: none;
    }
    
    .subscriptions-table {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    }
    
    .table {
        margin: 0;
        color: #fff;
    }
    
    .table tbody tr {
        border-bottom: 1px solid rgba(255,255,255,0.05);
        transition: all 0.3s ease;
        animation: fadeInRow 0.5s ease;
    }
    
    .subscription-row-first {
        background: rgba(255,255,255,0.02);
    }
    
    .subscription-row-first:hover {
        background: rgba(255,255,255,0.04);
    }
    
    .subscription-row-second {
        background: rgba(15, 23, 42, 0.3);
    }
    
    .subscription-row-second:hover {
        background: rgba(15, 23, 42, 0.5);
    }
    
    .subscription-row-first.fade-out,
    .subscription-row-second.fade-out {
        animation: fadeOutRow 0.3s ease forwards;
    }
    
    @keyframes fadeInRow {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeOutRow {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
    
    .tab-pane.active {
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
    
    .table tbody td {
        padding: 1.5rem 1rem;
        border: none;
        vertical-align: middle;
    }
    
    .user-details-cell {
        width: 70%;
    }
    
    .amount-cell {
        width: 30%;
        text-align: left;
    }
    
    .details-cell {
        width: 70%;
    }
    
    .actions-cell {
        width: 30%;
        text-align: right;
    }
    
    .action-buttons {
        justify-content: flex-start;
    }
    
    .user-info-container {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .user-name {
        font-size: 1.1rem;
        color: #fff;
    }
    
    .user-contact {
        display: flex;
        flex-direction: row;
        gap: 1.5rem;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .contact-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #94a3b8;
        font-size: 0.9rem;
    }
    
    .contact-item i {
        color: #38bdf8;
        width: 16px;
    }
    
    .contact-value {
        flex: 1;
    }
    
    .whatsapp-link {
        color: #25D366;
        text-decoration: none;
        flex: 1;
        transition: all 0.3s ease;
    }
    
    .whatsapp-link:hover {
        color: #128C7E;
        text-decoration: underline;
    }
    
    .btn-copy {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 6px;
        padding: 0.25rem 0.5rem;
        color: #94a3b8;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.8rem;
    }
    
    .btn-copy:hover {
        background: rgba(255,255,255,0.15);
        color: #fff;
        border-color: #38bdf8;
    }
    
    .amount-display {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
        text-align: center;
    }
    
    .workshop-title {
        color: #38bdf8;
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-align: center;
    }
    
    .amount-display strong {
        font-size: 1.5rem;
        color: #10b981;
    }
    
    .currency {
        color: #94a3b8;
        font-size: 0.9rem;
    }
    
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }
    
    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .detail-label {
        color: #94a3b8;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .detail-value {
        color: #fff;
        font-size: 0.9rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.25rem;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .btn-action {
        border: none;
        border-radius: 6px;
        padding: 0.4rem 0.6rem;
        color: white;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        min-width: 32px;
        height: 32px;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }
    
    .btn-action i {
        font-size: 0.9rem;
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }
    
    .btn-delete:hover {
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    .btn-view {
        background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
    }
    
    .btn-view:hover {
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }
    
    .btn-edit:hover {
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }
    
    .btn-transfer {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    .btn-transfer:hover {
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    
    .btn-certificate {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .btn-certificate:hover {
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .btn-invoice {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    }
    
    .btn-invoice:hover {
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    
    .btn-refund {
        background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
    }
    
    .btn-refund:hover {
        box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
    }
    .btn-calendar {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
    }
    
    .btn-calendar:hover {
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .btn-user {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    }
    
    .btn-user:hover {
        box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
    }
    
    .badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .badge-paid {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
    }
    
    .badge-active {
        background: linear-gradient(135deg, #38bdf8, #0284c7);
        color: #fff;
    }
    
    .badge-pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
    }
    
    .badge-expired {
        background: linear-gradient(135deg, #64748b, #475569);
        color: #fff;
    }
    
    .badge-failed {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
    }
    
    .badge-refunded {
        background: linear-gradient(135deg, #ec4899, #db2777);
        color: #fff;
    }
    
    .badge-gift {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: #fff;
        font-size: 0.7rem;
        padding: 0.3rem 0.6rem;
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
    
    .user-search-container {
        position: relative;
    }
    
    .user-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        margin-top: 0.25rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    
    .search-result-item {
        padding: 0.75rem 1rem;
        cursor: pointer;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        transition: all 0.3s ease;
    }
    
    .search-result-item:hover {
        background: rgba(255,255,255,0.05);
    }
    
    .search-result-item:last-child {
        border-bottom: none;
    }
    
    .search-result-item strong {
        color: #fff;
        display: block;
        margin-bottom: 0.25rem;
    }
    
    .search-result-item small {
        color: #94a3b8;
        font-size: 0.85rem;
    }
</style>
@endsection

@section('main')
<div class="subscriptions-container" dir="rtl">
    <!-- Filters Section -->
    <!-- Action Buttons Section -->
    <div class="action-buttons-section" style="background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%); border-radius: 15px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 4px 16px rgba(0,0,0,0.25);">
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
            <!-- First Row -->
            <button type="button" class="action-btn" onclick="openPendingApprovalsModal()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; border-radius: 10px; padding: 1rem; color: #fff; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fa fa-check-circle me-2"></i>
                موافقات معلقة
            </button>

             <button type="button" class="action-btn" onclick="openTotalSubscriptionsModal()" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; border-radius: 10px; padding: 1rem; color: #fff; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fa fa-chart-line me-2"></i>
                إجمالي الإشتراكات
            </button>

            <button type="button" class="action-btn" onclick="alert('سيتم تنفيذ هذه الوظيفة لاحقاً')" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none; border-radius: 10px; padding: 1rem; color: #fff; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fa fa-exchange-alt me-2"></i>
                التحويلات
            </button>
            
            <button type="button" class="action-btn" onclick="alert('سيتم تنفيذ هذه الوظيفة لاحقاً')" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border: none; border-radius: 10px; padding: 1rem; color: #fff; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fa fa-undo me-2"></i>
                المستردات
            </button>
           
            <!-- Second Row -->
            <button type="button" class="action-btn" onclick="alert('سيتم تنفيذ هذه الوظيفة لاحقاً')" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border: none; border-radius: 10px; padding: 1rem; color: #fff; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fa fa-credit-card me-2"></i>
                اشتراكات بالرصيد
            </button>

            <button type="button" class="action-btn" onclick="alert('سيتم تنفيذ هذه الوظيفة لاحقاً')" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border: none; border-radius: 10px; padding: 1rem; color: #fff; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fa fa-wallet me-2"></i>
                الأرصدة المتاحة
            </button>

            <button type="button" class="action-btn" onclick="alert('سيتم تنفيذ هذه الوظيفة لاحقاً')" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; border-radius: 10px; padding: 1rem; color: #fff; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fa fa-exclamation-triangle me-2"></i>
                المديونيات
            </button>

            <button type="button" class="action-btn" onclick="alert('سيتم تنفيذ هذه الوظيفة لاحقاً')" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; border-radius: 10px; padding: 1rem; color: #fff; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fa fa-gift me-2"></i>
                الهدايا المعلقة
            </button>
    
        </div>
    </div>
    
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.subscriptions.index') }}" id="filterForm">
            <input type="hidden" name="tab" value="{{ $tab }}" id="tabInput">
            <div class="filters-row">
                <div class="filter-group" style="flex: 2;">
                    <label for="search">بحث شامل</label>
                    <input type="text" 
                        id="search" 
                           name="search" 
                           class="search-input" 
                           placeholder="بحث بالاسم, الايميل, الهاتف, الورشة...."
                        value="{{ request('search') }}"
                           onkeyup="handleSearch(event)">
                </div>
                <div class="filter-group">
                    <label for="workshop_id">الورشة</label>
                    <select name="workshop_id" id="workshop_id" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">الكل</option>
                        @foreach($workshops as $workshop)
                            <option value="{{ $workshop->id }}" {{ request('workshop_id') == $workshop->id ? 'selected' : '' }}>
                                {{ $workshop->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="status">حالة الاشتراك</label>
                    <select name="status" id="status" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">الكل</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                {{ __('enums.subscription_statuses.' . $status->value, [], 'ar') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="actions-section">
                <button type="button" class="btn-action-top" onclick="openNewSubscriptionModal()">
                    <i class="fa fa-plus"></i>
                    اشتراك جديد
                </button>
                <a href="{{ route('admin.subscriptions.export.excel', array_merge(request()->all(), ['tab' => $tab])) }}" class="btn-export">
                    <i class="fa fa-file-excel"></i>
                    تصدير Excel
                </a>
                <a href="{{ route('admin.subscriptions.export.pdf', array_merge(request()->all(), ['tab' => $tab])) }}" class="btn-export">
                    <i class="fa fa-file-pdf"></i>
                    تصدير PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Tabs -->
    <div class="tabs-container">
        <ul class="nav nav-tabs" id="subscriptionsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $tab === 'active' ? 'active' : '' }}" 
                        id="active-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#active-subscriptions" 
                        type="button" 
                        role="tab"
                        onclick="switchTab('active')">
                    الاشتراكات الموجودة
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $tab === 'deleted' ? 'active' : '' }}" 
                        id="deleted-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#deleted-subscriptions" 
                        type="button" 
                        role="tab"
                        onclick="switchTab('deleted')">
                    الاشتراكات المحذوفة
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="subscriptionsTabContent">
        <!-- Active Subscriptions Tab -->
        <div class="tab-pane fade {{ $tab === 'active' ? 'show active' : '' }}" 
             id="active-subscriptions" 
             role="tabpanel">
            @include('Admin.subscriptions.partials.subscriptions-table', ['subscriptions' => $subscriptions, 'isDeleted' => false])
        </div>

        <!-- Deleted Subscriptions Tab -->
        <div class="tab-pane fade {{ $tab === 'deleted' ? 'show active' : '' }}" 
             id="deleted-subscriptions" 
             role="tabpanel">
            @include('Admin.subscriptions.partials.subscriptions-table', ['subscriptions' => $subscriptions, 'isDeleted' => true])
        </div>
    </div>
</div>

<!-- Main Modal -->
<div class="modal fade" id="subscriptionModal" tabindex="-1" aria-labelledby="subscriptionModalLabel" aria-hidden="true" dir="rtl">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subscriptionModalLabel">إضافة اشتراك جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Create Subscription Modal Template -->
<div style="display: none;">
    <div id="createSubscriptionModalTemplate">
        <form id="createSubscriptionForm" dir="rtl">
            @csrf
            <!-- Section 1: User Selection -->
            <div class="mb-4">
                <h6 class="mb-3" style="color: #38bdf8; font-weight: 700;">1. تحديد المستفيد أو إنشاء مستفيد جديد</h6>
                <div class="mb-3">
                    <label class="form-label">ابحث عن مستفيد (بالاسم, الايميل, أو الهاتف)</label>
                    <div class="user-search-container" style="position: relative;">
                        <input type="text" 
                               id="userSearchInput" 
                               class="form-control" 
                               placeholder="ابدأ الكتابة للبحث..."
                               autocomplete="off">
                        <input type="hidden" id="selectedUserId" name="user_id">
                        <div id="userSearchResults" class="user-search-results" style="display: none;"></div>
                    </div>
                    <button type="button" class="btn btn-secondary mt-2" onclick="showNewUserForm()" style="font-size: 0.85rem;">
                        <i class="fa fa-plus"></i> إنشاء مستفيد جديد
                    </button>
                </div>
                
                <!-- New User Form (Hidden by default) -->
                <div id="newUserForm" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="new_full_name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="new_full_name" name="full_name" placeholder="الاسم الكامل">
                        </div>
                        <div class="col-md-6">
                            <label for="new_email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="new_email" name="email" placeholder="example@email.com">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="new_phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="new_phone" name="phone" placeholder="501234567">
                        </div>
                        <div class="col-md-6">
                            <label for="new_country_id" class="form-label">اختر الدولة</label>
                            <select class="form-select" id="new_country_id" name="country_id">
                                <option value="">اختر الدولة</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                    </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" onclick="hideNewUserForm()" style="font-size: 0.85rem;">
                        <i class="fa fa-times"></i> إلغاء
                    </button>
                </div>
            </div>

            <!-- Section 2: Subscription Details -->
            <div class="mb-4">
                <h6 class="mb-3" style="color: #38bdf8; font-weight: 700;">2. تفاصيل الاشتراك</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="workshop_id" class="form-label">الورشة <span class="text-danger">*</span></label>
                        <select class="form-select" id="workshop_id" name="workshop_id" required onchange="handleWorkshopChange(event)">
                            <option value="">اختر ورشة...</option>
                            @foreach($workshops as $workshop)
                                <option value="{{ $workshop->id }}">{{ $workshop->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="package_id" class="form-label">الباقة (إن وجدت)</label>
                        <select class="form-select" id="package_id" name="package_id">
                            <option value="">لا توجد باقات</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="actual_price" class="form-label">المبلغ الفعلي للورشة / الباقة</label>
                        <input type="text" class="form-control" id="actual_price" readonly placeholder="السعر الفعلي" style="background: rgba(255,255,255,0.1);">
                    </div>
                    <div class="col-md-6">
                        <label for="paid_amount" class="form-label">المبلغ المدفوع <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" id="paid_amount" name="paid_amount" required placeholder="المبلغ المدفوع">
                    </div>
                </div>
                <!-- User Balance Section (shown only when user is selected and has balance) -->
                <div class="row mb-3" id="userBalanceSection" style="display: none;">
                    <div class="col-md-12">
                        <div class="alert alert-info" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 8px; padding: 1rem;">
                            <label for="balance_used" class="form-label d-block mb-2">
                                <strong>استخدام الرصيد الداخلي</strong>
                                <span id="userBalanceDisplay" class="ms-2" style="color: #3b82f6;"></span>
                            </label>
                            <input type="number" 
                                   step="0.01" 
                                   min="0" 
                                   class="form-control" 
                                   id="balance_used" 
                                   name="balance_used" 
                                   placeholder="0.00"
                                   style="max-width: 300px;">
                            <small class="text-muted d-block mt-1">المبلغ المستخدم من الرصيد الداخلي للمستخدم</small>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="payment_type" class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                        <select class="form-select" id="payment_type" name="payment_type" required>
                            <option value="">اختر طريقة...</option>
                            @foreach(\App\Enums\Payment\PaymentType::cases() as $paymentType)
                                <option value="{{ $paymentType->value }}">{{ __('enums.payment_types.' . $paymentType->value, [], 'ar') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="transferer_name" class="form-label">الشخص المحول منه قيمة الاشتراك</label>
                        <input type="text" class="form-control" id="transferer_name" name="transferer_name" placeholder="اسم المحول">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="notes" class="form-label">ملاحظات</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="ملاحظات إضافية"></textarea>
                    </div>
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

    <!-- Edit Subscription Modal Template -->
    <div id="editSubscriptionModalTemplate">
        <form id="editSubscriptionForm" dir="rtl">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" id="edit_subscription_id" name="subscription_id">
            
            <!-- Section 1: User Info (Read-only) -->
            <div class="mb-4">
                <h6 class="mb-3" style="color: #38bdf8; font-weight: 700;">1. معلومات المستفيد</h6>
                <div class="alert alert-secondary" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                    <strong id="edit_user_name">-</strong>
                </div>
        </div>

            <!-- Section 2: Subscription Details -->
            <div class="mb-4">
                <h6 class="mb-3" style="color: #38bdf8; font-weight: 700;">2. تفاصيل الاشتراك</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="edit_workshop_id" class="form-label">الورشة</label>
                        <input type="text" class="form-control" id="edit_workshop_id" readonly style="background: rgba(255,255,255,0.1);">
                                    </div>
                    <div class="col-md-6">
                        <label for="edit_package_id" class="form-label">الباقة (إن وجدت)</label>
                        <select class="form-select" id="edit_package_id" name="package_id">
                            <option value="">لا توجد باقة</option>
                        </select>
                                    </div>
                                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="edit_actual_price" class="form-label">المبلغ الفعلي للورشة / الباقة</label>
                        <input type="text" class="form-control" id="edit_actual_price" readonly style="background: rgba(255,255,255,0.1);">
                    </div>
                    <div class="col-md-6">
                        <label for="edit_paid_amount" class="form-label">المبلغ المدفوع <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" id="edit_paid_amount" name="paid_amount" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="edit_payment_type" class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_payment_type" name="payment_type" required>
                            <option value="">اختر طريقة...</option>
                            @foreach(\App\Enums\Payment\PaymentType::cases() as $paymentType)
                                <option value="{{ $paymentType->value }}">{{ __('enums.payment_types.' . $paymentType->value, [], 'ar') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_transferer_name" class="form-label">الشخص المحول منه قيمة الاشتراك</label>
                        <input type="text" class="form-control" id="edit_transferer_name" name="transferer_name" placeholder="اسم المحول">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="edit_notes" class="form-label">ملاحظات</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3" placeholder="ملاحظات إضافية"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-2"></i>
                    حفظ التغييرات
                                    </button>
            </div>
                                </form>
    </div>
    
    <!-- Invoice Modal Template -->
    <div id="invoiceModalTemplate" style="display: none;">
        <div class="invoice-preview" style="background: white; padding: 2rem; border-radius: 8px; max-width: 800px; margin: 0 auto;">
            <!-- Invoice Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb;">
                <div>
                    <h2 style="color: #1f2937; margin: 0; font-size: 1.75rem; font-weight: 700;">فاتورة ضريبية</h2>
                    <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.875rem;">Tax Invoice</p>
                </div>
                <div>
                    <img id="invoice_logo" src="" alt="Logo" style="max-width: 150px; max-height: 80px; object-fit: contain;">
                </div>
            </div>
            
            <!-- Company and Recipient Info -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <h4 style="color: #374151; margin-bottom: 0.75rem; font-size: 0.875rem; font-weight: 600;">من:</h4>
                    <div style="color: #6b7280; font-size: 0.875rem; line-height: 1.6;">
                        <div id="invoice_company_name" style="font-weight: 600; color: #1f2937; margin-bottom: 0.5rem;">-</div>
                        <div id="invoice_company_address">-</div>
                        <div id="invoice_company_phone" style="margin-top: 0.5rem;">-</div>
                    </div>
                </div>
                <div>
                    <h4 style="color: #374151; margin-bottom: 0.75rem; font-size: 0.875rem; font-weight: 600;">إلى:</h4>
                    <div style="color: #6b7280; font-size: 0.875rem; line-height: 1.6;">
                        <div id="invoice_user_name" style="font-weight: 600; color: #1f2937; margin-bottom: 0.5rem;">-</div>
                        <div id="invoice_user_email">-</div>
                        <div id="invoice_user_phone" style="margin-top: 0.5rem;">-</div>
                    </div>
                </div>
            </div>
            
            <!-- Invoice Details -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem; padding: 1rem; background: #f9fafb; border-radius: 6px;">
                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">تاريخ الفاتورة:</div>
                    <div id="invoice_date" style="color: #1f2937; font-weight: 600;">-</div>
                </div>
                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">رقم الفاتورة:</div>
                    <div id="invoice_number" style="color: #1f2937; font-weight: 600;">-</div>
                </div>
            </div>
            
            <!-- Package Info -->
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f3f4f6; border-radius: 6px;">
                <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">الباقة:</div>
                <div id="invoice_package" style="color: #1f2937; font-weight: 600;">-</div>
            </div>
            
            <!-- Items Table -->
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 1.5rem;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                        <th style="padding: 0.75rem; text-align: right; color: #374151; font-weight: 600; font-size: 0.875rem;">الوصف</th>
                        <th style="padding: 0.75rem; text-align: center; color: #374151; font-weight: 600; font-size: 0.875rem;">صافي المبلغ</th>
                        <th style="padding: 0.75rem; text-align: center; color: #374151; font-weight: 600; font-size: 0.875rem;">الضريبة (5%)</th>
                        <th style="padding: 0.75rem; text-align: center; color: #374151; font-weight: 600; font-size: 0.875rem;">الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem; color: #1f2937;">
                            <div id="invoice_workshop_title" style="font-weight: 600; margin-bottom: 0.25rem;">-</div>
                            <div id="invoice_package_title" style="font-size: 0.875rem; color: #6b7280;">-</div>
                            </td>
                        <td style="padding: 1rem; text-align: center; color: #1f2937;" id="invoice_subtotal">-</td>
                        <td style="padding: 1rem; text-align: center; color: #1f2937;" id="invoice_vat">-</td>
                        <td style="padding: 1rem; text-align: center; color: #1f2937; font-weight: 600;" id="invoice_total">-</td>
                        </tr>
                    </tbody>
                </table>
            
            <!-- Summary -->
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid #e5e7eb;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                    <span style="color: #6b7280; font-size: 0.875rem;">المجموع الفرعي:</span>
                    <span style="color: #1f2937; font-weight: 600;" id="invoice_summary_subtotal">-</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                    <span style="color: #6b7280; font-size: 0.875rem;">ضريبة القيمة المضافة (5%):</span>
                    <span style="color: #1f2937; font-weight: 600;" id="invoice_summary_vat">-</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 1rem; background: #7c3aed; color: white; border-radius: 6px; margin-top: 1rem;">
                    <span style="font-weight: 600; font-size: 1rem;">الإجمالي المستحق (درهم):</span>
                    <span style="font-weight: 700; font-size: 1.125rem;" id="invoice_summary_total">-</span>
                </div>
            </div>

            <!-- Footer -->
            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb; text-align: center;">
                <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">شكرا لثقتكم بنا!</div>
                <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">الرقم الضريبي: <span id="invoice_tax_number" style="font-weight: 600; color: #1f2937;">-</span></div>
                <div style="color: #6b7280; font-size: 0.875rem; font-weight: 600;">Nawaya Events</div>
            </div>
                </div>
            </div>
    
    <!-- Include Modal Partials -->
    @include('Admin.subscriptions.partials.modals.pending-approvals')
    @include('Admin.subscriptions.partials.modals.total-subscriptions')
    
    <!-- Transfer Modal Template -->
    <div id="transferModalTemplate" style="display: none;">
        <form id="transferForm" dir="rtl">
            @csrf
            <input type="hidden" id="transfer_subscription_id" name="subscription_id">
            <input type="hidden" id="transfer_current_workshop_id" name="current_workshop_id">
            <input type="hidden" id="transfer_paid_amount" name="paid_amount">
            
            <!-- User and Current Workshop Info (Read-only) -->
            <div class="mb-4">
                <div class="alert alert-secondary" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 1rem; border-radius: 8px;">
                    <div class="mb-2">
                        <strong style="color: #94a3b8; font-size: 0.9rem;">المشترك:</strong>
                        <span id="transfer_user_name" style="color: #fff; font-weight: 600; margin-right: 0.5rem;">-</span>
                    </div>
                    <div>
                        <strong style="color: #94a3b8; font-size: 0.9rem;">الورشة الحالية:</strong>
                        <span id="transfer_current_workshop_title" style="color: #fff; font-weight: 600; margin-right: 0.5rem;">-</span>
                    </div>
                </div>
            </div>
            
            <!-- New Workshop Selection -->
            <div class="mb-4">
                <label for="transfer_workshop_id" class="form-label">اختر الورشة الجديدة للتحويل إليها: <span class="text-danger">*</span></label>
                <select class="form-select" id="transfer_workshop_id" name="workshop_id" required onchange="handleTransferWorkshopChange(event)">
                    <option value="">اختر ورشة...</option>
                    @foreach($workshops as $workshop)
                        <option value="{{ $workshop->id }}">{{ $workshop->title }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Package Selection -->
            <div class="mb-4">
                <label for="transfer_package_id" class="form-label">الباقة (إن وجدت)</label>
                <select class="form-select" id="transfer_package_id" name="package_id" onchange="handleTransferPackageChange(event)">
                    <option value="">لا توجد باقة</option>
                </select>
            </div>
            
            <!-- Price Information -->
            <div class="mb-4" id="transfer_price_info" style="display: none;">
                <div class="alert alert-info" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 8px; padding: 1rem;">
                    <div class="mb-2">
                        <strong style="color: #94a3b8; font-size: 0.9rem;">المبلغ المدفوع سابقاً:</strong>
                        <span id="transfer_old_paid_amount" style="color: #fff; font-weight: 600; margin-right: 0.5rem;">-</span>
                        <span style="color: #94a3b8;">د.إ</span>
                    </div>
                    <div class="mb-2">
                        <strong style="color: #94a3b8; font-size: 0.9rem;">سعر الورشة الجديدة:</strong>
                        <span id="transfer_new_price" style="color: #fff; font-weight: 600; margin-right: 0.5rem;">-</span>
                        <span style="color: #94a3b8;">د.إ</span>
                    </div>
                    <div class="mb-2">
                        <strong style="color: #94a3b8; font-size: 0.9rem;">فرق السعر:</strong>
                        <span id="transfer_price_difference" style="color: #fff; font-weight: 600; margin-right: 0.5rem;">-</span>
                        <span style="color: #94a3b8;">د.إ</span>
                    </div>
                    <div id="transfer_positive_difference_note" style="display: none; margin-top: 0.5rem; color: #10b981; font-size: 0.85rem;">
                        <i class="fa fa-info-circle"></i> سيتم إضافة الفرق إلى رصيد المشترك الداخلي
                    </div>
                    <div id="transfer_negative_difference_note" style="display: none; margin-top: 0.5rem; color: #f59e0b; font-size: 0.85rem;">
                        <i class="fa fa-info-circle"></i> مبلغ مستحق على المشترك
                    </div>
                </div>
            </div>
            
            <!-- Balance Section (shown when difference is negative and user has balance) -->
            <div class="mb-4" id="transfer_balance_section" style="display: none;">
                <div class="alert alert-warning" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 8px; padding: 1rem;">
                    <label for="transfer_balance_used" class="form-label d-block mb-2">
                        <strong>استخدام الرصيد الداخلي</strong>
                        <span id="transfer_user_balance_display" class="ms-2" style="color: #f59e0b;"></span>
                    </label>
                    <input type="number" 
                           step="0.01" 
                           min="0" 
                           class="form-control" 
                           id="transfer_balance_used" 
                           name="balance_used" 
                           placeholder="0.00"
                           style="max-width: 300px;">
                    <small class="text-muted d-block mt-1">المبلغ المستخدم من الرصيد الداخلي للمستخدم</small>
                </div>
            </div>
            
            <!-- Additional Paid Amount (shown when difference is negative) -->
            <div class="mb-4" id="transfer_additional_paid_section" style="display: none;">
                <label for="transfer_additional_paid_amount" class="form-label">المبلغ المدفوع الإضافي</label>
                <input type="number" 
                       step="0.01" 
                       min="0" 
                       class="form-control" 
                       id="transfer_additional_paid_amount" 
                       name="paid_amount" 
                       placeholder="0.00"
                       oninput="validateTransferAdditionalPaidAmount(this)">
                <small class="text-muted d-block mt-1">المبلغ الإضافي الذي سيدفعه المشترك (يمكن تعديله لاحقاً)</small>
                <small id="transfer_additional_paid_error" class="text-danger d-block mt-1" style="display: none;"></small>
            </div>
            
            <!-- Notes -->
            <div class="mb-4">
                <label for="transfer_notes" class="form-label">ملاحظات (اختياري)</label>
                <textarea class="form-control" id="transfer_notes" name="notes" rows="3" placeholder="أضف ملاحظات حول عملية التحويل..."></textarea>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;">
                    <i class="fa fa-check me-2"></i>
                    تأكيد التحويل
                </button>
            </div>
        </form>
    </div>
    
    <!-- Refund Modal Template -->
    <div id="refundModalTemplate" style="display: none;">
        <form id="refundForm" dir="rtl">
            @csrf
            <input type="hidden" id="refund_subscription_id" name="subscription_id">
            
            <!-- User Information (Read-only) -->
            <div class="mb-4">
                <div class="alert alert-secondary" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 1rem; border-radius: 8px;">
                    <div class="mb-2">
                        <strong style="color: #94a3b8; font-size: 0.9rem;">المستخدم:</strong>
                        <span id="refund_user_name" style="color: #fff; font-weight: 600; margin-right: 0.5rem;">-</span>
                    </div>
                    <div class="mb-2">
                        <strong style="color: #94a3b8; font-size: 0.9rem;">الورشة:</strong>
                        <span id="refund_workshop_title" style="color: #fff; font-weight: 600; margin-right: 0.5rem;">-</span>
                    </div>
                    <div>
                        <strong style="color: #94a3b8; font-size: 0.9rem;">المبلغ المدفوع:</strong>
                        <span id="refund_paid_amount" style="color: #10b981; font-weight: 700; font-size: 1.1rem; margin-right: 0.5rem;">-</span>
                        <span style="color: #94a3b8;">د.إ</span>
                    </div>
                </div>
            </div>
            
            <!-- Refund Type Selection -->
            <div class="mb-4">
                <label for="refund_type" class="form-label">اختر طريقة الاسترداد: <span class="text-danger">*</span></label>
                <select class="form-select" id="refund_type" name="refund_type" required>
                    <option value="">اختر طريقة...</option>
                    @foreach(\App\Enums\Payment\RefundType::cases() as $refundType)
                        <option value="{{ $refundType->value }}">{{ __('enums.refund_types.' . $refundType->value, [], 'ar') }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Refund Notes -->
            <div class="mb-4">
                <label for="refund_notes" class="form-label">ملاحظات الاسترداد</label>
                <textarea class="form-control" id="refund_notes" name="refund_notes" rows="4" placeholder="أدخل ملاحظات حول الاسترداد (اختياري)"></textarea>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;">
                    <i class="fa fa-check me-2"></i>
                    تأكيد الاسترداد
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentTab = '{{ $tab }}';
let allPackages = @json($packages);
console.log('All packages loaded:', allPackages);
console.log('Packages count:', allPackages ? allPackages.length : 0);

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

let userSearchTimeout;
let selectedUser = null;

function openNewSubscriptionModal() {
    const template = document.getElementById('createSubscriptionModalTemplate');
    document.getElementById('modalContent').innerHTML = template.innerHTML;
    document.getElementById('subscriptionModalLabel').textContent = 'إضافة اشتراك جديد';
    const modal = new bootstrap.Modal(document.getElementById('subscriptionModal'));
    modal.show();
    
    // Initialize form handlers after modal is shown
    setTimeout(() => {
        initializeSubscriptionForm();
    }, 300);
}

function initializeSubscriptionForm() {
    console.log('Initializing subscription form...');
    console.log('allPackages available:', typeof allPackages !== 'undefined', allPackages);
    
    // User search handler
    const userSearchInput = document.getElementById('userSearchInput');
    if (userSearchInput) {
        userSearchInput.addEventListener('input', handleUserSearch);
        userSearchInput.addEventListener('blur', () => {
            setTimeout(() => {
                const results = document.getElementById('userSearchResults');
                if (results) results.style.display = 'none';
            }, 200);
        });
    }

    // Workshop change handler
    const workshopSelect = document.getElementById('workshop_id');
    if (workshopSelect) {
        console.log('✅ Workshop select found');
        console.log('handleWorkshopChange function exists?', typeof handleWorkshopChange);
        // The inline onchange handler should work, but also attach programmatically
        workshopSelect.addEventListener('change', function(e) {
            console.log('📢 Change event fired via addEventListener!', e.target.value);
            handleWorkshopChange(e);
        });
    } else {
        console.error('❌ Workshop select NOT found!');
    }

    // Package change handler
    const packageSelect = document.getElementById('package_id');
    if (packageSelect) {
        packageSelect.addEventListener('change', handlePackageChange);
    }

    // Form submission handler
    const form = document.getElementById('createSubscriptionForm');
    if (form) {
        form.addEventListener('submit', handleSubscriptionSubmit);
    }
}

function handleUserSearch(event) {
    const query = event.target.value.trim();
    const resultsDiv = document.getElementById('userSearchResults');
    
    if (query.length < 2) {
        if (resultsDiv) resultsDiv.style.display = 'none';
        selectedUser = null;
        const userIdInput = document.getElementById('selectedUserId');
        if (userIdInput) userIdInput.value = '';
        return;
    }

    clearTimeout(userSearchTimeout);
    userSearchTimeout = setTimeout(() => {
        fetch(`/subscriptions/search-users?search=${encodeURIComponent(query)}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                displayUserSearchResults(data.data);
            }
        })
        .catch(error => {
            console.error('Search error:', error);
        });
    }, 300);
}

function displayUserSearchResults(users) {
    const resultsDiv = document.getElementById('userSearchResults');
    if (!resultsDiv) return;

    if (users.length === 0) {
        resultsDiv.innerHTML = '<div class="search-result-item" style="padding: 0.75rem; color: #94a3b8;">لا توجد نتائج</div>';
        resultsDiv.style.display = 'block';
        return;
    }

    resultsDiv.innerHTML = users.map(user => {
        const name = (user.full_name || '').replace(/'/g, "\\'");
        const email = (user.email || '').replace(/'/g, "\\'");
        const phone = (user.phone || '').replace(/'/g, "\\'");
        const balance = parseFloat(user.balance || 0);
        return `
            <div class="search-result-item" onclick="selectUser(${user.id}, '${name}', '${email}', '${phone}', ${balance})">
                <strong>${user.full_name}</strong>
                ${user.email ? `<br><small style="color: #94a3b8;">${user.email}</small>` : ''}
                ${user.phone ? `<br><small style="color: #94a3b8;">${user.phone}</small>` : ''}
                ${balance > 0 ? `<br><small style="color: #10b981; font-weight: 600;">الرصيد: ${balance.toFixed(2)} د.إ</small>` : ''}
            </div>
        `;
    }).join('');
    resultsDiv.style.display = 'block';
}

function selectUser(userId, name, email, phone, balance = 0) {
    selectedUser = { id: userId, name, email, phone, balance: parseFloat(balance) || 0 };
    const userIdInput = document.getElementById('selectedUserId');
    const searchInput = document.getElementById('userSearchInput');
    if (userIdInput) userIdInput.value = userId;
    if (searchInput) searchInput.value = name;
    const results = document.getElementById('userSearchResults');
    if (results) results.style.display = 'none';
    hideNewUserForm();
    
    // Show/hide balance section
    updateUserBalanceDisplay(selectedUser.balance);
}

function showNewUserForm() {
    const newUserForm = document.getElementById('newUserForm');
    if (newUserForm) newUserForm.style.display = 'block';
    const searchInput = document.getElementById('userSearchInput');
    if (searchInput) searchInput.value = '';
    const userIdInput = document.getElementById('selectedUserId');
    if (userIdInput) userIdInput.value = '';
    selectedUser = null;
    const results = document.getElementById('userSearchResults');
    if (results) results.style.display = 'none';
    // Hide balance section when creating new user
    updateUserBalanceDisplay(0);
}

function hideNewUserForm() {
    const newUserForm = document.getElementById('newUserForm');
    if (newUserForm) {
        newUserForm.style.display = 'none';
        // Clear new user fields
        const fullName = document.getElementById('new_full_name');
        const email = document.getElementById('new_email');
        const phone = document.getElementById('new_phone');
        const country = document.getElementById('new_country_id');
        if (fullName) fullName.value = '';
        if (email) email.value = '';
        if (phone) phone.value = '';
        if (country) country.value = '';
    }
    // Hide balance section when creating new user
    updateUserBalanceDisplay(0);
}

function updateUserBalanceDisplay(balance) {
    const balanceSection = document.getElementById('userBalanceSection');
    const balanceDisplay = document.getElementById('userBalanceDisplay');
    const balanceInput = document.getElementById('balance_used');
    
    if (balance > 0) {
        if (balanceSection) balanceSection.style.display = 'block';
        if (balanceDisplay) balanceDisplay.textContent = `(المتاح: ${balance.toFixed(2)} د.إ)`;
        if (balanceInput) {
            balanceInput.setAttribute('max', balance);
            balanceInput.addEventListener('input', function() {
                const value = parseFloat(this.value) || 0;
                if (value > balance) {
                    this.value = balance.toFixed(2);
                    showToast('لا يمكن استخدام مبلغ أكبر من الرصيد المتاح', 'error');
                }
            });
        }
    } else {
        if (balanceSection) balanceSection.style.display = 'none';
        if (balanceInput) balanceInput.value = '';
    }
}

function handleWorkshopChange(event) {
    console.log('🚀 handleWorkshopChange called!', event);
    const workshopId = event ? parseInt(event.target.value) : parseInt(this.value);
    console.log('Workshop ID from event:', workshopId);
    const packageSelect = document.getElementById('package_id');
    const actualPriceInput = document.getElementById('actual_price');
    
    console.log('Workshop ID:', workshopId);
    console.log('Package select element:', packageSelect);
    console.log('allPackages:', allPackages);
    console.log('allPackages type:', typeof allPackages);
    console.log('Is array?', Array.isArray(allPackages));
    
    // Clear package and price
    if (packageSelect) packageSelect.innerHTML = '<option value="">لا توجد باقات</option>';
    if (actualPriceInput) actualPriceInput.value = '';
    const paidAmount = document.getElementById('paid_amount');
    if (paidAmount) paidAmount.value = '';

    if (!workshopId) {
        console.error('No workshop ID provided!');
        return;
    }
    
    if (!allPackages) {
        console.error('allPackages is not defined!');
        return;
    }

    // Ensure allPackages is an array
    const packagesArray = Array.isArray(allPackages) ? allPackages : [];
    console.log('Packages array length:', packagesArray.length);
    console.log('First package:', packagesArray[0]);
    console.log('Filtering for workshop_id:', workshopId, 'Type:', typeof workshopId);

    // Filter packages client-side - compare as numbers
    const filteredPackages = packagesArray.filter(pkg => {
        const pkgWorkshopId = parseInt(pkg.workshop_id);
        const matches = pkgWorkshopId === workshopId;
        console.log(`Package ${pkg.id}: workshop_id=${pkgWorkshopId} (${typeof pkgWorkshopId}), matches=${matches}`);
        return matches;
    });
    
    console.log('Filtered packages count:', filteredPackages.length);
    console.log('Filtered packages:', filteredPackages);
    
    if (filteredPackages.length > 0 && packageSelect) {
        const options = filteredPackages.map(pkg => {
            const offerExpiry = pkg.offer_expiry_date || '';
            const offerPrice = pkg.offer_price || '';
            const isOffer = pkg.is_offer ? '1' : '0';
            return `<option value="${pkg.id}" data-price="${pkg.price}" data-offer-price="${offerPrice}" data-is-offer="${isOffer}" data-offer-expiry="${offerExpiry}">${pkg.title}</option>`;
        });
        packageSelect.innerHTML = '<option value="">اختر باقة...</option>' + options.join('');
        console.log('✅ Packages loaded in dropdown:', filteredPackages.length);
    } else {
        console.error('❌ No packages found for workshop_id:', workshopId);
        console.error('Package select exists?', !!packageSelect);
        if (packageSelect) packageSelect.innerHTML = '<option value="">لا توجد باقات</option>';
    }
}

function handlePackageChange(event) {
    const packageSelect = event.target;
    const selectedOption = packageSelect.options[packageSelect.selectedIndex];
    const actualPriceInput = document.getElementById('actual_price');
    const paidAmountInput = document.getElementById('paid_amount');

    if (!selectedOption || !selectedOption.value) {
        if (actualPriceInput) actualPriceInput.value = '';
        if (paidAmountInput) paidAmountInput.value = '';
        return;
    }

    const isOffer = selectedOption.dataset.isOffer === '1';
    const offerExpiry = selectedOption.dataset.offerExpiry;
    const regularPrice = parseFloat(selectedOption.dataset.price);
    const offerPrice = selectedOption.dataset.offerPrice ? parseFloat(selectedOption.dataset.offerPrice) : null;

    // Check if offer is still valid
    let price = regularPrice;
    if (isOffer && offerPrice && offerExpiry) {
        const expiryDate = new Date(offerExpiry);
        if (expiryDate > new Date()) {
            price = offerPrice;
        }
    }

    if (actualPriceInput) actualPriceInput.value = price.toFixed(2);
    if (paidAmountInput) paidAmountInput.value = price.toFixed(2);
}

function handleSubscriptionSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Validate balance_used
    const balanceUsed = parseFloat(formData.get('balance_used') || 0);
    if (balanceUsed > 0 && selectedUser) {
        if (balanceUsed > selectedUser.balance) {
            showToast('لا يمكن استخدام مبلغ أكبر من الرصيد المتاح', 'error');
            return;
        }
    }
    
    // If new user form is visible, include those fields
    const newUserForm = document.getElementById('newUserForm');
    if (newUserForm && newUserForm.style.display !== 'none') {
        const fullName = formData.get('full_name');
        const phone = formData.get('phone');
        if (!fullName || !phone) {
            showToast('يرجى إدخال الاسم الكامل ورقم الهاتف للمستفيد الجديد', 'error');
            return;
        }
        formData.delete('user_id'); // Remove user_id if creating new user
        formData.set('balance_used', '0'); // New users can't use balance
    } else {
        const userId = formData.get('user_id');
        if (!userId) {
            showToast('يرجى اختيار مستفيد أو إنشاء مستفيد جديد', 'error');
            return;
        }
        // Remove new user fields if existing user is selected
        formData.delete('full_name');
        formData.delete('email');
        formData.delete('phone');
        formData.delete('country_id');
        // Set balance_used to 0 if not provided or if user has no balance
        if (!balanceUsed || !selectedUser || selectedUser.balance <= 0) {
            formData.set('balance_used', '0');
        }
    }

    // Validate required fields
    if (!formData.get('workshop_id')) {
        showToast('يرجى اختيار ورشة', 'error');
        return;
    }
    if (!formData.get('paid_amount')) {
        showToast('يرجى إدخال المبلغ المدفوع', 'error');
        return;
    }
    if (!formData.get('payment_type')) {
        showToast('يرجى اختيار طريقة الدفع', 'error');
        return;
    }

    // Show loading
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> جاري الحفظ...';

    fetch('/subscriptions', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.msg || 'تم إنشاء الاشتراك بنجاح', 'success');
            bootstrap.Modal.getInstance(document.getElementById('subscriptionModal')).hide();
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showToast(data.msg || 'حدث خطأ أثناء إنشاء الاشتراك', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Submit error:', error);
        showToast('حدث خطأ أثناء إنشاء الاشتراك', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

function deleteSubscription(subscriptionId) {
    if (confirm('هل أنت متأكد من حذف هذا الاشتراك؟')) {
        // Find and fade out the subscription rows
        const rows = document.querySelectorAll(`tr[data-subscription-id="${subscriptionId}"]`);
        rows.forEach(row => {
            row.classList.add('fade-out');
        });
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        
        fetch(`/subscriptions/${subscriptionId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(text || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast(data.msg || 'تم حذف الاشتراك بنجاح', 'success');
                setTimeout(function() {
                    location.reload();
                }, 500);
            } else {
                // Remove fade-out class if deletion failed
                rows.forEach(row => {
                    row.classList.remove('fade-out');
                });
                showToast(data.msg || 'حدث خطأ أثناء حذف الاشتراك', 'error');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            // Remove fade-out class on error
            rows.forEach(row => {
                row.classList.remove('fade-out');
            });
            showToast('حدث خطأ أثناء حذف الاشتراك. يرجى المحاولة مرة أخرى', 'error');
        });
    }
}

function restoreSubscription(subscriptionId) {
    if (confirm('هل أنت متأكد من استعادة هذا الاشتراك؟')) {
        // Find and fade out the subscription rows
        const rows = document.querySelectorAll(`tr[data-subscription-id="${subscriptionId}"]`);
        rows.forEach(row => {
            row.classList.add('fade-out');
        });
        
        fetch(`/subscriptions/${subscriptionId}/restore`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.msg || 'تم استعادة الاشتراك بنجاح', 'success');
                setTimeout(function() {
                    location.reload();
                }, 500);
            } else {
                // Remove fade-out class if restore failed
                rows.forEach(row => {
                    row.classList.remove('fade-out');
                });
                showToast(data.msg || 'حدث خطأ أثناء استعادة الاشتراك', 'error');
            }
        })
        .catch(error => {
            // Remove fade-out class on error
            rows.forEach(row => {
                row.classList.remove('fade-out');
            });
            showToast('حدث خطأ أثناء استعادة الاشتراك', 'error');
        });
    }
}

function permanentlyDeleteSubscription(subscriptionId) {
    if (confirm('هل أنت متأكد من الحذف النهائي لهذا الاشتراك؟ لا يمكن التراجع عن هذا الإجراء!')) {
        // Find and fade out the subscription rows
        const rows = document.querySelectorAll(`tr[data-subscription-id="${subscriptionId}"]`);
        rows.forEach(row => {
            row.classList.add('fade-out');
        });
        
        fetch(`/subscriptions/${subscriptionId}/permanent`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.msg || 'تم حذف الاشتراك نهائياً بنجاح', 'success');
                setTimeout(function() {
                    location.reload();
                }, 500);
            } else {
                // Remove fade-out class if deletion failed
                rows.forEach(row => {
                    row.classList.remove('fade-out');
                });
                showToast(data.msg || 'حدث خطأ أثناء حذف الاشتراك', 'error');
            }
        })
        .catch(error => {
            // Remove fade-out class on error
            rows.forEach(row => {
                row.classList.remove('fade-out');
            });
            showToast('حدث خطأ أثناء حذف الاشتراك', 'error');
        });
    }
}

// Edit Subscription Modal
function openEditModal(subscriptionId) {
    fetch(`/subscriptions/${subscriptionId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            const subscription = data.data;
            const template = document.getElementById('editSubscriptionModalTemplate');
            document.getElementById('modalContent').innerHTML = template.innerHTML;
            document.getElementById('subscriptionModalLabel').textContent = 'تعديل اشتراك';
            
            // Populate form fields
            document.getElementById('edit_subscription_id').value = subscription.id;
            document.getElementById('edit_user_name').textContent = subscription.user_name;
            document.getElementById('edit_workshop_id').value = subscription.workshop_title;
            document.getElementById('edit_paid_amount').value = subscription.paid_amount;
            document.getElementById('edit_payment_type').value = subscription.payment_type;
            document.getElementById('edit_transferer_name').value = subscription.transferer_name || '';
            document.getElementById('edit_notes').value = subscription.notes || '';
            
            // Load packages for the workshop
            const workshopId = subscription.workshop_id;
            if (workshopId && allPackages) {
                const filteredPackages = allPackages.filter(pkg => pkg.workshop_id === workshopId);
                const packageSelect = document.getElementById('edit_package_id');
                if (packageSelect && filteredPackages.length > 0) {
                    packageSelect.innerHTML = '<option value="">لا توجد باقة</option>' + 
                        filteredPackages.map(pkg => {
                            const selected = pkg.id === subscription.package_id ? 'selected' : '';
                            return `<option value="${pkg.id}" ${selected} data-price="${pkg.price}" data-offer-price="${pkg.offer_price || ''}" data-is-offer="${pkg.is_offer ? '1' : '0'}" data-offer-expiry="${pkg.offer_expiry_date || ''}">${pkg.title}</option>`;
                        }).join('');
                    
                    // Trigger package change to update actual price
                    if (subscription.package_id) {
                        const selectedOption = packageSelect.options[packageSelect.selectedIndex];
                        if (selectedOption) {
                            const isOffer = selectedOption.dataset.isOffer === '1';
                            const offerExpiry = selectedOption.dataset.offerExpiry;
                            const regularPrice = parseFloat(selectedOption.dataset.price);
                            const offerPrice = selectedOption.dataset.offerPrice ? parseFloat(selectedOption.dataset.offerPrice) : null;
                            
                            let actualPrice = regularPrice;
                            if (isOffer && offerPrice && offerExpiry) {
                                const expiryDate = new Date(offerExpiry);
                                if (expiryDate > new Date()) {
                                    actualPrice = offerPrice;
                                }
                            }
                            
                            document.getElementById('edit_actual_price').value = actualPrice.toFixed(2) + ' د.إ';
                        }
                    }
                }
            }
            
            // Initialize form handlers
            setTimeout(() => {
                initializeEditSubscriptionForm(subscription.workshop_id);
            }, 300);
            
            const modal = new bootstrap.Modal(document.getElementById('subscriptionModal'));
            modal.show();
        } else {
            showToast('حدث خطأ أثناء جلب بيانات الاشتراك', 'error');
        }
    })
    .catch(error => {
        console.error('Error fetching subscription:', error);
        showToast('حدث خطأ أثناء جلب بيانات الاشتراك', 'error');
    });
}

function initializeEditSubscriptionForm(workshopId) {
    // Package change handler
    const packageSelect = document.getElementById('edit_package_id');
    if (packageSelect) {
        packageSelect.addEventListener('change', function(e) {
            handleEditPackageChange(e);
        });
    }
    
    // Form submission handler
    const form = document.getElementById('editSubscriptionForm');
    if (form) {
        form.addEventListener('submit', handleEditSubscriptionSubmit);
    }
}

function handleEditPackageChange(event) {
    const packageSelect = event.target;
    const selectedOption = packageSelect.options[packageSelect.selectedIndex];
    const actualPriceInput = document.getElementById('edit_actual_price');
    
    if (!selectedOption || !selectedOption.value) {
        if (actualPriceInput) actualPriceInput.value = '';
        return;
    }
    
    const isOffer = selectedOption.dataset.isOffer === '1';
    const offerExpiry = selectedOption.dataset.offerExpiry;
    const regularPrice = parseFloat(selectedOption.dataset.price);
    const offerPrice = selectedOption.dataset.offerPrice ? parseFloat(selectedOption.dataset.offerPrice) : null;
    
    let actualPrice = regularPrice;
    if (isOffer && offerPrice && offerExpiry) {
        const expiryDate = new Date(offerExpiry);
        if (expiryDate > new Date()) {
            actualPrice = offerPrice;
        }
    }
    
    if (actualPriceInput) actualPriceInput.value = actualPrice.toFixed(2) + ' د.إ';
}

function handleEditSubscriptionSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const subscriptionId = formData.get('subscription_id');
    
    // Validate required fields
    if (!formData.get('paid_amount')) {
        showToast('يرجى إدخال المبلغ المدفوع', 'error');
        return;
    }
    if (!formData.get('payment_type')) {
        showToast('يرجى اختيار طريقة الدفع', 'error');
        return;
    }
    
    // Show loading
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> جاري الحفظ...';
    
    fetch(`/subscriptions/${subscriptionId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        
        if (data.success) {
            showToast(data.msg || 'تم تحديث الاشتراك بنجاح', 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showToast(data.msg || 'حدث خطأ أثناء تحديث الاشتراك', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating subscription:', error);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        showToast('حدث خطأ أثناء تحديث الاشتراك', 'error');
    });
}

function openTransferModal(subscriptionId) {
    // Fetch subscription data to populate the modal
    fetch(`/subscriptions/${subscriptionId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            const subscription = data.data;
            const template = document.getElementById('transferModalTemplate');
            document.getElementById('modalContent').innerHTML = template.innerHTML;
            document.getElementById('subscriptionModalLabel').textContent = 'تحويل اشتراك';
            
            // Populate form fields
            document.getElementById('transfer_subscription_id').value = subscriptionId;
            document.getElementById('transfer_current_workshop_id').value = subscription.workshop_id;
            document.getElementById('transfer_paid_amount').value = subscription.paid_amount;
            document.getElementById('transfer_user_name').textContent = subscription.user_name || '-';
            document.getElementById('transfer_current_workshop_title').textContent = subscription.workshop_title || '-';
            
            // Store subscription data for calculations
            window.currentTransferData = {
                subscriptionId: subscriptionId,
                paidAmount: parseFloat(subscription.paid_amount || 0),
                currentWorkshopId: subscription.workshop_id,
                userBalance: parseFloat(subscription.user_balance || 0)
            };
            
            // Remove current workshop from dropdown
            const workshopSelect = document.getElementById('transfer_workshop_id');
            if (workshopSelect) {
                Array.from(workshopSelect.options).forEach(option => {
                    if (parseInt(option.value) === subscription.workshop_id) {
                        option.remove();
                    }
                });
            }
            
            // Initialize form handlers
            setTimeout(() => {
                initializeTransferForm();
            }, 100);
            
            const modal = new bootstrap.Modal(document.getElementById('subscriptionModal'));
            modal.show();
        } else {
            showToast('حدث خطأ أثناء جلب بيانات الاشتراك', 'error');
        }
    })
    .catch(error => {
        console.error('Error fetching subscription:', error);
        showToast('حدث خطأ أثناء جلب بيانات الاشتراك', 'error');
    });
}

function initializeTransferForm() {
    // Workshop change handler
    const workshopSelect = document.getElementById('transfer_workshop_id');
    if (workshopSelect) {
        workshopSelect.addEventListener('change', handleTransferWorkshopChange);
    }
    
    // Package change handler
    const packageSelect = document.getElementById('transfer_package_id');
    if (packageSelect) {
        packageSelect.addEventListener('change', handleTransferPackageChange);
    }
    
    // Balance used input handler
    const balanceUsedInput = document.getElementById('transfer_balance_used');
    if (balanceUsedInput) {
        balanceUsedInput.addEventListener('input', handleTransferBalanceUsedChange);
    }
    
    // Form submission handler
    const form = document.getElementById('transferForm');
    if (form) {
        form.addEventListener('submit', handleTransferSubmit);
    }
}

function handleTransferWorkshopChange(event) {
    const workshopId = parseInt(event.target.value);
    const packageSelect = document.getElementById('transfer_package_id');
    const priceInfo = document.getElementById('transfer_price_info');
    
    // Clear package and price info
    if (packageSelect) packageSelect.innerHTML = '<option value="">لا توجد باقة</option>';
    if (priceInfo) priceInfo.style.display = 'none';
    document.getElementById('transfer_balance_section').style.display = 'none';
    document.getElementById('transfer_additional_paid_section').style.display = 'none';
    
    if (!workshopId || !allPackages) {
        return;
    }
    
    // Filter packages for the selected workshop
    const filteredPackages = allPackages.filter(pkg => parseInt(pkg.workshop_id) === workshopId);
    
    if (filteredPackages.length > 0 && packageSelect) {
        const options = filteredPackages.map(pkg => {
            const offerExpiry = pkg.offer_expiry_date || '';
            const offerPrice = pkg.offer_price || '';
            const isOffer = pkg.is_offer ? '1' : '0';
            const actualPrice = (isOffer === '1' && offerPrice && offerExpiry) ? 
                (new Date(offerExpiry) > new Date() ? offerPrice : pkg.price) : 
                pkg.price;
            return `<option value="${pkg.id}" data-price="${pkg.price}" data-offer-price="${offerPrice}" data-is-offer="${isOffer}" data-offer-expiry="${offerExpiry}" data-actual-price="${actualPrice}">${pkg.title} - (السعر: ${parseFloat(actualPrice).toFixed(2)})</option>`;
        });
        packageSelect.innerHTML = '<option value="">لا توجد باقة</option>' + options.join('');
    }
    
    // Calculate price without package (use minimum package price)
    if (filteredPackages.length > 0) {
        const minPackage = filteredPackages.reduce((min, pkg) => {
            const pkgPrice = (pkg.is_offer && pkg.offer_price && pkg.offer_expiry_date && new Date(pkg.offer_expiry_date) > new Date()) ? 
                parseFloat(pkg.offer_price) : 
                parseFloat(pkg.price);
            return (!min || pkgPrice < min.price) ? { price: pkgPrice, id: pkg.id } : min;
        }, null);
        
        if (minPackage) {
            updateTransferPriceInfo(minPackage.price);
        }
    } else {
        // No packages, price is 0
        updateTransferPriceInfo(0);
    }
}

function handleTransferPackageChange(event) {
    const packageSelect = event.target;
    const selectedOption = packageSelect.options[packageSelect.selectedIndex];
    
    if (!selectedOption || !selectedOption.value) {
        // If no package selected, use minimum package price for the workshop
        const workshopId = parseInt(document.getElementById('transfer_workshop_id').value);
        if (workshopId && allPackages) {
            const filteredPackages = allPackages.filter(pkg => parseInt(pkg.workshop_id) === workshopId);
            if (filteredPackages.length > 0) {
                const minPackage = filteredPackages.reduce((min, pkg) => {
                    const pkgPrice = (pkg.is_offer && pkg.offer_price && pkg.offer_expiry_date && new Date(pkg.offer_expiry_date) > new Date()) ? 
                        parseFloat(pkg.offer_price) : 
                        parseFloat(pkg.price);
                    return (!min || pkgPrice < min.price) ? { price: pkgPrice } : min;
                }, null);
                if (minPackage) {
                    updateTransferPriceInfo(minPackage.price);
                }
            }
        }
        return;
    }
    
    const isOffer = selectedOption.dataset.isOffer === '1';
    const offerExpiry = selectedOption.dataset.offerExpiry;
    const regularPrice = parseFloat(selectedOption.dataset.price);
    const offerPrice = selectedOption.dataset.offerPrice ? parseFloat(selectedOption.dataset.offerPrice) : null;
    
    // Check if offer is still valid
    let actualPrice = regularPrice;
    if (isOffer && offerPrice && offerExpiry) {
        const expiryDate = new Date(offerExpiry);
        if (expiryDate > new Date()) {
            actualPrice = offerPrice;
        }
    }
    
    updateTransferPriceInfo(actualPrice);
}

function updateTransferPriceInfo(newPrice) {
    if (!window.currentTransferData) return;
    
    const oldPaidAmount = window.currentTransferData.paidAmount;
    const difference = newPrice - oldPaidAmount;
    const differenceElement = document.getElementById('transfer_price_difference');
    
    // Update display
    document.getElementById('transfer_old_paid_amount').textContent = oldPaidAmount.toFixed(2);
    document.getElementById('transfer_new_price').textContent = newPrice.toFixed(2);
    
    const priceInfo = document.getElementById('transfer_price_info');
    priceInfo.style.display = 'block';
    
    // Handle positive difference (old price > new price) - add to balance (user gets money back)
    if (difference < 0) {
        // Show as positive (green) - user gets money back
        const absoluteDifference = Math.abs(difference);
        differenceElement.textContent = `+${absoluteDifference.toFixed(2)}`;
        differenceElement.style.color = '#10b981'; // Green
        differenceElement.style.fontWeight = '600';
        
        document.getElementById('transfer_positive_difference_note').style.display = 'block';
        document.getElementById('transfer_negative_difference_note').style.display = 'none';
        document.getElementById('transfer_balance_section').style.display = 'none';
        document.getElementById('transfer_additional_paid_section').style.display = 'none';
    }
    // Handle negative difference (old price < new price) - use balance and/or additional payment (user owes money)
    else if (difference > 0) {
        // Show as negative (red) - user owes money
        differenceElement.textContent = `-${difference.toFixed(2)}`;
        differenceElement.style.color = '#ef4444'; // Red
        differenceElement.style.fontWeight = '600';
        
        document.getElementById('transfer_positive_difference_note').style.display = 'none';
        document.getElementById('transfer_negative_difference_note').style.display = 'block';
        
        // Show balance section if user has balance
        if (window.currentTransferData.userBalance > 0) {
            const balanceSection = document.getElementById('transfer_balance_section');
            const balanceDisplay = document.getElementById('transfer_user_balance_display');
            const balanceInput = document.getElementById('transfer_balance_used');
            
            balanceSection.style.display = 'block';
            balanceDisplay.textContent = `(المتاح: ${window.currentTransferData.userBalance.toFixed(2)} د.إ)`;
            
            // Set max balance to use (can't exceed available balance or difference)
            const maxBalance = Math.min(window.currentTransferData.userBalance, difference);
            balanceInput.setAttribute('max', maxBalance);
            balanceInput.value = maxBalance.toFixed(2);
            
            // Update additional paid amount section
            const remainingAfterBalance = difference - maxBalance;
            if (remainingAfterBalance > 0) {
                document.getElementById('transfer_additional_paid_section').style.display = 'block';
                document.getElementById('transfer_additional_paid_amount').value = remainingAfterBalance.toFixed(2);
                // Set max to prevent entering more than needed, but allow less (user can pay rest later)
                document.getElementById('transfer_additional_paid_amount').setAttribute('max', remainingAfterBalance);
                document.getElementById('transfer_additional_paid_amount').setAttribute('data-required-amount', remainingAfterBalance);
                document.getElementById('transfer_additional_paid_amount').removeAttribute('min');
            } else {
                document.getElementById('transfer_additional_paid_section').style.display = 'none';
                document.getElementById('transfer_additional_paid_amount').value = '';
                document.getElementById('transfer_additional_paid_amount').removeAttribute('max');
                document.getElementById('transfer_additional_paid_amount').removeAttribute('data-required-amount');
            }
        } else {
            // No balance, show only additional paid amount
            document.getElementById('transfer_balance_section').style.display = 'none';
            document.getElementById('transfer_additional_paid_section').style.display = 'block';
            document.getElementById('transfer_additional_paid_amount').value = difference.toFixed(2);
            // Set max to prevent entering more than needed, but allow less (user can pay rest later)
            document.getElementById('transfer_additional_paid_amount').setAttribute('max', difference);
            document.getElementById('transfer_additional_paid_amount').setAttribute('data-required-amount', difference);
            document.getElementById('transfer_additional_paid_amount').removeAttribute('min');
        }
    } else {
        // No difference
        differenceElement.textContent = '0.00';
        differenceElement.style.color = '#94a3b8'; // Gray
        differenceElement.style.fontWeight = 'normal';
        
        document.getElementById('transfer_positive_difference_note').style.display = 'none';
        document.getElementById('transfer_negative_difference_note').style.display = 'none';
        document.getElementById('transfer_balance_section').style.display = 'none';
        document.getElementById('transfer_additional_paid_section').style.display = 'none';
    }
}

function handleTransferBalanceUsedChange(event) {
    const balanceUsed = parseFloat(event.target.value) || 0;
    const maxBalance = parseFloat(event.target.getAttribute('max')) || 0;
    const oldPaidAmount = window.currentTransferData.paidAmount;
    const newPrice = parseFloat(document.getElementById('transfer_new_price').textContent) || 0;
    const difference = newPrice - oldPaidAmount;
    
    if (balanceUsed > maxBalance) {
        event.target.value = maxBalance.toFixed(2);
        showToast('لا يمكن استخدام مبلغ أكبر من الرصيد المتاح', 'error');
        return;
    }
    
    // Update additional paid amount
    const remainingAfterBalance = difference - balanceUsed;
    const additionalPaidInput = document.getElementById('transfer_additional_paid_amount');
    if (remainingAfterBalance > 0) {
        document.getElementById('transfer_additional_paid_section').style.display = 'block';
        additionalPaidInput.value = remainingAfterBalance.toFixed(2);
        // Set max to prevent entering more than needed, but allow less (user can pay rest later)
        additionalPaidInput.setAttribute('max', remainingAfterBalance);
        additionalPaidInput.setAttribute('data-required-amount', remainingAfterBalance);
        additionalPaidInput.removeAttribute('min');
    } else {
        document.getElementById('transfer_additional_paid_section').style.display = 'none';
        additionalPaidInput.value = '';
        additionalPaidInput.removeAttribute('max');
        additionalPaidInput.removeAttribute('data-required-amount');
    }
}

function validateTransferAdditionalPaidAmount(input) {
    const value = parseFloat(input.value) || 0;
    const maxAmount = parseFloat(input.getAttribute('max')) || 0;
    const errorElement = document.getElementById('transfer_additional_paid_error');
    
    // Clear previous error
    if (errorElement) {
        errorElement.style.display = 'none';
        errorElement.textContent = '';
    }
    
    // If value exceeds max, set it to max and show error
    if (maxAmount > 0 && value > maxAmount) {
        input.value = maxAmount.toFixed(2);
        if (errorElement) {
            errorElement.textContent = `لا يمكن إدخال مبلغ أكبر من ${maxAmount.toFixed(2)} د.إ`;
            errorElement.style.display = 'block';
        }
        // Clear error after 3 seconds
        setTimeout(() => {
            if (errorElement) {
                errorElement.style.display = 'none';
            }
        }, 3000);
    }
    
    // Allow values less than required (user can pay rest later)
    // No minimum validation needed
}

function handleTransferSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const subscriptionId = formData.get('subscription_id');
    
    // Validate workshop selection
    if (!formData.get('workshop_id')) {
        showToast('يرجى اختيار ورشة جديدة', 'error');
        return;
    }
    
    // Validate balance_used if provided
    const balanceUsed = parseFloat(formData.get('balance_used') || 0);
    if (balanceUsed > 0 && balanceUsed > window.currentTransferData.userBalance) {
        showToast('لا يمكن استخدام مبلغ أكبر من الرصيد المتاح', 'error');
        return;
    }
    
    // Show loading
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> جاري التحويل...';
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    
    fetch(`/subscriptions/${subscriptionId}/transfer`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.msg || 'Network response was not ok');
            });
        }
        return response.json();
    })
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        
        if (data.success) {
            showToast(data.msg || 'تم تحويل الاشتراك بنجاح', 'success');
            bootstrap.Modal.getInstance(document.getElementById('subscriptionModal')).hide();
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showToast(data.msg || 'حدث خطأ أثناء تحويل الاشتراك', 'error');
        }
    })
    .catch(error => {
        console.error('Transfer error:', error);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        showToast(error.message || 'حدث خطأ أثناء تحويل الاشتراك', 'error');
    });
}

function openTransferToInternalBalanceModal(subscriptionId) {
    // Fetch subscription data to show in confirmation
    fetch(`/subscriptions/${subscriptionId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            const subscription = data.data;
            const userName = subscription.user_name || 'المستخدم';
            const amount = parseFloat(subscription.paid_amount || 0).toFixed(2);
            
            // Show confirmation modal - matching the screenshot format
            const confirmMessage = `هل أنت متأكد من رغبتك في استرداد مبلغ ${amount} كـ "رصيد داخلي" لحساب ${userName} ؟`;
            
            if (confirm(confirmMessage)) {
                // Make the transfer request
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                
                fetch(`/subscriptions/${subscriptionId}/transfer-to-balance`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.msg || 'Network response was not ok');
                        });
                    }
                    return response.json();
                })
                .then(result => {
                    if (result.success) {
                        showToast(result.msg || 'تم تحويل المبلغ إلى الرصيد الداخلي بنجاح', 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showToast(result.msg || 'حدث خطأ أثناء تحويل المبلغ', 'error');
                    }
                })
                .catch(error => {
                    console.error('Transfer error:', error);
                    showToast(error.message || 'حدث خطأ أثناء تحويل المبلغ. يرجى المحاولة مرة أخرى', 'error');
                });
            }
        } else {
            showToast('حدث خطأ أثناء جلب بيانات الاشتراك', 'error');
        }
    })
    .catch(error => {
        console.error('Error fetching subscription:', error);
        showToast('حدث خطأ أثناء جلب بيانات الاشتراك', 'error');
    });
}

function openInvoiceModal(subscriptionId) {
    fetch(`/subscriptions/${subscriptionId}/invoice`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            const invoice = data.data;
            const template = document.getElementById('invoiceModalTemplate');
            document.getElementById('modalContent').innerHTML = template.innerHTML;
            document.getElementById('subscriptionModalLabel').textContent = 'معاينة الفاتورة الضريبية';
            
            // Populate invoice data
            document.getElementById('invoice_company_name').textContent = invoice.company.name;
            document.getElementById('invoice_company_address').textContent = invoice.company.address;
            document.getElementById('invoice_company_phone').textContent = invoice.company.phone;
            
            document.getElementById('invoice_user_name').textContent = invoice.user.name;
            document.getElementById('invoice_user_email').textContent = invoice.user.email;
            document.getElementById('invoice_user_phone').textContent = invoice.user.phone;
            
            document.getElementById('invoice_date').textContent = invoice.invoice_date_ar;
            document.getElementById('invoice_number').textContent = invoice.invoice_id;
            
            document.getElementById('invoice_workshop_title').textContent = invoice.workshop.title;
            document.getElementById('invoice_package_title').textContent = 'الباقة: ' + invoice.package_title;
            document.getElementById('invoice_package').textContent = invoice.package_title;
            
            document.getElementById('invoice_subtotal').textContent = invoice.subtotal.toFixed(2);
            document.getElementById('invoice_vat').textContent = invoice.vat.toFixed(2);
            document.getElementById('invoice_total').textContent = invoice.total.toFixed(2);
            
            document.getElementById('invoice_summary_subtotal').textContent = invoice.subtotal.toFixed(2) + ' د.إ';
            document.getElementById('invoice_summary_vat').textContent = invoice.vat.toFixed(2) + ' د.إ';
            document.getElementById('invoice_summary_total').textContent = invoice.total.toFixed(2) + ' د.إ';
            
            document.getElementById('invoice_tax_number').textContent = invoice.company.tax_number;
            
            // Set logo
            const logoImg = document.getElementById('invoice_logo');
            if (logoImg && invoice.company.logo) {
                logoImg.src = invoice.company.logo;
                logoImg.style.display = 'block';
            } else {
                if (logoImg) logoImg.style.display = 'none';
            }
            
            // Add print/save button to modal header
            setTimeout(() => {
                const modalHeader = document.querySelector('#subscriptionModal .modal-header');
                if (modalHeader) {
                    // Remove existing print button if any
                    const existingBtn = modalHeader.querySelector('.btn-print-invoice');
                    if (existingBtn) existingBtn.remove();
                    
                    const printBtn = document.createElement('button');
                    printBtn.className = 'btn btn-primary btn-print-invoice';
                    printBtn.style.cssText = 'background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%); border: none; margin-left: auto;';
                    printBtn.innerHTML = '<i class="fa fa-print ms-1"></i>طباعة / حفظ';
                    printBtn.style.marginRight = 'auto';
                    printBtn.onclick = () => {
                        window.open(`/subscriptions/${subscriptionId}/invoice/pdf`, '_blank');
                    };
                    const headerContent = modalHeader.querySelector('.d-flex') || modalHeader;
                    if (headerContent) {
                        headerContent.style.display = 'flex';
                        headerContent.style.alignItems = 'center';
                        headerContent.style.justifyContent = 'space-between';
                        headerContent.appendChild(printBtn);
                    }
                }
            }, 100);
            
            const modal = new bootstrap.Modal(document.getElementById('subscriptionModal'));
            modal.show();
        } else {
            showToast('حدث خطأ أثناء جلب بيانات الفاتورة', 'error');
        }
    })
    .catch(error => {
        console.error('Error fetching invoice:', error);
        showToast('حدث خطأ أثناء جلب بيانات الفاتورة', 'error');
    });
}

function openRefundModal(subscriptionId) {
    // Fetch subscription data to populate the modal
    fetch(`/subscriptions/${subscriptionId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            const subscription = data.data;
            const template = document.getElementById('refundModalTemplate');
            document.getElementById('modalContent').innerHTML = template.innerHTML;
            document.getElementById('subscriptionModalLabel').textContent = 'معالجة استرداد مبلغ';
            
            // Populate form fields
            document.getElementById('refund_subscription_id').value = subscriptionId;
            document.getElementById('refund_user_name').textContent = subscription.user_name || '-';
            document.getElementById('refund_workshop_title').textContent = subscription.workshop_title || '-';
            document.getElementById('refund_paid_amount').textContent = parseFloat(subscription.paid_amount || 0).toFixed(2);
            
            // Initialize form submission handler
            setTimeout(() => {
                const form = document.getElementById('refundForm');
                if (form) {
                    form.addEventListener('submit', handleRefundSubmit);
                }
            }, 100);
            
            const modal = new bootstrap.Modal(document.getElementById('subscriptionModal'));
            modal.show();
        } else {
            showToast('حدث خطأ أثناء جلب بيانات الاشتراك', 'error');
        }
    })
    .catch(error => {
        console.error('Error fetching subscription:', error);
        showToast('حدث خطأ أثناء جلب بيانات الاشتراك', 'error');
    });
}

function handleRefundSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const subscriptionId = formData.get('subscription_id');
    
    // Validate refund type
    if (!formData.get('refund_type')) {
        showToast('يرجى اختيار طريقة الاسترداد', 'error');
        return;
    }
    
    // Show loading
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> جاري المعالجة...';
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    
    fetch(`/subscriptions/${subscriptionId}/refund`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.msg || 'Network response was not ok');
            });
        }
        return response.json();
    })
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        
        if (data.success) {
            showToast(data.msg || 'تم معالجة الاسترداد بنجاح', 'success');
            bootstrap.Modal.getInstance(document.getElementById('subscriptionModal')).hide();
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showToast(data.msg || 'حدث خطأ أثناء معالجة الاسترداد', 'error');
        }
    })
    .catch(error => {
        console.error('Refund error:', error);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        showToast(error.message || 'حدث خطأ أثناء معالجة الاسترداد', 'error');
    });
}
function openRecordingPermissionsModal(subscriptionId) {
    alert('سيتم تنفيذ هذه الوظيفة لاحقاً');
}

function openDetailsModal(subscriptionId) {
    alert('سيتم تنفيذ هذه الوظيفة لاحقاً');
}

function openPendingApprovalsModal() {
    const template = document.getElementById('pendingApprovalsModalTemplate');
    document.getElementById('modalContent').innerHTML = template.innerHTML;
    document.getElementById('subscriptionModalLabel').textContent = 'موافقات معلقة';
    
    // Fetch pending approvals
    fetchPendingApprovals();
    
    const modal = new bootstrap.Modal(document.getElementById('subscriptionModal'));
    modal.show();
}

function fetchPendingApprovals() {
    const tbody = document.getElementById('pendingApprovalsTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = `
        <tr>
            <td colspan="6" class="text-center py-4" style="color: #94a3b8;">
                <i class="fa fa-spinner fa-spin fa-2x mb-2"></i>
                <div>جاري التحميل...</div>
            </td>
        </tr>
    `;
    
    fetch('{{ route("admin.subscriptions.pending-approvals") }}', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            const subscriptions = data.data;
            
            if (subscriptions.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4" style="color: #94a3b8;">
                            <i class="fa fa-inbox fa-3x mb-3"></i>
                            <div>لا توجد اشتراكات قيد المعالجة</div>
                        </td>
                    </tr>
                `;
                return;
            }
            
            tbody.innerHTML = subscriptions.map(sub => `
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 1rem; color: #fff;">${sub.name}</td>
                    <td style="padding: 1rem; color: #fff;">${sub.phone}</td>
                    <td style="padding: 1rem; color: #fff;">${sub.workshop_title}</td>
                    <td style="padding: 1rem; text-align: center; color: #94a3b8;">${sub.created_at_ar}</td>
                    <td style="padding: 1rem; text-align: center; color: #94a3b8;">${sub.payment_type}</td>
                    <td style="padding: 1rem; text-align: center;">
                        <button type="button" 
                                class="btn btn-sm me-2" 
                                onclick="approveSubscription(${sub.id})"
                                style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: #fff;">
                            <i class="fa fa-check me-1"></i>
                            موافقة
                        </button>
                        <button type="button" 
                                class="btn btn-sm" 
                                onclick="rejectSubscription(${sub.id})"
                                style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; color: #fff;">
                            <i class="fa fa-times me-1"></i>
                            رفض
                        </button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4" style="color: #ef4444;">
                        <i class="fa fa-exclamation-triangle fa-2x mb-2"></i>
                        <div>حدث خطأ أثناء جلب البيانات</div>
                    </td>
                </tr>
            `;
        }
    })
    .catch(error => {
        console.error('Error fetching pending approvals:', error);
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4" style="color: #ef4444;">
                    <i class="fa fa-exclamation-triangle fa-2x mb-2"></i>
                    <div>حدث خطأ أثناء جلب البيانات</div>
                </td>
            </tr>
        `;
    });
}

function approveSubscription(subscriptionId) {
    if (!confirm('هل أنت متأكد من الموافقة على هذا الاشتراك؟')) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    
    fetch(`/subscriptions/${subscriptionId}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.msg || 'تم الموافقة على الاشتراك بنجاح', 'success');
            fetchPendingApprovals(); // Refresh the list
        } else {
            showToast(data.msg || 'حدث خطأ أثناء الموافقة على الاشتراك', 'error');
        }
    })
    .catch(error => {
        console.error('Approve error:', error);
        showToast('حدث خطأ أثناء الموافقة على الاشتراك', 'error');
    });
}

function rejectSubscription(subscriptionId) {
    if (!confirm('هل أنت متأكد من رفض هذا الاشتراك؟')) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    
    fetch(`/subscriptions/${subscriptionId}/reject`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.msg || 'تم رفض الاشتراك بنجاح', 'success');
            fetchPendingApprovals(); // Refresh the list
        } else {
            showToast(data.msg || 'حدث خطأ أثناء رفض الاشتراك', 'error');
        }
    })
    .catch(error => {
        console.error('Reject error:', error);
        showToast('حدث خطأ أثناء رفض الاشتراك', 'error');
    });
}

function exportPendingApprovals() {
    window.location.href = '{{ route("admin.subscriptions.pending-approvals.export") }}';
}

function exportTotalSubscriptionsExcel() {
    const workshopId = document.getElementById('total_subscriptions_workshop_id')?.value;
    if (!workshopId) {
        showToast('يرجى اختيار ورشة عمل أولاً', 'error');
        return;
    }
    // Use the stored workshop ID from summary if available
    const summaryDiv = document.getElementById('total_subscriptions_summary');
    const storedWorkshopId = summaryDiv?.getAttribute('data-workshop-id') || workshopId;
    window.location.href = `/subscriptions/workshop/${storedWorkshopId}/stats/export/excel`;
}

function exportTotalSubscriptionsPdf() {
    const workshopId = document.getElementById('total_subscriptions_workshop_id')?.value;
    if (!workshopId) {
        showToast('يرجى اختيار ورشة عمل أولاً', 'error');
        return;
    }
    // Use the stored workshop ID from summary if available
    const summaryDiv = document.getElementById('total_subscriptions_summary');
    const storedWorkshopId = summaryDiv?.getAttribute('data-workshop-id') || workshopId;
    window.location.href = `/subscriptions/workshop/${storedWorkshopId}/stats/export/pdf`;
}

function openTotalSubscriptionsModal() {
    const template = document.getElementById('totalSubscriptionsModalTemplate');
    document.getElementById('modalContent').innerHTML = template.innerHTML;
    document.getElementById('subscriptionModalLabel').textContent = 'إحصائيات تفصيلية';
    
    // Reset form
    document.getElementById('total_subscriptions_workshop_id').value = '';
    document.getElementById('total_subscriptions_summary').style.display = 'none';
    document.getElementById('total_subscriptions_table_section').style.display = 'none';
    document.getElementById('total_subscriptions_loading').style.display = 'none';
    document.getElementById('total_subscriptions_empty').style.display = 'none';
    
    const modal = new bootstrap.Modal(document.getElementById('subscriptionModal'));
    modal.show();
}

function handleTotalSubscriptionsWorkshopChange(event) {
    const workshopId = parseInt(event.target.value);
    
    if (!workshopId) {
        document.getElementById('total_subscriptions_summary').style.display = 'none';
        document.getElementById('total_subscriptions_table_section').style.display = 'none';
        document.getElementById('total_subscriptions_loading').style.display = 'none';
        document.getElementById('total_subscriptions_empty').style.display = 'none';
        return;
    }
    
    // Show loading
    document.getElementById('total_subscriptions_summary').style.display = 'none';
    document.getElementById('total_subscriptions_table_section').style.display = 'none';
    document.getElementById('total_subscriptions_empty').style.display = 'none';
    document.getElementById('total_subscriptions_loading').style.display = 'block';
    
    fetch(`/subscriptions/workshop/${workshopId}/stats`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('total_subscriptions_loading').style.display = 'none';
        
        if (data.success && data.data) {
            const stats = data.data;
            
            // Update summary
            document.getElementById('total_subscriptions_workshop_title').textContent = stats.workshop_title;
            // Calculate total income from packages (sum of all package incomes)
            const totalIncome = stats.packages ? stats.packages.reduce((sum, pkg) => sum + parseFloat(pkg.income || 0), 0) : 0;
            document.getElementById('total_subscriptions_total_amount').textContent = totalIncome.toFixed(2) + ' د.إ';
            document.getElementById('total_subscriptions_count').textContent = stats.total_count;
            document.getElementById('total_subscriptions_summary').style.display = 'block';
            
            // Store workshop ID for export functions
            const summaryDiv = document.getElementById('total_subscriptions_summary');
            if (summaryDiv) {
                summaryDiv.setAttribute('data-workshop-id', workshopId);
            }
            
            // Update table
            const tbody = document.getElementById('total_subscriptions_table_body');
            if (stats.packages && stats.packages.length > 0) {
                tbody.innerHTML = stats.packages.map(pkg => `
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 1rem; color: #fff;">${pkg.title}</td>
                        <td style="padding: 1rem; text-align: center; color: #fff;">${pkg.count}</td>
                        <td style="padding: 1rem; text-align: center; color: #fff;">${parseFloat(pkg.income).toFixed(2)} د.إ</td>
                    </tr>
                `).join('');
                document.getElementById('total_subscriptions_table_section').style.display = 'block';
            } else {
                document.getElementById('total_subscriptions_empty').style.display = 'block';
            }
        } else {
            document.getElementById('total_subscriptions_empty').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error fetching workshop stats:', error);
        document.getElementById('total_subscriptions_loading').style.display = 'none';
        document.getElementById('total_subscriptions_empty').style.display = 'block';
    });
}

function copyToClipboard(elementId, text) {
    // Clean and validate text
    text = String(text || '').trim();
    
    if (!text || text === '-' || text === '') {
        showToast('لا يوجد نص للنسخ', 'error');
        return false;
    }
    
    // Try modern clipboard API first
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success feedback
            const element = document.getElementById(elementId);
            if (element) {
                const originalText = element.textContent || element.innerText;
                const originalColor = element.style.color;
                element.textContent = 'تم النسخ!';
                element.style.color = '#10b981';
                setTimeout(function() {
                    element.textContent = originalText;
                    element.style.color = originalColor;
                }, 2000);
            }
            
            // Show toast notification
            showToast('تم نسخ ' + (elementId.includes('email') ? 'البريد الإلكتروني' : 'رقم الهاتف') + ' بنجاح', 'success');
        }).catch(function(err) {
            console.error('Clipboard API failed:', err);
            // Fallback to execCommand
            fallbackCopyToClipboard(text, elementId);
        });
    } else {
        // Fallback for older browsers
        fallbackCopyToClipboard(text, elementId);
    }
    
    return false; // Prevent default button behavior
}

function fallbackCopyToClipboard(text, elementId) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.top = '0';
    textArea.style.left = '0';
    textArea.style.width = '2em';
    textArea.style.height = '2em';
    textArea.style.padding = '0';
    textArea.style.border = 'none';
    textArea.style.outline = 'none';
    textArea.style.boxShadow = 'none';
    textArea.style.background = 'transparent';
    textArea.style.opacity = '0';
    textArea.style.zIndex = '-9999';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        document.body.removeChild(textArea);
        
        if (successful) {
            const element = document.getElementById(elementId);
            if (element) {
                const originalText = element.textContent || element.innerText;
                const originalColor = element.style.color;
                element.textContent = 'تم النسخ!';
                element.style.color = '#10b981';
                setTimeout(function() {
                    element.textContent = originalText;
                    element.style.color = originalColor;
                }, 2000);
            }
            showToast('تم نسخ ' + (elementId.includes('email') ? 'البريد الإلكتروني' : 'رقم الهاتف') + ' بنجاح', 'success');
        } else {
            showToast('فشل النسخ. يرجى المحاولة يدوياً', 'error');
        }
    } catch (err) {
        console.error('Fallback copy failed:', err);
        document.body.removeChild(textArea);
        showToast('فشل النسخ. يرجى المحاولة يدوياً', 'error');
    }
}

function showToast(message, type) {
    // Create toast element
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 10000;
        font-weight: 600;
        animation: slideDown 0.3s ease;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(function() {
        toast.style.animation = 'slideUp 0.3s ease';
        setTimeout(function() {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideDown {
        from {
            transform: translateX(-50%) translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
    }
    @keyframes slideUp {
        from {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
        to {
            transform: translateX(-50%) translateY(-100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection
