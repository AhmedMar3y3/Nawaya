<!-- Balance Subscriptions Modal Template -->
<div id="balanceSubscriptionsModalTemplate" style="display: none;">
    <div dir="rtl">
        <!-- Header -->
        <div class="mb-4">
            <h6 style="color: #fff; font-weight: 700; margin: 0;">الاشتراكات المدفوعة بالرصيد</h6>
        </div>
        
        <!-- Table -->
        <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
            <table class="table" style="color: #fff; margin: 0;">
                <thead style="position: sticky; top: 0; background: rgba(15, 23, 42, 0.95); z-index: 10;">
                    <tr>
                        <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">المشترك</th>
                        <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">رقم الهاتف</th>
                        <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الورشة</th>
                        <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">تاريخ الاشتراك</th>
                        <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">المبلغ المدفوع (رصيد)</th>
                    </tr>
                </thead>
                <tbody id="balanceSubscriptionsTableBody">
                    <!-- Data will be populated here -->
                    <tr>
                        <td colspan="5" class="text-center py-4" style="color: #94a3b8;">
                            <i class="fa fa-spinner fa-spin fa-2x mb-2"></i>
                            <div>جاري التحميل...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Empty State -->
        <div id="balance_subscriptions_empty" style="display: none;" class="text-center py-4">
            <i class="fa fa-inbox fa-3x mb-3" style="color: #94a3b8;"></i>
            <div style="color: #94a3b8;">لا توجد اشتراكات مدفوعة بالرصيد</div>
        </div>
    </div>
</div>

