<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use mar3y\ImageUpload\Traits\HasImage;

class Setting extends Model
{
    use HasFactory, HasImage;

    protected $fillable = [
        'key',
        'value',
    ];

    protected static $imageAttributes = ['value'];
}
