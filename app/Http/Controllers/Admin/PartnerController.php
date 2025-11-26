<?php

namespace App\Http\Controllers\Admin;

use App\Models\Partner;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Partner\StorePartnerRequest;
use App\Http\Requests\Admin\Partner\UpdatePartnerRequest;

class PartnerController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $partners = Partner::latest()->get();
            return response()->json([
                'success'  => true,
                'partners' => $partners->map(function ($partner) {
                    return [
                        'id'          => $partner->id,
                        'title'       => $partner->title,
                        'image'       => $partner->image,
                        'link'        => $partner->link,
                        'description' => $partner->description,
                        'created_at'  => $partner->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات',
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $partner = Partner::findOrFail($id);
            return response()->json([
                'success' => true,
                'partner' => [
                    'id'          => $partner->id,
                    'title'       => $partner->title,
                    'image'       => $partner->image,
                    'link'        => $partner->link,
                    'description' => $partner->description,
                    'created_at'  => $partner->created_at->format('Y-m-d H:i:s'),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات الشريك',
            ], 404);
        }
    }

    public function store(StorePartnerRequest $request): JsonResponse
    {
        try {
            $data    = $request->validated();
            $partner = Partner::create($data);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الشريك بنجاح',
                'partner' => [
                    'id'          => $partner->id,
                    'title'       => $partner->title,
                    'image'       => $partner->image,
                    'link'        => $partner->link,
                    'description' => $partner->description,
                    'created_at'  => $partner->created_at->format('Y-m-d H:i:s'),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في إنشاء الشريك',
            ], 500);
        }
    }

    public function update(UpdatePartnerRequest $request, $id): JsonResponse
    {
        try {
            $partner = Partner::findOrFail($id);
            $data    = $request->validated();

            if (! $request->hasFile('image')) {
                unset($data['image']);
            }

            $partner->update($data);
            $partner->refresh();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الشريك بنجاح',
                'partner' => [
                    'id'          => $partner->id,
                    'title'       => $partner->title,
                    'image'       => $partner->image,
                    'link'        => $partner->link,
                    'description' => $partner->description,
                    'created_at'  => $partner->created_at->format('Y-m-d H:i:s'),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في تحديث الشريك',
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $partner = Partner::findOrFail($id);
            $partner->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الشريك بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في حذف الشريك',
            ], 500);
        }
    }
}
