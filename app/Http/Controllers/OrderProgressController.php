<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProgress;
use Illuminate\Http\Request;

class OrderProgressController extends Controller
{
    // Admin: Generate link for partner
    public function generateLink($orderId)
    {
        $order = Order::with('accountReceivable.submission')->findOrFail($orderId);
        
        // Check if progress link already exists
        if ($order->progress) {
            return redirect()->route('orders.index')
                ->with('error', 'Progress tracking link already exists for this order.');
        }
        
        // Create progress tracking record
        $progress = OrderProgress::create([
            'order_id' => $order->id,
            'unique_link' => OrderProgress::generateUniqueLink(),
            'current_stage' => 'printing',
            'total_quantity' => $order->accountReceivable->submission->total_quantity,
            'printing_started_at' => now(),
        ]);
        
        // Update order status
        $order->status = 'ongoing';
        $order->save();
        
        return redirect()->route('orders.index')
            ->with('success', 'Partner tracking link generated successfully!');
    }
    
    // Public: Show progress form for partner
    public function showProgress($uniqueLink)
    {
        $progress = OrderProgress::with('order.accountReceivable.submission.salesOrder')
            ->where('unique_link', $uniqueLink)
            ->firstOrFail();
        
        return view('order_progress.partner_view', compact('progress'));
    }
    
    // Public: Update progress from partner
    public function updateProgress(Request $request, $uniqueLink)
    {
        $progress = OrderProgress::where('unique_link', $uniqueLink)->firstOrFail();
        
        $request->validate([
            'stage' => 'required|in:printing,press,tailoring',
            'quantity_done' => 'required|integer|min:0|max:' . $progress->total_quantity,
            'notes' => 'nullable|string',
        ]);
        
        $stage = $request->stage;
        $quantityDone = $request->quantity_done;
        
        // Update the specific stage progress
        $progress->{$stage . '_done'} = $quantityDone;
        
        // Check if current stage is completed
        if ($quantityDone >= $progress->total_quantity) {
            $progress->{$stage . '_completed_at'} = now();
            
            // Move to next stage
            if ($stage === 'printing') {
                $progress->current_stage = 'press';
                $progress->press_started_at = now();
            } elseif ($stage === 'press') {
                $progress->current_stage = 'tailoring';
                $progress->tailoring_started_at = now();
            } elseif ($stage === 'tailoring') {
                $progress->current_stage = 'completed';
                
                // Update main order status to ready_for_delivery
                $order = $progress->order;
                $order->status = 'ready_for_delivery';
                $order->save();
            }
        }
        
        if ($request->notes) {
            $progress->notes = $request->notes;
        }
        
        $progress->save();
        
        return redirect()->back()->with('success', 'Progress updated successfully!');
    }
}
