<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderSubmission extends Model
{
    protected $fillable = [
        'sales_order_id',
        'images',
        'players',
        'total_quantity',
        'total_amount',
        'down_payment',
        'balance',
        'is_paid',
        'paid_at',
        'submitted_at'
    ];

    protected $casts = [
        'images' => 'array',
        'players' => 'array',
        'submitted_at' => 'datetime',
        'paid_at' => 'datetime',
        'is_paid' => 'boolean',
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function accountReceivable()
    {
        return $this->hasOne(AccountReceivable::class);
    }
}
