<?php

namespace App\Http\Controllers\Admin;

use Mpdf\Mpdf;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\DashboardResponses;
use App\Exports\SubscriptionsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Services\Admin\SubscriptionService;
use App\Http\Resources\Admin\Subscription\DebtResource;
use App\Http\Resources\Admin\Subscription\RefundResource;
use App\Http\Resources\Admin\Subscription\InvoiceResource;
use App\Http\Resources\Admin\Subscription\TransferResource;
use App\Http\Resources\Admin\Subscription\UserSearchResource;
use App\Http\Resources\Admin\Subscription\UserBalanceResource;
use App\Http\Requests\Admin\Subscription\StoreSubscriptionRequest;
use App\Http\Resources\Admin\Subscription\SubscriptionEditResource;
use App\Http\Requests\Admin\Subscription\UpdateSubscriptionRequest;
use App\Http\Requests\Admin\Subscription\RefundSubscriptionRequest;
use App\Http\Resources\Admin\Subscription\GiftSubscriptionResource;
use App\Http\Requests\Admin\Subscription\TransferSubscriptionRequest;
use App\Http\Resources\Admin\Subscription\BalanceSubscriptionResource;

class SubscriptionController extends Controller
{
    use DashboardResponses;

    public function __construct(
        private SubscriptionService $subscriptionService
    ) {}

    public function index(Request $request): View
    {
        $tab         = $request->get('tab', 'active');
        $onlyTrashed = $tab === 'deleted';

        $subscriptions = $this->subscriptionService->getSubscriptionsWithFilters($request, 15, $onlyTrashed);
        $indexData     = $this->subscriptionService->getIndexData();

        return view('Admin.subscriptions.index', array_merge(
            compact('subscriptions', 'tab'),
            $indexData
        ));
    }

    public function searchUsers(Request $request): JsonResponse
    {
        try {
            $search = $request->get('search', '');
            $users  = $this->subscriptionService->searchUsers($search);

            return $this->successWithDataResponse(UserSearchResource::collection($users));
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء البحث');
        }
    }

    public function getPackages(Request $request): JsonResponse
    {
        try {
            $workshopId = (int) $request->get('workshop_id');
            $packages   = $this->subscriptionService->getPackagesByWorkshop($workshopId);

            return $this->successWithDataResponse($packages);
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء جلب الباقات');
        }
    }

    public function store(StoreSubscriptionRequest $request): JsonResponse
    {
        try {
            $this->subscriptionService->createSubscription($request->validated());

            return $this->successResponse('تم إنشاء الاشتراك بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء إنشاء الاشتراك: ' . $e->getMessage());
        }
    }

    public function edit(int $id): JsonResponse
    {
        try {
            $subscription = $this->subscriptionService->getSubscriptionById($id);
            return $this->successWithDataResponse(new SubscriptionEditResource($subscription));
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء جلب بيانات الاشتراك');
        }
    }

    public function update(UpdateSubscriptionRequest $request, int $id): JsonResponse
    {
        try {
            $this->subscriptionService->updateSubscription($id, $request->validated());

            return $this->successResponse('تم تحديث الاشتراك بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء تحديث الاشتراك: ' . $e->getMessage());
        }
    }

    public function getInvoice(int $id): JsonResponse
    {
        try {
            $invoiceData = $this->subscriptionService->getInvoiceData($id);

            return $this->successWithDataResponse(
                new InvoiceResource(
                    $invoiceData['subscription'],
                    $invoiceData['package_title'],
                    $invoiceData['subtotal'],
                    $invoiceData['vat'],
                    $invoiceData['company']
                )
            );
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء جلب بيانات الفاتورة');
        }
    }

    public function downloadInvoicePdf(int $id)
    {
        try {
            $invoiceData = $this->subscriptionService->getInvoiceData($id);

            $html = view('Admin.subscriptions.invoice-pdf', $invoiceData)->render();

            $mpdf = new Mpdf([
                'mode'          => 'utf-8',
                'format'        => 'A4',
                'orientation'   => 'P',
                'margin_left'   => 15,
                'margin_right'  => 15,
                'margin_top'    => 15,
                'margin_bottom' => 15,
            ]);

            $mpdf->WriteHTML($html);

            $filename = 'invoice-' . $invoiceData['subscription']->invoice_id . '.pdf';

            return response()->streamDownload(function () use ($mpdf) {
                $mpdf->Output();
            }, $filename, [
                'Content-Type' => 'application/pdf',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الفاتورة');
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->subscriptionService->deleteSubscription($id);
            return $this->successResponse('تم حذف الاشتراك بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء حذف الاشتراك');
        }
    }

    public function restore($id): JsonResponse
    {
        try {
            $this->subscriptionService->restoreSubscription($id);
            return $this->successResponse('تم استعادة الاشتراك بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء استعادة الاشتراك');
        }
    }

    public function permanentlyDelete($id): JsonResponse
    {
        try {
            $this->subscriptionService->permanentlyDeleteSubscription($id);
            return $this->successResponse('تم حذف الاشتراك نهائياً بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء حذف الاشتراك');
        }
    }

    public function exportExcel(Request $request)
    {
        $tab         = $request->get('tab', 'active');
        $onlyTrashed = $tab === 'deleted';
        return Excel::download(new SubscriptionsExport($request->only(['search', 'workshop_id', 'status']), $onlyTrashed), 'subscriptions.xlsx');
    }

    public function exportPdf(Request $request)
    {
        try {
            set_time_limit(180);
            ini_set('memory_limit', '512M');

            $tab         = $request->get('tab', 'active');
            $onlyTrashed = $tab === 'deleted';

            $subscriptions = $this->subscriptionService->getSubscriptionsForExport($request, $onlyTrashed, 1000);

            $html = view('Admin.subscriptions.exports.pdf', [
                'subscriptions' => $subscriptions,
                'tab'           => $tab,
            ])->render();

            $tempDir = storage_path('app/temp');
            if (! file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $mpdf = new Mpdf([
                'mode'             => 'utf-8',
                'format'           => 'A4-L',
                'orientation'      => 'L',
                'margin_left'      => 10,
                'margin_right'     => 10,
                'margin_top'       => 15,
                'margin_bottom'    => 15,
                'margin_header'    => 10,
                'margin_footer'    => 10,
                'direction'        => 'rtl',
                'autoLangToFont'   => true,
                'autoScriptToLang' => true,
                'autoArabic'       => true,
                'useSubstitutions' => true,
                'tempDir'          => $tempDir,
            ]);

            $mpdf->SetDirectionality('rtl');
            $mpdf->WriteHTML($html);

            $pdfContent = $mpdf->Output('', 'S');

            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="subscriptions.pdf"')
                ->header('Content-Length', strlen($pdfContent))
                ->header('Cache-Control', 'no-cache, must-revalidate')
                ->header('Pragma', 'no-cache');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير التقرير: ' . $e->getMessage());
        }
    }

    public function transferToInternalBalance(int $id): JsonResponse
    {
        try {
            $this->subscriptionService->transferToInternalBalance($id);
            return $this->successResponse('تم تحويل المبلغ إلى الرصيد الداخلي بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage() ?: 'حدث خطأ أثناء تحويل المبلغ إلى الرصيد الداخلي');
        }
    }

    public function processRefund(RefundSubscriptionRequest $request, int $id): JsonResponse
    {
        try {
            $this->subscriptionService->processRefund($id, $request->validated());
            return $this->successResponse('تم معالجة الاسترداد بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء معالجة الاسترداد: ' . $e->getMessage());
        }
    }

    public function transfer(TransferSubscriptionRequest $request, int $id): JsonResponse
    {
        try {
            $this->subscriptionService->transferSubscription($id, $request->validated());
            return $this->successResponse('تم تحويل الاشتراك بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء تحويل الاشتراك: ' . $e->getMessage());
        }
    }

    public function getPendingApprovals(): JsonResponse
    {
        try {
            $subscriptions = $this->subscriptionService->getProcessingSubscriptions();

            $data = $subscriptions->map(function ($subscription) {
                return [
                    'id'             => $subscription->id,
                    'name'           => $subscription->user ? $subscription->user->full_name : ($subscription->full_name ?? '-'),
                    'phone'          => $subscription->user ? $subscription->user->phone : ($subscription->phone ?? '-'),
                    'workshop_title' => $subscription->workshop ? $subscription->workshop->title : '-',
                    'created_at'     => $subscription->created_at ? $subscription->created_at->format('Y-m-d H:i') : '-',
                    'created_at_ar'  => $subscription->created_at ? \App\Helpers\FormatArabicDates::formatArabicDate($subscription->created_at) : '-',
                    'payment_type'   => $subscription->payment_type ? __('enums.payment_types.' . $subscription->payment_type->value, [], 'ar') : '-',
                ];
            });

            return $this->successWithDataResponse($data);
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء جلب البيانات');
        }
    }

    public function approveSubscription(int $id): JsonResponse
    {
        try {
            $this->subscriptionService->approveSubscription($id);
            return $this->successResponse('تم الموافقة على الاشتراك بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء الموافقة على الاشتراك: ' . $e->getMessage());
        }
    }

    public function rejectSubscription(int $id): JsonResponse
    {
        try {
            $this->subscriptionService->rejectSubscription($id);
            return $this->successResponse('تم رفض الاشتراك بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء رفض الاشتراك: ' . $e->getMessage());
        }
    }

    public function exportPendingApprovalsExcel()
    {
        try {
            $subscriptions = $this->subscriptionService->getProcessingSubscriptions();

            $exportClass = new class($subscriptions) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping
            {
                private $subscriptions;

                public function __construct($subscriptions)
                {
                    $this->subscriptions = $subscriptions;
                }

                public function collection()
                {
                    return $this->subscriptions;
                }

                public function headings(): array
                {
                    return [
                        'الاسم',
                        'الهاتف',
                        'الورشة',
                        'تاريخ الإنشاء',
                        'طريقة الدفع',
                    ];
                }

                public function map($subscription): array
                {
                    return [
                        $subscription->user ? $subscription->user->full_name : ($subscription->full_name ?? '-'),
                        $subscription->user ? $subscription->user->phone : ($subscription->phone ?? '-'),
                        $subscription->workshop ? $subscription->workshop->title : '-',
                        $subscription->created_at ? $subscription->created_at->format('Y-m-d H:i') : '-',
                        $subscription->payment_type ? __('enums.payment_types.' . $subscription->payment_type->value, [], 'ar') : '-',
                    ];
                }
            };

            return Excel::download($exportClass, 'pending_approvals_' . date('Y-m-d') . '.xlsx');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير البيانات');
        }
    }

    public function getWorkshopSubscriptionsStats(int $workshopId): JsonResponse
    {
        try {
            $stats = $this->subscriptionService->getWorkshopSubscriptionsStats($workshopId);
            return $this->successWithDataResponse($stats);
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء جلب الإحصائيات: ' . $e->getMessage());
        }
    }

    public function exportWorkshopStatsExcel(int $workshopId)
    {
        try {
            $stats = $this->subscriptionService->getWorkshopSubscriptionsStatsForExport($workshopId);

            $exportClass = new class($stats) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping
            {
                private $stats;

                public function __construct($stats)
                {
                    $this->stats = $stats;
                }

                public function collection()
                {
                    return collect($this->stats['packages']);
                }

                public function headings(): array
                {
                    return [
                        'الباقة',
                        'عدد المشتركين',
                        'الإيرادات',
                    ];
                }

                public function map($package): array
                {
                    return [
                        $package['title'],
                        $package['count'],
                        number_format($package['income'], 2) . ' د.إ',
                    ];
                }
            };

            $fileName = 'workshop_stats_' . str_replace(' ', '_', $stats['workshop_title']) . '_' . date('Y-m-d') . '.xlsx';
            return Excel::download($exportClass, $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير البيانات');
        }
    }

    public function exportWorkshopStatsPdf(int $workshopId)
    {
        try {
            set_time_limit(180);
            ini_set('memory_limit', '512M');

            $stats = $this->subscriptionService->getWorkshopSubscriptionsStatsForExport($workshopId);

            $html = view('Admin.subscriptions.exports.workshop-stats-pdf', [
                'stats' => $stats,
            ])->render();

            $tempDir = storage_path('app/temp');
            if (! file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $mpdf = new Mpdf([
                'mode'             => 'utf-8',
                'format'           => 'A4',
                'orientation'      => 'P',
                'margin_left'      => 10,
                'margin_right'     => 10,
                'margin_top'       => 15,
                'margin_bottom'    => 15,
                'margin_header'    => 10,
                'margin_footer'    => 10,
                'direction'        => 'rtl',
                'autoLangToFont'   => true,
                'autoScriptToLang' => true,
                'autoArabic'       => true,
                'useSubstitutions' => true,
                'tempDir'          => $tempDir,
            ]);

            $mpdf->SetDirectionality('rtl');
            $mpdf->WriteHTML($html);

            $pdfContent = $mpdf->Output('', 'S');

            $fileName = 'workshop_stats_' . str_replace(' ', '_', $stats['workshop_title']) . '_' . date('Y-m-d') . '.pdf';

            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                ->header('Content-Length', strlen($pdfContent))
                ->header('Cache-Control', 'no-cache, must-revalidate')
                ->header('Pragma', 'no-cache');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير التقرير: ' . $e->getMessage());
        }
    }

    public function getTransfers(): JsonResponse
    {
        try {
            $transfers = $this->subscriptionService->getTransfers();
            return $this->successWithDataResponse(TransferResource::collection($transfers));
        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء جلب بيانات التحويلات');
        }
    }

    public function getRefunds(): JsonResponse
    {
        try {
            $refunds     = $this->subscriptionService->getRefundedSubscriptions();
            $totalCount  = $refunds->count();
            $totalAmount = $refunds->sum('paid_amount');

            return $this->successWithDataResponse([
                'refunds'      => RefundResource::collection($refunds),
                'total_count'  => $totalCount,
                'total_amount' => $totalAmount,
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء جلب بيانات المستردات');
        }
    }

    public function reactivateSubscription(int $id): JsonResponse
    {
        try {
            $this->subscriptionService->reactivateSubscription($id);
            return $this->successResponse('تم إعادة تفعيل الاشتراك بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() ?: 'حدث خطأ أثناء إعادة تفعيل الاشتراك');
        }
    }

    public function getUserDetails(int $id): JsonResponse
    {
        try {
            $data = $this->subscriptionService->getUserDetailsWithSubscriptions($id);
            return $this->successWithMessageAndDataResponse($data, 'تم جلب بيانات المستخدم بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() ?: 'حدث خطأ أثناء جلب بيانات المستخدم');
        }
    }

    public function getBalanceSubscriptions(): JsonResponse
    {
        try {
            $subscriptions = $this->subscriptionService->getBalanceSubscriptions();
            return $this->successWithDataResponse(BalanceSubscriptionResource::collection($subscriptions));
        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء جلب بيانات الاشتراكات المدفوعة بالرصيد');
        }
    }

    public function getDebts(Request $request): JsonResponse
    {
        try {
            $workshopId    = $request->get('workshop_id');
            $subscriptions = $this->subscriptionService->getDebtSubscriptions($workshopId);
            $totalDebt     = $subscriptions->sum(function ($subscription) {
                return $subscription->price - $subscription->paid_amount;
            });

            return $this->successWithDataResponse(['debts' => DebtResource::collection($subscriptions), 'total_debt' => $totalDebt]);
        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء جلب بيانات المديونيات');
        }
    }

    public function getUsersBalances(): JsonResponse
    {
        try {
            $users        = $this->subscriptionService->getUsersWithBalances();
            $totalBalance = $users->sum('balance');
            return $this->successWithDataResponse([
                'users'         => UserBalanceResource::collection($users),
                'total_balance' => $totalBalance,
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء جلب بيانات الأرصدة');
        }
    }

    public function deleteBalanceHistory(int $id): JsonResponse
    {
        try {
            $this->subscriptionService->deleteBalanceHistory($id);
            return $this->successResponse('تم حذف السجل بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء حذف السجل');
        }
    }

    public function restoreBalanceHistory(int $id): JsonResponse
    {
        try {
            $this->subscriptionService->restoreBalanceHistory($id);
            return $this->successResponse('تم استعادة السجل بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء استعادة السجل');
        }
    }

    public function permanentlyDeleteBalanceHistory(int $id): JsonResponse
    {
        try {
            $this->subscriptionService->permanentlyDeleteBalanceHistory($id);
            return $this->successResponse('تم حذف السجل نهائياً بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء الحذف النهائي');
        }
    }

    public function getGiftSubscriptions(): JsonResponse
    {
        try {
            $existing = $this->subscriptionService->getGiftSubscriptions(false);
            $deleted  = $this->subscriptionService->getGiftSubscriptions(true);

            return $this->successWithDataResponse([
                'existing' => GiftSubscriptionResource::collection($existing),
                'deleted'  => GiftSubscriptionResource::collection($deleted),
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء جلب بيانات الهدايا');
        }
    }

    public function deleteGiftSubscription(int $id): JsonResponse
    {
        try {
            $subscription = $this->subscriptionService->deleteGiftSubscription($id);
            return $this->successWithDataResponse(['subscription' => new GiftSubscriptionResource($subscription)]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() ?: 'حدث خطأ أثناء حذف الهدية');
        }
    }

    public function restoreGiftSubscription(int $id): JsonResponse
    {
        try {
            $subscription = $this->subscriptionService->restoreGiftSubscription($id);
            return $this->successWithDataResponse(['subscription' => new GiftSubscriptionResource($subscription)]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() ?: 'حدث خطأ أثناء استعادة الهدية');
        }
    }

    public function permanentlyDeleteGiftSubscription(int $id): JsonResponse
    {
        try {
            $this->subscriptionService->permanentlyDeleteGiftSubscription($id);
            return $this->successResponse('تم الحذف النهائي للهدية بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() ?: 'حدث خطأ أثناء الحذف النهائي');
        }
    }

    public function approveGiftSubscription(int $id): JsonResponse
    {
        try {
            $subscription = $this->subscriptionService->approveGiftSubscription($id);
            return $this->successWithDataResponse(['subscription' => new GiftSubscriptionResource($subscription)]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() ?: 'حدث خطأ أثناء الموافقة على الهدية');
        }
    }

    public function transferGiftSubscription(int $id): JsonResponse
    {
        try {
            $subscription = $this->subscriptionService->transferGiftSubscription($id);
            return $this->successWithDataResponse(['subscription' => new GiftSubscriptionResource($subscription)]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() ?: 'حدث خطأ أثناء تحويل الاشتراك');
        }
    }
}
