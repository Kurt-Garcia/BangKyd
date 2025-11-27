<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['accountReceivable.submission.salesOrder', 'progress', 'accountsPayable']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('accountReceivable.submission.salesOrder', function($q) use ($search) {
                $q->where('so_number', 'like', "%{$search}%")
                  ->orWhere('so_name', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->get();
        
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

        ActivityLog::log('update', "Updated order {$order->order_number} status to: {$request->status}", 'Order', $order->id);

        return redirect()->route('orders.index')
            ->with('success', 'Order status updated successfully!');
    }
}
