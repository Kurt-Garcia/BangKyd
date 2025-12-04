<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesOrder::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('so_number', 'like', "%{$search}%")
                  ->orWhere('so_name', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status == 'pending') {
                $query->where('is_submitted', false);
            } elseif ($request->status == 'submitted') {
                $query->where('is_submitted', true);
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $salesOrders = $query->with(['product', 'products'])->latest()->get();
        
        return view('sales_orders.SO_page', compact('salesOrders'));
    }

    public function create()
    {
        return view('sales_orders.SO_page');
    }

    public function store(Request $request)
    {
        $request->validate([
            'so_name' => 'required|string|max:255',
            'products' => 'required|array|min:1',
            'products.*' => 'required|exists:products,id',
        ]);

        $so = SalesOrder::create([
            'so_number' => SalesOrder::generateSONumber(),
            'so_name' => $request->so_name,
            'product_id' => $request->products[0], // Keep first product for backward compatibility
            'unique_link' => SalesOrder::generateUniqueLink(),
            'is_submitted' => false,
        ]);

        // Attach products with prices (quantity will be set when customer submits)
        foreach ($request->products as $productId) {
            $product = \App\Models\Product::find($productId);
            $so->products()->attach($productId, [
                'quantity' => 0, // Placeholder - will be updated when customer submits
                'price' => $product->price, // Store price at time of order
            ]);
        }

        ActivityLog::log('create', "Created Sales Order: {$so->so_number} - {$so->so_name}", 'SalesOrder', $so->id);

        return redirect()->route('sales-orders.index')
            ->with('success', 'Sales Order created successfully! Link: ' . $so->customer_link);
    }

    public function show($id)
    {
        // No longer needed - view is now in modal
        return redirect()->route('sales-orders.index');
    }

    public function edit($id)
    {
        // TODO: Implement edit logic if needed
        return redirect()->route('sales-orders.index');
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement update logic if needed
        return redirect()->route('sales-orders.index');
    }

    public function destroy($id)
    {
        // TODO: Implement delete logic if needed
        return redirect()->route('sales-orders.index');
    }
}
