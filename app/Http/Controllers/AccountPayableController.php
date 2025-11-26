<?php

namespace App\Http\Controllers;

use App\Models\AccountPayable;
use App\Models\APPayment;
use Illuminate\Http\Request;

class AccountPayableController extends Controller
{
    public function index()
    {
        $accountsPayable = AccountPayable::with(['order.accountReceivable.submission.salesOrder', 'payments'])
            ->latest()
            ->get();
        
        return view('accounts_payable.AP_page', compact('accountsPayable'));
    }

    public function recordPayment(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        $ap = AccountPayable::findOrFail($id);

        // Validate payment doesn't exceed balance
        if ($request->amount > $ap->balance) {
            return redirect()->route('accounts-payable.index')
                ->with('error', 'Payment amount cannot exceed balance of ₱' . number_format($ap->balance, 2));
        }

        // Create payment record with auto-generated reference
        APPayment::create([
            'account_payable_id' => $ap->id,
            'amount' => $request->amount,
            'payment_method' => null,
            'reference_number' => APPayment::generateReferenceNumber(),
            'notes' => $request->notes,
            'paid_at' => now(),
        ]);

        // Update AP amounts
        $ap->paid_amount += $request->amount;
        $ap->updatePaymentStatus();

        $message = 'Payment recorded successfully!';
        if ($ap->status === 'paid') {
            $message .= ' ' . ucfirst($ap->vendor_type) . ' vendor fully paid.';
        }

        return redirect()->route('accounts-payable.index')->with('success', $message);
    }
}
