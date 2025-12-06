<!-- Refunds Modal Template -->
<div id="refundsModalTemplate" style="display: none;">
    <div dir="rtl">
        <!-- Header -->
        <div class="mb-4">
            <h6 style="color: #fff; font-weight: 700; margin: 0;">إحصائيات المبالغ المستردة</h6>
        </div>
        
        <!-- Summary Boxes -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 8px; padding: 1.5rem;">
                <div style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 0.5rem;">إجمالي المبالغ المستردة</div>
                <div style="color: #fff; font-weight: 700; font-size: 1.5rem;" id="refunds_total_amount">0.00 د.إ</div>
            </div>
            <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 8px; padding: 1.5rem;">
                <div style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 0.5rem;">إجمالي عدد المستردين</div>
                <div style="color: #fff; font-weight: 700; font-size: 1.5rem;" id="refunds_total_count">0</div>
            </div>
        </div>
        
        <!-- Table -->
        <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
            <table class="table" style="color: #fff; margin: 0;">
                <thead style="position: sticky; top: 0; background: rgba(15, 23, 42, 0.95); z-index: 10;">
                    <tr>
                        <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">المستفيد</th>
                        <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">رقم الهاتف</th>
                        <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الورشة</th>
                        <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">المبلغ المسترد</th>
                        <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">طريقة الاسترداد</th>
                        <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">ملاحظات</th>
                        <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">تاريخ الاسترداد</th>
                        <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="refundsTableBody">
                    <!-- Data will be populated here -->
                    <tr>
                        <td colspan="8" class="text-center py-4" style="color: #94a3b8;">
                            <i class="fa fa-spinner fa-spin fa-2x mb-2"></i>
                            <div>جاري التحميل...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Empty State -->
        <div id="refunds_empty" style="display: none;" class="text-center py-4">
            <i class="fa fa-inbox fa-3x mb-3" style="color: #94a3b8;"></i>
            <div style="color: #94a3b8;">لا توجد مستردات</div>
        </div>
    </div>
</div>

