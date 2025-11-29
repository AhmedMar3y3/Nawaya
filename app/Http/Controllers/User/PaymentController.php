<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Models\Subscription;
use App\Traits\HttpResponses;
use App\Enums\Order\OrderStatus;
use App\Services\User\OrderService;
use App\Http\Controllers\Controller;
use App\Services\User\FoloosiPaymentService;
use App\Enums\Subscription\SubscriptionStatus;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    use HttpResponses;

    public function __construct(
        private readonly OrderService $orderService,
        private readonly FoloosiPaymentService $foloosiPaymentService
    ) {}
   public function foloosiWebhook()
    {
        $payload = request()->all();
        Log::info('Foloosi Webhook Received', $payload);

        $reference = $payload['payment_link_reference']
                  ?? $payload['invoice_id']
                  ?? $payload['reference_id']
                  ?? null;

        if (!$reference) {
            Log::warning('Foloosi Webhook: No reference ID found', $payload);
            return response('No reference', 400);
        }

        $verification = $this->foloosiPaymentService->verifyPayment($reference);

        Log::info('Foloosi Verification Result', [
            'reference' => $reference,
            'paid'      => $verification['paid'],
            'status'    => $verification['status'],
        ]);

        $updated = false;

        $order = Order::where('invoice_id', $reference)->first();
        if ($order) {
            $newStatus = $verification['paid'] ? OrderStatus::PAID : OrderStatus::FAILED;
            $order->update(['status' => $newStatus]);
            $updated = true;

            Log::info('Order status updated via Foloosi webhook', [
                'order_id'     => $order->id,
                'new_status'   => $newStatus->value,
                'reference'    => $reference,
            ]);
        }

        $subscription = Subscription::where('invoice_id', $reference)->first();
        if ($subscription) {
            $newStatus = $verification['paid'] 
                ? SubscriptionStatus::PAID 
                : SubscriptionStatus::FAILED;

            $subscription->update(['status' => $newStatus]);
            $updated = true;

            if ($verification['paid']) {

            }

            Log::info('Subscription status updated via Foloosi webhook', [
                'subscription_id' => $subscription->id,
                'new_status'      => $newStatus->value,
                'reference'       => $reference,
            ]);
        }

        if (!$updated) {
            Log::warning('Foloosi Webhook: No Order or Subscription found', [
                'reference' => $reference,
            ]);
        }

        return response('OK', 200);
    }
}
