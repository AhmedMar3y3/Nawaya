<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workshop;
use App\Models\WorkshopPackage;
use App\Models\WorkshopPayment;
use App\Models\Subscription;
use App\Enums\Workshop\WorkshopType;
use App\Enums\Subscription\SubscriptionStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkshopFinancialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a test user
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => '1234567890',
                'password' => bcrypt('password'),
            ]);
        }

        // Workshop titles in Arabic
        $workshopTitles = [
            'ورشة الذكاء العاطفي',
            'ورشة أساسيات التصوير الفوتوغرافي (مسجلة)',
            'التخطيط الاستراتيجي الشخصي لعام 2026',
            'مؤتمر نوايا السنوي للتمكين',
            'مقدمة في علم النفس الإيجابي (مسجلة)',
            'قيادة الفرق عالية الأداء',
            'فن الإلقاء والتحدث أمام الجمهور',
            'التسويق الرقمي عبر وسائل التواصل الاجتماعي (مسجلة)',
            'كيف تبدأ مشروعك الخاص ؟',
            'ورشة اليوغا والتأمل للمبتدئين',
            'إدارة الوقت بفعالية',
            'مهارات التفاوض والاقناع',
        ];

        $workshopTypes = [
            WorkshopType::ONLINE,
            WorkshopType::RECORDED,
            WorkshopType::ONLINE,
            WorkshopType::ONLINE_ONSITE,
            WorkshopType::RECORDED,
            WorkshopType::ONLINE,
            WorkshopType::ONSITE,
            WorkshopType::RECORDED,
            WorkshopType::ONLINE,
            WorkshopType::ONLINE,
            WorkshopType::ONLINE,
            WorkshopType::ONSITE,
        ];

        $teachers = [
            'د. سارة أحمد',
            'أ. محمد خالد',
            'د. فاطمة علي',
            'أ. أحمد محمود',
            'د. نورا حسن',
            'أ. خالد إبراهيم',
            'د. ليلى محمد',
            'أ. يوسف عبدالله',
            'د. مريم سالم',
            'أ. عمر ناصر',
            'د. هدى كريم',
            'أ. ريم فؤاد',
        ];

        $teacherPercentages = [20, 1, 0, 15, 10, 25, 0, 5, 30, 0, 12, 18];

        DB::transaction(function () use ($user, $workshopTitles, $workshopTypes, $teachers, $teacherPercentages) {
            foreach ($workshopTitles as $index => $title) {
                // Create workshop
                $workshop = Workshop::create([
                    'title' => $title,
                    'teacher' => $teachers[$index],
                    'teacher_per' => $teacherPercentages[$index],
                    'description' => 'وصف تفصيلي للورشة ' . $title,
                    'subject_of_discussion' => '<p>موضوع النقاش للورشة ' . $title . '</p>',
                    'is_active' => true,
                    'type' => $workshopTypes[$index],
                    'start_date' => now()->addDays(rand(1, 30)),
                    'end_date' => now()->addDays(rand(31, 60)),
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'online_link' => $workshopTypes[$index] !== WorkshopType::ONSITE ? 'https://zoom.us/j/123456789' : null,
                    'city' => $workshopTypes[$index] !== WorkshopType::ONLINE ? 'الرياض' : null,
                ]);

                // Create packages for the workshop
                $packagePrices = [1000, 1200, 1500, 800, 2000];
                $packageTitles = ['الحزمة الأساسية', 'الحزمة المتوسطة', 'الحزمة المتقدمة', 'الحزمة المميزة', 'الحزمة الشاملة'];
                
                $numPackages = $workshop->type === WorkshopType::RECORDED ? 1 : rand(2, 4);
                
                for ($p = 0; $p < $numPackages; $p++) {
                    $price = $packagePrices[array_rand($packagePrices)];
                    $isOffer = rand(0, 1) === 1;
                    
                    WorkshopPackage::create([
                        'workshop_id' => $workshop->id,
                        'title' => $packageTitles[$p] ?? 'حزمة ' . ($p + 1),
                        'price' => $price,
                        'is_offer' => $isOffer,
                        'offer_price' => $isOffer ? $price * 0.8 : null,
                        'offer_expiry_date' => $isOffer ? now()->addDays(rand(7, 30)) : null,
                        'features' => '<ul><li>ميزة 1</li><li>ميزة 2</li><li>ميزة 3</li></ul>',
                    ]);
                }

                // Get packages for subscriptions
                $packages = $workshop->packages;
                
                // Create subscriptions (paid and active)
                $numSubscriptions = rand(5, 25);
                for ($s = 0; $s < $numSubscriptions; $s++) {
                    $package = $packages->random();
                    $price = $package->is_offer && $package->offer_price ? $package->offer_price : $package->price;
                    
                    // Random status - mostly paid
                    $status = rand(0, 10) < 8 ? SubscriptionStatus::PAID : SubscriptionStatus::ACTIVE;
                    
                    Subscription::create([
                        'user_id' => $user->id,
                        'workshop_id' => $workshop->id,
                        'price' => $price,
                        'status' => $status,
                        'payment_type' => 'online',
                        'invoice_id' => 'INV-' . strtoupper(uniqid()),
                    ]);
                }

                // Create some payments for workshops with teacher percentage > 0
                if ($workshop->teacher_per > 0) {
                    $numPayments = rand(0, 3);
                    for ($pay = 0; $pay < $numPayments; $pay++) {
                        WorkshopPayment::create([
                            'workshop_id' => $workshop->id,
                            'amount' => rand(50, 500),
                            'date' => now()->subDays(rand(1, 90)),
                            'notes' => 'دفعة رقم ' . ($pay + 1),
                        ]);
                    }
                }
            }
        });

        $this->command->info('تم إنشاء ' . count($workshopTitles) . ' ورشة مع الحزم والاشتراكات والدفعات بنجاح!');
    }
}
