@extends('layouts.navbar')

@section('title', 'Sales Orders')

@push('styles')
<style>
    .bw-page .card,
    .bw-page .modal-content {
        border: 1px solid var(--bw-border, rgba(0, 0, 0, 0.10));
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .bw-page .table thead th {
        color: rgba(0, 0, 0, 0.75);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom-color: rgba(0, 0, 0, 0.10);
    }

    .bw-page .table td {
        border-top-color: rgba(0, 0, 0, 0.08);
    }

    .bw-page .so-row {
        cursor: pointer;
    }

    .bw-page .so-row:hover {
        background: rgba(0, 0, 0, 0.03);
    }

    .bw-page .form-label {
        font-weight: 700;
        color: rgba(0, 0, 0, 0.75);
    }

    .bw-page .form-control,
    .bw-page .form-select {
        border-color: rgba(0, 0, 0, 0.14);
        border-radius: 12px;
    }

    .bw-page .form-control:focus,
    .bw-page .form-select:focus {
        border-color: rgba(0, 0, 0, 0.65);
        box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.08);
    }

    .bw-page .modal-header {
        border-bottom-color: rgba(255, 255, 255, 0.14);
    }

    .bw-page .btn-dark {
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
    }
</style>
@endpush

@section('content')
<div class="bw-page">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Sales Orders</h1>
    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#createSOModal">
        <i class="bi bi-plus-circle"></i> Create SO
    </button>
</div>

<div class="card mb-4 border-0">
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
                        <button type="submit" class="btn btn-dark"><i class="bi bi-funnel"></i> Filter</button>
                        @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                            <a href="{{ route('sales-orders.index') }}" class="btn btn-outline-dark"><i class="bi bi-x-circle"></i> Clear</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0">
    <div class="card-body">
        @if($salesOrders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>SO Number</th>
                            <th>SO Name</th>
                            <th>Product</th>
                            <th>Price/Pcs</th>
                            <th>Link</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesOrders as $so)
                        <tr class="so-row" data-bs-toggle="modal" data-bs-target="#viewSOModal{{ $so->id }}" tabindex="0">
                            <td><strong>{{ $so->so_number }}</strong></td>
                            <td>{{ $so->so_name }}</td>
                            <td>
                                @if($so->is_submitted && $so->products->count() > 0)
                                    @foreach($so->products as $product)
                                        <span class="badge text-bg-dark">{{ $product->name }}</span>
                                    @endforeach
                                @elseif($so->is_submitted && $so->product)
                                    <span class="badge text-bg-dark">{{ $so->product->name }}</span>
                                @else
                                    <span class="badge bg-light text-dark border border-dark">Customer will select</span>
                                @endif
                            </td>
                            <td>
                                @if($so->is_submitted && $so->products->count() > 0)
                                    @php $priceRange = $so->products->pluck('pivot.price'); @endphp
                                    <span class="badge text-bg-dark">
                                        @if($priceRange->min() == $priceRange->max())
                                            ₱{{ number_format($priceRange->first(), 2) }}
                                        @else
                                            ₱{{ number_format($priceRange->min(), 2) }} - ₱{{ number_format($priceRange->max(), 2) }}
                                        @endif
                                    </span>
                                @elseif($so->is_submitted && $so->product)
                                    <span class="badge text-bg-dark">₱{{ number_format($so->product->price, 2) }}</span>
                                @else
                                    <span class="badge bg-light text-dark border border-dark">TBD by customer</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-dark" type="button" data-link="{{ $so->customer_link }}" onclick="copyCustomerLink(event, this)" aria-label="Copy customer link">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </td>
                            <td>
                                @if($so->is_submitted)
                                    <span class="badge text-bg-dark">Submitted</span>
                                @else
                                    <span class="badge bg-light text-dark border border-dark">Pending</span>
                                @endif
                            </td>
                            <td>{{ $so->created_at->format('M d, Y') }}</td>
                        </tr>

                        <!-- View SO Modal for each SO -->
                        <div class="modal fade" id="viewSOModal{{ $so->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-dark text-white">
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
                                                <h6 class="text-muted">Product(s)</h6>
                                                @if($so->is_submitted && $so->products->count() > 0)
                                                    @foreach($so->products as $product)
                                                        <p class="fw-bold">{{ $product->name }}</p>
                                                    @endforeach
                                                @elseif($so->is_submitted && $so->product)
                                                    <p class="fw-bold">{{ $so->product->name }}</p>
                                                @else
                                                    <p class="text-muted fst-italic">Products will be selected by customer</p>
                                                @endif
                                            </div>
                                            <div class="col-md-3">
                                                <h6 class="text-muted">Price Range</h6>
                                                @if($so->is_submitted && $so->products->count() > 0)
                                                    @php $priceRange = $so->products->pluck('pivot.price'); @endphp
                                                    @if($priceRange->min() == $priceRange->max())
                                                        <p class="fw-bold text-dark">₱{{ number_format($priceRange->first(), 2) }}</p>
                                                    @else
                                                        <p class="fw-bold text-dark">₱{{ number_format($priceRange->min(), 2) }} - ₱{{ number_format($priceRange->max(), 2) }}</p>
                                                    @endif
                                                @elseif($so->is_submitted && $so->product)
                                                    <p class="fw-bold text-dark">₱{{ number_format($so->product->price, 2) }}</p>
                                                @else
                                                    <p class="text-muted fst-italic">Varies by product selected</p>
                                                @endif
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
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button class="btn btn-outline-dark" type="button" data-link="{{ $so->customer_link }}" onclick="copyCustomerLink(event, this)">
                                            <i class="bi bi-clipboard"></i> Copy link
                                        </button>
                                        <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
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
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#createSOModal">
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
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="createSOModalLabel">Create New Sales Order</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sales-orders.store') }}" method="POST" id="createSOForm">
                @csrf
                <input type="hidden" name="unique_link" id="unique_link" value="{{ old('unique_link') }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="so_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('so_name') is-invalid @enderror" 
                               id="so_name" name="so_name" value="{{ old('so_name') }}" 
                               placeholder="e.g., Customer Name - Team Name" required>
                        <small class="text-muted">SO Number will be generated automatically. Customers will select products in the order form.</small>
                        @error('so_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">
                        <i class="bi bi-link-45deg"></i> Create Sales Order & Generate Link
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function generateAlphaNumeric(length) {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const bytes = new Uint8Array(length);
    window.crypto.getRandomValues(bytes);
    let output = '';
    for (let i = 0; i < bytes.length; i++) {
        output += chars[bytes[i] % chars.length];
    }
    return output;
}

function copyTextToClipboard(text) {
    if (navigator.clipboard && window.isSecureContext) {
        return navigator.clipboard.writeText(text);
    }

    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.top = '-1000px';
    textarea.style.left = '-1000px';
    document.body.appendChild(textarea);
    textarea.focus();
    textarea.select();

    const ok = document.execCommand('copy');
    document.body.removeChild(textarea);
    return ok ? Promise.resolve() : Promise.reject(new Error('Copy failed'));
}

function copyCustomerLink(event, button) {
    if (event) {
        event.stopPropagation();
    }

    const link = button.getAttribute('data-link') || '';
    copyTextToClipboard(link);

    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="bi bi-check"></i>';
    setTimeout(() => {
        button.innerHTML = originalHTML;
    }, 1500);
}

document.addEventListener('DOMContentLoaded', function() {
    const createForm = document.getElementById('createSOForm');
    if (createForm) {
        createForm.addEventListener('submit', function() {
            const nameInput = document.getElementById('so_name');
            const nameValue = (nameInput?.value || '').trim();
            if (!nameValue || nameValue.length > 255) {
                return;
            }

            const uniqueInput = document.getElementById('unique_link');
            if (uniqueInput && !uniqueInput.value) {
                uniqueInput.value = generateAlphaNumeric(32);
            }

            const uniqueLink = uniqueInput?.value || '';
            if (uniqueLink.length !== 32) {
                return;
            }

            const customerLink = '{{ url("/order") }}/' + uniqueLink;
            copyTextToClipboard(customerLink);
        });
    }

    document.querySelectorAll('tr.so-row').forEach(function(row) {
        row.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                row.click();
            }
        });
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
</div>
@endsection
