<?php

namespace App\Http\Controllers;

use App\Models\AccountReceivable;
use App\Models\ARPayment;
use App\Models\SalesOrderSubmission;
use Illuminate\Http\Request;

class AccountReceivableController extends Controller
{
    public function index()
    {
        $accountReceivables = AccountReceivable::with(['submission.salesOrder'])->latest()->get();
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
