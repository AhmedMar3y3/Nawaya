<?php

namespace App\Models;

use App\Enums\Order\OrderStatus;
use App\Enums\Payment\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'payment_type',
        'invoice_id',
        'invoice_url',
    ];

    protected $casts = [
        'total_price'  => 'double',
        'status'       => OrderStatus::class,
        'payment_type' => PaymentType::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
