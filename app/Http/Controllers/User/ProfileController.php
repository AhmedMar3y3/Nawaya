<?php
namespace App\Http\Controllers\User;

use Mpdf\Mpdf;
use App\Models\Setting;
use App\Models\Workshop;
use App\Models\Certificate;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\WorkshopReview;
use App\Enums\Workshop\WorkshopType;
use App\Http\Controllers\Controller;
use App\Enums\Subscription\SubscriptionStatus;
use App\Http\Resources\Profile\ProfileResource;
use App\Http\Resources\Workshop\WorkshopResource;
use App\Http\Requests\API\Review\StoreReviewRequest;

class ProfileController extends Controller
{

    public function getProfileDetails(Request $request)
    {
        $user = $request->user();
        $user->load([
            'subscriptions' => function ($query) {
                $query->where('status', SubscriptionStatus::PAID->value)
                      ->with([
                          'workshop.attachments',
                          'workshop.files',
                          'workshop.recordings',
                          'certificate'
                      ]);
            }
        ]);
        return $this->successWithDataResponse(new ProfileResource($user));
    }

    public function suggestWorkshops(Request $request)
    {
        $user = $request->user();

        $subscribedWorkshopIds = $user->subscriptions()
            ->pluck('workshop_id')
            ->filter()
            ->unique()
            ->toArray();

        $activeSubscriptions = $user->activeSubscriptions()
            ->with('workshop.country')
            ->get();

        $suggestedWorkshops = collect();

        if ($activeSubscriptions->isNotEmpty()) {
            $subscribedTypes = $activeSubscriptions
                ->pluck('workshop.type')
                ->filter()
                ->map(function ($type) {
                    return $type instanceof WorkshopType ? $type->value : $type;
                })
                ->unique()
                ->values()
                ->toArray();

            $suggestedWorkshops = Workshop::with('country')
                ->where('is_active', true)
                ->whereIn('type', $subscribedTypes)
                ->whereNotIn('id', $subscribedWorkshopIds)
                ->inRandomOrder()
                ->limit(2)
                ->get();
        }

        if ($suggestedWorkshops->count() < 2) {
            $needed     = 2 - $suggestedWorkshops->count();
            $excludeIds = array_merge(
                $suggestedWorkshops->pluck('id')->toArray(),
                $subscribedWorkshopIds
            );

            $randomWorkshops = Workshop::with('country')
                ->where('is_active', true)
                ->whereIn('type', [
                    WorkshopType::ONLINE->value,
                    WorkshopType::ONSITE->value,
                    WorkshopType::ONLINE_ONSITE->value,
                ])
                ->whereNotIn('id', $excludeIds)
                ->inRandomOrder()
                ->limit($needed)
                ->get();

            $suggestedWorkshops = $suggestedWorkshops->merge($randomWorkshops);
        }

        return $this->successWithDataResponse(WorkshopResource::collection($suggestedWorkshops->take(2)));
    }

    public function postReview(StoreReviewRequest $request)
    {
        WorkshopReview::create($request->validated() + ['user_id' => auth()->user()->id]);
        return $this->successResponse('تم إضافة المراجعة بنجاح');
    }

    public function downloadInvoice($subscriptionId)
    {
        try {
            $user = auth()->user();
            $subscription = Subscription::with(['workshop', 'package', 'user'])
                ->where('id', $subscriptionId)
                ->where('user_id', $user->id)
                ->where('status', SubscriptionStatus::PAID->value)
                ->firstOrFail();

            $settings = Setting::pluck('value', 'key');
            $packageTitle = $subscription->package ? $subscription->package->title : ($subscription->workshop ? $subscription->workshop->title : '-');
            
            $vatRate = 0.05;
            $subtotal = $subscription->paid_amount / (1 + $vatRate);
            $vat = $subscription->paid_amount - $subtotal;
            
            $company = [
                'name' => 'مؤسسة نوايا للفعاليات',
                'address' => $settings['address'] ?? '',
                'phone' => $settings['phone_number'] ?? '+971 4 123 4567',
                'tax_number' => $settings['tax_number'] ?? '100000000000003',
            ];

            $invoiceData = [
                'subscription' => $subscription,
                'package_title' => $packageTitle,
                'subtotal' => round($subtotal, 2),
                'vat' => round($vat, 2),
                'total' => $subscription->paid_amount,
                'company' => $company,
            ];

            $html = view('Admin.subscriptions.invoice-pdf', $invoiceData)->render();

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 15,
            ]);

            $mpdf->WriteHTML($html);

            $filename = 'invoice-' . $subscription->invoice_id . '.pdf';

            return response()->streamDownload(function () use ($mpdf) {
                $mpdf->Output();
            }, $filename, [
                'Content-Type' => 'application/pdf',
            ]);
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء تحميل الفاتورة');
        }
    }

    public function downloadCertificate($subscriptionId)
    {
        try {
            $user = auth()->user();
            $subscription = Subscription::with(['workshop', 'user'])
                ->where('id', $subscriptionId)
                ->where('user_id', $user->id)
                ->where('status', SubscriptionStatus::PAID->value)
                ->firstOrFail();

            $certificate = Certificate::with(['user', 'workshop.country'])
                ->where('subscription_id', $subscriptionId)
                ->where('user_id', $user->id)
                ->firstOrFail();

            if (!$certificate->is_active) {
                return $this->failureResponse('الشهادة غير مفعلة');
            }

            if (!$subscription->workshop->is_certificates_generated) {
                return $this->failureResponse('لم يتم إصدار الشهادات لهذه الورشة بعد');
            }

            $html = view('Admin.certificates.pdf', [
                'certificate' => $certificate,
            ])->render();

            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'L',
                'margin_left' => 20,
                'margin_right' => 20,
                'margin_top' => 20,
                'margin_bottom' => 20,
                'margin_header' => 0,
                'margin_footer' => 0,
                'direction' => 'rtl',
                'autoLangToFont' => true,
                'autoScriptToLang' => true,
                'autoArabic' => true,
                'useSubstitutions' => true,
                'tempDir' => $tempDir,
            ]);

            $mpdf->SetDirectionality('rtl');
            $mpdf->WriteHTML($html);

            $pdfContent = $mpdf->Output('', 'S');
            $fileName = 'شهادة_' . str_replace(' ', '_', $certificate->user->full_name) . '_' . date('Y-m-d') . '.pdf';

            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->failureResponse('لم يتم العثور على الشهادة');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء تحميل الشهادة');
        }
    }
}
