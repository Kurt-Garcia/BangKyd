<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SalesOrder extends Model
{
    protected $fillable = [
        'so_number',
        'so_name',
        'price_per_pcs',
        'unique_link',
        'is_submitted'
    ];

    protected $casts = [
        'is_submitted' => 'boolean',
    ];

    public function submission()
    {
        return $this->hasOne(SalesOrderSubmission::class);
    }

    public static function generateSONumber()
    {
        $year = date('Y');
        $month = date('m');
        $lastSO = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastSO ? (int)substr($lastSO->so_number, -4) + 1 : 1;
        return 'SO' . $year . $month . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public static function generateUniqueLink()
    {
        return Str::random(32);
    }

    public function getCustomerLinkAttribute()
    {
        return url('/order/' . $this->unique_link);
    }
}
