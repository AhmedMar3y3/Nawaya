<?php

namespace App\Http\Controllers\User;

use App\Traits\HttpResponses;
use App\Enums\Payment\PaymentType;
use Illuminate\Support\Facades\DB;
use App\Services\User\OrderService;
use App\Http\Controllers\Controller;
use App\Services\User\FoloosiPaymentService;
use App\Http\Resources\Order\OrderSummaryResource;
use App\Http\Requests\API\Order\StoreOrderRequest;

class OrderController extends Controller
{
    use HttpResponses;

    public function __construct(
        private readonly OrderService $orderService,
        private readonly FoloosiPaymentService $foloosiPaymentService
    ) {}

    public function orderSummary()
    {
        try {
            $user    = auth()->user();
            $summary = $this->orderService->getOrderSummary($user);

            return $this->successWithDataResponse(OrderSummaryResource::make($summary));
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $user        = auth()->user();
            $paymentType = PaymentType::from($request->payment_type);

            $result = DB::transaction(function () use ($user, $paymentType) {

                $order = $this->orderService->createOrder($user, $paymentType);

                if ($paymentType === PaymentType::BANK_TRANSFER) {
                    return [
                        'type'     => 'bank',
                        'order_id' => $order->id,
                    ];
                }

                $paymentData = $this->foloosiPaymentService->createPayment($order);

                return [
                    'type'        => 'online',
                    'order_id'    => $order->id,
                    'invoice_url' => $paymentData['invoice_url'],
                    'invoice_id'  => $paymentData['invoice_id'],
                ];
            });

            if ($result['type'] === 'bank') {
                return $this->successWithDataResponse([
                    'message'  => 'نعمل على معالجة طلبك الآن',
                    'order_id' => $result['order_id'],
                ]);
            }

            return $this->successWithDataResponse([
                'invoice_url' => $result['invoice_url'],
                'invoice_id'  => $result['invoice_id'],
                'order_id'    => $result['order_id'],
            ]);

        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }
}
