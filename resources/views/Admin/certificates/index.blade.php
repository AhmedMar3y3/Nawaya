@extends('Admin.layout')

@section('styles')
    <style>
        .certificates-container {
            padding: 2rem 0;
        }

        .page-header {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
        }

        .page-title {
            color: #fff;
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .workshop-selection-section {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
        }

        .workshop-selection-section label {
            display: block;
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .workshop-select {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: #fff;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }

        .workshop-select:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #38bdf8;
            box-shadow: 0 0 0 0.2rem rgba(56, 189, 248, 0.25);
            color: #fff;
            outline: none;
        }

        .workshop-select option {
            background: #1E293B;
            color: #fff;
        }

        .certificate-status-section {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
        }

        .status-title {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .status-text {
            color: #10b981;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .status-text.not-issued {
            color: #ef4444;
        }

        .btn-generate {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-generate:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-cancel {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .attendance-list-section {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
        }

        .attendance-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .attendance-title {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .total-amount {
            color: #94a3b8;
            font-size: 1rem;
            font-weight: 500;
        }

        .btn-export {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
            color: white;
            text-decoration: none;
        }

        .certificates-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .certificates-table thead {
            background: rgba(255, 255, 255, 0.05);
        }

        .certificates-table th {
            color: #94a3b8;
            padding: 1rem;
            text-align: right;
            font-weight: 600;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .certificates-table td {
            color: #fff;
            padding: 1rem;
            text-align: right;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .certificates-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        /* Toggle Switch Styles */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ef4444;
            transition: 0.3s;
            border-radius: 30px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        .toggle-switch input:checked+.toggle-slider {
            background-color: #10b981;
        }

        .toggle-switch input:checked+.toggle-slider:before {
            transform: translateX(30px);
        }

        .toggle-label {
            color: #94a3b8;
            font-size: 0.85rem;
            margin-right: 0.5rem;
        }

        .btn-download {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            color: white;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            color: white;
            text-decoration: none;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #94a3b8;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state-text {
            font-size: 1.1rem;
        }
    </style>
@endsection

@section('main')
    <div class="certificates-container" dir="rtl">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">إدارة الشهادات</h1>
        </div>

        <!-- Workshop Selection -->
        <div class="workshop-selection-section">
            <label for="workshop_id">اختر الورشة</label>
            <select id="workshop_id" name="workshop_id" class="workshop-select">
                <option value="">-- اختر ورشة --</option>
                @foreach ($workshops as $workshop)
                    <option value="{{ $workshop->id }}"
                        {{ $selectedWorkshop && $selectedWorkshop->id == $workshop->id ? 'selected' : '' }}>
                        {{ $workshop->title }}
                    </option>
                @endforeach
            </select>
        </div>

        @if ($selectedWorkshop)
            <!-- Certificate Status Section -->
            <div class="certificate-status-section">
                <div class="status-title">
                    حالة إصدار الشهادات لورشة: {{ $selectedWorkshop->title }}
                </div>
                @if ($selectedWorkshop->is_certificates_generated)
                    <div class="status-text">
                        الشهادات مصدرة حالياً.
                    </div>
                    <button type="button" class="btn-cancel" onclick="cancelCertificates()">
                        إلغاء إصدار الشهادات
                    </button>
                @else
                    <div class="status-text not-issued">
                        الشهادات غير مصدرة.
                    </div>
                    <button type="button" class="btn-generate" onclick="generateCertificates()">
                        إصدار الشهادات الآن
                    </button>
                @endif
            </div>

            <!-- Attendance List Section -->
            @if ($selectedWorkshop->is_certificates_generated)
                <div class="attendance-list-section">
                    <div class="attendance-header">
                        <div>
                            <h3 class="attendance-title">
                                قائمة الحضور ({{ $certificates->count() }} / {{ $certificates->count() }})
                            </h3>
                        </div>
                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <span class="total-amount">إجمالي المبلغ {{ number_format($totalAmount, 2) }}</span>
                            <button type="button" class="btn-export" onclick="exportCertificates()">
                                <i class="fa fa-download"></i>
                                تصدير
                            </button>
                        </div>
                    </div>

                    @if ($certificates->count() > 0)
                        <table class="certificates-table">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>الهاتف</th>
                                    <th>الإيميل</th>
                                    <th>نوع الورشة</th>
                                    <th>تاريخ الاشتراك</th>
                                    <th>السعر</th>
                                    <th>المبلغ المدفوع</th>
                                    <th style="padding-right: 80px;">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($certificates as $certificate)
                                    <tr>
                                        <td>{{ $certificate->user->full_name ?? 'N/A' }}</td>
                                        <td>{{ $certificate->user->phone ?? 'N/A' }}</td>
                                        <td>{{ $certificate->user->email ?? 'N/A' }}</td>
                                        <td>{{ $certificate->workshop->type->getLocalizedName() }}</td>
                                        <td>{{ $certificate->subscription ? $certificate->subscription->created_at->locale('ar')->translatedFormat('j F Y') : 'N/A' }}
                                        </td>
                                        <td>{{ number_format($certificate->subscription ? $certificate->subscription->price ?? 0 : 0, 2) }}
                                        </td>
                                        <td>{{ number_format($certificate->subscription ? $certificate->subscription->paid_amount ?? 0 : 0, 2) }}
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <label
                                                    class="toggle-label">{{ $certificate->is_active ? 'مفعل' : 'غير مفعل' }}</label>
                                                <label class="toggle-switch">
                                                    <input type="checkbox" {{ $certificate->is_active ? 'checked' : '' }}
                                                        onchange="toggleCertificateStatus({{ $certificate->id }}, this.checked)">
                                                    <span class="toggle-slider"></span>
                                                </label>
                                                <a href="{{ route('admin.certificates.download', $certificate->id) }}"
                                                    class="btn-download">
                                                    <i class="fa fa-download"></i>
                                                    تحميل
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">📋</div>
                            <div class="empty-state-text">لا توجد شهادات متاحة</div>
                        </div>
                    @endif
                </div>
            @else
                <div class="attendance-list-section">
                    <div class="attendance-header">
                        <h3 class="attendance-title">قائمة الحضور (0 / 0)</h3>
                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <span class="total-amount">إجمالي المبلغ 0.00</span>
                            <button type="button" class="btn-export" disabled>
                                <i class="fa fa-download"></i>
                                تصدير
                            </button>
                        </div>
                    </div>
                    <table class="certificates-table">
                        <thead>
                            <tr>
                                <th>إصدار</th>
                                <th>الاسم</th>
                                <th>الهاتف</th>
                                <th>الإيميل</th>
                                <th>نوع الورشة</th>
                                <th>تاريخ الاشتراك</th>
                                <th>السعر</th>
                                <th>المبلغ المدفوع</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="8" class="empty-state">
                                    <div class="empty-state-text">يرجى إصدار الشهادات أولاً</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        @else
            <div class="attendance-list-section">
                <div class="attendance-header">
                    <h3 class="attendance-title">قائمة الحضور (0 / 0)</h3>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <span class="total-amount">إجمالي المبلغ 0.00</span>
                        <button type="button" class="btn-export" disabled>
                            <i class="fa fa-download"></i>
                            تصدير
                        </button>
                    </div>
                </div>
                <table class="certificates-table">
                    <thead>
                        <tr>
                            <th>إصدار</th>
                            <th>الاسم</th>
                            <th>الهاتف</th>
                            <th>الإيميل</th>
                            <th>نوع الورشة</th>
                            <th>تاريخ الاشتراك</th>
                            <th>المبلغ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="empty-state">
                                <div class="empty-state-text">يرجى اختيار ورشة أولاً</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('workshop_id').addEventListener('change', function() {
            const workshopId = this.value;
            if (workshopId) {
                window.location.href = '{{ route('admin.certificates.index') }}?workshop_id=' + workshopId;
            } else {
                window.location.href = '{{ route('admin.certificates.index') }}';
            }
        });

        function generateCertificates() {
            const workshopId = document.getElementById('workshop_id').value;

            if (!workshopId) {
                alert('يرجى اختيار ورشة');
                return;
            }

            if (!confirm('هل أنت متأكد من إصدار الشهادات لهذه الورشة؟')) {
                return;
            }

            fetch('{{ route('admin.certificates.generate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        workshop_id: workshopId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.msg || data.message);
                        location.reload();
                    } else {
                        alert(data.msg || data.message || 'حدث خطأ أثناء إصدار الشهادات');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء إصدار الشهادات');
                });
        }

        function cancelCertificates() {
            const workshopId = document.getElementById('workshop_id').value;

            if (!workshopId) {
                alert('يرجى اختيار ورشة');
                return;
            }

            if (!confirm('هل أنت متأكد من إلغاء إصدار الشهادات؟ سيتم حذف جميع الشهادات لهذه الورشة.')) {
                return;
            }

            fetch('{{ route('admin.certificates.cancel') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        workshop_id: workshopId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.msg || data.message);
                        location.reload();
                    } else {
                        alert(data.msg || data.message || 'حدث خطأ أثناء إلغاء إصدار الشهادات');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء إلغاء إصدار الشهادات');
                });
        }

        function toggleCertificateStatus(certificateId, isActive) {
            const checkbox = event.target;
            const originalState = !isActive;

            fetch('{{ route('admin.certificates.toggle-status', ':id') }}'.replace(':id', certificateId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const toggleLabel = checkbox.closest('.action-buttons').querySelector('.toggle-label');
                        if (toggleLabel) {
                            toggleLabel.textContent = isActive ? 'مفعل' : 'غير مفعل';
                        }
                    } else {
                        checkbox.checked = originalState;
                        alert(data.msg || data.message || 'حدث خطأ أثناء تحديث حالة الشهادة');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    checkbox.checked = originalState;
                    alert('حدث خطأ أثناء تحديث حالة الشهادة');
                });
        }

        function exportCertificates() {
            const workshopId = document.getElementById('workshop_id').value;

            if (!workshopId) {
                alert('يرجى اختيار ورشة أولاً');
                return;
            }

            window.location.href = '{{ route('admin.certificates.export.excel') }}?workshop_id=' + workshopId;
        }
    </script>
@endsection
