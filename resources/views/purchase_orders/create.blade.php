@extends('layouts.app')

@section('title', 'Create Purchase Order')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Create New Purchase Order</h1>
    <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('purchase-orders.store') }}" method="POST">
            @csrf
            
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
                          id="items" name="items" rows="4" 
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
                          id="notes" name="notes" rows="3" 
                          placeholder="Additional notes or comments">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Create Purchase Order
                </button>
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
