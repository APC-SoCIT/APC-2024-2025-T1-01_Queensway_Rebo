<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'image',
        'description',
        'quantity',
        'date_created',
        'category',
        'sku', // Make sure this is fillable
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->sku)) {
                do {
                    $sku = strtoupper(Str::random(8));
                } while (self::where('sku', $sku)->exists());

                $product->sku = $sku;
            }
        });
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
