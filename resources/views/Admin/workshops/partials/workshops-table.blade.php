<div class="workshops-table">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>المدرب</th>
                    <th>تاريخ البداية</th>
                    <th>النوع</th>
                    <th>عدد المشتركين</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($workshops as $workshop)
                <tr>
                    <td>{{ $workshop->title }}</td>
                    <td>{{ $workshop->teacher }}</td>
                    <td>{{ $workshop->start_date ? $workshop->start_date->format('Y-m-d') : '-' }}</td>
                    <td>{{ $workshop->type->getLocalizedName() }}</td>
                    <td>{{ $workshop->subscribers_count ?? 0 }}</td>
                    <td>
                        @if($isDeleted)
                            <span class="badge bg-secondary">محذوف</span>
                        @else
                            <label class="toggle-switch">
                                <input type="checkbox" 
                                       {{ $workshop->is_active ? 'checked' : '' }}
                                       onchange="toggleWorkshopStatus({{ $workshop->id }})">
                                <span class="toggle-slider"></span>
                            </label>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            @if($isDeleted)
                                <button type="button" 
                                        class="btn-action btn-restore" 
                                        onclick="restoreWorkshop({{ $workshop->id }})"
                                        title="استعادة">
                                    <i class="fa fa-undo"></i>
                                    استعادة
                                </button>
                                <button type="button" 
                                        class="btn-action btn-delete" 
                                        onclick="permanentlyDeleteWorkshop({{ $workshop->id }})"
                                        title="حذف نهائي">
                                    <i class="fa fa-trash"></i>
                                    حذف نهائي
                                </button>
                            @else
                                <button type="button" 
                                        class="btn-action btn-view" 
                                        onclick="openShowModal({{ $workshop->id }})"
                                        title="عرض التفاصيل">
                                    <i class="fa fa-eye"></i>
                                    عرض
                                </button>
                                <button type="button" 
                                        class="btn-action btn-edit" 
                                        onclick="openEditModal({{ $workshop->id }})"
                                        title="تعديل">
                                    <i class="fa fa-edit"></i>
                                    تعديل
                                </button>
                                <button type="button" 
                                        class="btn-action btn-delete" 
                                        onclick="deleteWorkshop({{ $workshop->id }})"
                                        title="حذف">
                                    <i class="fa fa-trash"></i>
                                    حذف
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fa fa-graduation-cap"></i>
                            <h4>لا توجد ورش عمل</h4>
                            <p>لم يتم العثور على ورش عمل مطابقة للبحث</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination Info and Controls -->
@if($workshops->hasPages() || $workshops->total() > 0)
<div class="pagination-wrapper">
    <div class="pagination-info">
        <span class="pagination-text">
            عرض 
            <strong>{{ $workshops->firstItem() ?? 0 }}</strong>
            إلى 
            <strong>{{ $workshops->lastItem() ?? 0 }}</strong>
            من أصل 
            <strong>{{ $workshops->total() }}</strong>
            ورشة عمل
        </span>
    </div>
    
    @if($workshops->hasPages())
    <div class="pagination-container">
        <ul class="custom-pagination">
            {{-- Previous Page Link --}}
            @if ($workshops->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="fa fa-chevron-right"></i>
                        السابق
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $workshops->appends(request()->except('page'))->previousPageUrl() }}&tab={{ $isDeleted ? 'deleted' : 'active' }}" rel="prev">
                        <i class="fa fa-chevron-right"></i>
                        السابق
                    </a>
                </li>
            @endif

            @php
                $currentPage = $workshops->currentPage();
                $lastPage = $workshops->lastPage();
                $pages = [];
                
                if ($lastPage <= 7) {
                    for ($i = 1; $i <= $lastPage; $i++) {
                        $pages[] = $i;
                    }
                } else {
                    $pages[] = 1;
                    
                    $start = max(2, $currentPage - 1);
                    $end = min($lastPage - 1, $currentPage + 2);
                    
                    if ($currentPage <= 4) {
                        for ($i = 2; $i <= min(5, $lastPage - 1); $i++) {
                            $pages[] = $i;
                        }
                        if ($lastPage > 6) {
                            $pages[] = 'dots';
                        }
                    } elseif ($currentPage >= $lastPage - 3) {
                        if ($lastPage > 6) {
                            $pages[] = 'dots';
                        }
                        for ($i = max(2, $lastPage - 4); $i <= $lastPage - 1; $i++) {
                            $pages[] = $i;
                        }
                    } else {
                        $pages[] = 'dots';
                        for ($i = $start; $i <= $end; $i++) {
                            $pages[] = $i;
                        }
                        $pages[] = 'dots';
                    }
                    
                    if ($lastPage > 1) {
                        $pages[] = $lastPage;
                    }
                }
            @endphp

            {{-- Page Numbers --}}
            @foreach ($pages as $page)
                @if ($page === 'dots')
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @elseif ($page == $currentPage)
                    <li class="page-item active">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $workshops->appends(request()->except('page'))->url($page) }}&tab={{ $isDeleted ? 'deleted' : 'active' }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($workshops->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $workshops->appends(request()->except('page'))->nextPageUrl() }}&tab={{ $isDeleted ? 'deleted' : 'active' }}" rel="next">
                        التالي
                        <i class="fa fa-chevron-left"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">
                        التالي
                        <i class="fa fa-chevron-left"></i>
                    </span>
                </li>
            @endif
        </ul>
    </div>
    @endif
</div>
@endif

