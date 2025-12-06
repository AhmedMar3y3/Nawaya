<!-- Pending Gifts Modal Template -->
<div id="pendingGiftsModalTemplate" style="display: none;">
    <div dir="rtl">
        <!-- Header -->
        <div class="mb-4">
            <h6 style="color: #fff; font-weight: 700; margin: 0;">الهدايا المعلقة</h6>
        </div>
        
        <!-- Tabs -->
        <div style="margin-bottom: 1rem;">
            <div style="display: flex; gap: 0.5rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <button type="button" onclick="switchGiftTab('existing')" id="gift_tab_existing" class="gift-tab-btn" style="padding: 0.75rem 1.5rem; background: rgba(59, 130, 246, 0.2); border: none; border-bottom: 2px solid #3b82f6; color: #fff; cursor: pointer; font-weight: 600;">
                    نشط
                </button>
                <button type="button" onclick="switchGiftTab('deleted')" id="gift_tab_deleted" class="gift-tab-btn" style="padding: 0.75rem 1.5rem; background: transparent; border: none; border-bottom: 2px solid transparent; color: #94a3b8; cursor: pointer;">
                    سلة المهملات
                </button>
            </div>
        </div>
        
        <!-- Tab Content Container (Single Scroll Area) -->
        <div id="giftTabContentContainer" style="max-height: 65vh; overflow-y: auto; overflow-x: hidden;">
            <!-- Existing Gifts Tab -->
            <div id="gift_tab_content_existing" class="gift-tab-content">
                <table class="table" style="color: #fff; margin: 0; width: 100%; table-layout: fixed;">
                    <colgroup>
                        <col style="width: 20%;">
                        <col style="width: 25%;">
                        <col style="width: 20%;">
                        <col style="width: 15%;">
                        <col style="width: 20%;">
                    </colgroup>
                    <thead style="position: sticky; top: 0; background: rgba(15, 23, 42, 0.98); z-index: 10; backdrop-filter: blur(10px);">
                        <tr>
                            <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">المرسل</th>
                            <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الورشة</th>
                            <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">المستلم</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">تاريخ الإنشاء</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="giftExistingTableBody">
                        <tr>
                            <td colspan="5" class="text-center py-4" style="color: #94a3b8;">
                                <i class="fa fa-spinner fa-spin fa-2x mb-2"></i>
                                <div>جاري التحميل...</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- Empty State for Existing -->
                <div id="gift_existing_empty" style="display: none;" class="text-center py-4">
                    <i class="fa fa-inbox fa-3x mb-3" style="color: #94a3b8;"></i>
                    <div style="color: #94a3b8;">لا توجد هدايا في هذا القسم</div>
                </div>
            </div>
            
            <!-- Deleted Gifts Tab -->
            <div id="gift_tab_content_deleted" class="gift-tab-content" style="display: none;">
                <table class="table" style="color: #fff; margin: 0; width: 100%; table-layout: fixed;">
                    <colgroup>
                        <col style="width: 20%;">
                        <col style="width: 25%;">
                        <col style="width: 20%;">
                        <col style="width: 15%;">
                        <col style="width: 20%;">
                    </colgroup>
                    <thead style="position: sticky; top: 0; background: rgba(15, 23, 42, 0.98); z-index: 10; backdrop-filter: blur(10px);">
                        <tr>
                            <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">المرسل</th>
                            <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الورشة</th>
                            <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">المستلم</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">تاريخ الإنشاء</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="giftDeletedTableBody">
                        <tr>
                            <td colspan="5" class="text-center py-4" style="color: #94a3b8;">
                                <i class="fa fa-spinner fa-spin fa-2x mb-2"></i>
                                <div>جاري التحميل...</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- Empty State for Deleted -->
                <div id="gift_deleted_empty" style="display: none;" class="text-center py-4">
                    <i class="fa fa-inbox fa-3x mb-3" style="color: #94a3b8;"></i>
                    <div style="color: #94a3b8;">لا توجد هدايا محذوفة</div>
                </div>
            </div>
        </div>
    </div>
</div>

