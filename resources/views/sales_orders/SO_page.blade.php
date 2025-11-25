@extends('layouts.navbar')

@section('title', 'Sales Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Sales Orders</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSOModal">
        <i class="bi bi-plus-circle"></i> Create SO
    </button>
</div>

<div class="card">
    <div class="card-body">
        @if($salesOrders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>SO Number</th>
                            <th>SO Name</th>
                            <th>Price/Pcs</th>
                            <th>Customer Link</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesOrders as $so)
                        <tr>
                            <td><strong>{{ $so->so_number }}</strong></td>
                            <td>{{ $so->so_name }}</td>
                            <td><span class="badge bg-info">₱{{ number_format($so->price_per_pcs, 2) }}</span></td>
                            <td>
                                <div class="input-group input-group-sm" style="max-width: 350px;">
                                    <input type="text" class="form-control" value="{{ $so->customer_link }}" id="link-{{ $so->id }}" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyLink({{ $so->id }})">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                @if($so->is_submitted)
                                    <span class="badge bg-success">Submitted</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>{{ $so->created_at->format('M d, Y') }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewSOModal{{ $so->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- View SO Modal for each SO -->
                        <div class="modal fade" id="viewSOModal{{ $so->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Sales Order Details</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <h6 class="text-muted">SO Number</h6>
                                                <p class="fw-bold">{{ $so->so_number }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="text-muted">SO Name</h6>
                                                <p>{{ $so->so_name }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="text-muted">Price per Piece</h6>
                                                <p class="fw-bold text-primary">₱{{ number_format($so->price_per_pcs, 2) }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <h6 class="text-muted">Status</h6>
                                                <p>
                                                    @if($so->is_submitted)
                                                        <span class="badge bg-success">Submitted</span>
                                                    @else
                                                        <span class="badge bg-warning">Pending</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-muted">Created</h6>
                                                <p>{{ $so->created_at->format('M d, Y h:i A') }}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="mb-3">
                                            <h6 class="text-muted"><i class="bi bi-link-45deg"></i> Customer Link</h6>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ $so->customer_link }}" id="modalLink-{{ $so->id }}" readonly>
                                                <button class="btn btn-outline-secondary" type="button" onclick="copyModalLink({{ $so->id }})">
                                                    <i class="bi bi-clipboard"></i> Copy
                                                </button>
                                            </div>
                                            <small class="text-muted">Share this link with your customer to submit their order.</small>
                                            @if(!$so->is_submitted)
                                            <div class="alert alert-warning mt-2 mb-0">
                                                <small><i class="bi bi-exclamation-triangle"></i> This link can only be used once.</small>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">No sales orders found.</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSOModal">
                    Create Your First Sales Order
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Create Sales Order Modal -->
<div class="modal fade" id="createSOModal" tabindex="-1" aria-labelledby="createSOModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSOModalLabel">Create New Sales Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sales-orders.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="so_name" class="form-label">SO Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('so_name') is-invalid @enderror" 
                               id="so_name" name="so_name" value="{{ old('so_name') }}" 
                               placeholder="e.g., Customer Name - Team Name" required>
                        <small class="text-muted">SO Number will be generated automatically</small>
                        @error('so_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="price_per_pcs" class="form-label">Price per Piece (₱) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('price_per_pcs') is-invalid @enderror" 
                               id="price_per_pcs" name="price_per_pcs" value="{{ old('price_per_pcs') }}" 
                               placeholder="e.g., 280" step="0.01" min="0" required>
                        <small class="text-muted">Enter the price per jersey</small>
                        @error('price_per_pcs')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-link-45deg"></i> Generate SO Link
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyLink(id) {
    const input = document.getElementById('link-' + id);
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value);
    
    // Show feedback
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-check"></i>';
    setTimeout(() => {
        btn.innerHTML = originalHTML;
    }, 2000);
}

function copyModalLink(id) {
    const input = document.getElementById('modalLink-' + id);
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value);
    
    // Show feedback
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-check"></i> Copied!';
    setTimeout(() => {
        btn.innerHTML = originalHTML;
    }, 2000);
}

@if($errors->any())
    // Reopen modal if there are validation errors
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('createSOModal'));
        modal.show();
    });
@endif
</script>
@endpush
@endsection
