<?php

namespace App\Models;

use App\Enums\Boutique\OwnerType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use mar3y\ImageUpload\Traits\HasImage;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasImage;

    protected $fillable = [
        'owner_type',
        'owner_id',
        'owner_per',
        'title',
        'price',
        'image',
    ];

    protected $casts = [
        'owner_type' => OwnerType::class,
        'owner_per'  => 'double',
        'price'      => 'double',
    ];

    protected static $imageAttributes = ['image'];

    public function userOwner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function isPlatformOwned(): bool
    {
        return $this->owner_type === OwnerType::PLATFORM;
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

}
