<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'account_receivable_id',
        'order_number',
        'status',
        'production_notes',
        'started_at',
        'completed_at',
        'claimed_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'claimed_at' => 'datetime',
    ];

    public function accountReceivable()
    {
        return $this->belongsTo(AccountReceivable::class);
    }

    public static function generateOrderNumber()
    {
        $year = date('Y');
        $month = date('m');
        $lastOrder = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastOrder ? (int)substr($lastOrder->order_number, -4) + 1 : 1;
        return 'ORD' . $year . $month . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
