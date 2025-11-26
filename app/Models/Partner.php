<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use mar3y\ImageUpload\Traits\HasImage;

class Partner extends Model
{
    use HasFactory, HasImage;

    protected $fillable = [
        'title',
        'image',
        'description',
        'link',
    ];

    protected static $imageAttributes = ['image'];
}
