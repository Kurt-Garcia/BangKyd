<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProgress extends Model
{
    protected $table = 'order_progress';

    protected $fillable = [
        'order_id',
        'unique_link',
        'current_stage',
        'total_quantity',
        'printing_done',
        'press_done',
        'tailoring_done',
        'printing_started_at',
        'printing_completed_at',
        'press_started_at',
        'press_completed_at',
        'tailoring_started_at',
        'tailoring_completed_at',
        'notes',
    ];

    protected $casts = [
        'printing_started_at' => 'datetime',
        'printing_completed_at' => 'datetime',
        'press_started_at' => 'datetime',
        'press_completed_at' => 'datetime',
        'tailoring_started_at' => 'datetime',
        'tailoring_completed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
