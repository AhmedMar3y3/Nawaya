<!-- Charity Subscriptions Modal Template -->
<div id="charitySubscriptionsModalTemplate" style="display: none;">
    <div dir="rtl">
        <!-- Header with Total Available Amount -->
        <div class="mb-4" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); padding: 1.5rem; border-radius: 12px;">
            <div style="text-align: center;">
                <div style="color: rgba(255,255,255,0.9); font-size: 0.9rem; margin-bottom: 0.5rem;">إجمالي رصيد الدعم المتاح</div>
                <div style="color: #fff; font-size: 2rem; font-weight: 700;" id="charityTotalAvailable">0.00 درهم</div>
                <div style="color: rgba(255,255,255,0.8); font-size: 0.85rem; margin-top: 0.5rem;">رصيد مخصص لمقاعد مجانية للمشتركين</div>
            </div>
        </div>
        
        <!-- Tabs -->
        <div style="margin-bottom: 1rem;">
            <div style="display: flex; gap: 0.5rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <button type="button" onclick="switchCharityTab('existing')" id="charity_tab_existing" class="charity-tab-btn" style="padding: 0.75rem 1.5rem; background: rgba(236, 72, 153, 0.2); border: none; border-bottom: 2px solid #ec4899; color: #fff; cursor: pointer; font-weight: 600;">
                    نشط
                </button>
                <button type="button" onclick="switchCharityTab('deleted')" id="charity_tab_deleted" class="charity-tab-btn" style="padding: 0.75rem 1.5rem; background: transparent; border: none; border-bottom: 2px solid transparent; color: #94a3b8; cursor: pointer;">
                    سلة المهملات
                </button>
            </div>
        </div>
        
        <!-- Tab Content Container -->
        <div id="charityTabContentContainer" style="max-height: 65vh; overflow-y: auto; overflow-x: hidden;">
            <!-- Existing Charities Tab -->
            <div id="charity_tab_content_existing" class="charity-tab-content">
                <table class="table" style="color: #fff; margin: 0; width: 100%; table-layout: fixed;">
                    <colgroup>
                        <col style="width: 15%;">
                        <col style="width: 12%;">
                        <col style="width: 20%;">
                        <col style="width: 15%;">
                        <col style="width: 12%;">
                        <col style="width: 26%;">
                    </colgroup>
                    <thead style="position: sticky; top: 0; background: rgba(15, 23, 42, 0.98); z-index: 10; backdrop-filter: blur(10px);">
                        <tr>
                            <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الداعم</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الهاتف</th>
                            <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الورشة</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">السعر والمقاعد</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">المقاعد المتاحة</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="charityExistingTableBody">
                        <tr>
                            <td colspan="6" class="text-center py-4" style="color: #94a3b8;">
                                <i class="fa fa-spinner fa-spin fa-2x mb-2"></i>
                                <div>جاري التحميل...</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div id="charity_existing_empty" style="display: none;" class="text-center py-4">
                    <i class="fa fa-inbox fa-3x mb-3" style="color: #94a3b8;"></i>
                    <div style="color: #94a3b8;">لا توجد اشتراكات دعم في هذا القسم</div>
                </div>
            </div>
            
            <!-- Deleted Charities Tab -->
            <div id="charity_tab_content_deleted" class="charity-tab-content" style="display: none;">
                <table class="table" style="color: #fff; margin: 0; width: 100%; table-layout: fixed;">
                    <colgroup>
                        <col style="width: 15%;">
                        <col style="width: 12%;">
                        <col style="width: 20%;">
                        <col style="width: 15%;">
                        <col style="width: 12%;">
                        <col style="width: 26%;">
                    </colgroup>
                    <thead style="position: sticky; top: 0; background: rgba(15, 23, 42, 0.98); z-index: 10; backdrop-filter: blur(10px);">
                        <tr>
                            <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الداعم</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الهاتف</th>
                            <th style="padding: 0.75rem; text-align: right; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الورشة</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">السعر والمقاعد</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">المقاعد المتاحة</th>
                            <th style="padding: 0.75rem; text-align: center; color: #94a3b8; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid rgba(255,255,255,0.1);">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="charityDeletedTableBody">
                        <tr>
                            <td colspan="6" class="text-center py-4" style="color: #94a3b8;">
                                <i class="fa fa-spinner fa-spin fa-2x mb-2"></i>
                                <div>جاري التحميل...</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div id="charity_deleted_empty" style="display: none;" class="text-center py-4">
                    <i class="fa fa-inbox fa-3x mb-3" style="color: #94a3b8;"></i>
                    <div style="color: #94a3b8;">لا توجد اشتراكات دعم محذوفة</div>
                </div>
            </div>
        </div>
    </div>
</div>
