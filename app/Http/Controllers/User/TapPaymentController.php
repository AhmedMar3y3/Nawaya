<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Models\Subscription;
use App\Traits\HttpResponses;
use App\Enums\Order\OrderStatus;
use App\Http\Controllers\Controller;
use App\Services\User\TapPaymentService;
use App\Enums\Subscription\SubscriptionStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class TapPaymentController extends Controller
{
    use HttpResponses;

    protected $paymentService;

    public function __construct(TapPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function subscriptionCallback(Request $request)
    {
        $subscriptionId = $request->get('subscription_id') ?? $request->route('subscription_id');
        
        DB::beginTransaction();

        try {
            $subscription = Subscription::findOrFail($subscriptionId);

            $invoiceId = $subscription->invoice_id ?? $request->get('tap_id') ?? $request->get('id');
            
            if (!$invoiceId) {
                throw new \Exception('Invoice ID not found');
            }

            $paymentStatus = $this->paymentService->getPaymentStatus($invoiceId);

            Log::info('Tap Payment Status (Subscription):', [
                'subscription_id' => $subscriptionId,
                'invoice_id'      => $invoiceId,
                'status'          => $paymentStatus,
            ]);

            if ($paymentStatus['Data']['InvoiceStatus'] === 'Paid') {
                $subscription->update([
                    'status' => SubscriptionStatus::PAID,
                ]);

                DB::commit();

                $successUrl = config('Tap.front_end_success_url', config('app.front_end_url'));
                return redirect($successUrl . '?status=success&subscription_id=' . $subscription->id);
            } else {
                $subscription->update([
                    'status' => SubscriptionStatus::FAILED,
                ]);

                DB::commit();

                $errorUrl = config('Tap.front_end_error_url', config('app.front_end_url'));
                return redirect($errorUrl . '?status=failed&message=Payment not successful&subscription_id=' . $subscription->id);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Tap Subscription Callback Error', [
                'subscription_id' => $subscriptionId ?? null,
                'error'          => $e->getMessage(),
                'trace'          => $e->getTraceAsString(),
            ]);

            $errorUrl = config('Tap.front_end_error_url', config('app.front_end_url'));
            return redirect($errorUrl . '?status=failed&message=' . urlencode($e->getMessage()));
        }
    }

    public function orderCallback(Request $request)
    {
        $orderId = $request->get('order_id') ?? $request->route('order_id');
        
        DB::beginTransaction();

        try {
            $order = Order::findOrFail($orderId);

            $invoiceId = $order->invoice_id ?? $request->get('tap_id') ?? $request->get('id');
            
            if (!$invoiceId) {
                throw new \Exception('Invoice ID not found');
            }

            $paymentStatus = $this->paymentService->getPaymentStatus($invoiceId);

            Log::info('Tap Payment Status (Order):', [
                'order_id'  => $orderId,
                'invoice_id' => $invoiceId,
                'status'    => $paymentStatus,
            ]);

            if ($paymentStatus['Data']['InvoiceStatus'] === 'Paid') {
                $order->update([
                    'status' => OrderStatus::PAID,
                ]);

                DB::commit();

                $successUrl = config('Tap.front_end_success_url', config('app.front_end_url'));
                return redirect($successUrl . '?status=success&order_id=' . $order->id);
            } else {
                $order->update([
                    'status' => OrderStatus::FAILED,
                ]);

                DB::commit();

                $errorUrl = config('Tap.front_end_error_url', config('app.front_end_url'));
                return redirect($errorUrl . '?status=failed&message=Payment not successful&order_id=' . $order->id);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Tap Order Callback Error', [
                'order_id'  => $orderId ?? null,
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);

            $errorUrl = config('Tap.front_end_error_url', config('app.front_end_url'));
            return redirect($errorUrl . '?status=failed&message=' . urlencode($e->getMessage()));
        }
    }

    public function subscriptionError(Request $request)
    {
        $subscriptionId = $request->get('subscription_id') ?? $request->route('subscription_id');

        try {
            $subscription = Subscription::findOrFail($subscriptionId);
            $subscription->update([
                'status' => SubscriptionStatus::FAILED,
            ]);

            $errorUrl = config('Tap.front_end_error_url', config('app.front_end_url'));
            return redirect($errorUrl . '?status=failed&subscription_id=' . $subscription->id);
        } catch (\Exception $e) {
            Log::error('Tap Subscription Error Handler', [
                'subscription_id' => $subscriptionId ?? null,
                'error'          => $e->getMessage(),
            ]);

            $errorUrl = config('Tap.front_end_error_url', config('app.front_end_url'));
            return redirect($errorUrl . '?status=failed&message=' . urlencode($e->getMessage()));
        }
    }

    public function orderError(Request $request)
    {
        $orderId = $request->get('order_id') ?? $request->route('order_id');

        try {
            $order = Order::findOrFail($orderId);
            $order->update([
                'status' => OrderStatus::FAILED,
            ]);

            $errorUrl = config('Tap.front_end_error_url', config('app.front_end_url'));
            return redirect($errorUrl . '?status=failed&order_id=' . $order->id);
        } catch (\Exception $e) {
            Log::error('Tap Order Error Handler', [
                'order_id' => $orderId ?? null,
                'error'    => $e->getMessage(),
            ]);

            $errorUrl = config('Tap.front_end_error_url', config('app.front_end_url'));
            return redirect($errorUrl . '?status=failed&message=' . urlencode($e->getMessage()));
        }
    }
}

