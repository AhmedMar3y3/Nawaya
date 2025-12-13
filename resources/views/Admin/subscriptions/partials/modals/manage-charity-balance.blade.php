<div class="modal fade" id="manageCharityBalanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); border: none; border-radius: 15px;">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <h5 class="modal-title" style="color: #fff; font-weight: 700;">إدارة تذاكر التبرع</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 1.5rem;" dir="rtl">
                <input type="hidden" id="mcbCharityId">
                
                <div class="mb-3" style="background: rgba(236, 72, 153, 0.1); border: 1px solid rgba(236, 72, 153, 0.3); border-radius: 10px; padding: 1rem;">
                    <div style="margin-bottom: 0.75rem;">
                        <div style="color: #94a3b8; font-size: 0.8rem;">المشترك:</div>
                        <div style="color: #fff; font-weight: 600; font-size: 0.9rem;" id="mcbDonorName">-</div>
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <div style="color: #94a3b8; font-size: 0.8rem;">الورشة:</div>
                        <div style="color: #fff; font-weight: 600; font-size: 0.9rem;" id="mcbWorkshop">-</div>
                    </div>
                    <div>
                        <div style="color: #94a3b8; font-size: 0.8rem;">قيمة المقعد:</div>
                        <div style="color: #ec4899; font-weight: 700; font-size: 1rem;" id="mcbPricePerSeat">-</div>
                    </div>
                </div>

                <div class="mb-3" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 10px; padding: 0.75rem; text-align: center;">
                    <div style="color: #94a3b8; font-size: 0.8rem; margin-bottom: 0.25rem;">الرصيد الحالي بالصندوق:</div>
                    <div style="color: #10b981; font-weight: 700; font-size: 1.1rem;">
                        <span id="mcbAvailableSeats">-</span> مقاعد (<span id="mcbAvailableAmount">-</span> درهم)
                    </div>
                </div>

                <div class="mb-3">
                    <label style="color: #fff; font-weight: 600; text-align: center; display: block; margin-bottom: 1rem;">
                        كم عدد التذاكر التي تريد استرجاعها؟
                    </label>
                    <div style="display: flex; align-items: center; justify-content: center; gap: 1rem;">
                        <button type="button" id="mcbMinusBtn" style="width: 50px; height: 50px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 10px; color: #fff; font-size: 1.5rem; font-weight: 700; cursor: pointer;">-</button>
                        <div style="text-align: center; min-width: 100px;">
                            <div style="font-size: 2.5rem; color: #ec4899; font-weight: 700;" id="mcbSeatsDisplay">1</div>
                            <div style="color: #94a3b8; font-size: 0.85rem;">مقعد</div>
                        </div>
                        <button type="button" id="mcbPlusBtn" style="width: 50px; height: 50px; background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); border: none; border-radius: 10px; color: #fff; font-size: 1.5rem; font-weight: 700; cursor: pointer;">+</button>
                    </div>
                    <input type="hidden" id="mcbSeatsCount" value="1">
                    <div style="text-align: center; margin-top: 1rem; color: #fff; font-weight: 600; font-size: 1.1rem;">
                        القيمة: <span id="mcbTotalValue" style="color: #ec4899; font-size: 1.3rem; font-weight: 700;">0.00</span> درهم
                    </div>
                </div>

                <div class="mb-3">
                    <label style="color: #fff; font-weight: 600; text-align: center; display: block; margin-bottom: 0.75rem;">
                        ماذا تريد أن تفعل بالقيمة؟
                    </label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <button type="button" onclick="selectMcbAction('keep_balance')" id="mcbActionKeep" style="padding: 1rem; background: rgba(16, 185, 129, 0.15); border: 2px solid rgba(16, 185, 129, 0.3); border-radius: 10px; color: #fff; cursor: pointer;">
                            <i class="fa fa-wallet" style="font-size: 1.2rem; color: #10b981; margin-bottom: 0.25rem;"></i>
                            <div style="font-weight: 600; font-size: 0.9rem;">احتفاظ كرصيد</div>
                        </button>
                        <button type="button" onclick="selectMcbAction('refund')" id="mcbActionRefund" style="padding: 1rem; background: rgba(190, 24, 93, 0.15); border: 2px solid rgba(190, 24, 93, 0.3); border-radius: 10px; color: #fff; cursor: pointer;">
                            <i class="fa fa-money-bill-wave" style="font-size: 1.2rem; color: #be185d; margin-bottom: 0.25rem;"></i>
                            <div style="font-weight: 600; font-size: 0.9rem;">استرجاع مالي</div>
                        </button>
                    </div>
                    <input type="hidden" id="mcbAction">
                </div>

                <div style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 8px; padding: 0.75rem; margin-bottom: 1rem; text-align: center;">
                    <div style="color: #94a3b8; font-size: 0.8rem; line-height: 1.5;" id="mcbActionNote">يرجى اختيار الإجراء المطلوب</div>
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn" data-bs-dismiss="modal" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff;">إلغاء</button>
                    <button type="button" id="mcbSubmitBtn" class="btn" onclick="submitMcbForm()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: #fff; font-weight: 600;"><i class="fa fa-lock me-2"></i>تأكيد الاسترجاع</button>
                </div>
            </div>
        </div>
    </div>
</div>

