<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function salesOrders()
    {
        return $this->belongsToMany(SalesOrder::class, 'sales_order_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
