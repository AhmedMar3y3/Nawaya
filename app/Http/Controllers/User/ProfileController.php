<?php
namespace App\Http\Controllers\User;

use App\Models\Workshop;
use Illuminate\Http\Request;
use App\Enums\Workshop\WorkshopType;
use App\Http\Controllers\Controller;
use App\Enums\Subscription\SubscriptionStatus;
use App\Http\Resources\Profile\ProfileResource;
use App\Http\Resources\Workshop\WorkshopResource;

class ProfileController extends Controller
{

    public function getProfileDetails(Request $request)
    {
        $user = $request->user();
        $user->load(['subscriptions' => function ($query) {$query->where('status', SubscriptionStatus::PAID->value);}]);
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
}
