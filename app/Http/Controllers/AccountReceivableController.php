<?php

namespace App\Http\Controllers;

use App\Models\AccountReceivable;
use App\Models\ARPayment;
use App\Models\SalesOrderSubmission;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AccountReceivableController extends Controller
{
    public function index(Request $request)
    {
        $query = AccountReceivable::with(['submission.salesOrder']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ar_number', 'like', "%{$search}%")
                  ->orWhereHas('submission.salesOrder', function($subQ) use ($search) {
                      $subQ->where('so_number', 'like', "%{$search}%")
                           ->orWhere('so_name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('confirmed_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('confirmed_at', '<=', $request->date_to);
        }

        $accountReceivables = $query->latest()->get();
        return view('account_receivables.AR_page', compact('accountReceivables'));
    }

    public function confirmOrder(Request $request, $submissionId)
    {
        $submission = SalesOrderSubmission::with('salesOrder')->findOrFail($submissionId);

        // Create AR record
        $ar = AccountReceivable::create([
            'sales_order_submission_id' => $submission->id,
            'ar_number' => AccountReceivable::generateARNumber(),
            'status' => 'pending',
            'total_amount' => $submission->total_amount,
            'paid_amount' => 0,
            'balance' => $submission->total_amount,
            'confirmed_at' => now(),
        ]);

        ActivityLog::log('create', "Confirmed order and created AR: {$ar->ar_number} for SO: {$submission->salesOrder->so_number}", 'AccountReceivable', $ar->id);

        return redirect()->route('account-receivables.index')
            ->with('success', 'Order confirmed! AR Number: ' . $ar->ar_number);
    }

    public function recordPayment(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        $ar = AccountReceivable::findOrFail($id);

        // Validate amount doesn't exceed balance
        if ($request->amount > $ar->balance) {
            return redirect()->back()->with('error', 'Payment amount cannot exceed balance.');
        }

        // Auto-detect payment type based on amount
        $paymentType = ($request->amount >= $ar->balance) ? 'full' : 'partial';

        // Record payment
        ARPayment::create([
            'account_receivable_id' => $ar->id,
            'amount' => $request->amount,
            'payment_type' => $paymentType,
            'notes' => $request->notes,
            'paid_at' => now(),
        ]);

        // Update AR
        $ar->paid_amount += $request->amount;
        $ar->updatePaymentStatus();

        ActivityLog::log('create', "Recorded payment of ₱" . number_format($request->amount, 2) . " for AR: {$ar->ar_number}", 'ARPayment', $ar->id);

        // Create Order record if it doesn't exist (for partial or full payment)
        if (!$ar->order) {
            \App\Models\Order::create([
                'account_receivable_id' => $ar->id,
                'order_number' => \App\Models\Order::generateOrderNumber(),
                'status' => 'ongoing',
                'started_at' => now(),
            ]);
            // Reload the order relationship
            $ar->load('order');
        }
        
        // If fully paid and order exists, check if ready_for_delivery and complete it
        if ($ar->status === 'paid') {
            // Reload order to get fresh status
            $ar->load('order');
            
            if ($ar->order && $ar->order->status === 'ready_for_delivery') {
                $order = $ar->order;
                $order->status = 'completed';
                $order->completed_at = now();
                $order->save();
            }
        }

        $message = 'Payment recorded successfully!';
        if ($ar->status === 'paid' && $ar->order && $ar->order->status === 'completed') {
            $message .= ' Order completed and ready for claiming.';
        } else {
            $message .= ' Order in production.';
        }

        return redirect()->route('account-receivables.index')->with('success', $message);
    }
}
