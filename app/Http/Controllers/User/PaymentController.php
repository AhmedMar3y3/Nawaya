<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Traits\HttpResponses;
use App\Enums\Order\OrderStatus;
use App\Services\User\OrderService;
use App\Http\Controllers\Controller;
use App\Services\User\FoloosiPaymentService;

class PaymentController extends Controller
{
    use HttpResponses;

    public function __construct(
        private readonly OrderService $orderService,
        private readonly FoloosiPaymentService $foloosiPaymentService
    ) {}
    public function foloosiCallback()
    {
        try {
            $invoiceId = request()->input('invoice_id') ?? request()->input('invoiceId');

            if (! $invoiceId) {
                return $this->failureResponse('معرف الفاتورة غير موجود');
            }

            $order = Order::where('invoice_id', $invoiceId)->first();

            if (! $order) {
                return $this->failureResponse('الطلب غير موجود');
            }

            $verification = $this->foloosiPaymentService->verifyPayment($invoiceId);

            if ($verification['paid']) {
                $order->update(['status' => OrderStatus::PAID]);
                return $this->successResponse('تم الدفع بنجاح');
            }

            $order->update(['status' => OrderStatus::FAILED]);
            return $this->failureResponse('فشل الدفع');
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }
}
