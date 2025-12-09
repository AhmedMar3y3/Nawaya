<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Filters\ReviewFilter;
use App\Models\WorkshopReview;
use Illuminate\Http\JsonResponse;
use App\Traits\DashboardResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ReviewResource;

class ReviewController extends Controller
{
    use DashboardResponses;

    public function index(Request $request): View
    {
        $query   = WorkshopReview::with(['user', 'workshop']);
        $filter  = new ReviewFilter($request);
        $query   = $filter->apply($query);
        $reviews = $query->latest()->paginate(15);

        return view('Admin.reviews.index', compact('reviews'));
    }

    public function show($id): JsonResponse
    {
        try {
            $review = WorkshopReview::with(['user', 'workshop'])->findOrFail($id);

            return $this->successWithDataResponse(new ReviewResource($review));
        } catch (\Exception) {
            return $this->failureResponse('لم يتم العثور على التقييم');
        }
    }

    public function toggleStatus($id): JsonResponse
    {
        try {
            $review            = WorkshopReview::findOrFail($id);
            $review->is_active = ! $review->is_active;
            $review->save();

            return $this->successResponse('تم تحديث حالة التقييم بنجاح');
        } catch (\Exception) {
            return $this->failureResponse('حدث خطأ أثناء تحديث حالة التقييم');
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $review = WorkshopReview::findOrFail($id);
            $review->delete();

            return $this->successResponse('تم حذف التقييم بنجاح');
        } catch (\Exception) {
            return $this->failureResponse('حدث خطأ أثناء حذف التقييم');
        }
    }
}
