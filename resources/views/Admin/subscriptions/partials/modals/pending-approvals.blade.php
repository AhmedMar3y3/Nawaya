<!-- Pending Approvals Modal Template -->
<div id="pendingApprovalsModalTemplate" style="display: none;">
    <div dir="rtl">
        <!-- Header with Export Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 style="color: #fff; font-weight: 700; margin: 0;">الاشتراكات قيد المعالجة</h6>
            <button type="button" class="btn btn-sm" onclick="exportPendingApprovals()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: #fff;">
                <i class="fa fa-file-excel me-2"></i>
                تصدير Excel
            </button>
        </div>
        
        <!-- Table -->
        <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
            <table class="table" style="color: #fff; margin: 0;">
                <thead style="position: sticky; top: 0; background: rgba(15, 23, 42, 0.95); z-index: 10;">
                    <tr>
                        <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الاسم</th>
                        <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الهاتف</th>
                        <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الورشة</th>
                        <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">نوع الطلب</th>
                        <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">تاريخ الإنشاء</th>
                        <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">طريقة الدفع</th>
                        <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="pendingApprovalsTableBody">
                    <!-- Data will be populated here -->
                    <tr>
                        <td colspan="7" class="text-center py-4" style="color: #94a3b8;">
                            <i class="fa fa-spinner fa-spin fa-2x mb-2"></i>
                            <div>جاري التحميل...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


