<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'workshop_id',
        'amount',
        'date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'double',
    ];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function teacherPercentage()
    {
        return $this->workshop()->first()->teacher_per;
    }
}
