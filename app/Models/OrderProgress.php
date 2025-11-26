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
        'print_press_started_at',
        'print_press_completed_at',
        'tailoring_started_at',
        'tailoring_completed_at',
        'notes',
    ];

    protected $casts = [
        'print_press_started_at' => 'datetime',
        'print_press_completed_at' => 'datetime',
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
        $totalSteps = 2; // 2 stages: print_press and tailoring
        $completedSteps = 0;
        
        if ($this->print_press_completed_at) {
            $completedSteps++;
        }
        
        if ($this->tailoring_completed_at) {
            $completedSteps++;
        }
        
        return round(($completedSteps / $totalSteps) * 100, 2);
    }

    public function getDetailedStatus()
    {
        if ($this->current_stage === 'completed') {
            return 'Ready for Delivery';
        }
        
        $stages = [
            'print_press' => 'Print & Press',
            'tailoring' => 'Tailoring'
        ];
        
        return 'Ongoing - ' . ($stages[$this->current_stage] ?? 'Processing');
    }
}
