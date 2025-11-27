<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use mar3y\ImageUpload\Traits\HasImage;
use App\Enums\Workshop\WorkshopAttachmentType;

class WorkshopAttachment extends Model
{
    use HasFactory, SoftDeletes, HasImage;

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

    protected static $imageAttributes = ['file'];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }
}
