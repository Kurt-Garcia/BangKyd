<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\AccountPayable;
use App\Models\APPayment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AccountPayableController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['accountReceivable.submission.salesOrder', 'accountsPayable.payments']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('accountReceivable.submission.salesOrder', function($subQ) use ($search) {
                      $subQ->where('so_number', 'like', "%{$search}%")
                           ->orWhere('so_name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();
        
        return view('accounts_payable.AP_page', compact('orders'));
    }

    public function store(Request $request, $orderId)
    {
        $request->validate([
            'vendor_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string'
        ]);

        $order = Order::findOrFail($orderId);

        $ap = AccountPayable::create([
            'ap_number' => AccountPayable::generateAPNumber(),
            'order_id' => $order->id,
            'vendor_type' => $request->vendor_type,
            'quantity' => 1,
            'price_per_pcs' => $request->amount,
            'total_amount' => $request->amount,
            'paid_amount' => $request->amount, // Deducted from downpayment automatically
            'balance' => 0,
            'status' => 'paid',
            'paid_at' => now(),
            'notes' => $request->notes,
        ]);

        APPayment::create([
            'account_payable_id' => $ap->id,
            'amount' => $request->amount,
            'payment_method' => 'Downpayment Deduction',
            'reference_number' => APPayment::generateReferenceNumber(),
            'notes' => 'Deducted from Order Downpayment',
            'paid_at' => now(),
        ]);

        ActivityLog::log('create', "Added payable of ₱" . number_format($request->amount, 2) . " for {$request->vendor_type} on Order {$order->order_number}", 'AccountPayable', $ap->id);

        return redirect()->route('accounts-payable.index')->with('success', 'Payable added and deducted from downpayment successfully!');
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
