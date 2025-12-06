<?php

namespace App\Models;

use App\Enums\Workshop\WorkshopType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\Subscription\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workshop extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'teacher',
        'teacher_per',
        'description',
        'subject_of_discussion',
        'is_active',
        'type',

        // Online & Onsite & Onsite_Online cases
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'online_link',

        // Onsite & Onsite_Online cases
        'city',
        'country_id',
        'hotel',
        'hall',
        'is_certificates_generated',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'teacher_per' => 'double',
        'type'        => WorkshopType::class,
        'start_date'  => 'date',
        'end_date'    => 'date',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function recordings()
    {
        return $this->hasMany(WorkshopRecording::class);
    }

    public function attachments()
    {
        return $this->hasMany(WorkshopAttachment::class);
    }

    public function files()
    {
        return $this->hasMany(WorkshopFile::class);
    }

    public function packages()
    {
        return $this->hasMany(WorkshopPackage::class);
    }

    public function payments()
    {
        return $this->hasMany(WorkshopPayment::class);
    }

    public function activeSubscriptions()
    {
        return $this->subscriptions()->where('status', SubscriptionStatus::PAID->value);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function userBalanceHistories()
    {
        return $this->hasMany(UserBalanceHistory::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
