<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $fillable = ['user_id', 'total_amount', 'order_status', 'payment_status', 'paypal_order_id'];
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
}

