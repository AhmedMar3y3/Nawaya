<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Admin\OrderService;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function index(Request $request): View
    {
        $tab     = $request->get('tab', 'pending');
        $section = $request->get('section', 'orders');

        if ($tab === 'pending') {
            $orders = $this->orderService->getPendingOrders($request, 15);
        } elseif ($tab === 'completed') {
            $orders = $this->orderService->getCompletedOrders($request, 15);
        } elseif ($tab === 'trashed') {
            $orders = $this->orderService->getTrashedOrders($request, 15);
        } else {
            $orders = $this->orderService->getPendingOrders($request, 15);
        }

        $users = \App\Models\User::get(['id', 'full_name']);

        $products        = null;
        $activeProducts  = null;
        $deletedProducts = null;
        $stats           = null;

        return view('Admin.products.index', compact('orders', 'products', 'activeProducts', 'deletedProducts', 'stats', 'tab', 'section', 'users'));
    }

    public function getOrderItems($id): JsonResponse
    {
        try {
            $order = Order::withTrashed()->with(['orderItems.product'])->findOrFail($id);
            $items = $order->orderItems ? $order->orderItems->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'product_title' => $item->product ? $item->product->title : 'منتج محذوف',
                    'quantity'      => $item->quantity,
                    'price'         => $item->price,
                    'total_price'   => $item->total_price,
                ];
            })->toArray() : [];

            return response()->json([
                'success' => true,
                'order'   => [
                    'id'    => $order->id,
                    'items' => $items,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات الطلب',
            ], 404);
        }
    }

    public function getUserDetails($id): JsonResponse
    {
        try {
            $order = Order::withTrashed()->with('user')->findOrFail($id);
            $user  = $order->user;

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير موجود',
                ], 404);
            }

            $completedOrders = Order::where('user_id', $user->id)
                ->where('status', \App\Enums\Order\OrderStatus::COMPLETED->value)
                ->with('orderItems')
                ->latest()
                ->get()
                ->map(function ($order) {
                    return [
                        'id'           => $order->id,
                        'total_price'  => $order->total_price,
                        'payment_type' => $order->payment_type->value,
                        'created_at'   => $order->created_at->format('Y-m-d H:i:s'),
                        'items_count'  => $order->orderItems->count(),
                    ];
                });

            return response()->json([
                'success' => true,
                'user'    => [
                    'id'               => $user->id,
                    'full_name'        => $user->full_name,
                    'email'            => $user->email,
                    'phone'            => $user->phone,
                    'created_at'       => $user->created_at->format('Y-m-d H:i:s'),
                    'completed_orders' => $completedOrders,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات المستخدم',
            ], 404);
        }
    }

    public function markOrderCompleted($id): JsonResponse
    {
        try {
            $this->orderService->markAsCompleted($id);
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة الطلب بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة الطلب',
            ], 500);
        }
    }

    public function deleteOrder($id): JsonResponse
    {
        try {
            $this->orderService->deleteOrder($id);
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الطلب بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الطلب',
            ], 500);
        }
    }

    public function restoreOrder($id): JsonResponse
    {
        try {
            $this->orderService->restoreOrder($id);
            return response()->json([
                'success' => true,
                'message' => 'تم استعادة الطلب بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استعادة الطلب',
            ], 500);
        }
    }

    public function permanentlyDeleteOrder($id): JsonResponse
    {
        try {
            $this->orderService->permanentlyDeleteOrder($id);
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الطلب نهائياً بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الطلب',
            ], 500);
        }
    }
}
