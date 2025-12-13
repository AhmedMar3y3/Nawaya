<div id="assignCharitySeatModalTemplate" style="display: none;">
    <div dir="rtl">
        <form id="assignCharitySeatForm">
            <input type="hidden" id="assignCharityId" name="charity_id">
            
            <div class="mb-3">
                <label for="assignCharityWorkshop" class="form-label" style="color: #fff; font-weight: 600;">الورشة المراد منحها</label>
                <input type="text" class="form-control" id="assignCharityWorkshop" disabled style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #94a3b8;">
            </div>

            <div class="mb-3">
                <label for="assignCharityOwner" class="form-label" style="color: #fff; font-weight: 600;">اختر الداعم (صاحب الرصيد)</label>
                <input type="text" class="form-control" id="assignCharityOwner" disabled style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #94a3b8;">
            </div>

            <div class="mb-3" style="position: relative;">
                <label for="assignCharityUserSearch" class="form-label" style="color: #fff; font-weight: 600;">البحث عن المشترك المستحق</label>
                <input type="text" 
                       class="form-control" 
                       id="assignCharityUserSearch" 
                       placeholder="ابحث بالاسم أو الهاتف..."
                       autocomplete="off"
                       style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff;">
                <input type="hidden" id="assignCharityUserId" name="user_id">
                <div id="assignCharityUserSearchResults" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: rgba(15, 23, 42, 0.98); border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; margin-top: 0.5rem; max-height: 300px; overflow-y: auto; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.3);"></div>
            </div>

            <div class="mb-3">
                <label for="assignCharityNotes" class="form-label" style="color: #fff; font-weight: 600;">ملاحظات إدارية (اختياري)</label>
                <textarea class="form-control" 
                          id="assignCharityNotes" 
                          name="charity_notes" 
                          rows="3" 
                          placeholder="سبب المنح أو ملاحظات أخرى..."
                          style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff;"></textarea>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn" onclick="closeAssignCharitySeatModal()" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff;">
                    إلغاء
                </button>
                <button type="button" class="btn" onclick="submitAssignCharitySeat()" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); border: none; color: #fff; font-weight: 600;">
                    <i class="fa fa-check me-2"></i>
                    تأكيد المنح
                </button>
            </div>
        </form>
    </div>
</div>

