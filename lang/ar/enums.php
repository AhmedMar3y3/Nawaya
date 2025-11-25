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
        'cash'          => 'نقدي',
        'online'        => 'بطاقة بنكية',
        'bank_transfer' => 'تحويل بنكي',
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
];
