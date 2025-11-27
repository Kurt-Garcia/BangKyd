<?php

namespace App\Http\Controllers;

use App\Models\AccountPayable;
use App\Models\APPayment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AccountPayableController extends Controller
{
    public function index(Request $request)
    {
        $query = AccountPayable::with(['order.accountReceivable.submission.salesOrder', 'payments']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ap_number', 'like', "%{$search}%")
                  ->orWhereHas('order.accountReceivable.submission.salesOrder', function($subQ) use ($search) {
                      $subQ->where('so_number', 'like', "%{$search}%");
                  });
            });
        }

        // Vendor type filter
        if ($request->filled('vendor_type')) {
            $query->where('vendor_type', $request->vendor_type);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        $accountsPayable = $query->latest()->get();
        
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

        ActivityLog::log('create', "Recorded payment of ₱" . number_format($request->amount, 2) . " for AP: {$ap->ap_number} ({$ap->vendor_type})", 'APPayment', $ap->id);

        $message = 'Payment recorded successfully!';
        if ($ap->status === 'paid') {
            $message .= ' ' . ucfirst($ap->vendor_type) . ' vendor fully paid.';
        }

        return redirect()->route('accounts-payable.index')->with('success', $message);
    }
}
