<?php

namespace App\Http\Controllers\Admin;

use App\Models\Workshop;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Exports\WorkshopsExport;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Admin\WorkshopService;
use App\Http\Requests\Admin\Workshop\StoreWorkshopRequest;
use App\Http\Requests\Admin\Workshop\UpdateWorkshopRequest;
use App\Http\Requests\Admin\Workshop\WorkshopFilterRequest;

class WorkshopController extends Controller
{
    public function __construct(
        private WorkshopService $workshopService
    ) {}

    public function index(WorkshopFilterRequest $request): View
    {
        $tab = $request->get('tab', 'active');

        $onlyTrashed = $tab === 'deleted';
        $workshops   = $this->workshopService->getWorkshopsWithFilters($request, 15, $onlyTrashed);
        $stats       = $this->workshopService->getWorkshopStats();
        $countries   = \App\Models\Country::get(['id', 'name']);

        return view('Admin.workshops.index', compact('workshops', 'stats', 'tab', 'countries'));
    }

    public function show($id): JsonResponse
    {
        try {
            $workshop = $this->workshopService->getWorkshopById($id);

            return response()->json([
                'success'   => true,
                'modalType' => 'show',
                'workshop'  => [
                    'id'                    => $workshop->id,
                    'title'                 => $workshop->title,
                    'teacher'               => $workshop->teacher,
                    'teacher_percentage'    => $workshop->teacher_per,
                    'description'           => $workshop->description,
                    'subject_of_discussion' => $workshop->subject_of_discussion,
                    'type'                  => $workshop->type->value,
                    'is_active'             => $workshop->is_active,
                    'start_date'            => $workshop->start_date ? (is_string($workshop->start_date) ? $workshop->start_date : $workshop->start_date->format('Y-m-d')) : null,
                    'end_date'              => $workshop->end_date ? (is_string($workshop->end_date) ? $workshop->end_date : $workshop->end_date->format('Y-m-d')) : null,
                    'start_time'            => $workshop->start_time ? (is_string($workshop->start_time) ? substr($workshop->start_time, 0, 5) : $workshop->start_time->format('H:i')) : null,
                    'end_time'              => $workshop->end_time ? (is_string($workshop->end_time) ? substr($workshop->end_time, 0, 5) : $workshop->end_time->format('H:i')) : null,
                    'online_link'           => $workshop->online_link,
                    'city'                  => $workshop->city,
                    'country_id'            => $workshop->country_id,
                    'hotel'                 => $workshop->hotel,
                    'hall'                  => $workshop->hall,
                    'subscribers_count'     => $workshop->subscribers_count ?? 0,
                    'country'               => $workshop->country ? ['id' => $workshop->country->id, 'name' => $workshop->country->name] : null,
                    'packages'              => $workshop->packages->map(function ($package) {
                        return [
                            'id'                => $package->id,
                            'title'             => $package->title,
                            'price'             => $package->price,
                            'is_offer'          => $package->is_offer,
                            'offer_price'       => $package->offer_price,
                            'offer_expiry_date' => $package->offer_expiry_date ? (is_string($package->offer_expiry_date) ? $package->offer_expiry_date : $package->offer_expiry_date->format('Y-m-d')) : null,
                            'features'          => $package->features,
                        ];
                    })->toArray(),
                    'attachments'           => $workshop->attachments->map(function ($attachment) {
                        return [
                            'id'    => $attachment->id,
                            'type'  => $attachment->type->value,
                            'title' => $attachment->title,
                            'file'  => $attachment->file,
                            'notes' => $attachment->notes,
                        ];
                    })->toArray(),
                    'files'                 => $workshop->files->map(function ($file) {
                        return [
                            'id'    => $file->id,
                            'title' => $file->title,
                            'file'  => $file->file,
                        ];
                    })->toArray(),
                    'recordings'            => $workshop->recordings->map(function ($recording) {
                        return [
                            'id'            => $recording->id,
                            'title'         => $recording->title,
                            'link'          => $recording->link,
                            'available_from' => $recording->available_from ? (is_string($recording->available_from) ? $recording->available_from : $recording->available_from->format('Y-m-d')) : null,
                            'available_to'   => $recording->available_to ? (is_string($recording->available_to) ? $recording->available_to : $recording->available_to->format('Y-m-d')) : null,
                        ];
                    })->toArray(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات الورشة',
            ], 404);
        }
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'success'   => true,
            'modalType' => 'create',
        ]);
    }

    public function store(StoreWorkshopRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $allFiles = $request->allFiles();
            if (isset($allFiles['attachments']) && is_array($allFiles['attachments'])) {
                foreach ($allFiles['attachments'] as $index => $attachmentFileArray) {
                    if (isset($attachmentFileArray['file']) && $attachmentFileArray['file']->isValid()) {
                        if (! isset($data['attachments'][$index])) {
                            $data['attachments'][$index] = [];
                        }
                        $data['attachments'][$index]['file'] = $attachmentFileArray['file'];
                    }
                }
            }

            if (isset($allFiles['files']) && is_array($allFiles['files'])) {
                foreach ($allFiles['files'] as $index => $fileArray) {
                    if (isset($fileArray['file']) && $fileArray['file']->isValid()) {
                        if (! isset($data['files'][$index])) {
                            $data['files'][$index] = [];
                        }
                        $data['files'][$index]['file'] = $fileArray['file'];
                    }
                }
            }

            $this->workshopService->createWorkshop($data);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الورشة بنجاح',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في التحقق من البيانات',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الورشة: ' . $e->getMessage(),
                'errors'  => ['general' => [$e->getMessage()]],
            ], 500);
        }
    }

    public function edit($id): JsonResponse
    {
        try {
            $workshop = $this->workshopService->getWorkshopById($id);

            return response()->json([
                'success'   => true,
                'modalType' => 'edit',
                'workshop'  => [
                    'id'                    => $workshop->id,
                    'title'                 => $workshop->title,
                    'teacher'               => $workshop->teacher,
                    'teacher_percentage'    => $workshop->teacher_per,
                    'description'           => $workshop->description,
                    'subject_of_discussion' => $workshop->subject_of_discussion,
                    'type'                  => $workshop->type->value,
                    'is_active'             => $workshop->is_active,
                    'start_date'            => $workshop->start_date ? (is_string($workshop->start_date) ? $workshop->start_date : $workshop->start_date->format('Y-m-d')) : null,
                    'end_date'              => $workshop->end_date ? (is_string($workshop->end_date) ? $workshop->end_date : $workshop->end_date->format('Y-m-d')) : null,
                    'start_time'            => $workshop->start_time ? (is_string($workshop->start_time) ? substr($workshop->start_time, 0, 5) : $workshop->start_time->format('H:i')) : null,
                    'end_time'              => $workshop->end_time ? (is_string($workshop->end_time) ? substr($workshop->end_time, 0, 5) : $workshop->end_time->format('H:i')) : null,
                    'online_link'           => $workshop->online_link,
                    'city'                  => $workshop->city,
                    'country_id'            => $workshop->country_id,
                    'hotel'                 => $workshop->hotel,
                    'hall'                  => $workshop->hall,
                    'country'               => $workshop->country ? ['id' => $workshop->country->id, 'name' => $workshop->country->name] : null,
                    'packages'              => $workshop->packages->map(function ($package) {
                        return [
                            'id'                => $package->id,
                            'title'             => $package->title,
                            'price'             => $package->price,
                            'is_offer'          => $package->is_offer,
                            'offer_price'       => $package->offer_price,
                            'offer_expiry_date' => $package->offer_expiry_date ? (is_string($package->offer_expiry_date) ? $package->offer_expiry_date : $package->offer_expiry_date->format('Y-m-d')) : null,
                            'features'          => $package->features,
                        ];
                    })->toArray(),
                    'attachments'           => $workshop->attachments->map(function ($attachment) {
                        return [
                            'id'    => $attachment->id,
                            'type'  => $attachment->type->value,
                            'title' => $attachment->title,
                            'file'  => $attachment->file,
                            'notes' => $attachment->notes,
                        ];
                    })->toArray(),
                    'files'                 => $workshop->files->map(function ($file) {
                        return [
                            'id'    => $file->id,
                            'title' => $file->title,
                            'file'  => $file->file,
                        ];
                    })->toArray(),
                    'recordings'            => $workshop->recordings->map(function ($recording) {
                        return [
                            'id'            => $recording->id,
                            'title'         => $recording->title,
                            'link'          => $recording->link,
                            'available_from' => $recording->available_from ? (is_string($recording->available_from) ? $recording->available_from : $recording->available_from->format('Y-m-d')) : null,
                            'available_to'   => $recording->available_to ? (is_string($recording->available_to) ? $recording->available_to : $recording->available_to->format('Y-m-d')) : null,
                        ];
                    })->toArray(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات الورشة',
            ], 404);
        }
    }

    public function update(UpdateWorkshopRequest $request, $id): JsonResponse
    {
        try {
            $workshop = Workshop::findOrFail($id);
            $data     = $request->validated();
            // dd($request->all);
            $this->workshopService->updateWorkshop($workshop, $data);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الورشة بنجاح',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في التحقق من البيانات',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الورشة: ' . $e->getMessage(),
                'errors'  => ['general' => [$e->getMessage()]],
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $workshop = Workshop::findOrFail($id);
            $this->workshopService->deleteWorkshop($workshop);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الورشة بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الورشة',
            ], 500);
        }
    }

    public function restore($id): JsonResponse
    {
        try {
            $this->workshopService->restoreWorkshop($id);

            return response()->json([
                'success' => true,
                'message' => 'تم استعادة الورشة بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استعادة الورشة',
            ], 500);
        }
    }

    public function permanentlyDelete($id): JsonResponse
    {
        try {
            $this->workshopService->permanentlyDeleteWorkshop($id);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الورشة نهائياً بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الورشة',
            ], 500);
        }
    }

    public function toggleStatus($id): JsonResponse
    {
        try {
            $workshop = Workshop::findOrFail($id);
            $this->workshopService->toggleWorkshopStatus($workshop);

            return response()->json([
                'success'   => true,
                'message'   => 'تم تحديث حالة الورشة بنجاح',
                'is_active' => $workshop->fresh()->is_active,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة الورشة',
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        $tab         = $request->get('tab', 'active');
        $onlyTrashed = $tab === 'deleted';

        return Excel::download(new WorkshopsExport($request->only(['search', 'type', 'status']), $onlyTrashed), 'workshops.xlsx');
    }

    public function getRecordingPermissions($id): JsonResponse
    {
        try {
            $workshop = $this->workshopService->getWorkshopById($id);

            if ($workshop->type->value !== 'recorded') {
                return response()->json([
                    'success' => false,
                    'message' => 'هذه الورشة ليست من نوع مسجلة',
                ], 400);
            }

            return response()->json([
                'success'  => true,
                'workshop' => [
                    'id'         => $workshop->id,
                    'title'      => $workshop->title,
                    'recordings' => $workshop->recordings->map(function ($recording) {
                        return [
                            'id'            => $recording->id,
                            'title'         => $recording->title,
                            'link'          => $recording->link,
                            'available_from' => $recording->available_from ? (is_string($recording->available_from) ? $recording->available_from : $recording->available_from->format('Y-m-d')) : null,
                            'available_to'   => $recording->available_to ? (is_string($recording->available_to) ? $recording->available_to : $recording->available_to->format('Y-m-d')) : null,
                        ];
                    })->toArray(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات الورشة',
            ], 404);
        }
    }

    public function updateRecordingPermissions(Request $request, $id): JsonResponse
    {
        try {
            $workshop = $this->workshopService->getWorkshopById($id);

            if ($workshop->type->value !== 'recorded') {
                return response()->json([
                    'success' => false,
                    'message' => 'هذه الورشة ليست من نوع مسجلة',
                ], 400);
            }

            $permissions = $request->input('permissions', []);

            foreach ($permissions as $permission) {
                $recording = $workshop->recordings()->find($permission['id']);
                
                if ($recording) {
                    $recording->update([
                        'available_from' => $permission['available_from'] ?? null,
                        'available_to'   => $permission['available_to'] ?? null,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ صلاحيات التسجيلات بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ صلاحيات التسجيلات',
            ], 500);
        }
    }
}
