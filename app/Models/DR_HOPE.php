<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use mar3y\ImageUpload\Traits\HasImage;
use App\Enums\Settings\DrHope;

class DR_HOPE extends Model
{
    use HasFactory, HasImage;

       protected $fillable = [
        'title',
        'image',
        'link',
        'type',
    ];

    protected $casts = [
        'type' => DrHope::class,
    ];

    protected static $imageAttributes = ['image'];

    public function scopeInstagram()
    {
        return $this->where('type', DrHope::INSTAGRAM);
    }
    public function scopeImage()
    {
        return $this->where('type', DrHope::IMAGE);
    }
    public function scopeVideo()
    {
        return $this->where('type', DrHope::VIDEO);
    }
}
