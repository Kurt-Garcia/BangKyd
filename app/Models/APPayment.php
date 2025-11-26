<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class APPayment extends Model
{
    protected $table = 'ap_payments';
    
    protected $fillable = [
        'account_payable_id',
        'amount',
        'payment_method',
        'reference_number',
        'notes',
        'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function accountPayable()
    {
        return $this->belongsTo(AccountPayable::class);
    }

    public static function generateReferenceNumber()
    {
        $year = date('Y');
        $month = date('m');
        $lastPayment = self::whereYear('created_at', $year)
                          ->whereMonth('created_at', $month)
                          ->orderBy('id', 'desc')
                          ->first();
        
        $number = $lastPayment ? (int)substr($lastPayment->reference_number, -4) + 1 : 1;
        return 'APP' . $year . $month . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
