<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['accountReceivable.submission.salesOrder', 'progress', 'accountsPayable'])
            ->latest()
            ->get();
        
        return view('orders.Orders_page', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:ongoing,ready_for_delivery,completed,claimed',
            'production_notes' => 'nullable|string',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        
        if ($request->production_notes) {
            $order->production_notes = $request->production_notes;
        }

        // Update timestamps based on status
        if ($request->status === 'completed' && !$order->completed_at) {
            $order->completed_at = now();
        } elseif ($request->status === 'claimed' && !$order->claimed_at) {
            $order->claimed_at = now();
        }

        $order->save();

        return redirect()->route('orders.index')
            ->with('success', 'Order status updated successfully!');
    }
}
