<!-- Modal Templates (Hidden) -->
<div style="display: none;">
    <!-- Create Modal Template -->
    <div id="createModalTemplate">
        <form action="{{ route('admin.workshops.store') }}" method="POST" id="createWorkshopForm" enctype="multipart/form-data" dir="rtl">
            @csrf
            <!-- Common Fields -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="title" class="form-label">عنوان الورشة <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" required placeholder="أدخل عنوان الورشة">
                </div>
                <div class="col-md-6">
                    <label for="teacher" class="form-label">المدرب <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="teacher" name="teacher" required placeholder="أدخل اسم المدرب">
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="teacher_percentage" class="form-label">نسبة المدرب <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" max="100" class="form-control" id="teacher_percentage" name="teacher_percentage" required placeholder="0.00">
                </div>
                <div class="col-md-6">
                    <label for="type" class="form-label">النوع <span class="text-danger">*</span></label>
                    <select class="form-select" id="type" name="type" required onchange="handleTypeChange(event)">
                        <option value="">اختر النوع</option>
                        <option value="online">أونلاين</option>
                        <option value="onsite">حضوري</option>
                        <option value="online_onsite">أونلاين و حضوري</option>
                        <option value="recorded">مسجلة</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="description" class="form-label">الوصف</label>
                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="أدخل وصف الورشة"></textarea>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="subject_of_discussion" class="form-label">محاور الورشة<span class="text-danger">*</span></label>
                    <textarea class="form-control" id="subject_of_discussion" name="subject_of_discussion" rows="6"></textarea>
                </div>
            </div>

            <!-- Dynamic Type-Based Section -->
            <div id="type_specific_section" class="mb-4" style="display: none;">
                <div class="dynamic-section-header mb-3">
                    <h6 class="mb-0" style="color: #38bdf8; font-weight: 600;">
                        <i class="fa fa-info-circle me-2"></i>
                        تفاصيل النوع
                    </h6>
                </div>
                
                <!-- Online & Onsite & Online_Onsite: Date and Time -->
                <div id="datetime_section" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">تاريخ البداية <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">تاريخ النهاية</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label">وقت البداية <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="start_time" name="start_time">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label">وقت النهاية</label>
                            <input type="time" class="form-control" id="end_time" name="end_time">
                        </div>
                    </div>
                </div>
                
                <!-- Online & Online_Onsite: Zoom Link -->
                <div id="zoom_section" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="online_link" class="form-label">رابط الزوم <span class="text-danger">*</span></label>
                            <input type="url" class="form-control" id="online_link" name="online_link" placeholder="https://zoom.us/j/...">
                        </div>
                    </div>
                </div>
                
                <!-- Onsite & Online_Onsite: Location Fields -->
                <div id="location_section" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="city" class="form-label">المدينة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="أدخل اسم المدينة">
                        </div>
                        <div class="col-md-3">
                            <label for="country_id" class="form-label">الدولة <span class="text-danger">*</span></label>
                            <select class="form-select" id="country_id" name="country_id">
                                <option value="">اختر الدولة</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="hotel" class="form-label">الفندق</label>
                            <input type="text" class="form-control" id="hotel" name="hotel" placeholder="أدخل اسم الفندق">
                        </div>
                        <div class="col-md-3">
                            <label for="hall" class="form-label">القاعة</label>
                            <input type="text" class="form-control" id="hall" name="hall" placeholder="أدخل اسم القاعة">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Packages Section -->
            <div class="dynamic-section mb-4">
                <div class="dynamic-section-header mb-3">
                    <h6 class="mb-0" style="color: #38bdf8; font-weight: 600;">
                        <i class="fa fa-box me-2"></i>
                        الباقات
                    </h6>
                </div>
                <div id="packages_container"></div>
                <button type="button" class="btn-add-item" data-action="add-package">
                    <i class="fa fa-plus me-2"></i> إضافة باقة جديدة
                </button>
            </div>

            <!-- Attachments Section -->
            <div class="dynamic-section mb-4">
                <div class="dynamic-section-header mb-3">
                    <h6 class="mb-0" style="color: #38bdf8; font-weight: 600;">
                        <i class="fa fa-paperclip me-2"></i>
                        المرفقات (صور/فيديوهات)
                    </h6>
                </div>
                <div id="attachments_container"></div>
                <button type="button" class="btn-add-item" data-action="add-attachment">
                    <i class="fa fa-plus me-2"></i> إضافة مرفق جديد
                </button>
            </div>

            <!-- Files Section -->
            <div class="dynamic-section mb-4">
                <div class="dynamic-section-header mb-3">
                    <h6 class="mb-0" style="color: #38bdf8; font-weight: 600;">
                        <i class="fa fa-file me-2"></i>
                        الملفات
                    </h6>
                </div>
                <div id="files_container"></div>
                <button type="button" class="btn-add-item" data-action="add-file">
                    <i class="fa fa-plus me-2"></i> إضافة ملف جديد
                </button>
            </div>

            <!-- Recordings Section (only for recorded type) -->
            <div class="dynamic-section mb-4" id="recordings_section" style="display: none;">
                <div class="dynamic-section-header mb-3">
                    <h6 class="mb-0" style="color: #38bdf8; font-weight: 600;">
                        <i class="fa fa-video me-2"></i>
                        التسجيلات
                    </h6>
                </div>
                <div id="recordings_container"></div>
                <button type="button" class="btn-add-item" data-action="add-recording">
                    <i class="fa fa-plus me-2"></i> إضافة تسجيل جديد
                </button>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-2"></i>
                    حفظ
                </button>
            </div>
        </form>
    </div>

    <!-- Edit Modal Template -->
    <div id="editModalTemplate">
        <form id="editWorkshopForm" method="POST" enctype="multipart/form-data" dir="rtl">
            @csrf
            @method('PUT')
            <!-- Common Fields -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="edit_title" class="form-label">العنوان <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="edit_title" name="title" required placeholder="أدخل عنوان الورشة">
                </div>
                <div class="col-md-6">
                    <label for="edit_teacher" class="form-label">المدرب <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="edit_teacher" name="teacher" required placeholder="أدخل اسم المدرب">
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="edit_teacher_percentage" class="form-label">نسبة المدرب</label>
                    <input type="number" step="0.01" min="0" max="100" class="form-control" id="edit_teacher_percentage" name="teacher_percentage" placeholder="0.00">
                </div>
                <div class="col-md-6">
                    <label for="edit_type" class="form-label">النوع <span class="text-danger">*</span></label>
                    <select class="form-select" id="edit_type" name="type" required onchange="handleTypeChange(event)">
                        <option value="">اختر النوع</option>
                        <option value="online">أونلاين</option>
                        <option value="onsite">حضوري</option>
                        <option value="online_onsite">أونلاين و حضوري</option>
                        <option value="recorded">مسجلة</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="edit_description" class="form-label">الوصف</label>
                    <textarea class="form-control" id="edit_description" name="description" rows="4" placeholder="أدخل وصف الورشة"></textarea>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="edit_subject_of_discussion" class="form-label">موضوع النقاش <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="edit_subject_of_discussion" name="subject_of_discussion" rows="6"></textarea>
                </div>
            </div>

            <!-- Dynamic Type-Based Section -->
            <div id="edit_type_specific_section" class="mb-4" style="display: none;">
                <div class="dynamic-section-header mb-3">
                    <h6 class="mb-0" style="color: #38bdf8; font-weight: 600;">
                        <i class="fa fa-info-circle me-2"></i>
                        تفاصيل النوع
                    </h6>
                </div>
                
                <!-- Online & Onsite & Online_Onsite: Date and Time -->
                <div id="edit_datetime_section" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="edit_start_date" class="form-label">تاريخ البداية <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_start_date" name="start_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_end_date" class="form-label">تاريخ النهاية</label>
                            <input type="date" class="form-control" id="edit_end_date" name="end_date">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="edit_start_time" class="form-label">وقت البداية <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="edit_start_time" name="start_time">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_end_time" class="form-label">وقت النهاية</label>
                            <input type="time" class="form-control" id="edit_end_time" name="end_time">
                        </div>
                    </div>
                </div>
                
                <!-- Online & Online_Onsite: Zoom Link -->
                <div id="edit_zoom_section" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="edit_online_link" class="form-label">رابط الزوم <span class="text-danger">*</span></label>
                            <input type="url" class="form-control" id="edit_online_link" name="online_link" placeholder="https://zoom.us/j/...">
                        </div>
                    </div>
                </div>
                
                <!-- Onsite & Online_Onsite: Location Fields -->
                <div id="edit_location_section" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="edit_city" class="form-label">المدينة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_city" name="city" placeholder="أدخل اسم المدينة">
                        </div>
                        <div class="col-md-3">
                            <label for="edit_country_id" class="form-label">الدولة <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_country_id" name="country_id">
                                <option value="">اختر الدولة</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="edit_hotel" class="form-label">الفندق</label>
                            <input type="text" class="form-control" id="edit_hotel" name="hotel" placeholder="أدخل اسم الفندق">
                        </div>
                        <div class="col-md-3">
                            <label for="edit_hall" class="form-label">القاعة</label>
                            <input type="text" class="form-control" id="edit_hall" name="hall" placeholder="أدخل اسم القاعة">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Packages Section -->
            <div class="dynamic-section mb-4">
                <div class="dynamic-section-header mb-3">
                    <h6 class="mb-0" style="color: #38bdf8; font-weight: 600;">
                        <i class="fa fa-box me-2"></i>
                        الحزم
                    </h6>
                </div>
                <div id="edit_packages_container"></div>
                <button type="button" class="btn-add-item" data-action="add-package">
                    <i class="fa fa-plus me-2"></i> إضافة حزمة جديدة
                </button>
            </div>

            <!-- Attachments Section -->
            <div class="dynamic-section mb-4">
                <div class="dynamic-section-header mb-3">
                    <h6 class="mb-0" style="color: #38bdf8; font-weight: 600;">
                        <i class="fa fa-paperclip me-2"></i>
                        المرفقات (صور/فيديوهات)
                    </h6>
                </div>
                <div id="edit_attachments_container"></div>
                <button type="button" class="btn-add-item" data-action="add-attachment">
                    <i class="fa fa-plus me-2"></i> إضافة مرفق جديد
                </button>
            </div>

            <!-- Files Section -->
            <div class="dynamic-section mb-4">
                <div class="dynamic-section-header mb-3">
                    <h6 class="mb-0" style="color: #38bdf8; font-weight: 600;">
                        <i class="fa fa-file me-2"></i>
                        الملفات
                    </h6>
                </div>
                <div id="edit_files_container"></div>
                <button type="button" class="btn-add-item" data-action="add-file">
                    <i class="fa fa-plus me-2"></i> إضافة ملف جديد
                </button>
            </div>

            <!-- Recordings Section (only for recorded type) -->
            <div class="dynamic-section mb-4" id="edit_recordings_section" style="display: none;">
                <div class="dynamic-section-header mb-3">
                    <h6 class="mb-0" style="color: #38bdf8; font-weight: 600;">
                        <i class="fa fa-video me-2"></i>
                        التسجيلات
                    </h6>
                </div>
                <div id="edit_recordings_container"></div>
                <button type="button" class="btn-add-item" data-action="add-recording">
                    <i class="fa fa-plus me-2"></i> إضافة تسجيل جديد
                </button>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-2"></i>
                    حفظ
                </button>
            </div>
        </form>
    </div>

    <!-- Show Modal Template -->
    <div id="showModalTemplate">
        <div dir="rtl">
            <h5 class="mb-4" style="color: #38bdf8; font-weight: 700;">تفاصيل الورشة</h5>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">العنوان</label>
                    <div class="info-box">
                        <p class="text-white mb-0" id="show_title"></p>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">المدرب</label>
                    <div class="info-box">
                        <p class="text-white mb-0" id="show_teacher"></p>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">نسبة المدرب</label>
                    <div class="info-box">
                        <p class="text-white mb-0" id="show_teacher_percentage"></p>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">النوع</label>
                    <div class="info-box">
                        <p class="text-white mb-0" id="show_type"></p>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">الحالة</label>
                    <div class="info-box">
                        <p class="text-white mb-0" id="show_is_active"></p>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">عدد المشتركين</label>
                    <div class="info-box">
                        <p class="text-white mb-0" id="show_subscribers_count"></p>
                    </div>
                </div>
            </div>
            <!-- Type-Specific Details Section -->
            <div id="show_type_specific_section" style="display: none;">
                <h6 class="mb-3" style="color: #38bdf8; font-weight: 600;">
                    <i class="fa fa-info-circle me-2"></i>
                    تفاصيل النوع
                </h6>
                
                <!-- Date and Time Section (Online, Onsite, Online_Onsite) -->
                <div id="show_datetime_section" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ البداية</label>
                            <div class="info-box">
                                <p class="text-white mb-0" id="show_start_date">-</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ النهاية</label>
                            <div class="info-box">
                                <p class="text-white mb-0" id="show_end_date">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">وقت البداية</label>
                            <div class="info-box">
                                <p class="text-white mb-0" id="show_start_time">-</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">وقت النهاية</label>
                            <div class="info-box">
                                <p class="text-white mb-0" id="show_end_time">-</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Online Link Section (Online, Online_Onsite) -->
                <div id="show_zoom_section" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">رابط الزوم</label>
                            <div class="info-box">
                                <p class="text-white mb-0">
                                    <a href="#" id="show_online_link" target="_blank" style="color: #38bdf8;">-</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Location Section (Onsite, Online_Onsite) -->
                <div id="show_location_section" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">المدينة</label>
                            <div class="info-box">
                                <p class="text-white mb-0" id="show_city">-</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">الدولة</label>
                            <div class="info-box">
                                <p class="text-white mb-0" id="show_country">-</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">الفندق</label>
                            <div class="info-box">
                                <p class="text-white mb-0" id="show_hotel">-</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">القاعة</label>
                            <div class="info-box">
                                <p class="text-white mb-0" id="show_hall">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">الوصف</label>
                <div class="info-box">
                    <p class="text-white mb-0" id="show_description"></p>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">موضوع النقاش</label>
                <div class="info-box">
                    <div id="show_subject_of_discussion" style="color: #fff;"></div>
                </div>
            </div>
            
            <!-- Packages Section -->
            <div class="mb-4">
                <h6 class="mb-3" style="color: #38bdf8; font-weight: 600;">
                    <i class="fa fa-box me-2"></i>
                    الحزم
                </h6>
                <div id="show_packages_container"></div>
            </div>
            
            <!-- Attachments Section -->
            <div class="mb-4">
                <h6 class="mb-3" style="color: #38bdf8; font-weight: 600;">
                    <i class="fa fa-paperclip me-2"></i>
                    المرفقات
                </h6>
                <div id="show_attachments_container"></div>
            </div>
            
            <!-- Files Section -->
            <div class="mb-4">
                <h6 class="mb-3" style="color: #38bdf8; font-weight: 600;">
                    <i class="fa fa-file me-2"></i>
                    الملفات
                </h6>
                <div id="show_files_container"></div>
            </div>
            
            <!-- Recordings Section (only for recorded type) -->
            <div class="mb-4" id="show_recordings_section" style="display: none;">
                <h6 class="mb-3" style="color: #38bdf8; font-weight: 600;">
                    <i class="fa fa-video me-2"></i>
                    التسجيلات
                </h6>
                <div id="show_recordings_container"></div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
