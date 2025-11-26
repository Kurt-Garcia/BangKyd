<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountPayable extends Model
{
    protected $table = 'accounts_payable';
    
    protected $fillable = [
        'ap_number',
        'order_id',
        'vendor_type',
        'quantity',
        'price_per_pcs',
        'total_amount',
        'paid_amount',
        'balance',
        'status',
        'due_date',
        'paid_at',
        'notes'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payments()
    {
        return $this->hasMany(APPayment::class, 'account_payable_id');
    }

    public static function generateAPNumber()
    {
        $year = date('Y');
        $month = date('m');
        $lastAP = self::whereYear('created_at', $year)
                      ->whereMonth('created_at', $month)
                      ->orderBy('id', 'desc')
                      ->first();
        
        $number = $lastAP ? (int)substr($lastAP->ap_number, -4) + 1 : 1;
        return 'AP' . $year . $month . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function updatePaymentStatus()
    {
        if ($this->paid_amount >= $this->total_amount) {
            $this->status = 'paid';
            $this->balance = 0;
            $this->paid_at = $this->paid_at ?? now();
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
            $this->balance = $this->total_amount - $this->paid_amount;
            $this->paid_at = null;
        } else {
            $this->status = 'pending';
            $this->balance = $this->total_amount;
            $this->paid_at = null;
        }
        $this->save();
    }
}
