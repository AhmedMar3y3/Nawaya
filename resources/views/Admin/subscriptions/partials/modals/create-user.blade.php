<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); border: none; border-radius: 15px;">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <h5 class="modal-title" id="createUserModalLabel" style="color: #fff; font-weight: 700;">إنشاء حساب للمستلم</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 1.5rem;" dir="rtl">
                <p style="color: #94a3b8; margin-bottom: 1.5rem; font-size: 0.9rem;">
                    لم يتم العثور على حساب بهذا الرقم. يرجى إكمال البيانات لإنشاء حساب جديد وتفعيل الهدية فوراً.
                </p>
                <form id="createUserForm">
                    <input type="hidden" id="createUserGiftId" name="gift_id">
                    <div class="mb-3">
                        <label for="createUserFullName" class="form-label" style="color: #fff; font-weight: 600;">الاسم الكامل</label>
                        <input type="text" class="form-control" id="createUserFullName" name="full_name" required style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff;">
                    </div>
                    <div class="mb-3">
                        <label for="createUserPhone" class="form-label" style="color: #fff; font-weight: 600;">رقم الهاتف (للتأكيد)</label>
                        <input type="text" class="form-control" id="createUserPhone" name="phone" required style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff;">
                    </div>
                    <div class="mb-3">
                        <label for="createUserEmail" class="form-label" style="color: #fff; font-weight: 600;">البريد الإلكتروني (مطلوب)</label>
                        <input type="email" class="form-control" id="createUserEmail" name="email" required style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff;">
                    </div>
                    <div class="mb-3">
                        <label for="createUserCountry" class="form-label" style="color: #fff; font-weight: 600;">الدولة</label>
                        <select class="form-select" id="createUserCountry" name="country_id" required style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff;">
                            <option value="">اختر الدولة</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" style="background: #1E293B; color: #fff;">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.1);">
                <button type="button" class="btn" data-bs-dismiss="modal" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff;">إلغاء</button>
                <button type="button" class="btn" onclick="submitCreateUserForm()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: #fff; font-weight: 600;">إنشاء وتفعيل</button>
            </div>
        </div>
    </div>
</div>

