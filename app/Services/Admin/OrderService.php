<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Enums\Order\OrderStatus;
use App\Enums\Payment\PaymentType;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class OrderService
{
    public function getPendingOrders(Request $request, int $perPage = 15): LengthAwarePaginator
    {
        $query = Order::with(['user', 'orderItems.product'])
            ->where(function ($q) {
                $q->where(function ($statusQ) {
                    $statusQ->where('status', OrderStatus::PROCESSING->value)
                           ->where('payment_type', PaymentType::BANK_TRANSFER->value);
                })
                ->orWhere(function ($statusQ) {
                    $statusQ->where('status', OrderStatus::PAID->value)
                           ->where('payment_type', PaymentType::ONLINE->value);
                });
            })
            ->latest();

        return $query->paginate($perPage);
    }

    public function getCompletedOrders(Request $request, int $perPage = 15): LengthAwarePaginator
    {
        $query = Order::with(['user', 'orderItems.product'])
            ->where('status', OrderStatus::COMPLETED->value)
            ->latest();

        return $query->paginate($perPage);
    }

    public function getTrashedOrders(Request $request, int $perPage = 15): LengthAwarePaginator
    {
        $query = Order::onlyTrashed()
            ->with(['user', 'orderItems.product'])
            ->latest();

        return $query->paginate($perPage);
    }

    public function getOrderById(int $id): Order
    {
        return Order::with(['user', 'orderItems.product'])
            ->findOrFail($id);
    }

    public function markAsCompleted(int $id): bool
    {
        $order = Order::findOrFail($id);
        return $order->update(['status' => OrderStatus::COMPLETED]);
    }

    public function deleteOrder(int $id): bool
    {
        $order = Order::findOrFail($id);
        return $order->delete();
    }

    public function restoreOrder(int $id): bool
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        return $order->restore();
    }

    public function permanentlyDeleteOrder(int $id): bool
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        return $order->forceDelete();
    }
}

