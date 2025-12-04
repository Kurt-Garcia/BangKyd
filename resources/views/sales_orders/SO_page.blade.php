@extends('layouts.navbar')

@section('title', 'Sales Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Sales Orders</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSOModal">
        <i class="bi bi-plus-circle"></i> Create SO
    </button>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('sales-orders.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label"><i class="bi bi-search"></i> Search</label>
                    <input type="text" class="form-control" name="search" placeholder="SO Number or Customer Name" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label"><i class="bi bi-filter"></i> Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label"><i class="bi bi-calendar"></i> From Date</label>
                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label"><i class="bi bi-calendar"></i> To Date</label>
                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                        @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                            <a href="{{ route('sales-orders.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
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
                            <th>Product</th>
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
                            <td>
                                @if($so->products->count() > 0)
                                    @foreach($so->products as $product)
                                        <span class="badge bg-secondary">{{ $product->name }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary">{{ $so->product->name ?? 'N/A' }}</span>
                                @endif
                            </td>
                            <td>
                                @if($so->products->count() > 0)
                                    @php $priceRange = $so->products->pluck('pivot.price'); @endphp
                                    <span class="badge bg-info">
                                        @if($priceRange->min() == $priceRange->max())
                                            ₱{{ number_format($priceRange->first(), 2) }}
                                        @else
                                            ₱{{ number_format($priceRange->min(), 2) }} - ₱{{ number_format($priceRange->max(), 2) }}
                                        @endif
                                    </span>
                                @else
                                    <span class="badge bg-info">₱{{ $so->product ? number_format($so->product->price, 2) : '0.00' }}</span>
                                @endif
                            </td>
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
                                            <div class="col-md-3">
                                                <h6 class="text-muted">SO Number</h6>
                                                <p class="fw-bold">{{ $so->so_number }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <h6 class="text-muted">SO Name</h6>
                                                <p>{{ $so->so_name }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <h6 class="text-muted">Product</h6>
                                                <p class="fw-bold">{{ $so->product->name ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <h6 class="text-muted">Price per Piece</h6>
                                                <p class="fw-bold text-primary">₱{{ $so->product ? number_format($so->product->price, 2) : '0.00' }}</p>
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
    <div class="modal-dialog modal-lg">
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
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0">Products <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-sm btn-success" onclick="addProductRow()">
                                <i class="bi bi-plus-circle"></i> Add Product
                            </button>
                        </div>
                        <div id="productsContainer">
                            <div class="product-row card mb-2">
                                <div class="card-body p-2">
                                    <div class="row g-2">
                                        <div class="col-md-10">
                                            <select class="form-select form-select-sm product-select" name="products[]" required>
                                                <option value="">-- Select Product --</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeProductRow(this)" style="display:none;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted">Add products for this order. Customers will select which product each player wants.</small>
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

let productRowIndex = 1;
let productsData = [];

function addProductRow() {
    const container = document.getElementById('productsContainer');
    const rowCount = container.children.length;
    
    const productRow = document.createElement('div');
    productRow.className = 'product-row card mb-2';
    productRow.innerHTML = `
        <div class="card-body p-2">
            <div class="row g-2">
                <div class="col-md-10">
                    <select class="form-select form-select-sm product-select" name="products[]" required>
                        <option value="">-- Select Product --</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeProductRow(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.appendChild(productRow);
    
    // Populate the new select with products
    const newSelect = productRow.querySelector('.product-select');
    productsData.forEach(product => {
        const option = document.createElement('option');
        option.value = product.id;
        option.textContent = product.name + ' - ₱' + parseFloat(product.price).toFixed(2);
        newSelect.appendChild(option);
    });
    
    productRowIndex++;
    updateRemoveButtons();
}

function removeProductRow(button) {
    button.closest('.product-row').remove();
    updateRemoveButtons();
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.product-row');
    rows.forEach((row, index) => {
        const removeBtn = row.querySelector('button[onclick*="removeProductRow"]');
        if (rows.length === 1) {
            removeBtn.style.display = 'none';
        } else {
            removeBtn.style.display = 'block';
        }
    });
}

// Load products when modal opens
document.addEventListener('DOMContentLoaded', function() {
    const createSOModal = document.getElementById('createSOModal');
    
    createSOModal.addEventListener('shown.bs.modal', function() {
        // Load products only if not already loaded
        if (productsData.length === 0) {
            fetch('{{ route("api.products") }}')
                .then(response => response.json())
                .then(products => {
                    productsData = products;
                    // Populate all existing selects
                    document.querySelectorAll('.product-select').forEach(select => {
                        if (select.options.length === 1) {
                            products.forEach(product => {
                                const option = document.createElement('option');
                                option.value = product.id;
                                option.textContent = product.name + ' - ₱' + parseFloat(product.price).toFixed(2);
                                select.appendChild(option);
                            });
                        }
                    });
                })
                .catch(error => console.error('Error loading products:', error));
        }
    });
});

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
