<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use mar3y\ImageUpload\Traits\HasImage;

class WorkshopFile extends Model
{
    use HasFactory, SoftDeletes, HasImage;

    protected $fillable = [
        'workshop_id',
        'title',
        'file',
    ];

    protected static $imageAttributes = ['file'];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }
}
