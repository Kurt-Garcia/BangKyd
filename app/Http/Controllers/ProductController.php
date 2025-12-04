<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->get();
        return view('products.productsPage', compact('products'));
    }

    public function create()
    {
        return redirect()->route('products.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $product = Product::create($validated);

        ActivityLog::log('product_created', "Created product: {$product->name}");

        return redirect()->route('products.index')
            ->with('success', 'Product added successfully!');
    }

    public function edit(Product $product)
    {
        return redirect()->route('products.index');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        // Handle checkbox - if not present in request, set to false
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $product->update($validated);

        ActivityLog::log('product_updated', "Updated product: {$product->name}");

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $productName = $product->name;
        $product->delete();

        ActivityLog::log('product_deleted', "Deleted product: {$productName}");

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }

    public function getProducts()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'price']);
        
        return response()->json($products);
    }
}
