<div class="subscriptions-table">
    <div class="table-responsive">
        <table class="table">
            <tbody>
                @forelse($subscriptions as $subscription)
                @php
                    $userName = $subscription->user ? $subscription->user->full_name : ($subscription->full_name ?? '-');
                    $userEmail = $subscription->user ? $subscription->user->email : null;
                    $userPhone = $subscription->user ? $subscription->user->phone : ($subscription->phone ?? null);
                    
                    $packageTitle = '-';
                    if ($subscription->workshop && $subscription->workshop->packages) {
                        $matchingPackage = $subscription->workshop->packages->first(function ($package) use ($subscription) {
                            return abs($package->price - $subscription->price) < 0.01 || 
                                   ($package->is_offer && abs($package->offer_price - $subscription->price) < 0.01);
                        });
                        if ($matchingPackage) {
                            $packageTitle = $matchingPackage->title;
                        } else {
                            $packageTitle = $subscription->workshop->title;
                        }
                    } elseif ($subscription->workshop) {
                        $packageTitle = $subscription->workshop->title;
                    }
                    
                    $statusClass = match($subscription->status->value) {
                        'paid' => 'badge-paid',
                        'active' => 'badge-active',
                        'pending' => 'badge-pending',
                        'expired' => 'badge-expired',
                        'failed' => 'badge-failed',
                        'refunded' => 'badge-refunded',
                        default => 'badge-pending'
                    };
                    
                    $statusText = __('enums.subscription_statuses.' . $subscription->status->value, [], 'ar');
                    $paymentTypeText = $subscription->payment_type ? __('enums.payment_types.' . $subscription->payment_type->value, [], 'ar') : '-';
                @endphp
                <!-- First Row: User Details and Amount -->
                <tr class="subscription-row-first" data-subscription-id="{{ $subscription->id }}">
                    <td class="user-details-cell">
                        <div class="user-info-container">
                            <div class="user-name">
                                <strong>{{ $userName }}</strong>
                            </div>
                            <div class="user-contact">
                                @if($userEmail)
                                    <span class="contact-item">
                                        <button type="button" class="btn-copy" onclick="event.preventDefault(); copyToClipboard('email-{{ $subscription->id }}', '{{ addslashes($userEmail) }}')" title="نسخ البريد الإلكتروني">
                                            <i class="fa fa-copy"></i>
                                        </button>
                                        <i class="fa fa-envelope"></i>
                                        <span class="contact-value" id="email-{{ $subscription->id }}">{{ $userEmail }}</span>
                                    </span>
                                @endif
                                @if($userPhone)
                                    <span class="contact-item">
                                        <button type="button" class="btn-copy" onclick="event.preventDefault(); copyToClipboard('phone-{{ $subscription->id }}', '{{ addslashes($userPhone) }}')" title="نسخ رقم الهاتف">
                                            <i class="fa fa-copy"></i>
                                        </button>
                                        <i class="fab fa-whatsapp"></i>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $userPhone) }}" target="_blank" class="whatsapp-link" title="فتح واتساب">
                                            {{ $userPhone }}
                                        </a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="amount-cell">
                        <div class="amount-display">
                            @if($subscription->workshop)
                                <div class="workshop-title">
                                    {{ $subscription->workshop->title }}
                                </div>
                            @endif
                            <div style="display: flex; flex-direction: column; gap: 0.5rem; align-items: center;">
                                <div>
                                    <span style="color: #94a3b8; font-size: 0.85rem;">السعر:</span>
                                    <strong>{{ number_format($subscription->price, 2) }}</strong>
                                    <span class="currency">د.إ</span>
                                </div>
                                <div>
                                    <span style="color: #94a3b8; font-size: 0.85rem;">المدفوع:</span>
                                    <strong style="color: #10b981;">{{ number_format($subscription->paid_amount, 2) }}</strong>
                                    <span class="currency">د.إ</span>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <!-- Second Row: Other Details and Actions -->
                <tr class="subscription-row-second" data-subscription-id="{{ $subscription->id }}">
                    <td class="details-cell">
                        <div class="details-grid">
                            <div class="detail-item">
                                <span class="detail-label">الحالة:</span>
                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">الباقة:</span>
                                <span class="detail-value" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: inline-block;">{{ $packageTitle }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">نوع الدفع:</span>
                                <span class="detail-value">{{ $paymentTypeText }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">هدية:</span>
                                @if($subscription->is_gift)
                                    <span class="badge badge-gift">نعم</span>
                                @else
                                    <span class="detail-value">لا</span>
                                @endif
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">التاريخ:</span>
                                <span class="detail-value">
                                    @if($subscription->created_at)
                                        {{ \App\Helpers\FormatArabicDates::formatArabicDate($subscription->created_at) }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="actions-cell">
                        <div class="action-buttons">
                            @if($isDeleted)
                                <button type="button" 
                                        class="btn-action btn-view" 
                                        style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);"
                                        onclick="restoreSubscription({{ $subscription->id }})"
                                        title="استعادة">
                                    <i class="fa fa-undo"></i>
                                </button>
                                <button type="button" 
                                        class="btn-action btn-delete" 
                                        onclick="permanentlyDeleteSubscription({{ $subscription->id }})"
                                        title="حذف نهائي">
                                    <i class="fa fa-trash"></i>
                                </button>
                            @else
                                <button type="button" 
                                        class="btn-action btn-delete" 
                                        onclick="deleteSubscription({{ $subscription->id }})"
                                        title="حذف">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <button type="button" 
                                        class="btn-action btn-invoice" 
                                        onclick="openInvoiceModal({{ $subscription->id }})"
                                        title="الفاتورة">
                                    <i class="fa fa-file-invoice"></i>
                                </button>
                                <button type="button" 
                                        class="btn-action btn-certificate" 
                                        onclick="openTransferToInternalBalanceModal({{ $subscription->id }})"
                                        title="تحويل للرصيد الداخلي">
                                    <i class="fa fa-wallet"></i>
                                </button>
                                <button type="button" 
                                        class="btn-action btn-transfer" 
                                        onclick="openTransferModal({{ $subscription->id }})"
                                        title="تحويل">
                                    <i class="fa fa-exchange-alt"></i>
                                </button>
                                <button type="button" 
                                        class="btn-action btn-refund" 
                                        onclick="openRefundModal({{ $subscription->id }})"
                                        title="استرداد">
                                    <i class="fa fa-undo"></i>
                                </button>
                                @if($subscription->workshop && $subscription->workshop->type->value === 'recorded')
                                <button type="button" 
                                        class="btn-action btn-calendar" 
                                        onclick="openSubscriptionRecordingPermissionsModal({{ $subscription->id }})"
                                        title="صلاحيات التسجيل">
                                    <i class="fa fa-calendar"></i>
                                </button>
                                @endif
                                <button type="button" 
                                        class="btn-action btn-user" 
                                        onclick="openDetailsModal({{ $subscription->id }})"
                                        title="عرض التفاصيل">
                                    <i class="fa fa-user"></i>
                                </button>
                                <button type="button" 
                                        class="btn-action btn-edit" 
                                        onclick="openEditModal({{ $subscription->id }})"
                                        title="تعديل">
                                    <i class="fa fa-edit"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2">
                        <div class="empty-state">
                            <i class="fa fa-inbox" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                            <h4>لا توجد اشتراكات</h4>
                            <p>لم يتم العثور على اشتراكات مطابقة للبحث</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination Info and Controls -->
@if($subscriptions->hasPages() || $subscriptions->total() > 0)
<div class="pagination-wrapper">
    <div class="pagination-info">
        <span class="pagination-text">
            عرض 
            <strong>{{ $subscriptions->firstItem() ?? 0 }}</strong>
            إلى 
            <strong>{{ $subscriptions->lastItem() ?? 0 }}</strong>
            من أصل 
            <strong>{{ $subscriptions->total() }}</strong>
            اشتراك
        </span>
    </div>
    
    @if($subscriptions->hasPages())
    <div class="pagination-container">
        <ul class="custom-pagination">
            {{-- Previous Page Link --}}
            @if ($subscriptions->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="fa fa-chevron-right"></i>
                        السابق
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $subscriptions->appends(request()->except('page'))->previousPageUrl() }}&tab={{ $isDeleted ? 'deleted' : 'active' }}" rel="prev">
                        <i class="fa fa-chevron-right"></i>
                        السابق
                    </a>
                </li>
            @endif

            @php
                $currentPage = $subscriptions->currentPage();
                $lastPage = $subscriptions->lastPage();
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
                        <a class="page-link" href="{{ $subscriptions->appends(request()->except('page'))->url($page) }}&tab={{ $isDeleted ? 'deleted' : 'active' }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($subscriptions->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $subscriptions->appends(request()->except('page'))->nextPageUrl() }}&tab={{ $isDeleted ? 'deleted' : 'active' }}" rel="next">
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
