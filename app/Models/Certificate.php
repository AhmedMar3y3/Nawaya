<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'workshop_id',
        'subscription_id',
        'issued_at',
        'is_generated',
        'is_active',
    ];

    protected $casts = [
        'issued_at'    => 'date',
        'is_generated' => 'boolean',
        'is_active'    => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
