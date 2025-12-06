<?php

namespace App\Http\Controllers\Admin;

use Mpdf\Mpdf;
use App\Models\Workshop;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\DashboardResponses;
use App\Exports\CertificatesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Services\Admin\CertificateService;

class CertificateController extends Controller
{
    use DashboardResponses;

    public function __construct(
        private CertificateService $certificateService
    ) {}

    public function index(Request $request): View
    {
        $workshopId = $request->get('workshop_id');
        $data       = $this->certificateService->getIndexData($workshopId);
        return view('Admin.certificates.index', $data);
    }

    public function generate(Request $request): JsonResponse
    {
        try {
            $workshopId = $request->input('workshop_id');

            if (! $workshopId) {
                return $this->failureResponse('يرجى اختيار ورشة');
            }

            $this->certificateService->generateCertificates($workshopId);

            return $this->successResponse('تم إصدار الشهادات بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }

    public function cancel(Request $request): JsonResponse
    {
        try {
            $workshopId = $request->input('workshop_id');

            if (! $workshopId) {
                return $this->failureResponse('يرجى اختيار ورشة');
            }

            $this->certificateService->cancelCertificates($workshopId);

            return $this->successResponse('تم إلغاء إصدار الشهادات بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }

    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $isActive = $this->certificateService->toggleCertificateStatus($id);

            return $this->successWithMessageAndDataResponse(['is_active' => $isActive],'تم تحديث حالة الشهادة بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء تحديث حالة الشهادة');
        }
    }

    public function download(int $id)
    {
        try {
            $certificate = $this->certificateService->getCertificateForDownload($id);

            if (! $certificate->is_active) {
                return redirect()->back()->with('error', 'الشهادة غير مفعلة');
            }

            $html = view('Admin.certificates.pdf', [
                'certificate' => $certificate,
            ])->render();

            $tempDir = storage_path('app/temp');
            if (! file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $mpdf = new Mpdf([
                'mode'             => 'utf-8',
                'format'           => 'A4',
                'orientation'      => 'L',
                'margin_left'      => 20,
                'margin_right'     => 20,
                'margin_top'       => 20,
                'margin_bottom'    => 20,
                'margin_header'    => 0,
                'margin_footer'    => 0,
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
            $fileName   = 'شهادة_' . str_replace(' ', '_', $certificate->user->full_name) . '_' . date('Y-m-d') . '.pdf';

            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                ->header('Content-Length', strlen($pdfContent))
                ->header('Cache-Control', 'no-cache, must-revalidate')
                ->header('Pragma', 'no-cache');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحميل الشهادة: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $workshopId = $request->get('workshop_id');

            if (! $workshopId) {
                return redirect()->back()->with('error', 'يرجى اختيار ورشة');
            }

            $workshop = Workshop::findOrFail($workshopId);
            $fileName = 'certificates_' . str_replace(' ', '_', $workshop->title) . '_' . date('Y-m-d') . '.xlsx';

            return Excel::download(new CertificatesExport($workshopId), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير البيانات: ' . $e->getMessage());
        }
    }
}
