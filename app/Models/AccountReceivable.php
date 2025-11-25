<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountReceivable extends Model
{
    protected $fillable = [
        'sales_order_submission_id',
        'ar_number',
        'status',
        'total_amount',
        'paid_amount',
        'balance',
        'confirmed_at'
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
    ];

    public function submission()
    {
        return $this->belongsTo(SalesOrderSubmission::class, 'sales_order_submission_id');
    }

    public function payments()
    {
        return $this->hasMany(ARPayment::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public static function generateARNumber()
    {
        $year = date('Y');
        $month = date('m');
        $lastAR = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastAR ? (int)substr($lastAR->ar_number, -4) + 1 : 1;
        return 'AR' . $year . $month . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function updatePaymentStatus()
    {
        if ($this->paid_amount >= $this->total_amount) {
            $this->status = 'paid';
            $this->balance = 0;
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
            $this->balance = $this->total_amount - $this->paid_amount;
        } else {
            $this->status = 'pending';
            $this->balance = $this->total_amount;
        }
        $this->save();
    }
}
