<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkshopAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'workshop_id',
        'type',
        'title',
        'file',
        'notes',
    ];

    protected $casts = [
        'type' => WorkshopAttachmentType::class,
    ];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }
}
