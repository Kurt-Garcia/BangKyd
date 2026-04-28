<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderSubmission extends Model
{
    public const FREEBIE_THRESHOLD_QTY = 15;

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
        'submitted_at',
        'deadline_date'
    ];

    protected $casts = [
        'images' => 'array',
        'players' => 'array',
        'submitted_at' => 'datetime',
        'deadline_date' => 'date',
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

    public static function freeQtyFor(int $orderedQty, ?int $thresholdQty = null): int
    {
        $thresholdQty = $thresholdQty ?? self::FREEBIE_THRESHOLD_QTY;
        if ($thresholdQty <= 0) {
            return 0;
        }
        if ($orderedQty <= 0) {
            return 0;
        }
        return intdiv($orderedQty, $thresholdQty);
    }

    public static function billableQtyFor(int $orderedQty, ?int $thresholdQty = null): int
    {
        $freeQty = self::freeQtyFor($orderedQty, $thresholdQty);
        $billableQty = $orderedQty - $freeQty;
        return $billableQty > 0 ? $billableQty : 0;
    }
}
