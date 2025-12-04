<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'price',
        'quantity',
        'total',
    ];

    protected $casts = [
        'price' => 'float',
        'total' => 'float',
        'quantity' => 'int',
        'product_id' => 'int',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
