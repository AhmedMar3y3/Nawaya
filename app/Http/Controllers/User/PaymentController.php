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
        // 1. Log raw payload for debugging
        $payload = request()->all();
        Log::info('Foloosi Webhook Received', $payload);

        // 2. Extract the reference (Foloosi sends it in multiple possible keys)
        $reference = $payload['payment_link_reference']
                  ?? $payload['invoice_id']
                  ?? $payload['reference_id']
                  ?? null;

        if (!$reference) {
            Log::warning('Foloosi Webhook: No reference ID found', $payload);
            return response('No reference', 400);
        }

        // 3. OPTIONAL: Verify signature if you want extra security
        // (Foloosi doesn't sign webhooks by default, but you can ask support to enable it)

        // 4. Get fresh status from Foloosi (most reliable)
        $verification = $this->foloosiPaymentService->verifyPayment($reference);

        Log::info('Foloosi Verification Result', [
            'reference' => $reference,
            'paid'      => $verification['paid'],
            'status'    => $verification['status'],
        ]);

        // 5. Update Order OR Subscription based on what exists
        $updated = false;

        // Check Order first
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

        // Check Subscription
        $subscription = Subscription::where('invoice_id', $reference)->first();
        if ($subscription) {
            $newStatus = $verification['paid'] 
                ? SubscriptionStatus::PAID 
                : SubscriptionStatus::FAILED;

            $subscription->update(['status' => $newStatus]);
            $updated = true;

            // If paid → activate access, send email, etc.
            if ($verification['paid']) {
                // Your logic: give workshop access, send welcome email, etc.
                // event(new SubscriptionPaid($subscription));
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

        // 6. Always return 200 OK quickly
        return response('OK', 200);
    }
}
