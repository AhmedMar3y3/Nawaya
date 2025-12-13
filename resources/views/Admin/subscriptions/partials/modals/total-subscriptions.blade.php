<!-- Total Subscriptions Modal Template -->
<div id="totalSubscriptionsModalTemplate" style="display: none;">
    <div dir="rtl">
        <!-- Workshop Selection -->
        <div class="mb-4">
            <label for="total_subscriptions_workshop_id" class="form-label">اختر الورشة لعرض الإحصائيات <span class="text-danger">*</span></label>
            <select class="form-select" id="total_subscriptions_workshop_id" name="workshop_id" onchange="handleTotalSubscriptionsWorkshopChange(event)">
                <option value="">اختر ورشة...</option>
                @foreach($workshops as $workshop)
                    <option value="{{ $workshop->id }}">{{ $workshop->title }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- Summary Section -->
        <div id="total_subscriptions_summary" style="display: none;" class="mb-4">
            <div class="alert alert-info" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 8px; padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h6 style="color: #fff; font-weight: 700; margin: 0;" id="total_subscriptions_workshop_title">-</h6>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="button" class="btn btn-sm" onclick="exportTotalSubscriptionsExcel()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: #fff;">
                            <i class="fa fa-file-excel me-2"></i>
                            Excel
                        </button>
                        <button type="button" class="btn btn-sm" onclick="exportTotalSubscriptionsPdf()" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; color: #fff;">
                            <i class="fa fa-file-pdf me-2"></i>
                            PDF
                        </button>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <div style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 0.25rem;">إجمالي الإيرادات:</div>
                        <div style="color: #fff; font-weight: 600; font-size: 1.2rem;" id="total_subscriptions_total_amount">-</div>
                    </div>
                    <div>
                        <div style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 0.25rem;">عدد المشتركين:</div>
                        <div style="color: #fff; font-weight: 600; font-size: 1.2rem;" id="total_subscriptions_count">-</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabs Section -->
        <div id="total_subscriptions_tabs_section" style="display: none;" class="mb-4">
            <div style="background: rgba(255,255,255,0.05); border-radius: 8px; padding: 0.25rem; margin-bottom: 1rem;">
                <div style="display: flex; gap: 0.5rem;">
                    <button type="button" id="total_subscriptions_tab_packages" class="btn btn-sm" onclick="switchTotalSubscriptionsTab('packages')" style="flex: 1; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border: none; color: #fff; font-weight: 600;">
                        تحليل الباقات
                    </button>
                    <button type="button" id="total_subscriptions_tab_payment_methods" class="btn btn-sm" onclick="switchTotalSubscriptionsTab('payment_methods')" style="flex: 1; background: rgba(255,255,255,0.1); border: none; color: #94a3b8; font-weight: 600;">
                        تحليل طرق الدفع
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Packages Table Section -->
        <div id="total_subscriptions_packages_table" style="display: none;">
            <div class="table-responsive" style="max-height: 50vh; overflow-y: auto;">
                <table class="table" style="color: #fff; margin: 0;">
                    <thead style="position: sticky; top: 0; background: rgba(15, 23, 42, 0.95); z-index: 10;">
                        <tr>
                            <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الباقة</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">عدد المشتركين</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الإيرادات</th>
                        </tr>
                    </thead>
                    <tbody id="total_subscriptions_packages_table_body">
                        <!-- Data will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Payment Methods Table Section -->
        <div id="total_subscriptions_payment_methods_table" style="display: none;">
            <div class="table-responsive" style="max-height: 50vh; overflow-y: auto;">
                <table class="table" style="color: #fff; margin: 0;">
                    <thead style="position: sticky; top: 0; background: rgba(15, 23, 42, 0.95); z-index: 10;">
                        <tr>
                            <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">طريقة الدفع</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">عدد المشتركين</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">عدد الهدايا</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الإيرادات</th>
                        </tr>
                    </thead>
                    <tbody id="total_subscriptions_payment_methods_table_body">
                        <!-- Data will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Loading State -->
        <div id="total_subscriptions_loading" style="display: none;" class="text-center py-4">
            <i class="fa fa-spinner fa-spin fa-2x mb-2" style="color: #94a3b8;"></i>
            <div style="color: #94a3b8;">جاري التحميل...</div>
        </div>
        
        <!-- Empty State -->
        <div id="total_subscriptions_empty" style="display: none;" class="text-center py-4">
            <i class="fa fa-inbox fa-3x mb-3" style="color: #94a3b8;"></i>
            <div style="color: #94a3b8;">لا توجد اشتراكات مدفوعة لهذه الورشة</div>
        </div>
    </div>
</div>

