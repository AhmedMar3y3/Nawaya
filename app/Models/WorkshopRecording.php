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
    ];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }
}
