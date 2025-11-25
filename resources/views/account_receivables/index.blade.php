@extends('layouts.navbar')

@section('title', 'Accounts Receivable')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="bi bi-cash-coin"></i> Accounts Receivable</h1>
    <span class="badge bg-primary">{{ $accountReceivables->count() }} Total AR</span>
</div>

<div class="card">
    <div class="card-body">
        @if($accountReceivables->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>AR Number</th>
                            <th>SO Number</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Confirmed Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accountReceivables as $ar)
                        <tr>
                            <td><strong>{{ $ar->ar_number }}</strong></td>
                            <td>{{ $ar->submission->salesOrder->so_number }}</td>
                            <td>{{ $ar->submission->salesOrder->so_name }}</td>
                            <td>₱{{ number_format($ar->total_amount, 2) }}</td>
                            <td>₱{{ number_format($ar->paid_amount, 2) }}</td>
                            <td>₱{{ number_format($ar->balance, 2) }}</td>
                            <td>
                                @if($ar->status === 'paid')
                                    <span class="badge bg-success">Fully Paid</span>
                                @elseif($ar->status === 'partial')
                                    <span class="badge bg-warning">Partial</span>
                                @else
                                    <span class="badge bg-danger">Pending</span>
                                @endif
                            </td>
                            <td>{{ $ar->confirmed_at->format('M d, Y') }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewARModal{{ $ar->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- View AR Modal -->
                        <div class="modal fade" id="viewARModal{{ $ar->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <div>
                                            <h5 class="modal-title">{{ $ar->ar_number }} - Account Receivable Details</h5>
                                            <small>{{ $ar->submission->salesOrder->so_number }} - {{ $ar->submission->salesOrder->so_name }}</small>
                                        </div>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- AR Summary -->
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2"><i class="bi bi-info-circle"></i> AR Information</h6>
                                                <p class="mb-1"><strong>AR Number:</strong> {{ $ar->ar_number }}</p>
                                                <p class="mb-1"><strong>SO Number:</strong> {{ $ar->submission->salesOrder->so_number }}</p>
                                                <p class="mb-1"><strong>Customer:</strong> {{ $ar->submission->salesOrder->so_name }}</p>
                                                <p class="mb-1"><strong>Confirmed:</strong> {{ $ar->confirmed_at->format('M d, Y h:i A') }}</p>
                                                <p class="mb-0">
                                                    <strong>Status:</strong>
                                                    @if($ar->status === 'paid')
                                                        <span class="badge bg-success">Fully Paid</span>
                                                    @elseif($ar->status === 'partial')
                                                        <span class="badge bg-warning">Partial</span>
                                                    @else
                                                        <span class="badge bg-danger">Pending</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2"><i class="bi bi-cash-stack"></i> Payment Summary</h6>
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <p class="mb-2"><strong>Total Amount:</strong> <span class="text-primary fs-5">₱{{ number_format($ar->total_amount, 2) }}</span></p>
                                                        <p class="mb-2"><strong>Paid Amount:</strong> <span class="text-success">₱{{ number_format($ar->paid_amount, 2) }}</span></p>
                                                        <p class="mb-0"><strong>Balance:</strong> <span class="text-danger fs-5">₱{{ number_format($ar->balance, 2) }}</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Payment History -->
                                        <h6 class="border-bottom pb-2"><i class="bi bi-clock-history"></i> Payment History</h6>
                                        @if($ar->payments->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Amount</th>
                                                            <th>Type</th>
                                                            <th>Notes</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($ar->payments as $payment)
                                                        <tr>
                                                            <td>{{ $payment->paid_at->format('M d, Y h:i A') }}</td>
                                                            <td>₱{{ number_format($payment->amount, 2) }}</td>
                                                            <td>
                                                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</span>
                                                            </td>
                                                            <td>{{ $payment->notes ?? '-' }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted">No payments recorded yet.</p>
                                        @endif

                                        <!-- Order Details -->
                                        <h6 class="border-bottom pb-2 mt-4"><i class="bi bi-people"></i> Order Details ({{ $ar->submission->total_quantity }} jerseys)</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Full Name</th>
                                                        <th>Jersey Name</th>
                                                        <th>Number</th>
                                                        <th>Size</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($ar->submission->players as $index => $player)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $player['full_name'] }}</td>
                                                        <td><strong>{{ $player['jersey_name'] }}</strong></td>
                                                        <td><span class="badge bg-secondary">{{ $player['jersey_number'] }}</span></td>
                                                        <td>{{ $player['jersey_size'] }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        @if($ar->balance > 0)
                                            <button type="button" class="btn btn-success" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $ar->id }}">
                                                <i class="bi bi-credit-card"></i> Record Payment
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Modal -->
                        <div class="modal fade" id="paymentModal{{ $ar->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title"><i class="bi bi-credit-card"></i> Record Payment</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('account-receivables.payment', $ar->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <strong>Balance Due:</strong> ₱{{ number_format($ar->balance, 2) }}
                                            </div>

                                            <div class="mb-3">
                                                <label for="payment_type{{ $ar->id }}" class="form-label">Payment Type <span class="text-danger">*</span></label>
                                                <select class="form-select" id="payment_type{{ $ar->id }}" name="payment_type" required onchange="updatePaymentAmount{{ $ar->id }}(this.value)">
                                                    <option value="">Select Payment Type</option>
                                                    <option value="partial">Partial Payment</option>
                                                    <option value="full">Full Payment</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="amount{{ $ar->id }}" class="form-label">Amount (₱) <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="amount{{ $ar->id }}" name="amount" 
                                                       step="0.01" min="0.01" max="{{ $ar->balance }}" required>
                                                <small class="text-muted">Maximum: ₱{{ number_format($ar->balance, 2) }}</small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="notes{{ $ar->id }}" class="form-label">Notes (Optional)</label>
                                                <textarea class="form-control" id="notes{{ $ar->id }}" name="notes" rows="3" placeholder="Payment reference, remarks, etc."></textarea>
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
                        function updatePaymentAmount{{ $ar->id }}(type) {
                            const amountField = document.getElementById('amount{{ $ar->id }}');
                            if (type === 'full') {
                                amountField.value = {{ $ar->balance }};
                            } else {
                                amountField.value = '';
                            }
                        }
                        </script>

                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-cash-coin text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">No accounts receivable records found.</p>
            </div>
        @endif
    </div>
</div>
@endsection
