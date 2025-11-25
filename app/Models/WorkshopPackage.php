<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkshopPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'workshop_id',
        'title',
        'price',
        'is_offer',
        'offer_price',
        'offer_expiry_date',
        'features',
    ];

    protected $casts = [
        'is_offer'          => 'boolean',
        'offer_price'       => 'double',
        'offer_expiry_date' => 'date',
    ];
    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }
}
