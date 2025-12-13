<?php

namespace App\Models;

use App\Enums\Payment\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\Subscription\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Charity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'workshop_id',
        'package_id',
        'number_of_seats',
        'used_seats',
        'price',
        'status',
        'payment_type',
        'invoice_id',
        'invoice_url',
        'refunded_seats',
        'user_balance',
    ];

    protected $casts = [
        'number_of_seats' => 'integer',
        'used_seats' => 'integer',
        'price' => 'double',
        'status' => SubscriptionStatus::class,
        'payment_type' => PaymentType::class,
        'refunded_seats' => 'integer',
        'user_balance' => 'integer',
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

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function getAvailableSeatsAttribute()
    {
        $totalAllocated = ($this->used_seats ?? 0) + ($this->refunded_seats ?? 0) + ($this->user_balance ?? 0);
        return $this->number_of_seats - $totalAllocated;
    }

    public function getAvailableAmountAttribute()
    {
        $pricePerSeat = $this->price / $this->number_of_seats;
        $totalAllocated = ($this->used_seats ?? 0) + ($this->refunded_seats ?? 0) + ($this->user_balance ?? 0);
        $allocatedPrice = $pricePerSeat * $totalAllocated;
        return $this->price - $allocatedPrice;
    }
}