<?php

namespace App\Services\User;

use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FoloosiPaymentService
{
    private string $secretKey;
    private string $baseUrl;
    private string $callbackUrl;

    public function __construct()
    {
        $this->secretKey = config('services.foloosi.secret_key');
        $this->baseUrl   = config('services.foloosi.base_url', 'https://api.foloosi.com');
        $this->callbackUrl = config('services.foloosi.callback_url');
    }

    public function createPayment(Order $order): array
    {
        $payload = [
            'amount'           => number_format($order->total_price, 2, '.', ''),
            'currency'         => 'AED',
            'description'      => 'Order #' . $order->id . ' - ' . config('app.name'),
            'link_type'        => 'single',
            'customer_name'    => $order->user->full_name ?? 'Customer',
            'customer_email'   => $order->user->email,
            'customer_mobile'  => $order->user->phone ?? '',
            'phone_code'       => $order->user->country->code ?? '',
            'notify_email'     => 'No',
            'notify_sms'       => 'No',
            'expire_date'      => now()->addDays(7)->format('Y-m-d H:i:s'),
            'callback_url'     => $this->callbackUrl,
        ];

        try {
            $response = Http::withHeaders([
                'secret_key'     => $this->secretKey,
                'Content-Type'   => 'application/json',
                'Accept'         => 'application/json',
            ])->post("{$this->baseUrl}/merchant/v1/payment-links/create", $payload);

            Log::info('Foloosi Create Payment Link Response', [
                'order_id' => $order->id,
                'status'   => $response->status(),
                'body'     => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (
                    isset($data['message']) &&
                    str_contains($data['message'], 'Payment link created successfully') &&
                    isset($data['data']['payment_link_reference'])
                ) {
                    $reference   = $data['data']['payment_link_reference'];
                    $paymentUrl  = "https://foloosi.com/v1/pay/{$reference}";

                    $order->update([
                        'invoice_id'   => $reference,
                        'invoice_url'  => $paymentUrl,
                    ]);

                    return [
                        'invoice_id'   => $reference,
                        'invoice_url'  => $paymentUrl,
                    ];
                }

                throw new \Exception($data['message'] ?? 'Unknown response from Foloosi');
            }

            $errorMessage = $response->json('message') ?? $response->body();
            throw new \Exception("Foloosi API Error [{$response->status()}]: {$errorMessage}");

        } catch (\Exception $e) {
            Log::error('Foloosi Payment Link Creation Failed', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);

            throw new \Exception('فشل في إنشاء رابط الدفع: ' . $e->getMessage());
        }
    }

    public function verifyPayment(string $referenceId): array
    {
        try {
            $response = Http::withHeaders([
                'secret_key'   => $this->secretKey,
                'Content-Type' => 'application/json',
            ])->get("{$this->baseUrl}/merchant/v1/payment-links/details/{$referenceId}");

            if ($response->successful()) {
                $data = $response->json();

                $status = $data['data']['payment_status'] ?? 'unknown';

                return [
                    'paid'      => $status === 'paid',
                    'status'    => $status,
                    'amount'    => $data['data']['amount'] ?? null,
                    'reference' => $referenceId,
                ];
            }

            return [
                'paid'   => false,
                'status' => 'failed',
                'error'  => $response->json('message') ?? 'Verification failed',
            ];
        } catch (\Exception $e) {
            Log::error('Foloosi Verification Failed', [
                'reference_id' => $referenceId,
                'error'        => $e->getMessage(),
            ]);

            return ['paid' => false, 'status' => 'error'];
        }
    }

    public function createPaymentForSubscription(Subscription $subscription): array
    {
        if ($subscription->is_gift) {
            $gifter = $subscription->gifter;
            $customerName = $gifter->full_name ?? 'Customer';
            $customerEmail = $gifter->email ?? '';
            $customerMobile = $gifter->phone ?? '';
            $phoneCode = $gifter->country->code ?? '';
        } else {
            $customerName = $subscription->user->full_name ?? 'Customer';
            $customerEmail = $subscription->user->email;
            $customerMobile = $subscription->user->phone ?? '';
            $phoneCode = $subscription->user->country->code ?? '';
        }

        $payload = [
            'amount'           => number_format($subscription->price, 2, '.', ''),
            'currency'         => 'AED',
            'description'      => 'Workshop Subscription #' . $subscription->id . ' - ' . config('app.name'),
            'link_type'        => 'single',
            'customer_name'    => $customerName,
            'customer_email'   => $customerEmail,
            'customer_mobile'  => $customerMobile,
            'phone_code'       => $phoneCode,
            'notify_email'     => 'No',
            'notify_sms'       => 'No',
            'expire_date'      => now()->addDays(7)->format('Y-m-d H:i:s'),
            'callback_url'     => $this->callbackUrl,
        ];

        try {
            $response = Http::withHeaders([
                'secret_key'     => $this->secretKey,
                'Content-Type'   => 'application/json',
                'Accept'         => 'application/json',
            ])->post("{$this->baseUrl}/merchant/v1/payment-links/create", $payload);

            Log::info('Foloosi Create Payment Link Response (Subscription)', [
                'subscription_id' => $subscription->id,
                'status'          => $response->status(),
                'body'            => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (
                    isset($data['message']) &&
                    str_contains($data['message'], 'Payment link created successfully') &&
                    isset($data['data']['payment_link_reference'])
                ) {
                    $reference   = $data['data']['payment_link_reference'];
                    $paymentUrl  = "https://foloosi.com/v1/pay/{$reference}";

                    $subscription->update([
                        'invoice_id'  => $reference,
                        'invoice_url' => $paymentUrl,
                    ]);

                    return [
                        'invoice_id'   => $reference,
                        'invoice_url'  => $paymentUrl,
                    ];
                }

                throw new \Exception($data['message'] ?? 'Unknown response from Foloosi');
            }

            $errorMessage = $response->json('message') ?? $response->body();
            throw new \Exception("Foloosi API Error [{$response->status()}]: {$errorMessage}");

        } catch (\Exception $e) {
            Log::error('Foloosi Payment Link Creation Failed (Subscription)', [
                'subscription_id' => $subscription->id,
                'error'          => $e->getMessage(),
                'trace'          => $e->getTraceAsString(),
            ]);

            throw new \Exception('فشل في إنشاء رابط الدفع: ' . $e->getMessage());
        }
    }
}