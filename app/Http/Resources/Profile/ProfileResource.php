<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Workshop\WorkshopResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
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
                        $additionalData['recordings'] = $workshop->recordings->map(function ($recording) {
                            return [
                                'id'    => $recording->id,
                                'title' => $recording->title,
                                'link'  => $recording->link,
                            ];
                        });
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
