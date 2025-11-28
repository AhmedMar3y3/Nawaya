<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\Order;
use App\Models\Setting;
use App\Enums\Order\OrderStatus;
use App\Enums\Payment\PaymentType;
use App\Services\User\CartService;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        private readonly CartService $cartService
    ) {}

    public function getOrderSummary(User $user): array
    {
        $cartSummary = $this->cartService->getFullCartSummary($user);
        $items       = $cartSummary['items'];
        $prices      = $cartSummary['prices'];

        $products = $items->map(function ($item) {
            return [
                'id'          => $item->product->id,
                'title'       => $item->product->title,
                'quantity'    => $item->quantity,
                'price'       => (float) $item->price,
                'total_price' => (float) ($item->price * $item->quantity),
            ];
        });

        $paymentSettings = $this->getPaymentSettings();
        $bankAccountSettings = $paymentSettings['bank_transfer'] 
            ? $this->getBankAccountSettings() 
            : null;

        return [
            'products'        => $products,
            'prices'          => $prices,
            'bank_account'    => $bankAccountSettings,
            'payment_options' => $paymentSettings,
        ];
    }

    private function getBankAccountSettings(): array
    {
        $settings = Setting::whereIn('key', [
            'account_name',
            'bank_name',
            'IBAN_number',
            'account_number',
            'swift',
        ])->pluck('value', 'key');

        return [
            'account_name'   => $settings['account_name'] ?? null,
            'bank_name'      => $settings['bank_name'] ?? null,
            'IBAN_number'    => $settings['IBAN_number'] ?? null,
            'account_number' => $settings['account_number'] ?? null,
            'swift'          => $settings['swift'] ?? null,
        ];
    }

    private function getPaymentSettings(): array
    {
        $settings = Setting::whereIn('key', [
            'online_payment',
            'bank_transfer',
        ])->pluck('value', 'key');

        return [
            'online_payment' => filter_var($settings['online_payment'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'bank_transfer'  => filter_var($settings['bank_transfer'] ?? true, FILTER_VALIDATE_BOOLEAN),
        ];
    }

    public function createOrder(User $user, PaymentType $paymentType): Order
    {
        $this->validatePaymentType($paymentType);

        $cart = $user->cart;

        if (! $cart || $cart->cartItems()->count() === 0) {
            throw new \Exception('السلة فارغة');
        }

        $cartSummary = $this->cartService->getFullCartSummary($user);
        $prices      = $cartSummary['prices'];
        $items       = $cartSummary['items'];

        DB::beginTransaction();

        try {
            $status = $paymentType === PaymentType::BANK_TRANSFER
                ? OrderStatus::PROCESSING
                : OrderStatus::PENDING;

            $order = Order::create([
                'user_id'      => $user->id,
                'total_price'  => $prices['total_price'],
                'status'       => $status,
                'payment_type' => $paymentType,
            ]);

            foreach ($items as $item) {
                $order->orderItems()->create([
                    'product_id'  => $item->product_id,
                    'quantity'    => $item->quantity,
                    'price'       => $item->price,
                    'total_price' => $item->price * $item->quantity,
                ]);
            }

            $cart->cartItems()->delete();
            $cart->update(['total_price' => 0]);

            DB::commit();

            return $order->fresh('orderItems.product');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function validatePaymentType(PaymentType $paymentType): void
    {
        $paymentSettings = $this->getPaymentSettings();

        if ($paymentType === PaymentType::ONLINE && ! $paymentSettings['online_payment']) {
            throw new \Exception('الدفع الإلكتروني غير متاح حالياً');
        }

        if ($paymentType === PaymentType::BANK_TRANSFER && ! $paymentSettings['bank_transfer']) {
            throw new \Exception('التحويل البنكي غير متاح حالياً');
        }
    }
}
