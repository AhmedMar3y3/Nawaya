<?php

namespace App\Http\Controllers\Admin;

use App\Models\DR_HOPE;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DR_Hope\StoreContentRequest;
use App\Http\Requests\Admin\DR_Hope\UpdateContentRequest;

class DR_HopeController extends Controller
{
    public function getByType(string $type): JsonResponse
    {
        try {
            $items = DR_HOPE::where('type', $type)->latest()->get();
            return response()->json([
                'success' => true,
                'items' => $items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'image' => $item->image,
                        'link' => $item->link,
                        'type' => $item->type->value,
                        'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات'
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $item = DR_HOPE::findOrFail($id);
            return response()->json([
                'success' => true,
                'item' => [
                    'id' => $item->id,
                    'title' => $item->title,
                    'image' => $item->image,
                    'link' => $item->link,
                    'type' => $item->type->value,
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات العنصر'
            ], 404);
        }
    }

    public function store(StoreContentRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $item = DR_HOPE::create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء المحتوى بنجاح',
                'item' => [
                    'id' => $item->id,
                    'title' => $item->title,
                    'image' => $item->image,
                    'link' => $item->link,
                    'type' => $item->type->value,
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في إنشاء المحتوى'
            ], 500);
        }
    }

    public function update(UpdateContentRequest $request, $id): JsonResponse
    {
        try {
            $item = DR_HOPE::findOrFail($id);
            $data = $request->validated();
            
            if ($item->type->value === 'image' && !$request->hasFile('image')) {
                unset($data['image']);
            }
            
            $item->update($data);
            $item->refresh();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المحتوى بنجاح',
                'item' => [
                    'id' => $item->id,
                    'title' => $item->title,
                    'image' => $item->image,
                    'link' => $item->link,
                    'type' => $item->type->value,
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في تحديث المحتوى'
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $item = DR_HOPE::findOrFail($id);
            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المحتوى بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في حذف المحتوى'
            ], 500);
        }
    }
}
