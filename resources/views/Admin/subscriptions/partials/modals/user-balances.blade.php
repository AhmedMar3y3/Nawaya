<!-- User Balances Modal Template -->
<div id="userBalancesModalTemplate" style="display: none;">
    <div dir="rtl">
        <!-- Header -->
        <div class="mb-4">
            <h6 style="color: #fff; font-weight: 700; margin: 0;">تفاصيل أرصدة المستخدمين</h6>
        </div>
        
        <!-- Total Balance Summary -->
        <div style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <div style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 0.5rem;">إجمالي الأرصدة المتاحة لجميع المستخدمين</div>
            <div style="color: #fff; font-weight: 700; font-size: 1.5rem;" id="user_balances_total">0.00</div>
        </div>
        
        <!-- Users List -->
        <div id="userBalancesList" style="max-height: 65vh; overflow-y: auto; overflow-x: hidden;">
            <!-- Users will be populated here -->
            <div class="text-center py-4" style="color: #94a3b8;">
                <i class="fa fa-spinner fa-spin fa-2x mb-2"></i>
                <div>جاري التحميل...</div>
            </div>
        </div>
        
        <!-- Empty State -->
        <div id="user_balances_empty" style="display: none;" class="text-center py-4">
            <i class="fa fa-inbox fa-3x mb-3" style="color: #94a3b8;"></i>
            <div style="color: #94a3b8;">لا توجد أرصدة متاحة</div>
        </div>
    </div>
</div>

