<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'order_status',
        'payment_status',
        'paypal_order_id',
        'recipient_name',
        'shipping_address'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function histories()
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function getRouteKeyName()
    {
        return 'order_number';
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

}
