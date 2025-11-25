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

    public static function generateUniqueLink()
    {
        return 'OPR' . date('Ymd') . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
    }

    public function getProgressPercentage()
    {
        if ($this->total_quantity == 0) return 0;
        
        $totalSteps = $this->total_quantity * 3; // 3 stages
        $completedSteps = $this->printing_done + $this->press_done + $this->tailoring_done;
        
        return round(($completedSteps / $totalSteps) * 100, 2);
    }

    public function getDetailedStatus()
    {
        if ($this->current_stage === 'completed') {
            return 'Ready for Delivery';
        }
        
        $stages = [
            'printing' => 'Printing',
            'press' => 'Press',
            'tailoring' => 'Tailoring'
        ];
        
        return 'Ongoing - ' . ($stages[$this->current_stage] ?? 'Processing');
    }
}
