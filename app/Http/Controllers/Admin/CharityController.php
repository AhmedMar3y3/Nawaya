<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use App\Traits\DashboardResponses;
use App\Http\Controllers\Controller;
use App\Services\Admin\CharityService;
use App\Http\Resources\Admin\Charity\CharityResource;
use App\Http\Requests\Admin\Charity\AssignCharitySeatRequest;
use App\Http\Requests\Admin\Charity\ReturnSeatsRequest;
use Log;

class CharityController extends Controller
{
    use DashboardResponses;

    public function __construct(
        private CharityService $charityService
    ) {}

    public function getCharities(): JsonResponse
    {
        try {
            $existing = $this->charityService->getCharities(false);
            $deleted = $this->charityService->getCharities(true);
            $totalAvailable = $this->charityService->getTotalAvailableAmount();

            return $this->successWithDataResponse([
                'existing' => CharityResource::collection($existing),
                'deleted' => CharityResource::collection($deleted),
                'total_available_amount' => $totalAvailable,
            ]);
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء جلب بيانات الدعم');
        }
    }

    public function deleteCharity(int $id): JsonResponse
    {
        try {
            $charity = $this->charityService->deleteCharity($id);
            return $this->successWithDataResponse(['charity' => new CharityResource($charity)]);
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage() ?: 'حدث خطأ أثناء حذف الدعم');
        }
    }

    public function restoreCharity(int $id): JsonResponse
    {
        try {
            $charity = $this->charityService->restoreCharity($id);
            return $this->successWithDataResponse(['charity' => new CharityResource($charity)]);
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage() ?: 'حدث خطأ أثناء استعادة الدعم');
        }
    }

    public function permanentlyDeleteCharity(int $id): JsonResponse
    {
        try {
            $this->charityService->permanentlyDeleteCharity($id);
            return $this->successResponse('تم الحذف النهائي للدعم بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage() ?: 'حدث خطأ أثناء الحذف النهائي');
        }
    }

    public function assignSeat(AssignCharitySeatRequest $request, int $id): JsonResponse
    {
        try {
            $result = $this->charityService->assignSeat(
                $id,
                $request->user_id,
                $request->charity_notes
            );
            
            return $this->successWithDataResponse([
                'charity' => new CharityResource($result['charity']),
            ]);
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage() ?: 'حدث خطأ أثناء تعيين المقعد');
        }
    }

    public function returnSeats(ReturnSeatsRequest $request, int $id): JsonResponse
    {
        try {
            $charity = $this->charityService->returnSeats(
                $id,
                $request->seats_count,
                $request->action
            );
            
            return $this->successWithDataResponse([
                'charity' => new CharityResource($charity),
            ]);
        } catch (\Exception $e) {
            Log::info('Error returning charity seats', ['error' => $e->getMessage()]);
            return $this->failureResponse($e->getMessage() ?: 'حدث خطأ أثناء استرجاع المقاعد');
        }
    }
}

