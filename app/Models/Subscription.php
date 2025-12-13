<?php

namespace App\Models;

use App\Enums\Payment\RefundType;
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
        'package_id',
        'paid_amount',
        'transferer_name',
        'notes',
        'is_refunded',
        'refund_type',
        'refund_notes',

        // Gift flow
        'is_gift',
        'gift_user_id',
        'full_name',
        'phone',
        'country_id',
        'message',
        'is_gift_approved',

        'charity_id',
        'charity_notes',
    ];

    protected $casts = [
        'price'            => 'double',
        'paid_amount'      => 'double',
        'is_refunded'      => 'boolean',
        'is_gift_approved' => 'boolean',
        'refund_type'      => RefundType::class,
        'status'           => SubscriptionStatus::class,
        'payment_type'     => PaymentType::class,
        'is_gift'          => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function package()
    {
        return $this->belongsTo(WorkshopPackage::class, 'package_id');
    }

    public function gifter()
    {
        return $this->belongsTo(User::class, 'gift_user_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    public function recordingPermissions()
    {
        return $this->belongsToMany(WorkshopRecording::class, 'subscription_recordings', 'subscription_id', 'recording_id')
            ->withPivot('available_from', 'available_to')
            ->withTimestamps();
    }

    public function charity()
    {
        return $this->belongsTo(Charity::class);
    }
}
