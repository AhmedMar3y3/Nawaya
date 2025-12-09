<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\API\SupportMessage\StoreMessageRequest;
use App\Http\Resources\DrHope\ReviewsResource;
use App\Models\DR_HOPE;
use App\Models\Partner;
use App\Models\Product;
use App\Models\SupportMessage;
use App\Models\WorkshopReview;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\DrHope\VideosResource;
use App\Http\Resources\DrHope\GalleryResource;
use App\Http\Resources\DrHope\PartnersResource;
use App\Http\Resources\DrHope\ProductsResource;
use App\Http\Resources\DrHope\InstagramLivesResource;
use App\Http\Resources\DrHope\DetailedPartnerResource;

class DrHopeController extends Controller
{
    use HttpResponses;

    public function products()
    {
        return $this->successWithDataResponse(ProductsResource::collection(Product::paginate(10)));
    }

    public function videos()
    {
        return $this->successWithDataResponse(VideosResource::collection(DR_HOPE::videos()->get()));
    }

    public function gallery()
    {
        return $this->successWithDataResponse(GalleryResource::collection(DR_HOPE::image()->get()));
    }

    public function instagramLives()
    {
        return $this->successWithDataResponse(InstagramLivesResource::collection(DR_HOPE::instagram()->get()));
    }

    public function partners()
    {
        return $this->successWithDataResponse(PartnersResource::collection(Partner::get()));
    }

    public function partnerDetails($id)
    {
        return $this->successWithDataResponse(new DetailedPartnerResource(Partner::findOrFail($id)));
    }

    public function support(StoreMessageRequest $request)
    {
        SupportMessage::create($request->validated() + ['user_id' => $request->user()->id]);
        return $this->successResponse('تم إرسال استشارتك للمتخصصين');
    }

    public function reviews()
    {
        $reviews = WorkshopReview::active()->with(['user', 'workshop'])->latest()->paginate(20);
        return $this->successWithDataResponse(ReviewsResource::collection($reviews));
    }
}
