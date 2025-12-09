<?php

namespace App\Services\User;

use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TapPaymentService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('Tap.secret_key');
        $this->baseUrl = config('Tap.url');
    }

    public function createPayment(Order $order): array
    {
        $user = $order->user;
        $nameParts = explode(' ', $user->full_name ?? $user->name ?? 'Customer');
        
        $payload = [
            'amount'      => number_format($order->total_price, 3, '.', ''),
            'currency'    => 'AED',
            'customer'    => [
                'first_name' => $nameParts[0] ?? 'Customer',
                'last_name'  => $nameParts[1] ?? '',
                'email'      => $user->email,
                'phone'      => [
                    'country_code' => $user->country->code ?? '+971',
                    'number'       => $user->phone ?? '000000000',
                ],
            ],
            'source'      => [
                'id' => 'src_all',
            ],
            'redirect'    => [
                'url' => route('tap.order.callback', [
                    'order_id' => $order->id,
                ]),
            ],
            'post'        => [
                'url' => route('tap.order.callback', [
                    'order_id' => $order->id,
                ]),
            ],
            'reference'   => [
                'transaction' => 'ORDER_' . $order->id,
                'order'       => 'ORDER_' . $order->id,
            ],
            'description' => 'Order #' . $order->id . ' - ' . config('app.name'),
            'metadata'    => [
                'order_id' => $order->id,
                'user_id'  => $user->id,
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/v2/charges", $payload);

            Log::info('Tap Create Payment Response (Order)', [
                'order_id' => $order->id,
                'status'   => $response->status(),
                'body'     => $response->body(),
            ]);

            if (!$response->ok()) {
                throw new \Exception('Payment initiation failed: ' . $response->body());
            }

            $responseData = $response->json();

            if (!isset($responseData['id']) || !isset($responseData['transaction']['url'])) {
                throw new \Exception('Invalid response from Tap API');
            }

            $invoiceId = $responseData['id'];
            $invoiceUrl = $responseData['transaction']['url'];

            $order->update([
                'invoice_id'  => $invoiceId,
                'invoice_url' => $invoiceUrl,
            ]);

            return [
                'invoice_id'   => $invoiceId,
                'invoice_url' => $invoiceUrl,
            ];
        } catch (\Exception $e) {
            Log::error('Tap Payment Link Creation Failed (Order)', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);

            throw new \Exception('فشل في إنشاء رابط الدفع: ' . $e->getMessage());
        }
    }

    public function createPaymentForSubscription(Subscription $subscription): array
    {
        if ($subscription->is_gift) {
            $user = $subscription->gifter;
        } else {
            $user = $subscription->user;
        }

        $nameParts = explode(' ', $user->full_name ?? $user->name ?? 'Customer');
        
        $payload = [
            'amount'      => number_format($subscription->price, 3, '.', ''),
            'currency'    => 'AED',
            'customer'    => [
                'first_name' => $nameParts[0] ?? 'Customer',
                'last_name'  => $nameParts[1] ?? '',
                'email'      => $user->email,
                'phone'      => [
                    'country_code' => $user->country->code ?? '+971',
                    'number'       => $user->phone ?? '000000000',
                ],
            ],
            'source'      => [
                'id' => 'src_all',
            ],
            'redirect'    => [
                'url' => route('tap.subscription.callback', [
                    'subscription_id' => $subscription->id,
                ]),
            ],
            'post'        => [
                'url' => route('tap.subscription.callback', [
                    'subscription_id' => $subscription->id,
                ]),
            ],
            'reference'   => [
                'transaction' => 'SUB_' . $subscription->id,
                'order'       => 'SUB_' . $subscription->id,
            ],
            'description' => 'Subscription payment for ' . ($subscription->package->title ?? 'Workshop Subscription #' . $subscription->id),
            'metadata'    => [
                'subscription_id' => $subscription->id,
                'user_id'         => $user->id,
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/v2/charges", $payload);

            Log::info('Tap Create Payment Response (Subscription)', [
                'subscription_id' => $subscription->id,
                'status'          => $response->status(),
                'body'            => $response->body(),
            ]);

            if (!$response->ok()) {
                throw new \Exception('Payment initiation failed: ' . $response->body());
            }

            $responseData = $response->json();

            if (!isset($responseData['id']) || !isset($responseData['transaction']['url'])) {
                throw new \Exception('Invalid response from Tap API');
            }

            $invoiceId = $responseData['id'];
            $invoiceUrl = $responseData['transaction']['url'];

            $subscription->update([
                'invoice_id'  => $invoiceId,
                'invoice_url' => $invoiceUrl,
            ]);

            return [
                'invoice_id'   => $invoiceId,
                'invoice_url' => $invoiceUrl,
            ];
        } catch (\Exception $e) {
            Log::error('Tap Payment Link Creation Failed (Subscription)', [
                'subscription_id' => $subscription->id,
                'error'          => $e->getMessage(),
                'trace'          => $e->getTraceAsString(),
            ]);

            throw new \Exception('فشل في إنشاء رابط الدفع: ' . $e->getMessage());
        }
    }

    public function getPaymentStatus($invoiceId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->get("{$this->baseUrl}/v2/charges/{$invoiceId}");

            if (!$response->ok()) {
                Log::error('Tap Payment Status Check Failed', [
                    'invoice_id' => $invoiceId,
                    'status'    => $response->status(),
                    'body'      => $response->body(),
                ]);
                throw new \Exception('Failed to retrieve payment status: ' . $response->body());
            }

            $responseData = $response->json();

            return [
                'Data' => [
                    'InvoiceStatus' => $responseData['status'] === 'CAPTURED' ? 'Paid' : 'Unpaid',
                    'InvoiceId'     => $responseData['id'],
                    'InvoiceValue'  => $responseData['amount'],
                    'Currency'      => $responseData['currency'],
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Tap Payment Status Check Exception', [
                'invoice_id' => $invoiceId,
                'error'     => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}

