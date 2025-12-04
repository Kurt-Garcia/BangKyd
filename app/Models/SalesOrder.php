<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SalesOrder extends Model
{
    protected $fillable = [
        'so_number',
        'so_name',
        'product_id',
        'unique_link',
        'is_submitted',
        'draft_data'
    ];

    protected $casts = [
        'is_submitted' => 'boolean',
        'draft_data' => 'array',
    ];

    // Keep old relationship for backward compatibility
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // New many-to-many relationship
    public function products()
    {
        return $this->belongsToMany(Product::class, 'sales_order_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function submission()
    {
        return $this->hasOne(SalesOrderSubmission::class);
    }

    public function submissions()
    {
        return $this->hasMany(SalesOrderSubmission::class);
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
