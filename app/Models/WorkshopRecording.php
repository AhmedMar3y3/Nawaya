<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkshopRecording extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'workshop_id',
        'title',
        'link',
        'available_from',
        'available_to',
    ];

    protected $casts = [
        'available_from' => 'date',
        'available_to'   => 'date',
    ];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function subscriptionPermissions()
    {
        return $this->belongsToMany(Subscription::class, 'subscription_recordings', 'recording_id', 'subscription_id')
            ->withPivot('available_from', 'available_to')
            ->withTimestamps();
    }
}
