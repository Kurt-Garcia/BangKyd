<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProgress;
use App\Models\AccountPayable;
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
            'current_stage' => 'print_press',
            'total_quantity' => $order->accountReceivable->submission->total_quantity,
            'print_press_started_at' => now(),
        ]);
        
        // Determine partner pricing based on customer price
        // If customer price > 200, assume upper jersey (150 total for print & press)
        // If customer price <= 200, assume lower jersey (160 total for print & press)
        $customerPrice = $order->accountReceivable->submission->salesOrder->product->price;
        $partnerPrice = $customerPrice > 200 ? 150 : 160;
        $quantity = $order->accountReceivable->submission->total_quantity;
        
        // Create single AP for Print & Press (one partner handles both)
        AccountPayable::create([
            'ap_number' => AccountPayable::generateAPNumber(),
            'order_id' => $order->id,
            'vendor_type' => 'printing',
            'quantity' => $quantity,
            'price_per_pcs' => $partnerPrice,
            'total_amount' => $quantity * $partnerPrice,
            'paid_amount' => 0,
            'balance' => $quantity * $partnerPrice,
            'status' => 'pending',
            'due_date' => now()->addDays(14), // Payment due in 14 days
            'notes' => 'Price includes both printing and press work',
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
            'stage' => 'required|in:print_press,tailoring',
            'notes' => 'nullable|string',
        ]);
        
        $stage = $request->stage;
        
        // Mark stage as completed
        $progress->{$stage . '_completed_at'} = now();
        
        // Move to next stage
        if ($stage === 'print_press') {
            $progress->current_stage = 'tailoring';
            $progress->tailoring_started_at = now();
        } elseif ($stage === 'tailoring') {
            $progress->current_stage = 'completed';
            
            // Update main order status to ready_for_delivery
            $order = $progress->order;
            $order->status = 'ready_for_delivery';
            $order->save();
        }
        
        if ($request->notes) {
            $progress->notes = $request->notes;
        }
        
        $progress->save();
        
        return redirect()->back()->with('success', 'Stage marked as completed!');
    }
}
