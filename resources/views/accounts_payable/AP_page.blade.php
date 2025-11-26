@extends('layouts.navbar')

@section('title', 'Accounts Payable')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="bi bi-wallet2"></i> Accounts Payable</h1>
    <div>
        <span class="badge bg-danger me-2">₱{{ number_format($accountsPayable->where('status', '!=', 'paid')->sum('balance'), 2) }} Outstanding</span>
        <span class="badge bg-primary">{{ $accountsPayable->count() }} Total AP</span>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('accounts-payable.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label"><i class="bi bi-search"></i> Search</label>
                    <input type="text" class="form-control" name="search" placeholder="AP/SO Number" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label"><i class="bi bi-filter"></i> Vendor Type</label>
                    <select class="form-select" name="vendor_type">
                        <option value="">All Vendors</option>
                        <option value="printing" {{ request('vendor_type') == 'printing' ? 'selected' : '' }}>Printing</option>
                        <option value="press" {{ request('vendor_type') == 'press' ? 'selected' : '' }}>Press</option>
                        <option value="tailoring" {{ request('vendor_type') == 'tailoring' ? 'selected' : '' }}>Tailoring</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label"><i class="bi bi-filter"></i> Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label"><i class="bi bi-calendar"></i> From Date</label>
                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                        @if(request()->hasAny(['search', 'vendor_type', 'status', 'date_from']))
                            <a href="{{ route('accounts-payable.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($accountsPayable->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>AP Number</th>
                            <th>Order Number</th>
                            <th>SO Number</th>
                            <th>Vendor Type</th>
                            <th>Quantity</th>
                            <th>Price/Pcs</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accountsPayable as $ap)
                        <tr>
                            <td><strong>{{ $ap->ap_number }}</strong></td>
                            <td>{{ $ap->order->order_number }}</td>
                            <td>{{ $ap->order->accountReceivable->submission->salesOrder->so_number }}</td>
                            <td>
                                @if($ap->vendor_type === 'printing')
                                    <span class="badge bg-primary"><i class="bi bi-printer"></i> Print & Press</span>
                                @else
                                    <span class="badge bg-warning"><i class="bi bi-layers"></i> Press</span>
                                @endif
                            </td>
                            <td>{{ $ap->quantity }} pcs</td>
                            <td>₱{{ number_format($ap->price_per_pcs, 2) }}</td>
                            <td>₱{{ number_format($ap->total_amount, 2) }}</td>
                            <td>₱{{ number_format($ap->paid_amount, 2) }}</td>
                            <td>₱{{ number_format($ap->balance, 2) }}</td>
                            <td>
                                @if($ap->status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($ap->status === 'partial')
                                    <span class="badge bg-warning">Partial</span>
                                @else
                                    <span class="badge bg-danger">Pending</span>
                                @endif
                            </td>
                            <td>{{ $ap->due_date ? $ap->due_date->format('M d, Y') : '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewAPModal{{ $ap->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modals (outside table) -->
            @foreach($accountsPayable as $ap)
            <!-- View AP Modal -->
            <div class="modal fade" id="viewAPModal{{ $ap->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <div>
                                <h5 class="modal-title">{{ $ap->ap_number }} - Account Payable Details</h5>
                                <small>{{ $ap->order->order_number }} - {{ $ap->order->accountReceivable->submission->salesOrder->so_number }}</small>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="border-bottom pb-2"><i class="bi bi-info-circle"></i> AP Information</h6>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <p class="mb-2"><strong>AP Number:</strong> {{ $ap->ap_number }}</p>
                                            <p class="mb-2"><strong>Order Number:</strong> {{ $ap->order->order_number }}</p>
                                            <p class="mb-2"><strong>SO Number:</strong> {{ $ap->order->accountReceivable->submission->salesOrder->so_number }}</p>
                                            <p class="mb-2"><strong>Customer:</strong> {{ $ap->order->accountReceivable->submission->salesOrder->so_name }}</p>
                                            <p class="mb-2">
                                                <strong>Vendor Type:</strong> 
                                                @if($ap->vendor_type === 'printing')
                                                    <span class="badge bg-primary"><i class="bi bi-printer"></i> Print & Press Partner</span>
                                                @else
                                                    <span class="badge bg-warning"><i class="bi bi-layers"></i> Press</span>
                                                @endif
                                            </p>
                                            <p class="mb-2"><strong>Quantity:</strong> {{ $ap->quantity }} jerseys</p>
                                            <p class="mb-2"><strong>Price per piece:</strong> ₱{{ number_format($ap->price_per_pcs, 2) }}</p>
                                            <p class="mb-2"><strong>Due Date:</strong> {{ $ap->due_date ? $ap->due_date->format('M d, Y') : '-' }}</p>
                                            <p class="mb-0">
                                                <strong>Status:</strong> 
                                                @if($ap->status === 'paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @elseif($ap->status === 'partial')
                                                    <span class="badge bg-warning">Partial</span>
                                                @else
                                                    <span class="badge bg-danger">Pending</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="border-bottom pb-2"><i class="bi bi-cash-stack"></i> Payment Summary</h6>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <p class="mb-2"><strong>Total Amount:</strong> <span class="text-primary fs-5">₱{{ number_format($ap->total_amount, 2) }}</span></p>
                                            <p class="mb-2"><strong>Paid Amount:</strong> <span class="text-success">₱{{ number_format($ap->paid_amount, 2) }}</span></p>
                                            <p class="mb-0"><strong>Balance:</strong> <span class="text-danger fs-5">₱{{ number_format($ap->balance, 2) }}</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment History -->
                            <h6 class="border-bottom pb-2"><i class="bi bi-clock-history"></i> Payment History</h6>
                            @if($ap->payments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Reference Number</th>
                                                <th>Amount</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($ap->payments as $payment)
                                            <tr>
                                                <td>{{ $payment->paid_at->format('M d, Y h:i A') }}</td>
                                                <td><span class="badge bg-secondary">{{ $payment->reference_number ?? '-' }}</span></td>
                                                <td>₱{{ number_format($payment->amount, 2) }}</td>
                                                <td>{{ $payment->notes ?? '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No payments made yet.</p>
                            @endif

                            @if($ap->notes)
                            <div class="mt-3">
                                <h6 class="border-bottom pb-2"><i class="bi bi-sticky"></i> Notes</h6>
                                <p class="mb-0">{{ $ap->notes }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            @if($ap->balance > 0)
                                <button type="button" class="btn btn-success" onclick="switchToPaymentModal{{ $ap->id }}()">
                                    <i class="bi bi-credit-card"></i> Record Payment
                                </button>
                            @endif
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Modal -->
            <div class="modal fade" id="paymentModal{{ $ap->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title"><i class="bi bi-credit-card"></i> Record Payment to Partner</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('accounts-payable.payment', $ap->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <strong>Balance Due:</strong> ₱{{ number_format($ap->balance, 2) }}<br>
                                    <strong>Vendor:</strong> {{ ucfirst($ap->vendor_type) }} Partner
                                </div>

                                <div class="mb-3">
                                    <label for="amount{{ $ap->id }}" class="form-label">Payment Amount (₱) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="amount{{ $ap->id }}" name="amount" 
                                           step="0.01" min="0.01" max="{{ $ap->balance }}" required>
                                    <small class="text-muted">Maximum: ₱{{ number_format($ap->balance, 2) }}</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Reference Number</label>
                                    <input type="text" class="form-control" value="Auto-generated upon payment" readonly style="background-color: #e9ecef;">
                                    <small class="text-muted">Reference number will be automatically generated</small>
                                </div>

                                <div class="mb-3">
                                    <label for="notes{{ $ap->id }}" class="form-label">Notes (Optional)</label>
                                    <textarea class="form-control" id="notes{{ $ap->id }}" name="notes" rows="3" 
                                              placeholder="Payment remarks, conditions, etc."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Record Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
            function switchToPaymentModal{{ $ap->id }}() {
                const viewModalElement = document.getElementById('viewAPModal{{ $ap->id }}');
                const paymentModalElement = document.getElementById('paymentModal{{ $ap->id }}');
                
                let viewModal = bootstrap.Modal.getInstance(viewModalElement);
                if (!viewModal) {
                    viewModal = new bootstrap.Modal(viewModalElement);
                }
                
                viewModal.hide();
                
                viewModalElement.addEventListener('hidden.bs.modal', function openPayment() {
                    const paymentModal = new bootstrap.Modal(paymentModalElement);
                    paymentModal.show();
                    viewModalElement.removeEventListener('hidden.bs.modal', openPayment);
                }, { once: true });
            }
            </script>
            @endforeach

        @else
            <div class="text-center py-5">
                <i class="bi bi-wallet2 text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">No accounts payable records found.</p>
                <small class="text-muted">AP records are created when you generate partner links for orders.</small>
            </div>
        @endif
    </div>
</div>
@endsection
