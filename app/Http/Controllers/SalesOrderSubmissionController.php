<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\SalesOrderSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SalesOrderSubmissionController extends Controller
{
    public function showForm($uniqueLink)
    {
        $salesOrder = SalesOrder::where('unique_link', $uniqueLink)->firstOrFail();

        if ($salesOrder->is_submitted) {
            $submission = $salesOrder->submission;
            return view('order_submitted', compact('submission', 'salesOrder'));
        }

        return view('order_form', compact('salesOrder'));
    }

    public function submit(Request $request, $uniqueLink)
    {
        $salesOrder = SalesOrder::where('unique_link', $uniqueLink)->firstOrFail();

        if ($salesOrder->is_submitted) {
            return redirect()->back()->with('error', 'This order has already been submitted.');
        }

        $request->validate([
            'images.*' => 'nullable|image|max:5120',
            'players.*.full_name' => 'required|string|max:255',
            'players.*.jersey_name' => 'required|string|max:255',
            'players.*.jersey_number' => 'required|integer',
            'players.*.jersey_size' => 'required|string',
        ]);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('order-images', 'public');
                $imagePaths[] = $path;
            }
        }

        // Calculate pricing
        $totalQuantity = count($request->players);
        $totalAmount = $totalQuantity * $salesOrder->price_per_pcs;
        $downPayment = $totalAmount * 0.5; // 50% down payment
        $balance = $totalAmount - $downPayment;

        // Create order submission
        $submission = SalesOrderSubmission::create([
            'sales_order_id' => $salesOrder->id,
            'images' => $imagePaths,
            'players' => $request->players,
            'total_quantity' => $totalQuantity,
            'total_amount' => $totalAmount,
            'down_payment' => $downPayment,
            'balance' => $balance,
            'is_paid' => false,
            'submitted_at' => now(),
        ]);

        // Mark SO as submitted
        $salesOrder->update(['is_submitted' => true]);

        return view('invoice', compact('submission', 'salesOrder'));
    }

    public function index()
    {
        // Only show submissions that haven't been confirmed to AR yet
        $submissions = SalesOrderSubmission::with('salesOrder')
            ->whereDoesntHave('accountReceivable')
            ->latest()
            ->get();
        return view('receiving_report', compact('submissions'));
    }

    public function showInvoice($id)
    {
        $submission = SalesOrderSubmission::with('salesOrder')->findOrFail($id);
        $salesOrder = $submission->salesOrder;
        return view('invoice', compact('submission', 'salesOrder'));
    }
}
