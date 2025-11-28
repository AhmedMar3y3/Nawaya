@if(isset($orders) && $orders && $orders->count() > 0)
    <div class="products-table">
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المستخدم</th>
                    <th>رقم الهاتف</th>
                    <th>تاريخ الإنشاء</th>
                    <th>السعر الإجمالي</th>
                    <th>نوع الدفع</th>
                    @if($tab === 'pending')
                        <th>تحديث الحالة</th>
                    @endif
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->user->full_name ?? 'غير متوفر' }}</td>
                        <td>
                            @if($order->user && $order->user->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->user->phone) }}" 
                                   target="_blank" 
                                   class="btn-action btn-view" 
                                   style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <i class="fab fa-whatsapp"></i>
                                    {{ $order->user->phone }}
                                </a>
                            @else
                                غير متوفر
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ number_format($order->total_price, 2) }} د.إ</td>
                        <td>
                            @if($order->payment_type->value === 'online')
                                <span class="badge badge-platform">دفع إلكتروني</span>
                            @else
                                <span class="badge badge-user">تحويل بنكي</span>
                            @endif
                        </td>
                        @if($tab === 'pending')
                            <td>
                                <button type="button" 
                                        class="btn-action btn-restore" 
                                        onclick="markOrderCompleted({{ $order->id }})">
                                    <i class="fa fa-check"></i>
                                    إكمال
                                </button>
                            </td>
                        @endif
                        <td>
                            <div class="action-buttons">
                                <button type="button" 
                                        class="btn-action btn-view" 
                                        onclick="openOrderItemsModal({{ $order->id }})">
                                    <i class="fa fa-list"></i>
                                    العناصر
                                </button>
                                <button type="button" 
                                        class="btn-action btn-view" 
                                        onclick="openUserDetailsModal({{ $order->id }})">
                                    <i class="fa fa-user"></i>
                                    عرض
                                </button>
                                @if($tab === 'trashed')
                                    <button type="button" 
                                            class="btn-action btn-restore" 
                                            onclick="restoreOrder({{ $order->id }})">
                                        <i class="fa fa-undo"></i>
                                        استعادة
                                    </button>
                                    <button type="button" 
                                            class="btn-action btn-delete" 
                                            onclick="permanentlyDeleteOrder({{ $order->id }})">
                                        <i class="fa fa-trash"></i>
                                        حذف نهائي
                                    </button>
                                @else
                                    <button type="button" 
                                            class="btn-action btn-delete" 
                                            onclick="deleteOrder({{ $order->id }})">
                                        <i class="fa fa-trash"></i>
                                        حذف
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($orders->hasPages() || $orders->total() > 0)
    <div class="pagination-wrapper">
        <div class="pagination-info">
            <span class="pagination-text">
                عرض 
                <strong>{{ $orders->firstItem() ?? 0 }}</strong>
                إلى 
                <strong>{{ $orders->lastItem() ?? 0 }}</strong>
                من أصل 
                <strong>{{ $orders->total() }}</strong>
                طلب
            </span>
        </div>
        
        @if($orders->hasPages())
        <div class="pagination-container">
            <ul class="custom-pagination">
                {{-- Previous Page Link --}}
                @if ($orders->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fa fa-chevron-right"></i>
                            السابق
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $orders->appends(request()->except('page'))->previousPageUrl() }}&tab={{ $tab }}&section={{ request('section', 'orders') }}" rel="prev">
                            <i class="fa fa-chevron-right"></i>
                            السابق
                        </a>
                    </li>
                @endif

                @php
                    $currentPage = $orders->currentPage();
                    $lastPage = $orders->lastPage();
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
                        }
                        elseif ($currentPage >= $lastPage - 3) {
                            if ($lastPage > 6) {
                                $pages[] = 'dots';
                            }
                            for ($i = max(2, $lastPage - 4); $i <= $lastPage - 1; $i++) {
                                $pages[] = $i;
                            }
                        }
                        else {
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
                            <a class="page-link" href="{{ $orders->appends(request()->except('page'))->url($page) }}&tab={{ $tab }}&section={{ request('section', 'orders') }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($orders->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $orders->appends(request()->except('page'))->nextPageUrl() }}&tab={{ $tab }}&section={{ request('section', 'orders') }}" rel="next">
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
@else
    <div class="empty-state">
        <i class="fa fa-shopping-cart" style="font-size: 4rem; margin-bottom: 1rem;"></i>
        <h4>لا توجد طلبات</h4>
        <p>لا توجد طلبات في هذا القسم</p>
    </div>
@endif

