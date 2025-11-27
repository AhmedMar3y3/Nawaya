<div class="products-table">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>اسم المنتج</th>
                    <th>  السعر (درهم) </th>
                    <th>المالك</th>
                    <th>نسبة المالك</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <img src="{{ asset($product->image) }}" alt="{{ $product->title }}" style="width:40px; height:40px;">
                    </td>
                    <td>{{ $product->title }}</td>
                    <td>{{ number_format($product->price, 2) }}</td>
                    <td>
                        @if($product->owner_type->value === 'platform')
                            <span class="badge badge-platform">المنصة</span>
                        @else
                            <span class="badge badge-user">{{ $product->userOwner->full_name ?? 'غير معروف' }}</span>
                        @endif
                    </td>
                    <td>
                        @if($product->owner_type->value === 'platform')
                            <span>100%</span>
                        @else
                            <span>{{ $product->owner_per }}%</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            @if($isDeleted)
                                <button type="button" 
                                        class="btn-action btn-restore" 
                                        onclick="restoreProduct({{ $product->id }})"
                                        title="استعادة">
                                    <i class="fa fa-undo"></i>
                                    استعادة
                                </button>
                                <button type="button" 
                                        class="btn-action btn-delete" 
                                        onclick="permanentlyDeleteProduct({{ $product->id }})"
                                        title="حذف نهائي">
                                    <i class="fa fa-trash"></i>
                                    حذف نهائي
                                </button>
                            @else
                                <button type="button" 
                                        class="btn-action btn-view" 
                                        onclick="openShowModal({{ $product->id }})"
                                        title="عرض التفاصيل">
                                    <i class="fa fa-eye"></i>
                                    عرض
                                </button>
                                <button type="button" 
                                        class="btn-action btn-edit" 
                                        onclick="openEditModal({{ $product->id }})"
                                        title="تعديل">
                                    <i class="fa fa-edit"></i>
                                    تعديل
                                </button>
                                <button type="button" 
                                        class="btn-action btn-delete" 
                                        onclick="deleteProduct({{ $product->id }})"
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
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fa fa-box fa-5x" aria-hidden="true"></i>
                            <h4>لا يوجد منتجات</h4>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination Info and Controls -->
@if($products->hasPages() || $products->total() > 0)
<div class="pagination-wrapper">
    <div class="pagination-info">
        <span class="pagination-text">
            عرض 
            <strong>{{ $products->firstItem() ?? 0 }}</strong>
            إلى 
            <strong>{{ $products->lastItem() ?? 0 }}</strong>
            من أصل 
            <strong>{{ $products->total() }}</strong>
            منتج
        </span>
    </div>
    
    @if($products->hasPages())
    <div class="pagination-container">
        <ul class="custom-pagination">
            {{-- Previous Page Link --}}
            @if ($products->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="fa fa-chevron-right"></i>
                        السابق
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $products->appends(request()->except('page'))->previousPageUrl() }}&tab={{ $isDeleted ? 'deleted' : 'active' }}&section={{ request('section', 'products') }}" rel="prev">
                        <i class="fa fa-chevron-right"></i>
                        السابق
                    </a>
                </li>
            @endif

            @php
                $currentPage = $products->currentPage();
                $lastPage = $products->lastPage();
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
                        <a class="page-link" href="{{ $products->appends(request()->except('page'))->url($page) }}&tab={{ $isDeleted ? 'deleted' : 'active' }}&section={{ request('section', 'products') }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($products->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $products->appends(request()->except('page'))->nextPageUrl() }}&tab={{ $isDeleted ? 'deleted' : 'active' }}&section={{ request('section', 'products') }}" rel="next">
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

