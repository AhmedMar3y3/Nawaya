<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use App\Helpers\FormatArabicDates;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Workshop\WorkshopResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id'        => $this->id,
            'full_name' => $this->full_name,
            'email'     => $this->email,
            'phone'     => $this->phone,
        ];

        if ($this->relationLoaded('subscriptions')) {
            $activeSubscriptions = $this->subscriptions->map(function ($subscription) {
                $workshop     = $subscription->workshop;
                $workshopData = $workshop ? new WorkshopResource($workshop) : null;

                $additionalData = [];
                if ($workshop) {
                    // Attachments
                    if ($workshop->relationLoaded('attachments') && $workshop->attachments->isNotEmpty()) {
                        $additionalData['attachments'] = $workshop->attachments->map(function ($attachment) {
                            return [
                                'id'    => $attachment->id,
                                'type'  => $attachment->type->value,
                                'title' => $attachment->title,
                                'file'  => $attachment->file,
                            ];
                        });
                    }

                    // Files
                    if ($workshop->relationLoaded('files') && $workshop->files->isNotEmpty()) {
                        $additionalData['files'] = $workshop->files->map(function ($file) {
                            return [
                                'id'    => $file->id,
                                'title' => $file->title,
                                'file'  => $file->file,
                            ];
                        });
                    }

                    // Recordings
                    if ($workshop->relationLoaded('recordings') && $workshop->recordings->isNotEmpty()) {
                        $subscriptionPermissions = [];
                        if ($subscription->relationLoaded('recordingPermissions')) {
                            $subscriptionPermissions = $subscription->recordingPermissions->keyBy('id');
                        }

                        $today = now()->startOfDay();

                        $additionalData['recordings'] = $workshop->recordings->map(function ($recording) use ($subscriptionPermissions, $today) {
                            $subscriptionPermission = $subscriptionPermissions->get($recording->id);

                            $availableFrom = null;
                            $availableTo   = null;

                            if ($subscriptionPermission && $subscriptionPermission->pivot) {
                                $availableFrom = $subscriptionPermission->pivot->available_from
                                    ? (\Illuminate\Support\Carbon::parse($subscriptionPermission->pivot->available_from)->startOfDay())
                                    : null;
                                $availableTo = $subscriptionPermission->pivot->available_to
                                    ? (\Illuminate\Support\Carbon::parse($subscriptionPermission->pivot->available_to)->endOfDay())
                                    : null;
                            } else {
                                $availableFrom = $recording->available_from
                                    ? $recording->available_from->startOfDay()
                                    : null;
                                $availableTo = $recording->available_to
                                    ? $recording->available_to->endOfDay()
                                    : null;
                            }

                            $isAvailable = false;

                            if ($availableTo !== null) {
                                if ($availableTo->gte($today)) {
                                    if ($availableFrom === null || $availableFrom->lte($today)) {
                                        $isAvailable = true;
                                    }
                                }
                            }

                            $result = [
                                'id'           => $recording->id,
                                'title'        => $recording->title,
                                'is_available' => $isAvailable,
                            ];

                            if ($isAvailable) {
                                $dateFrom = null;
                                $dateTo   = null;

                                if ($subscriptionPermission && $subscriptionPermission->pivot) {
                                    $dateFrom = $subscriptionPermission->pivot->available_from
                                        ? \Illuminate\Support\Carbon::parse($subscriptionPermission->pivot->available_from)
                                        : null;
                                    $dateTo = $subscriptionPermission->pivot->available_to
                                        ? \Illuminate\Support\Carbon::parse($subscriptionPermission->pivot->available_to)
                                        : null;
                                } else {
                                    $dateFrom = $recording->available_from;
                                    $dateTo = $recording->available_to;
                                }

                                // Build combined Arabic date string
                                $availabilityText = '';
                                if ($dateFrom && $dateTo) {
                                    $formattedFrom = FormatArabicDates::formatArabicDate($dateFrom);
                                    $formattedTo = FormatArabicDates::formatArabicDate($dateTo);
                                    $availabilityText = "متاح من {$formattedFrom} إلى {$formattedTo}";
                                } elseif ($dateTo) {
                                    $formattedTo = FormatArabicDates::formatArabicDate($dateTo);
                                    $availabilityText = "متاح حتى {$formattedTo}";
                                } elseif ($dateFrom) {
                                    $formattedFrom = FormatArabicDates::formatArabicDate($dateFrom);
                                    $availabilityText = "متاح من {$formattedFrom}";
                                }

                                $result['link'] = $recording->link;
                                $result['availability'] = $availabilityText;
                            }

                            return $result;
                        })->values();
                    }

                    // Online link
                    if ($workshop->online_link) {
                        $additionalData['online_link'] = $workshop->online_link;
                    }

                    // Can install certificate
                    if ($workshop->is_certificates_generated) {
                        $certificate                               = $subscription->certificate;
                        $additionalData['can_install_certificate'] = $certificate && $certificate->is_active;
                    } else {
                        $additionalData['can_install_certificate'] = false;
                    }
                }

                return array_merge([
                    'id'       => $subscription->id,
                    'workshop' => $workshopData,
                ], $additionalData);
            });
            $data['active_subscriptions'] = $activeSubscriptions;
        }

        return $data;
    }
}
