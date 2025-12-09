<?php

namespace App\Services\Admin;

use App\Models\Workshop;
use App\Models\Certificate;
use Illuminate\Support\Collection;
use App\Enums\Subscription\SubscriptionStatus;

class CertificateService
{
    public function getIndexData(?int $workshopId = null): array
    {
        $workshops        = Workshop::orderBy('title')->get(['id', 'title']);
        $selectedWorkshop = null;
        $certificates     = collect([]);
        $totalAmount      = 0;

        if ($workshopId) {
            $selectedWorkshop = Workshop::find($workshopId);
            if ($selectedWorkshop) {
                $certificates = $this->getCertificatesByWorkshop($workshopId);
                $totalAmount  = $this->calculateTotalAmount($certificates);
            }
        }

        return compact('workshops', 'selectedWorkshop', 'certificates', 'totalAmount');
    }

    public function getCertificatesByWorkshop(int $workshopId): Collection
    {
        return Certificate::with(['user', 'subscription', 'workshop'])
            ->where('workshop_id', $workshopId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function calculateTotalAmount(Collection $certificates): float
    {
        return $certificates->sum(function ($certificate) {
            return $certificate->subscription ? ($certificate->subscription->paid_amount ?? 0) : 0;
        });
    }

    public function generateCertificates(int $workshopId): void
    {
        $workshop = Workshop::findOrFail($workshopId);

        if ($workshop->is_certificates_generated) {
            throw new \Exception('تم إصدار الشهادات لهذه الورشة مسبقاً');
        }

        $paidSubscriptions = $workshop->subscriptions()
            ->where('status', SubscriptionStatus::PAID->value)
            ->whereNotNull('user_id')
            ->get();

        if ($paidSubscriptions->isEmpty()) {
            throw new \Exception('لا توجد اشتراكات مدفوعة لهذه الورشة');
        }

        $today = now()->toDateString();

        foreach ($paidSubscriptions as $subscription) {
            $existingCertificate = Certificate::where('subscription_id', $subscription->id)->first();

            if (! $existingCertificate) {
                Certificate::create([
                    'user_id'         => $subscription->user_id,
                    'workshop_id'     => $workshop->id,
                    'subscription_id' => $subscription->id,
                    'issued_at'       => $today,
                    'is_generated'    => true,
                    'is_active'       => true,
                ]);
            }
        }

        $workshop->update(['is_certificates_generated' => true]);
    }

    public function cancelCertificates(int $workshopId): void
    {
        $workshop = Workshop::findOrFail($workshopId);

        Certificate::where('workshop_id', $workshopId)->delete();
        $workshop->update(['is_certificates_generated' => false]);
    }

    public function toggleCertificateStatus(int $certificateId): bool
    {
        $certificate = Certificate::findOrFail($certificateId);
        $certificate->update(['is_active' => ! $certificate->is_active]);
        $certificate->refresh();

        return $certificate->is_active;
    }

    public function getCertificateForDownload(int $certificateId): Certificate
    {
        return Certificate::with(['user', 'workshop.country'])->findOrFail($certificateId);
    }
}
