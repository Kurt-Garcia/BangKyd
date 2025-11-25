@extends('layouts.navbar')

@section('title', 'Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="bi bi-bag-check"></i> Orders</h1>
    <span class="badge bg-primary">{{ $orders->count() }} Total Orders</span>
</div>

<div class="row">
    @forelse($orders as $order)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-start border-4 
            @if($order->status === 'ongoing') border-primary
            @elseif($order->status === 'ready') border-success
            @elseif($order->status === 'completed') border-info
            @else border-secondary
            @endif
        " style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1">{{ $order->order_number }}</h5>
                        <p class="text-muted small mb-0">{{ $order->accountReceivable->submission->salesOrder->so_number }}</p>
                    </div>
                    @if($order->status === 'ongoing')
                        <span class="badge bg-primary">Ongoing</span>
                    @elseif($order->status === 'ready')
                        <span class="badge bg-success">Ready</span>
                    @elseif($order->status === 'completed')
                        <span class="badge bg-info">Completed</span>
                    @else
                        <span class="badge bg-secondary">Claimed</span>
                    @endif
                </div>

                <h6 class="mb-2">{{ $order->accountReceivable->submission->salesOrder->so_name }}</h6>
                
                <hr class="my-2">
                
                <div class="row text-center">
                    <div class="col-6">
                        <small class="text-muted">Quantity</small>
                        <p class="mb-0 fw-bold">{{ $order->accountReceivable->submission->total_quantity }} pcs</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Total Amount</small>
                        <p class="mb-0 fw-bold text-primary">₱{{ number_format($order->accountReceivable->total_amount, 2) }}</p>
                    </div>
                </div>

                @if($order->accountReceivable->submission->images && count($order->accountReceivable->submission->images) > 0)
                <hr class="my-2">
                <div class="row g-1">
                    @foreach($order->accountReceivable->submission->images as $index => $image)
                        @if($index < 3)
                        <div class="col-4">
                            <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Design" style="height: 60px; width: 100%; object-fit: cover;">
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>
            <div class="card-footer bg-light text-muted">
                <small>
                    <i class="bi bi-clock"></i> Started: {{ $order->started_at->format('M d, Y') }}
                </small>
            </div>
        </div>
    </div>

    <!-- Order Detail Modal -->
    <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header 
                    @if($order->status === 'ongoing') bg-primary
                    @elseif($order->status === 'ready') bg-success
                    @elseif($order->status === 'completed') bg-info
                    @else bg-secondary
                    @endif
                    text-white">
                    <div>
                        <h5 class="modal-title">{{ $order->order_number }} - Order Details</h5>
                        <small>{{ $order->accountReceivable->submission->salesOrder->so_number }} - {{ $order->accountReceivable->submission->salesOrder->so_name }}</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Order Status -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2"><i class="bi bi-info-circle"></i> Order Information</h6>
                            <p class="mb-1"><strong>Order Number:</strong> {{ $order->order_number }}</p>
                            <p class="mb-1"><strong>SO Number:</strong> {{ $order->accountReceivable->submission->salesOrder->so_number }}</p>
                            <p class="mb-1"><strong>AR Number:</strong> {{ $order->accountReceivable->ar_number }}</p>
                            <p class="mb-1"><strong>Customer:</strong> {{ $order->accountReceivable->submission->salesOrder->so_name }}</p>
                            <p class="mb-1"><strong>Started:</strong> {{ $order->started_at->format('M d, Y h:i A') }}</p>
                            @if($order->completed_at)
                            <p class="mb-1"><strong>Completed:</strong> {{ $order->completed_at->format('M d, Y h:i A') }}</p>
                            @endif
                            @if($order->claimed_at)
                            <p class="mb-1"><strong>Claimed:</strong> {{ $order->claimed_at->format('M d, Y h:i A') }}</p>
                            @endif
                            <p class="mb-0">
                                <strong>Status:</strong>
                                @if($order->status === 'ongoing')
                                    <span class="badge bg-primary">Ongoing</span>
                                @elseif($order->status === 'ready')
                                    <span class="badge bg-success">Ready</span>
                                @elseif($order->status === 'completed')
                                    <span class="badge bg-info">Completed</span>
                                @else
                                    <span class="badge bg-secondary">Claimed</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2"><i class="bi bi-cash-stack"></i> Order Summary</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-2"><strong>Quantity:</strong> {{ $order->accountReceivable->submission->total_quantity }} pcs</p>
                                    <p class="mb-2"><strong>Price per piece:</strong> ₱{{ number_format($order->accountReceivable->submission->salesOrder->price_per_pcs, 2) }}</p>
                                    <p class="mb-0"><strong>Total Amount:</strong> <span class="text-primary fs-5">₱{{ number_format($order->accountReceivable->total_amount, 2) }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Design Images -->
                    @if($order->accountReceivable->submission->images && count($order->accountReceivable->submission->images) > 0)
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2"><i class="bi bi-images"></i> Design Images</h6>
                        <div class="row g-3">
                            @foreach($order->accountReceivable->submission->images as $image)
                            <div class="col-md-4">
                                <a href="{{ asset('storage/' . $image) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail" alt="Design" style="height: 200px; width: 100%; object-fit: cover;">
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Players List -->
                    <div>
                        <h6 class="border-bottom pb-2"><i class="bi bi-people"></i> Jersey Details ({{ $order->accountReceivable->submission->total_quantity }} jerseys)</h6>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
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
                                    @foreach($order->accountReceivable->submission->players as $index => $player)
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

                    @if($order->production_notes)
                    <div class="mt-3">
                        <h6 class="border-bottom pb-2"><i class="bi bi-journal-text"></i> Production Notes</h6>
                        <p class="text-muted">{{ $order->production_notes }}</p>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $order->id }}">
                        <i class="bi bi-arrow-repeat"></i> Update Status
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-arrow-repeat"></i> Update Order Status</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('orders.update-status', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status{{ $order->id }}" class="form-label">Order Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status{{ $order->id }}" name="status" required>
                                <option value="ongoing" {{ $order->status === 'ongoing' ? 'selected' : '' }}>Ongoing - In Production</option>
                                <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready - Ready for Pickup</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed - Picked Up</option>
                                <option value="claimed" {{ $order->status === 'claimed' ? 'selected' : '' }}>Claimed - Fully Claimed</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="production_notes{{ $order->id }}" class="form-label">Production Notes (Optional)</label>
                            <textarea class="form-control" id="production_notes{{ $order->id }}" name="production_notes" rows="3" placeholder="Add any production notes or updates...">{{ $order->production_notes }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Update Status
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
                <i class="bi bi-bag-x text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">No orders in production yet.</p>
                <small class="text-muted">Orders will appear here once they are fully paid.</small>
            </div>
        </div>
    </div>
    @endforelse
</div>
@endsection
