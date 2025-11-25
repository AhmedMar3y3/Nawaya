<?php

namespace App\Models;

use App\Enums\Payment\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\Subscription\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'workshop_id',
        'price',
        'status',
        'payment_type',
        'invoice_id',
        'invoice_url',

        // Gift flow
        'is_gift',
        'gift_user_id',
        'full_name',
        'phone',
        'country_id',
    ];

    protected $casts = [
        'price'        => 'double',
        'status'       => SubscriptionStatus::class,
        'payment_type' => PaymentType::class,
        'is_gift'      => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function gifter()
    {
        return $this->belongsTo(User::class, 'gift_user_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
