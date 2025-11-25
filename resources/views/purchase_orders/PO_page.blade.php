@extends('layouts.navbar')

@section('title', 'Purchase Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Purchase Orders</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPOModal">
        <i class="bi bi-plus-circle"></i> Create New Purchase Order
    </button>
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
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPOModal">
                    Create Your First Purchase Order
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Create Purchase Order Modal -->
<div class="modal fade" id="createPOModal" tabindex="-1" aria-labelledby="createPOModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPOModalLabel">Create New Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('purchase-orders.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="po_number" class="form-label">PO Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('po_number') is-invalid @enderror" 
                                   id="po_number" name="po_number" value="{{ old('po_number') }}" required>
                            @error('po_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="po_date" class="form-label">PO Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('po_date') is-invalid @enderror" 
                                   id="po_date" name="po_date" value="{{ old('po_date', date('Y-m-d')) }}" required>
                            @error('po_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="supplier" class="form-label">Supplier <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('supplier') is-invalid @enderror" 
                                   id="supplier" name="supplier" value="{{ old('supplier') }}" required>
                            @error('supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="received" {{ old('status') == 'received' ? 'selected' : '' }}>Received</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="items" class="form-label">Items</label>
                        <textarea class="form-control @error('items') is-invalid @enderror" 
                                  id="items" name="items" rows="3" 
                                  placeholder="Enter order items details">{{ old('items') }}</textarea>
                        @error('items')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="total_amount" class="form-label">Total Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control @error('total_amount') is-invalid @enderror" 
                                       id="total_amount" name="total_amount" value="{{ old('total_amount') }}" required>
                                @error('total_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="2" 
                                  placeholder="Additional notes or comments">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Create Purchase Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
@push('scripts')
<script>
    // Reopen modal if there are validation errors
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('createPOModal'));
        modal.show();
    });
</script>
@endpush
@endif
@endsection
