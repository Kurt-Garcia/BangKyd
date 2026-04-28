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
        $salesOrder = SalesOrder::with(['product', 'products'])->where('unique_link', $uniqueLink)->firstOrFail();

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
            'images' => 'nullable|array|max:3',
            'images.*' => 'nullable|image|max:5120',
            'deadline_date' => 'required|date|after_or_equal:today',
            'players' => 'required|array|min:1',
            'players.*.product_id' => 'required|exists:products,id',
            'players.*.jersey_name' => 'required|string|max:255',
            'players.*.jersey_number' => ['required', 'string', 'regex:/^[+-]?\d+$/'],
            'players.*.jersey_size' => 'required|string',
        ]);

        $imagePaths = [];
        $oldDraftImages = [];
        if (is_array($salesOrder->draft_data ?? null) && isset($salesOrder->draft_data['images']) && is_array($salesOrder->draft_data['images'])) {
            $oldDraftImages = $salesOrder->draft_data['images'];
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('order-images', 'public');
                $imagePaths[] = $path;
            }
            foreach ($oldDraftImages as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        } elseif ($salesOrder->draft_data && isset($salesOrder->draft_data['images'])) {
            $imagePaths = $oldDraftImages;
        }

        // Group players by product and calculate quantities
        $productQuantities = [];
        $totalAmount = 0;
        $players = $request->input('players', []);
        
        foreach ($players as $player) {
            $productId = $player['product_id'];
            if (!isset($productQuantities[$productId])) {
                $productQuantities[$productId] = 0;
            }
            $productQuantities[$productId]++;
        }
        
        // Attach products to Sales Order and calculate total amount
        // First, clear any existing product relationships
        $salesOrder->products()->detach();
        
        foreach ($productQuantities as $productId => $quantity) {
            $product = \App\Models\Product::find($productId);
            if ($product) {
                // Attach product with quantity and price
                $salesOrder->products()->attach($productId, [
                    'quantity' => $quantity,
                    'price' => $product->price, // Store current price
                ]);
                // Add to total amount
                $billableQty = \App\Models\SalesOrderSubmission::billableQtyFor((int) $quantity);
                $totalAmount += $billableQty * (float) $product->price;
            }
        }
        
        // Update Sales Order with first product for backward compatibility
        if (!empty($productQuantities)) {
            $salesOrder->update([
                'product_id' => array_keys($productQuantities)[0]
            ]);
        }
        
        $totalQuantity = count($players);
        $downPayment = $totalAmount * 0.5; // 50% down payment
        $balance = $totalAmount - $downPayment;

        // Create order submission
        $submission = SalesOrderSubmission::create([
            'sales_order_id' => $salesOrder->id,
            'images' => $imagePaths,
            'players' => $players,
            'total_quantity' => $totalQuantity,
            'total_amount' => $totalAmount,
            'down_payment' => $downPayment,
            'balance' => $balance,
            'is_paid' => false,
            'submitted_at' => now(),
            'deadline_date' => $request->deadline_date,
        ]);

        // Mark SO as submitted and clear draft data
        $salesOrder->update([
            'is_submitted' => true,
            'draft_data' => null
        ]);

        return view('invoice', compact('submission', 'salesOrder'));
    }

    public function index(Request $request)
    {
        // Only show submissions that haven't been confirmed to AR yet
        $query = SalesOrderSubmission::with('salesOrder.product')
            ->whereDoesntHave('accountReceivable');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('salesOrder', function($q) use ($search) {
                $q->where('so_number', 'like', "%{$search}%")
                  ->orWhere('so_name', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('submitted_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('submitted_at', '<=', $request->date_to);
        }

        $submissions = $query->latest()->get();
        return view('receiving_report', compact('submissions'));
    }

    public function showInvoice($id)
    {
        $submission = SalesOrderSubmission::with('salesOrder.product')->findOrFail($id);
        $salesOrder = $submission->salesOrder;
        return view('invoice', compact('submission', 'salesOrder'));
    }

    public function allowResubmission(Request $request, $id)
    {
        $submission = SalesOrderSubmission::with('salesOrder.product')->findOrFail($id);
        $salesOrder = $submission->salesOrder;

        // Check if already moved to AR
        if ($submission->accountReceivable) {
            return redirect()->back()->with('error', 'Cannot allow resubmission - order has already been confirmed to Account Receivables.');
        }

        // Save draft data before deleting (preserve customer's previous input)
        $draftData = [
            'players' => $submission->players,
            'images' => $submission->images,
            'deadline_date' => $submission->deadline_date?->toDateString(),
        ];
        $salesOrder->update(['draft_data' => $draftData]);

        // Note: Keep images in storage for now so customer can see them
        // They will be deleted when new submission is made or can be cleaned up later

        // Delete the submission
        $submission->delete();

        // Unlock the sales order for resubmission
        $salesOrder->update(['is_submitted' => false]);

        return redirect()->route('receiving-report')->with('success', 
            'Resubmission allowed for ' . $salesOrder->so_number . '. Customer can now resubmit their order with previous data preserved.');
    }
}
