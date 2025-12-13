<?php

return [
    'order_statuses'            => [
        'pending'   => 'قيد المراجعة',
        'paid'      => 'مدفوع',
        'completed' => 'مكتمل',
        'failed'    => 'فشل',
        'refunded'  => 'مسترجع',
    ],
    'payment_types'             => [
        'online'        => 'بطاقة بنكية',
        'bank_transfer' => 'تحويل بنكي',
        'link'          => 'رابط دفع',
        'user_balance'  => 'رصيد داخلي',
        'charity'       => 'منحة'
    ],
    
    'refund_types'              => [
        'cash'          => 'كاش',
        'bank_transfer' => 'تحويل بنكي',
        'link'          => 'رابط دفع',
    ],

    'boutique_owner_types'      => [
        'platform' => 'المنصة',
        'user'     => 'المستخدم',
    ],

    'workshop_types'            => [
        'online'        => 'أونلاين',
        'onsite'        => 'حضوري',
        'online_onsite' => 'أونلاين و حضوري',
        'recorded'      => 'مسجلة',
    ],

    'workshop_attachment_types' => [
        'audio' => 'ملف صوتي',
        'video' => 'ملف فيديو',
    ],

    'subscription_statuses'     => [
        'pending'      => 'قيد المراجعة',
        'processing'   => 'قيد المعالجة',
        'paid'         => 'مدفوع',
        'active'       => 'نشط',
        'expired'      => 'منتهي',
        'failed'       => 'فشل',
        'refunded'     => 'مسترد',
        'user_balance' => 'رصيد داخلي',
    ],
];
