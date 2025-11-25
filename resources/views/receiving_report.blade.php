@extends('layouts.navbar')

@section('title', 'Receiving Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="bi bi-inbox"></i> Receiving Report</h1>
    <span class="badge bg-primary">{{ $submissions->count() }} Total Submissions</span>
</div>

<div class="row">
    @forelse($submissions as $submission)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#submissionModal{{ $submission->id }}">
            <div class="card-body">
                <h5 class="card-title text-primary">{{ $submission->salesOrder->so_number }}</h5>
                <h6 class="card-subtitle mb-3 text-muted">{{ $submission->salesOrder->so_name }}</h6>
                
                <!-- Design Images Preview -->
                @if($submission->images && count($submission->images) > 0)
                <div class="row g-2">
                    @foreach($submission->images as $index => $image)
                        @if($index < 3)
                        <div class="col-4">
                            <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Design" style="height: 80px; width: 100%; object-fit: cover;">
                        </div>
                        @endif
                    @endforeach
                </div>
                @else
                <div class="text-center text-muted py-3">
                    <i class="bi bi-image" style="font-size: 2rem;"></i>
                    <p class="small mb-0">No images</p>
                </div>
                @endif
            </div>
            <div class="card-footer bg-light">
                <small class="text-muted">
                    <i class="bi bi-clock"></i> {{ $submission->submitted_at->format('M d, Y') }}
                    <span class="float-end"><i class="bi bi-people"></i> {{ count($submission->players) }} players</span>
                </small>
            </div>
        </div>
    </div>

    <!-- Modal for Full Details -->
    <div class="modal fade" id="submissionModal{{ $submission->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <div>
                        <h5 class="modal-title">{{ $submission->salesOrder->so_number }} - {{ $submission->salesOrder->so_name }}</h5>
                        <small>Submitted: {{ $submission->submitted_at->format('M d, Y h:i A') }}</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Design Images -->
                    @if($submission->images && count($submission->images) > 0)
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2"><i class="bi bi-images"></i> Design Images</h6>
                        <div class="row g-3">
                            @foreach($submission->images as $image)
                            <div class="col-md-4">
                                <a href="{{ asset('storage/' . $image) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail" alt="Design" style="height: 200px; width: 100%; object-fit: cover;">
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Payment Information -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2"><i class="bi bi-cash-stack"></i> Payment Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p class="mb-2"><strong>Total Quantity:</strong> {{ $submission->total_quantity }} pcs</p>
                                        <p class="mb-2"><strong>Price per Piece:</strong> ₱{{ number_format($submission->salesOrder->price_per_pcs, 2) }}</p>
                                        <p class="mb-2"><strong>Total Amount:</strong> <span class="text-primary fs-5 fw-bold">₱{{ number_format($submission->total_amount, 2) }}</span></p>
                                        <hr>
                                        <p class="mb-2 text-danger"><strong>Down Payment (50%):</strong> ₱{{ number_format($submission->down_payment, 2) }}</p>
                                        <p class="mb-0 text-success"><strong>Balance:</strong> ₱{{ number_format($submission->balance, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card {{ $submission->is_paid ? 'bg-success' : 'bg-warning' }}">
                                    <div class="card-body text-white">
                                        <h5 class="card-title">
                                            @if($submission->is_paid)
                                                <i class="bi bi-check-circle-fill"></i> Fully Paid
                                            @else
                                                <i class="bi bi-exclamation-triangle-fill"></i> Pending Payment
                                            @endif
                                        </h5>
                                        @if($submission->is_paid)
                                            <p class="mb-0">Paid on: {{ $submission->paid_at->format('M d, Y h:i A') }}</p>
                                        @else
                                            <p class="mb-0">Awaiting full payment</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Players Info -->
                    <div>
                        <h6 class="border-bottom pb-2"><i class="bi bi-people"></i> Players Information ({{ count($submission->players) }} players)</h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Full Name</th>
                                        <th>Jersey Name</th>
                                        <th>Jersey Number</th>
                                        <th>Jersey Size</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submission->players as $index => $player)
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#confirmPaymentModal{{ $submission->id }}">
                        <i class="bi bi-check-circle"></i> Confirm Order
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Payment Modal -->
    <div class="modal fade" id="confirmPaymentModal{{ $submission->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Confirm Payment Received</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('receiving-report.confirm', $submission->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Confirm that you have received the down payment for this order.</strong>
                        </div>
                        <p><strong>SO Number:</strong> {{ $submission->salesOrder->so_number }}</p>
                        <p><strong>Customer:</strong> {{ $submission->salesOrder->so_name }}</p>
                        <p><strong>Down Payment Expected:</strong> <span class="text-danger fs-5">₱{{ number_format($submission->down_payment, 2) }}</span></p>
                        <hr>
                        <p class="text-muted small">This will create an Account Receivable record and move the order to AR tracking.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check2-circle"></i> Confirm Payment Received
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">No submissions yet.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>
@endsection
