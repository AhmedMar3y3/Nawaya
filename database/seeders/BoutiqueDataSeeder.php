<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Enums\Boutique\OwnerType;
use App\Enums\Order\OrderStatus;
use App\Enums\Payment\PaymentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BoutiqueDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create users
        $users = User::take(5)->get();
        if ($users->isEmpty()) {
            // Create some users if none exist
            for ($i = 1; $i <= 5; $i++) {
                $users->push(User::create([
                    'name' => 'User ' . $i,
                    'email' => 'user' . $i . '@example.com',
                    'phone' => '123456789' . $i,
                    'password' => bcrypt('password'),
                ]));
            }
        }

        // Product titles in Arabic
        $productTitles = [
            'كتاب تطوير الذات',
            'دورة تدريبية أونلاين',
            'مجموعة أدوات إنتاجية',
            'كورس تعلم البرمجة',
            'كتاب فن الإدارة',
            'دليل التسويق الرقمي',
            'مجموعة قوالب تصميم',
            'كورس التصوير الفوتوغرافي',
            'كتاب القيادة الناجحة',
            'دورة إدارة المشاريع',
            'أدوات التخطيط الاستراتيجي',
            'كورس المبيعات والتسويق',
            'كتاب التفكير الإبداعي',
            'دورة تطوير المهارات',
            'مجموعة موارد تعليمية',
        ];

        $productPrices = [50, 75, 100, 120, 150, 200, 250, 300, 350, 400, 450, 500, 600, 750, 1000];

        DB::transaction(function () use ($users, $productTitles, $productPrices) {
            $products = [];

            // Create products
            foreach ($productTitles as $index => $title) {
                $price = $productPrices[array_rand($productPrices)];
                
                // Mix of platform-owned and user-owned products
                $isUserOwned = rand(0, 1) === 1;
                
                if ($isUserOwned) {
                    $owner = $users->random();
                    $ownerPer = [10, 15, 20, 25, 30, 35, 40][array_rand([10, 15, 20, 25, 30, 35, 40])];
                } else {
                    $owner = null;
                    $ownerPer = 0;
                }

                $product = Product::create([
                    'owner_type' => $isUserOwned ? OwnerType::USER->value : OwnerType::PLATFORM->value,
                    'owner_id' => $isUserOwned ? $owner->id : null,
                    'owner_per' => $ownerPer,
                    'title' => $title,
                    'price' => $price,
                    'image' => 'defaults/nawaya.png',
                ]);

                $products[] = $product;
            }

            // Create orders
            $orderStatuses = [
                OrderStatus::COMPLETED,
                OrderStatus::COMPLETED,
                OrderStatus::COMPLETED,
                OrderStatus::PAID,
                OrderStatus::PROCESSING,
                OrderStatus::PENDING,
            ];

            $paymentTypes = [
                PaymentType::ONLINE,
                PaymentType::BANK_TRANSFER,
            ];

            // Create 20-30 orders
            $numOrders = rand(20, 30);
            
            for ($o = 0; $o < $numOrders; $o++) {
                $user = $users->random();
                $status = $orderStatuses[array_rand($orderStatuses)];
                $paymentType = $paymentTypes[array_rand($paymentTypes)];
                
                // Select 1-4 random products for this order
                $selectedProducts = collect($products)->random(rand(1, 4));
                
                $orderTotal = 0;
                $orderItems = [];

                foreach ($selectedProducts as $product) {
                    $quantity = rand(1, 3);
                    // Item price includes VAT (price * 1.05)
                    $itemPriceWithVAT = $product->price * 1.05;
                    $itemTotalWithVAT = $itemPriceWithVAT * $quantity;
                    $orderTotal += $itemTotalWithVAT;
                    
                    $orderItems[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'price' => $itemPriceWithVAT, // Price with VAT
                        'total_price' => $itemTotalWithVAT, // Total with VAT
                    ];
                }

                // Order total already includes VAT
                $orderTotalWithVAT = $orderTotal;

                $order = Order::create([
                    'user_id' => $user->id,
                    'total_price' => $orderTotalWithVAT,
                    'status' => $status->value,
                    'payment_type' => $paymentType->value,
                    'invoice_id' => 'INV-' . strtoupper(uniqid()),
                    'invoice_url' => 'https://example.com/invoices/' . uniqid(),
                    'created_at' => now()->subDays(rand(0, 90)),
                ]);

                // Create order items
                foreach ($orderItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product']->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total_price' => $item['total_price'],
                    ]);
                }
            }

            $this->command->info('تم إنشاء ' . count($products) . ' منتج و ' . $numOrders . ' طلب بنجاح!');
            $this->command->info('عدد الطلبات المكتملة: ' . Order::where('status', OrderStatus::COMPLETED->value)->count());
        });
    }
}
