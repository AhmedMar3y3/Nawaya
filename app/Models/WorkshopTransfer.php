<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkshopTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'workshop_transfer_from',
        'workshop_transfer_to',
        'old_price',
        'new_price',
        'paid_amount',
        'notes',
    ];

    protected $casts = [
        'old_price'   => 'double',
        'new_price'   => 'double',
        'paid_amount' => 'double',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function workshopFrom()
    {
        return $this->belongsTo(Workshop::class, 'workshop_transfer_from');
    }

    public function workshopTo()
    {
        return $this->belongsTo(Workshop::class, 'workshop_transfer_to');
    }

    public function user()
    {
        return $this->subscription->user();
    }
}
