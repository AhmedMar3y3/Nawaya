<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'country_id',
        'is_active',
        'balance',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'balance'   => 'double',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'owner_id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function giftsSent()
    {
        return $this->hasMany(Subscription::class, 'gift_user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function activeSubscriptions()
    {
        return $this->subscriptions()->where('status', 'active');
    }

    public function balanceHistories()
    {
        return $this->hasMany(UserBalanceHistory::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
