<?php

namespace App\Http\Controllers\User;

use App\Models\Workshop;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\Workshop\WorkshopResource;
use App\Http\Resources\Workshop\WorkshopDetailsResource;
use App\Http\Requests\API\Workshop\WorkshopFilterRequest;

class WorkshopController extends Controller
{
    use HttpResponses;

    public function index(WorkshopFilterRequest $request)
    {
        $query = Workshop::with('country')->where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('teacher', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $workshops = $query->latest()->get();

        // Split workshops into two arrays
        $nonRecordedWorkshops = $workshops->filter(function ($workshop) {
            return in_array($workshop->type, [
                \App\Enums\Workshop\WorkshopType::ONLINE,
                \App\Enums\Workshop\WorkshopType::ONSITE,
                \App\Enums\Workshop\WorkshopType::ONLINE_ONSITE
            ]);
        });

        $recordedWorkshops = $workshops->filter(function ($workshop) {
            return $workshop->type === \App\Enums\Workshop\WorkshopType::RECORDED;
        });

        return $this->successWithDataResponse([
            'live_workshops'     => WorkshopResource::collection($nonRecordedWorkshops),
            'recorded_workshops' => WorkshopResource::collection($recordedWorkshops),
        ]);
    }

    public function show($id)
    {
        $workshop = Workshop::with(['country', 'packages', 'recordings'])
            ->where('is_active', true)
            ->findOrFail($id);

        return $this->successWithDataResponse(new WorkshopDetailsResource($workshop));
    }
}
