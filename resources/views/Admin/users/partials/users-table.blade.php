<div class="users-table">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>رقم الهاتف</th>
                    <th>عدد الاشتراكات النشطة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->email ?? '-' }}</td>
                    <td>
                        @if($user->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" 
                               target="_blank" 
                               class="whatsapp-link">
                                <i class="fab fa-whatsapp"></i>
                                {{ $user->phone }}
                            </a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $user->active_subscriptions_count ?? 0 }}</td>
                    <td>
                        <div class="action-buttons">
                            @if($isDeleted)
                                <button type="button" 
                                        class="btn-action btn-restore" 
                                        onclick="restoreUser({{ $user->id }})"
                                        title="استعادة">
                                    <i class="fa fa-undo"></i>
                                    استعادة
                                </button>
                                <button type="button" 
                                        class="btn-action btn-delete" 
                                        onclick="permanentlyDeleteUser({{ $user->id }})"
                                        title="حذف نهائي">
                                    <i class="fa fa-trash"></i>
                                    حذف نهائي
                                </button>
                            @else
                                <button type="button" 
                                        class="btn-action btn-view" 
                                        onclick="openShowModal({{ $user->id }})"
                                        title="عرض التفاصيل">
                                    <i class="fa fa-eye"></i>
                                    عرض
                                </button>
                                <button type="button" 
                                        class="btn-action btn-edit" 
                                        onclick="openEditModal({{ $user->id }})"
                                        title="تعديل">
                                    <i class="fa fa-edit"></i>
                                    تعديل
                                </button>
                                <button type="button" 
                                        class="btn-action btn-delete" 
                                        onclick="deleteUser({{ $user->id }})"
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
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="fa fa-users"></i>
                            <h4>لا يوجد مستخدمين</h4>
                            <p>لم يتم العثور على مستخدمين مطابقين للبحث</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    <!-- Pagination Info and Controls -->
    @if($users->hasPages() || $users->total() > 0)
    <div class="pagination-wrapper">
        <div class="pagination-info">
            <span class="pagination-text">
                عرض 
                <strong>{{ $users->firstItem() ?? 0 }}</strong>
                إلى 
                <strong>{{ $users->lastItem() ?? 0 }}</strong>
                من أصل 
                <strong>{{ $users->total() }}</strong>
                مستخدم
            </span>
        </div>
        
        @if($users->hasPages())
        <div class="pagination-container">
            <ul class="custom-pagination">
                {{-- Previous Page Link --}}
                @if ($users->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fa fa-chevron-right"></i>
                            السابق
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $users->appends(request()->except('page'))->previousPageUrl() }}&tab={{ $isDeleted ? 'deleted' : 'active' }}" rel="prev">
                            <i class="fa fa-chevron-right"></i>
                            السابق
                        </a>
                    </li>
                @endif

                @php
                    $currentPage = $users->currentPage();
                    $lastPage = $users->lastPage();
                    $pages = [];
                    
                    if ($lastPage <= 7) {
                        // Show all pages if 7 or less
                        for ($i = 1; $i <= $lastPage; $i++) {
                            $pages[] = $i;
                        }
                    } else {
                        // Always show first page
                        $pages[] = 1;
                        
                        // Calculate range around current page
                        $start = max(2, $currentPage - 1);
                        $end = min($lastPage - 1, $currentPage + 2);
                        
                        // If we're near the start
                        if ($currentPage <= 4) {
                            for ($i = 2; $i <= min(5, $lastPage - 1); $i++) {
                                $pages[] = $i;
                            }
                            if ($lastPage > 6) {
                                $pages[] = 'dots';
                            }
                        }
                        // If we're near the end
                        elseif ($currentPage >= $lastPage - 3) {
                            if ($lastPage > 6) {
                                $pages[] = 'dots';
                            }
                            for ($i = max(2, $lastPage - 4); $i <= $lastPage - 1; $i++) {
                                $pages[] = $i;
                            }
                        }
                        // We're in the middle
                        else {
                            $pages[] = 'dots';
                            for ($i = $start; $i <= $end; $i++) {
                                $pages[] = $i;
                            }
                            $pages[] = 'dots';
                        }
                        
                        // Always show last page
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
                            <a class="page-link" href="{{ $users->appends(request()->except('page'))->url($page) }}&tab={{ $isDeleted ? 'deleted' : 'active' }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($users->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $users->appends(request()->except('page'))->nextPageUrl() }}&tab={{ $isDeleted ? 'deleted' : 'active' }}" rel="next">
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

