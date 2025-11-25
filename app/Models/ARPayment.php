<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ARPayment extends Model
{
    protected $table = 'ar_payments';
    
    protected $fillable = [
        'account_receivable_id',
        'amount',
        'payment_type',
        'notes',
        'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function accountReceivable()
    {
        return $this->belongsTo(AccountReceivable::class);
    }
}
