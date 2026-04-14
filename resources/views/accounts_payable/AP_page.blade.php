@extends('layouts.navbar')

@section('title', 'Accounts Payable')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="bi bi-wallet2"></i> Accounts Payable</h1>
    <div>
        <span class="badge bg-primary">{{ $orders->count() }} Total Orders</span>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('accounts-payable.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label"><i class="bi bi-search"></i> Search</label>
                    <input type="text" class="form-control" name="search" placeholder="Order/SO Number or Customer" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                        @if(request()->hasAny(['search']))
                            <a href="{{ route('accounts-payable.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @forelse($orders as $order)
    @php
        $downpayment = $order->accountReceivable ? $order->accountReceivable->paid_amount : 0;
        $totalPayables = $order->accountsPayable->sum('total_amount');
        $remaining = $downpayment - $totalPayables;
    @endphp
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-start border-4 border-info" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1">{{ $order->order_number }}</h5>
                        <p class="text-muted small mb-0">{{ $order->accountReceivable->submission->salesOrder->so_number ?? '' }}</p>
                    </div>
                    <span class="badge {{ $remaining >= 0 ? 'bg-success' : 'bg-danger' }}">
                        ₱{{ number_format($remaining, 2) }} Rem.
                    </span>
                </div>

                <h6 class="mb-3">{{ $order->accountReceivable->submission->salesOrder->so_name ?? 'Unknown Customer' }}</h6>
                
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <small class="text-muted d-block">Downpayment</small>
                        <strong class="text-success">₱{{ number_format($downpayment, 2) }}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Payables</small>
                        <strong class="text-danger">₱{{ number_format($totalPayables, 2) }}</strong>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light text-muted">
                <small><i class="bi bi-wallet"></i> Click to view & add payables</small>
            </div>
        </div>
    </div>

    <!-- Order Payables Modal -->
    <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <div>
                        <h5 class="modal-title">{{ $order->order_number }} - Payables Details</h5>
                        <small>{{ $order->accountReceivable->submission->salesOrder->so_name ?? '' }}</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-title"><i class="bi bi-cash"></i> Total Downpayment</h6>
                                    <h3>₱{{ number_format($downpayment, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-title"><i class="bi bi-cart-dash"></i> Total Payables</h6>
                                    <h3>₱{{ number_format($totalPayables, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card {{ $remaining >= 0 ? 'bg-primary' : 'bg-warning' }} text-white h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-title"><i class="bi bi-wallet2"></i> Remaining Downpayment</h6>
                                    <h3>₱{{ number_format($remaining, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <h6 class="mb-0"><i class="bi bi-list-ul"></i> Payables List</h6>
                        <button type="button" class="btn btn-sm btn-primary" onclick="switchToAddPayableModal{{ $order->id }}()">
                            <i class="bi bi-plus-circle"></i> Add Payable
                        </button>
                    </div>

                    @if($order->accountsPayable->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->accountsPayable as $ap)
                                    <tr>
                                        <td>{{ $ap->created_at->format('M d, Y h:i A') }}</td>
                                        <td><span class="badge bg-secondary">{{ ucfirst($ap->vendor_type) }}</span></td>
                                        <td class="text-danger fw-bold">₱{{ number_format($ap->total_amount, 2) }}</td>
                                        <td>{{ $ap->notes ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No payables recorded for this order yet.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Payable Modal -->
    <div class="modal fade" id="addPayableModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Add Payable</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('accounts-payable.store', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Available Downpayment:</strong> ₱{{ number_format($remaining, 2) }}
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Payable Type <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="vendor_type" placeholder="e.g., Cloth, Print, Press, Paper, Plastic" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Amount (₱) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount" step="0.01" min="0.01" required>
                            <small class="text-muted">This will be deducted from the order's downpayment.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" name="notes" rows="2" placeholder="Any additional details..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="backToOrderModal{{ $order->id }}()">Back</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Save Payable
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function switchToAddPayableModal{{ $order->id }}() {
        const orderModalEl = document.getElementById('orderModal{{ $order->id }}');
        const addPayableModalEl = document.getElementById('addPayableModal{{ $order->id }}');
        
        let orderModal = bootstrap.Modal.getInstance(orderModalEl);
        orderModal.hide();
        
        orderModalEl.addEventListener('hidden.bs.modal', function openAdd() {
            const addModal = new bootstrap.Modal(addPayableModalEl);
            addModal.show();
            orderModalEl.removeEventListener('hidden.bs.modal', openAdd);
        }, { once: true });
    }

    function backToOrderModal{{ $order->id }}() {
        const orderModalEl = document.getElementById('orderModal{{ $order->id }}');
        const addPayableModalEl = document.getElementById('addPayableModal{{ $order->id }}');
        
        // Modal instance is handled by bootstrap data-bs-dismiss on the button,
        // so we just need to listen for when it's hidden to show the order modal again.
        addPayableModalEl.addEventListener('hidden.bs.modal', function openOrder() {
            const orderModal = new bootstrap.Modal(orderModalEl);
            orderModal.show();
            addPayableModalEl.removeEventListener('hidden.bs.modal', openOrder);
        }, { once: true });
    }
    </script>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-wallet2 text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">No orders found.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>
@endsection
