<!-- Debts Modal Template -->
<div id="debtsModalTemplate" style="display: none;">
    <div dir="rtl">
        <!-- Header -->
        <div class="mb-4">
            <h6 style="color: #fff; font-weight: 700; margin: 0;">المشتركون المدينون</h6>
        </div>
        
        <!-- Summary Box -->
        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <div style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 0.5rem;">إجمالي المديونيات المحددة</div>
            <div style="color: #fff; font-weight: 700; font-size: 1.5rem;" id="debts_total_amount">0.00 د.إ</div>
        </div>
        
        <!-- Workshop Filter -->
        <div class="mb-4">
            <label for="debts_workshop_id" class="form-label">اختر الورشة</label>
            <select class="form-select" id="debts_workshop_id" name="workshop_id" onchange="handleDebtsWorkshopChange(event)">
                <option value="">كل الورشات</option>
                @foreach($workshops as $workshop)
                    <option value="{{ $workshop->id }}">{{ $workshop->title }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- Table -->
        <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
            <table class="table" style="color: #fff; margin: 0;">
                <thead style="position: sticky; top: 0; background: rgba(15, 23, 42, 0.95); z-index: 10;">
                    <tr>
                        <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">المشترك</th>
                        <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">رقم الهاتف</th>
                        <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الورشة</th>
                        <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">المبلغ المتبقي</th>
                    </tr>
                </thead>
                <tbody id="debtsTableBody">
                    <!-- Data will be populated here -->
                    <tr>
                        <td colspan="4" class="text-center py-4" style="color: #94a3b8;">
                            <i class="fa fa-spinner fa-spin fa-2x mb-2"></i>
                            <div>جاري التحميل...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Empty State -->
        <div id="debts_empty" style="display: none;" class="text-center py-4">
            <i class="fa fa-inbox fa-3x mb-3" style="color: #94a3b8;"></i>
            <div style="color: #94a3b8;">لا توجد مديونيات</div>
        </div>
    </div>
</div>

