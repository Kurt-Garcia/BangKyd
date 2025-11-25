@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Purchase Orders</h1>
    <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create New Purchase Order
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($purchaseOrders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrders as $po)
                        <tr>
                            <td>{{ $po->po_number }}</td>
                            <td>{{ $po->supplier }}</td>
                            <td>{{ $po->po_date }}</td>
                            <td>${{ number_format($po->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $po->status_color }}">
                                    {{ ucfirst($po->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('purchase-orders.show', $po->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('purchase-orders.edit', $po->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $purchaseOrders->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">No purchase orders found.</p>
                <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">Create Your First Purchase Order</a>
            </div>
        @endif
    </div>
</div>
@endsection
