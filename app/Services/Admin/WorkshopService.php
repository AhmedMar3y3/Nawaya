<?php
namespace App\Services\Admin;

use App\Enums\Subscription\SubscriptionStatus;
use App\Enums\Workshop\WorkshopType;
use App\Filters\WorkshopFilter;
use App\Models\Workshop;
use App\Models\WorkshopAttachment;
use App\Models\WorkshopFile;
use App\Models\WorkshopPackage;
use App\Models\WorkshopRecording;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class WorkshopService
{
    public function getWorkshopsWithFilters(Request $request, int $perPage = 15, bool $onlyTrashed = false): LengthAwarePaginator
    {
        $query = $onlyTrashed ? Workshop::onlyTrashed() : Workshop::query();

        $filter = new WorkshopFilter($request);
        $query  = $filter->apply($query);

        $query->withCount([
            'subscriptions as subscribers_count' => function ($q) {
                $q->where('status', SubscriptionStatus::ACTIVE->value);
            },
        ]);

        $query->with('country');
        $query->latest();

        return $query->paginate($perPage);
    }

    public function getWorkshopsForExport(Request $request, bool $onlyTrashed = false, int $limit = 1000): array
    {
        $query = $onlyTrashed ? Workshop::onlyTrashed() : Workshop::query();

        $filter = new WorkshopFilter($request);
        $query  = $filter->apply($query);

        $query->withCount([
            'subscriptions as subscribers_count' => function ($q) {
                $q->where('status', SubscriptionStatus::ACTIVE->value);
            },
        ]);

        $query->with('country');
        $query->latest();

        return $query->limit($limit)->get()->map(function ($workshop) {
            return [
                'id'                => $workshop->id,
                'title'             => $workshop->title,
                'teacher'           => $workshop->teacher,
                'start_date'        => $workshop->start_date ? $workshop->start_date->format('Y-m-d') : '-',
                'type'              => $workshop->type->getLocalizedName(),
                'subscribers_count' => $workshop->subscribers_count ?? 0,
                'is_active'         => $workshop->is_active ? 'نشط' : 'غير نشط',
                'created_at'        => $workshop->created_at ? $workshop->created_at->format('Y-m-d H:i:s') : '-',
            ];
        })->toArray();
    }

    public function getWorkshopById(int $id): Workshop
    {
        /** @var Workshop $workshop */
        $workshop = Workshop::with([
            'country',
            'packages',
            'attachments',
            'files',
            'recordings',
        ])
            ->withCount([
                'subscriptions as subscribers_count' => function ($q) {
                    $q->where('status', SubscriptionStatus::ACTIVE->value);
                },
            ])
            ->findOrFail($id);

        return $workshop;
    }

    public function getWorkshopStats(): array
    {
        return [
            'total'   => Workshop::count(),
            'active'  => Workshop::where('is_active', true)->count(),
            'deleted' => Workshop::onlyTrashed()->count(),
        ];
    }

    public function createWorkshop(array $data): Workshop
    {
        return DB::transaction(function () use ($data) {
            // Extract related data
            $packages    = $data['packages'] ?? [];
            $attachments = $data['attachments'] ?? [];
            $files       = $data['files'] ?? [];
            $recordings  = $data['recordings'] ?? [];

            // Remove related data from main data
            unset($data['packages'], $data['attachments'], $data['files'], $data['recordings']);

            // Ensure teacher_per is set from teacher_percentage if present
            if (isset($data['teacher_percentage'])) {
                $data['teacher_per'] = $data['teacher_percentage'];
                unset($data['teacher_percentage']);
            }

            // Create workshop
            $workshop = Workshop::create($data);

            // Create packages
            if (! empty($packages)) {
                foreach ($packages as $packageData) {
                    // Convert checkbox value to boolean
                    if (isset($packageData['is_offer'])) {
                        $packageData['is_offer'] = filter_var($packageData['is_offer'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
                    } else {
                        $packageData['is_offer'] = false;
                    }

                    // If is_offer is false, set offer_price and offer_expiry_date to null
                    if (! $packageData['is_offer']) {
                        $packageData['offer_price']       = null;
                        $packageData['offer_expiry_date'] = null;
                    }

                    $workshop->packages()->create($packageData);
                }
            }

            // Create attachments
            if (! empty($attachments)) {
                foreach ($attachments as $attachmentData) {
                    $workshop->attachments()->create($attachmentData);
                }
            }

            // Create files
            if (! empty($files)) {
                foreach ($files as $fileData) {
                    $workshop->files()->create($fileData);
                }
            }

            // Create recordings (only for recorded type)
            if ($workshop->type === WorkshopType::RECORDED && ! empty($recordings)) {
                foreach ($recordings as $recordingData) {
                    $workshop->recordings()->create($recordingData);
                }
            }

            return $workshop->fresh(['packages', 'attachments', 'files', 'recordings', 'country']);
        });
    }

    public function updateWorkshop(Workshop $workshop, array $data): bool
    {
        return DB::transaction(function () use ($workshop, $data) {

            $packages    = $data['packages'] ?? null;
            $attachments = $data['attachments'] ?? null;
            $files       = $data['files'] ?? null;
            $recordings  = $data['recordings'] ?? null;

            unset($data['packages'], $data['attachments'], $data['files'], $data['recordings']);

            if (isset($data['teacher_percentage'])) {
                $data['teacher_per'] = $data['teacher_percentage'];
                unset($data['teacher_percentage']);
            }

            $workshop->update($data);

            // Packages
            if ($packages !== null) {
                $existingIds = $workshop->packages()->pluck('id')->toArray();
                $keepIds     = [];

                foreach ($packages as $p) {
                    $p['is_offer'] = $p['is_offer'] ?? false;
                    if (! $p['is_offer']) {
                        $p['offer_price'] = $p['offer_expiry_date'] = null;
                    }

                    if (! empty($p['id']) && in_array($p['id'], $existingIds)) {
                        $model = WorkshopPackage::find($p['id']);
                        unset($p['id']);
                        $model->update($p);
                        $keepIds[] = $model->id;
                    } else {
                        unset($p['id']);
                        $new       = $workshop->packages()->create($p);
                        $keepIds[] = $new->id;
                    }
                }
                $workshop->packages()->whereNotIn('id', $keepIds)->forceDelete();
            }

            // Attachments
            if ($attachments !== null) {
                $keepIds = [];

                foreach ($attachments as $item) {
                    $file     = $item['file'] ?? null;
                    $attachId = $item['id'] ?? null;

                    unset($item['file'], $item['id']);

                    if ($attachId && $attachment = WorkshopAttachment::find($attachId)) {
                        if ($file instanceof \Illuminate\Http\UploadedFile) {
                            $attachment->file = $file;
                        }

                        $attachment->fill($item);
                        $attachment->save();

                        $keepIds[] = $attachment->id;
                    } else {
                        if ($file instanceof \Illuminate\Http\UploadedFile) {
                            $item['file'] = $file;
                            $new          = $workshop->attachments()->create($item);
                            $keepIds[]    = $new->id;
                        }
                    }
                }

                $workshop->attachments()->whereNotIn('id', $keepIds)->forceDelete();
            } else {
                $workshop->attachments()->forceDelete();
            }

            // Files
            if ($files !== null) {
                $keepIds = [];

                foreach ($files as $item) {
                    $file   = $item['file'] ?? null;
                    $fileId = $item['id'] ?? null;

                    unset($item['file'], $item['id']);

                    if ($fileId && $model = WorkshopFile::find($fileId)) {
                        if ($file instanceof \Illuminate\Http\UploadedFile) {
                            $model->file = $file;
                        }
                        $model->fill($item);
                        $model->save();
                        $keepIds[] = $model->id;
                    } else {
                        if ($file instanceof \Illuminate\Http\UploadedFile) {
                            $item['file'] = $file;
                            $new          = $workshop->files()->create($item);
                            $keepIds[]    = $new->id;
                        }
                    }
                }

                $workshop->files()->whereNotIn('id', $keepIds)->forceDelete();
            } else {
                $workshop->files()->forceDelete();
            }

            // Recordings
            if ($recordings !== null && $workshop->type === WorkshopType::RECORDED) {
                $existingIds = $workshop->recordings()->pluck('id')->toArray();
                $keepIds     = [];

                foreach ($recordings as $r) {
                    if (! empty($r['id']) && in_array($r['id'], $existingIds)) {
                        $model = WorkshopRecording::find($r['id']);
                        unset($r['id']);
                        $model->update($r);
                        $keepIds[] = $model->id;
                    } else {
                        unset($r['id']);
                        $new       = $workshop->recordings()->create($r);
                        $keepIds[] = $new->id;
                    }
                }
                $workshop->recordings()->whereNotIn('id', $keepIds)->delete();
            } else {
                $workshop->recordings()->forceDelete();
            }

            return true;
        });
    }

    public function deleteWorkshop(Workshop $workshop): bool
    {
        return DB::transaction(function () use ($workshop) {
            $workshop->packages()->delete();
            $workshop->attachments()->delete();
            $workshop->files()->delete();
            $workshop->recordings()->delete();

            return $workshop->delete();
        });
    }

    public function restoreWorkshop(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $workshop = Workshop::onlyTrashed()->findOrFail($id);

            // Restore related data
            $workshop->packages()->onlyTrashed()->restore();
            $workshop->attachments()->onlyTrashed()->restore();
            $workshop->files()->onlyTrashed()->restore();
            $workshop->recordings()->onlyTrashed()->restore();

            return $workshop->restore();
        });
    }

    public function permanentlyDeleteWorkshop(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $workshop = Workshop::onlyTrashed()->findOrFail($id);

            // Permanently delete related data
            $workshop->packages()->onlyTrashed()->forceDelete();
            $workshop->attachments()->onlyTrashed()->forceDelete();
            $workshop->files()->onlyTrashed()->forceDelete();
            $workshop->recordings()->onlyTrashed()->forceDelete();

            return $workshop->forceDelete();
        });
    }

    public function toggleWorkshopStatus(Workshop $workshop): bool
    {
        return $workshop->update(['is_active' => ! $workshop->is_active]);
    }

    public function convertExpiredWorkshops(): int
    {
        $count            = 0;
        $expiredWorkshops = Workshop::whereIn('type', [WorkshopType::ONLINE, WorkshopType::ONLINE_ONSITE])
            ->where('end_date', '<=', now()->toDateString())
            ->where('is_active', true)
            ->get();

        foreach ($expiredWorkshops as $workshop) {
            $workshop->update([
                'type'      => WorkshopType::RECORDED,
                'is_active' => false,
            ]);
            $count++;
        }

        return $count;
    }
}
