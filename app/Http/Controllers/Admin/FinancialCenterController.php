<?php

namespace App\Http\Controllers\Admin;

use Mpdf\Mpdf;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Workshop;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\WorkshopPayment;
use App\Enums\Order\OrderStatus;
use App\Enums\Boutique\OwnerType;
use Illuminate\Http\JsonResponse;
use App\Traits\DashboardResponses;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Filters\FinancialCenterFilter;
use App\Exports\FinancialReportsExport;
use App\Enums\Subscription\SubscriptionStatus;
use App\Http\Resources\Admin\WorkshopPaymentResource;
use App\Http\Requests\Admin\FinancialCenter\AddPaymentRequest;
use App\Http\Requests\Admin\FinancialCenter\UpdateTeacherPercentageRequest;

class FinancialCenterController extends Controller
{
    use DashboardResponses;
    public function index(Request $request): View
    {
        $tab     = $request->get('tab', 'workshops');
        $perPage = 15;

        $workshopsQuery = Workshop::with(['payments', 'subscriptions']);

        $filter         = new FinancialCenterFilter($request);
        $workshopsQuery = $filter->apply($workshopsQuery);

        /** @var \Illuminate\Pagination\LengthAwarePaginator $workshopsPaginated */
        $workshopsPaginated = $workshopsQuery->paginate($perPage);

        $transformedItems = $workshopsPaginated->getCollection()->map(function ($workshop) {
            $totalRevenue = $workshop->subscriptions()
                ->where('status', SubscriptionStatus::PAID->value)
                ->sum('price');

            $profit        = $totalRevenue * 0.95;
            $taxExpenses   = Expense::where('is_including_tax', true)->where('workshop_id', $workshop->id)->sum('amount');
            $refundableTax = $taxExpenses * 0.05;
            $expenses      = Expense::where('workshop_id', $workshop->id)->sum('amount');
            $netProfit     = $profit + $refundableTax - $expenses;
            $teacherPer    = $workshop->teacher_per ?? 0;
            $teacherShare  = ($netProfit * $teacherPer) / 100;
            $companyShare  = $netProfit - $teacherShare;
            $totalPaid     = $workshop->payments()->sum('amount');
            $remaining     = $teacherShare - $totalPaid;

            return [
                'id'            => $workshop->id,
                'title'         => $workshop->title,
                'total_revenue' => $totalRevenue,
                'net_profit'    => $netProfit,
                'teacher_per'   => $teacherPer,
                'teacher_share' => $teacherShare,
                'company_share' => $companyShare,
                'total_paid'    => $totalPaid,
                'remaining'     => $remaining,
            ];
        });

        $workshopsPaginated->setCollection($transformedItems);
        $allWorkshops         = Workshop::select('id', 'title')->get();
        $boutiqueSummary      = $this->calculateBoutiqueSummary();
        $expensesTaxesSummary = $this->calculateExpensesTaxesSummary();

        $workshopNetProfits = Workshop::with(['subscriptions', 'payments'])->get()->sum(function ($workshop) {
            $totalRevenue = $workshop->subscriptions()
                ->where('status', SubscriptionStatus::PAID->value)
                ->sum('price');
            return $totalRevenue * 0.95;
        });

        return view('Admin.financial-center.index', [
            'workshops'            => $workshopsPaginated,
            'allWorkshops'         => $allWorkshops,
            'boutiqueSummary'      => $boutiqueSummary,
            'expensesTaxesSummary' => $expensesTaxesSummary,
            'workshopNetProfits'   => $workshopNetProfits,
            'tab'                  => $tab,
        ]);
    }

    private function calculateExpensesTaxesSummary(): array
    {
        $expenses          = Expense::sum('amount');
        $completedOrders   = Order::with(['orderItems.product'])->where('status', OrderStatus::COMPLETED->value)->sum('total_price');
        $paidSubscriptions = Subscription::where('status', SubscriptionStatus::PAID->value)->sum('price');

        $totalRevenue = $completedOrders + $paidSubscriptions;
        $vat          = $totalRevenue * 0.05;

        $expensesWithTax = Expense::where('is_including_tax', true)->sum('amount');
        $refundableTax   = $expensesWithTax * 0.05;

        $workshopNetProfits = Workshop::with(['subscriptions', 'payments'])->get()->sum(function ($workshop) {
            $totalRevenue = $workshop->subscriptions()
                ->where('status', SubscriptionStatus::PAID->value)
                ->sum('price');
            return $totalRevenue * 0.95;
        });

        $boutiqueSummary = $this->calculateBoutiqueSummary();
        $totalNetProfit  = $workshopNetProfits + $boutiqueSummary['platform_profit'];
        $annualTax       = $totalNetProfit * 0.09;

        return [
            'expenses'       => $expenses,
            'refundable_tax' => $refundableTax,
            'vat'            => $vat,
            'annual_tax'     => $annualTax,
        ];
    }

    private function calculateBoutiqueSummary(): array
    {
        $completedOrders = Order::with(['orderItems.product'])
            ->where('status', OrderStatus::COMPLETED->value)
            ->get();

        $completedOrdersCount = $completedOrders->count();
        $totalRevenue         = $completedOrders->sum('total_price');
        $vat                  = $totalRevenue * 0.05;
        $netRevenue           = $totalRevenue - $vat;
        $totalOwnerShares     = 0;
        foreach ($completedOrders as $order) {
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                if ($product && $product->owner_type === OwnerType::USER && $product->owner_per > 0) {
                    $itemNetPrice = $item->total_price * 0.95;
                    $ownerShare   = ($itemNetPrice * $product->owner_per) / 100;
                    $totalOwnerShares += $ownerShare;
                }
            }
        }

        $platformProfit = $netRevenue - $totalOwnerShares;

        return [
            'completed_orders_count' => $completedOrdersCount,
            'total_revenue'          => $totalRevenue,
            'vat'                    => $vat,
            'net_revenue'            => $netRevenue,
            'total_owner_shares'     => $totalOwnerShares,
            'platform_profit'        => $platformProfit,
        ];
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new FinancialReportsExport($request->only(['workshop_filter'])), 'financial-reports.xlsx');
    }

    public function getWorkshopPayments($workshopId): JsonResponse
    {
        try {
            $workshop  = Workshop::with('payments')->findOrFail($workshopId);
            $payments  = $workshop->payments()->orderBy('date', 'desc')->get();
            $totalPaid = $payments->sum('amount');

            return response()->json([
                'success'    => true,
                'workshop'   => [
                    'id'          => $workshop->id,
                    'title'       => $workshop->title,
                    'teacher_per' => $workshop->teacher_per ?? 0,
                ],
                'payments'   => WorkshopPaymentResource::collection($payments),
                'total_paid' => $totalPaid,
            ]);
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء جلب بيانات الدفعات');
        }
    }

    public function updateTeacherPercentage(UpdateTeacherPercentageRequest $request, $workshopId): JsonResponse
    {
        try {
            $workshop              = Workshop::findOrFail($workshopId);
            $workshop->teacher_per = $request->teacher_per;
            $workshop->save();

            return $this->successResponse('تم تحديث نسبة المدربة بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء تحديث نسبة المدربة');
        }
    }

    public function addPayment(AddPaymentRequest $request, $workshopId): JsonResponse
    {
        try {
            $workshop = Workshop::findOrFail($workshopId);

            $payment = WorkshopPayment::create([
                'workshop_id' => $workshop->id,
                'amount'      => $request->amount,
                'date'        => $request->date,
                'notes'       => $request->notes,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الدفعة بنجاح',
                'payment' => new WorkshopPaymentResource($payment),
            ]);
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء إضافة الدفعة');
        }
    }

    public function deletePayment($paymentId): JsonResponse
    {
        try {
            $payment = WorkshopPayment::findOrFail($paymentId);
            $payment->delete();

            return $this->successResponse('تم حذف الدفعة بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء حذف الدفعة');
        }
    }

    public function getAnnualTaxDetails(): JsonResponse
    {
        try {
            $workshopNetProfits = Workshop::with(['subscriptions', 'payments'])->get()->sum(function ($workshop) {
                $totalRevenue = $workshop->subscriptions()
                    ->where('status', SubscriptionStatus::PAID->value)
                    ->sum('price');
                return $totalRevenue * 0.95;
            });

            $boutiqueSummary = $this->calculateBoutiqueSummary();
            $totalNetProfit  = $workshopNetProfits + $boutiqueSummary['platform_profit'];
            $annualTax       = $totalNetProfit * 0.09;

            return response()->json([
                'success' => true,
                'data'    => [
                    'workshop_net_profits' => $workshopNetProfits,
                    'boutique_net_profits' => $boutiqueSummary['platform_profit'],
                    'total_net_profit'     => $totalNetProfit,
                    'annual_tax'           => $annualTax,
                ],
            ]);
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء جلب بيانات الضريبة السنوية');
        }
    }

    public function exportAnnualTaxPdf()
    {
        try {
            $workshopNetProfits = Workshop::with(['subscriptions', 'payments'])->get()->sum(function ($workshop) {
                $totalRevenue = $workshop->subscriptions()
                    ->where('status', SubscriptionStatus::PAID->value)
                    ->sum('price');
                return $totalRevenue * 0.95;
            });

            $boutiqueSummary = $this->calculateBoutiqueSummary();
            $totalNetProfit  = $workshopNetProfits + $boutiqueSummary['platform_profit'];
            $annualTax       = $totalNetProfit * 0.09;

            $html = view('Admin.financial-center.annual-tax-pdf', [
                'workshop_net_profits' => $workshopNetProfits,
                'boutique_net_profits' => $boutiqueSummary['platform_profit'],
                'total_net_profit'     => $totalNetProfit,
                'annual_tax'           => $annualTax,
            ])->render();

            $tempDir = storage_path('app/temp');
            if (! file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $mpdf = new Mpdf([
                'mode'             => 'utf-8',
                'format'           => 'A4',
                'orientation'      => 'P',
                'margin_left'      => 15,
                'margin_right'     => 15,
                'margin_top'       => 20,
                'margin_bottom'    => 20,
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
                ->header('Content-Disposition', 'attachment; filename="annual-tax-report.pdf"')
                ->header('Content-Length', strlen($pdfContent))
                ->header('Cache-Control', 'no-cache, must-revalidate')
                ->header('Pragma', 'no-cache');
        } catch (\Exception $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير التقرير: ' . $e->getMessage());
        }
    }

    public function exportAnnualTaxExcel()
    {
        try {
            $workshopNetProfits = Workshop::with(['subscriptions', 'payments'])->get()->sum(function ($workshop) {
                $totalRevenue = $workshop->subscriptions()
                    ->where('status', SubscriptionStatus::PAID->value)
                    ->sum('price');
                return $totalRevenue * 0.95;
            });

            $boutiqueSummary = $this->calculateBoutiqueSummary();
            $totalNetProfit  = $workshopNetProfits + $boutiqueSummary['platform_profit'];
            $annualTax       = $totalNetProfit * 0.09;

            return Excel::download(new \App\Exports\AnnualTaxExport([
                'workshop_net_profits' => $workshopNetProfits,
                'boutique_net_profits' => $boutiqueSummary['platform_profit'],
                'total_net_profit'     => $totalNetProfit,
                'annual_tax'           => $annualTax,
            ]), 'annual-tax-report.xlsx');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير التقرير');
        }
    }

    public function getVatReport(Request $request): JsonResponse
    {
        try {
            $workshopId = $request->get('workshop_id');
            $dateFrom   = $request->get('date_from');
            $dateTo     = $request->get('date_to');

            $ordersQuery = Order::where('status', OrderStatus::COMPLETED->value);
            if ($dateFrom) {
                $ordersQuery->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $ordersQuery->whereDate('created_at', '<=', $dateTo);
            }
            $completedOrders = $ordersQuery->get();
            $ordersTotal     = $completedOrders->sum('total_price');
            $ordersVat       = $ordersTotal * 0.05;

            $subscriptionsQuery = Subscription::where('status', SubscriptionStatus::PAID->value);
            if ($workshopId) {
                $subscriptionsQuery->where('workshop_id', $workshopId);
            }
            if ($dateFrom) {
                $subscriptionsQuery->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $subscriptionsQuery->whereDate('created_at', '<=', $dateTo);
            }
            $paidSubscriptions  = $subscriptionsQuery->get();
            $subscriptionsTotal = $paidSubscriptions->sum('price');
            $subscriptionsVat   = $subscriptionsTotal * 0.05;

            $totalVat = $ordersVat + $subscriptionsVat;

            return $this->successWithDataResponse([
                'total_vat'         => $totalVat,
                'orders_vat'        => $ordersVat,
                'subscriptions_vat' => $subscriptionsVat,
            ]);
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء جلب البيانات: ' . $e->getMessage());
        }
    }

    public function exportVatPdf(Request $request)
    {
        try {
            $workshopId = $request->get('workshop_id');
            $dateFrom   = $request->get('date_from');
            $dateTo     = $request->get('date_to');

            $ordersQuery = Order::where('status', OrderStatus::COMPLETED->value);
            if ($dateFrom) {
                $ordersQuery->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $ordersQuery->whereDate('created_at', '<=', $dateTo);
            }
            $completedOrders = $ordersQuery->get();
            $ordersVat       = $completedOrders->sum('total_price') * 0.05;

            $subscriptionsQuery = Subscription::where('status', SubscriptionStatus::PAID->value);
            if ($workshopId) {
                $subscriptionsQuery->where('workshop_id', $workshopId);
            }
            if ($dateFrom) {
                $subscriptionsQuery->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $subscriptionsQuery->whereDate('created_at', '<=', $dateTo);
            }
            $paidSubscriptions = $subscriptionsQuery->get();
            $subscriptionsVat  = $paidSubscriptions->sum('price') * 0.05;

            $totalVat     = $ordersVat + $subscriptionsVat;
            $workshopName = $workshopId ? Workshop::find($workshopId)?->title : 'الإجمالي';

            $html = view('Admin.financial-center.vat-report-pdf', [
                'total_vat'         => $totalVat,
                'orders_vat'        => $ordersVat,
                'subscriptions_vat' => $subscriptionsVat,
                'workshop_name'     => $workshopName,
                'date_from'         => $dateFrom,
                'date_to'           => $dateTo,
            ])->render();

            $tempDir = storage_path('app/temp');
            if (! file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $mpdf = new Mpdf([
                'mode'             => 'utf-8',
                'format'           => 'A4',
                'orientation'      => 'P',
                'margin_left'      => 15,
                'margin_right'     => 15,
                'margin_top'       => 20,
                'margin_bottom'    => 20,
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
                ->header('Content-Disposition', 'attachment; filename="vat-report.pdf"')
                ->header('Content-Length', strlen($pdfContent))
                ->header('Cache-Control', 'no-cache, must-revalidate')
                ->header('Pragma', 'no-cache');
        } catch (\Exception $e) {
            \Log::error('VAT PDF Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير التقرير: ' . $e->getMessage());
        }
    }

    public function exportVatExcel(Request $request)
    {
        try {
            $workshopId = $request->get('workshop_id');
            $dateFrom   = $request->get('date_from');
            $dateTo     = $request->get('date_to');

            $ordersQuery = Order::where('status', OrderStatus::COMPLETED->value);
            if ($dateFrom) {
                $ordersQuery->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $ordersQuery->whereDate('created_at', '<=', $dateTo);
            }
            $completedOrders = $ordersQuery->get();
            $ordersVat       = $completedOrders->sum('total_price') * 0.05;

            $subscriptionsQuery = Subscription::where('status', SubscriptionStatus::PAID->value);
            if ($workshopId) {
                $subscriptionsQuery->where('workshop_id', $workshopId);
            }
            if ($dateFrom) {
                $subscriptionsQuery->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $subscriptionsQuery->whereDate('created_at', '<=', $dateTo);
            }
            $paidSubscriptions = $subscriptionsQuery->get();
            $subscriptionsVat  = $paidSubscriptions->sum('price') * 0.05;

            $totalVat = $ordersVat + $subscriptionsVat;

            return Excel::download(new \App\Exports\VatReportExport([
                'total_vat'         => $totalVat,
                'orders_vat'        => $ordersVat,
                'subscriptions_vat' => $subscriptionsVat,
                'workshop_id'       => $workshopId,
                'date_from'         => $dateFrom,
                'date_to'           => $dateTo,
            ]), 'vat-report.xlsx');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير التقرير');
        }
    }

    public function getRefundableTaxReport(Request $request): JsonResponse
    {
        try {
            $workshopId = $request->get('workshop_id');
            $dateFrom   = $request->get('date_from');
            $dateTo     = $request->get('date_to');

            $expensesQuery = Expense::where('is_including_tax', true);

            if ($workshopId && $workshopId !== 'all') {
                if (is_numeric($workshopId)) {
                    $expensesQuery->where('workshop_id', $workshopId);
                }
            }

            if ($dateFrom) {
                $expensesQuery->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $expensesQuery->whereDate('created_at', '<=', $dateTo);
            }

            $expensesWithTax = $expensesQuery->get();
            $totalAmount     = $expensesWithTax->sum('amount');
            $refundableTax   = $totalAmount * 0.05;

            return $this->successWithDataResponse([
                'refundable_tax' => $refundableTax,
                'total_amount'   => $totalAmount,
                'expenses_count' => $expensesWithTax->count(),
            ]);
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء جلب البيانات: ' . $e->getMessage());
        }
    }

    public function exportRefundableTaxPdf(Request $request)
    {
        try {
            $workshopId = $request->get('workshop_id');
            $dateFrom   = $request->get('date_from');
            $dateTo     = $request->get('date_to');

            $expensesQuery = Expense::where('is_including_tax', true);

            if ($workshopId && $workshopId !== 'all') {
                if (is_numeric($workshopId)) {
                    $expensesQuery->where('workshop_id', $workshopId);
                }
            }

            if ($dateFrom) {
                $expensesQuery->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $expensesQuery->whereDate('created_at', '<=', $dateTo);
            }

            $expensesWithTax = $expensesQuery->with('workshop')->get();
            $totalAmount     = $expensesWithTax->sum('amount');
            $refundableTax   = $totalAmount * 0.05;

            $workshopName = 'الإجمالي (يشمل الورش والمصروفات العامة)';
            if ($workshopId && $workshopId !== 'all' && is_numeric($workshopId)) {
                $workshop     = Workshop::find($workshopId);
                $workshopName = $workshop ? $workshop->title : 'غير محدد';
            }

            $html = view('Admin.financial-center.refundable-tax-pdf', [
                'refundable_tax' => $refundableTax,
                'total_amount'   => $totalAmount,
                'expenses_count' => $expensesWithTax->count(),
                'workshop_name'  => $workshopName,
                'date_from'      => $dateFrom,
                'date_to'        => $dateTo,
            ])->render();

            $tempDir = storage_path('app/temp');
            if (! file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $mpdf = new Mpdf([
                'mode'             => 'utf-8',
                'format'           => 'A4',
                'orientation'      => 'P',
                'margin_left'      => 15,
                'margin_right'     => 15,
                'margin_top'       => 20,
                'margin_bottom'    => 20,
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
                ->header('Content-Disposition', 'attachment; filename="refundable-tax-report.pdf"')
                ->header('Content-Length', strlen($pdfContent))
                ->header('Cache-Control', 'no-cache, must-revalidate')
                ->header('Pragma', 'no-cache');
        } catch (\Exception $e) {
            \Log::error('Refundable Tax PDF Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير التقرير: ' . $e->getMessage());
        }
    }

    public function exportRefundableTaxExcel(Request $request)
    {
        try {
            $workshopId = $request->get('workshop_id');
            $dateFrom   = $request->get('date_from');
            $dateTo     = $request->get('date_to');

            $expensesQuery = Expense::where('is_including_tax', true);

            if ($workshopId && $workshopId !== 'all') {
                if (is_numeric($workshopId)) {
                    $expensesQuery->where('workshop_id', $workshopId);
                }
            }

            if ($dateFrom) {
                $expensesQuery->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $expensesQuery->whereDate('created_at', '<=', $dateTo);
            }

            $expensesWithTax = $expensesQuery->get();
            $totalAmount     = $expensesWithTax->sum('amount');
            $refundableTax   = $totalAmount * 0.05;

            return Excel::download(new \App\Exports\RefundableTaxExport([
                'refundable_tax' => $refundableTax,
                'total_amount'   => $totalAmount,
                'expenses_count' => $expensesWithTax->count(),
                'workshop_id'    => $workshopId,
                'date_from'      => $dateFrom,
                'date_to'        => $dateTo,
            ]), 'refundable-tax-report.xlsx');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير التقرير');
        }
    }
}
