<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'image',
        'description',
        'quantity',
        'date_created',
        'category',
        'sku',
        'embedding'
    ];

    protected $casts = [
        'embedding' => 'array', // JSON → PHP array
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
