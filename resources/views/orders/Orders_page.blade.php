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
            @elseif($order->status === 'ready_for_delivery') border-warning
            @elseif($order->status === 'completed') border-success
            @else border-secondary
            @endif
        " style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1">{{ $order->order_number }}</h5>
                        <p class="text-muted small mb-0">{{ $order->accountReceivable->submission->salesOrder->so_number }}</p>
                    </div>
                    @if($order->status === 'completed')
                        <span class="badge bg-success">Completed</span>
                    @elseif($order->status === 'claimed')
                        <span class="badge bg-secondary">Claimed</span>
                    @elseif($order->progress)
                        @if($order->progress->current_stage === 'print_press')
                            <span class="badge bg-primary">Ongoing - Print & Press</span>
                        @elseif($order->progress->current_stage === 'tailoring')
                            <span class="badge bg-primary">Ongoing - Tailoring</span>
                        @elseif($order->progress->current_stage === 'completed')
                            <span class="badge bg-warning">Ready for Delivery</span>
                        @endif
                    @elseif($order->status === 'ongoing')
                        <span class="badge bg-primary">Ongoing</span>
                    @elseif($order->status === 'ready_for_delivery')
                        <span class="badge bg-warning">Ready for Delivery</span>
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
                    @elseif($order->status === 'ready_for_delivery') bg-warning
                    @elseif($order->status === 'completed') bg-success
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
                                @if($order->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($order->status === 'claimed')
                                    <span class="badge bg-secondary">Claimed</span>
                                @elseif($order->progress)
                                    @if($order->progress->current_stage === 'print_press')
                                        <span class="badge bg-primary">Ongoing - Print & Press</span>
                                    @elseif($order->progress->current_stage === 'tailoring')
                                        <span class="badge bg-primary">Ongoing - Tailoring</span>
                                    @elseif($order->progress->current_stage === 'completed')
                                        <span class="badge bg-warning">Ready for Delivery</span>
                                    @endif
                                @elseif($order->status === 'ongoing')
                                    <span class="badge bg-primary">Ongoing</span>
                                @elseif($order->status === 'ready_for_delivery')
                                    <span class="badge bg-warning">Ready for Delivery</span>
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

                    <!-- Production Progress (if exists) -->
                    @if($order->progress)
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2"><i class="bi bi-graph-up"></i> Production Progress</h6>
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-body">
                                        <i class="bi bi-printer-fill fs-3 text-primary"></i>
                                        <div class="mt-2"><small class="text-muted">Print & Press</small></div>
                                        <div class="fw-bold fs-5">{{ $order->progress->total_quantity }} jerseys</div>
                                        @if($order->progress->print_press_completed_at)
                                            <small class="text-success"><i class="bi bi-check-circle-fill"></i> Done</small>
                                        @else
                                            <small class="text-muted">In Progress</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-body">
                                        <i class="bi bi-scissors fs-3 text-info"></i>
                                        <div class="mt-2"><small class="text-muted">Tailoring</small></div>
                                        <div class="fw-bold fs-5">{{ $order->progress->total_quantity }} jerseys</div>
                                        @if($order->progress->tailoring_completed_at)
                                            <small class="text-success"><i class="bi bi-check-circle-fill"></i> Done</small>
                                        @elseif($order->progress->print_press_completed_at)
                                            <small class="text-muted">In Progress</small>
                                        @else
                                            <small class="text-muted">Pending</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-gradient" style="width: {{ $order->progress->getProgressPercentage() }}%; background: linear-gradient(90deg, #fa709a 0%, #fee140 100%);">
                                {{ $order->progress->getProgressPercentage() }}% Complete
                            </div>
                        </div>
                        @if($order->progress->notes)
                        <div class="alert alert-info mt-3">
                            <strong>Partner Notes:</strong> {{ $order->progress->notes }}
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Partner Payables (AP) -->
                    @if($order->accountsPayable->count() > 0)
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2"><i class="bi bi-wallet2"></i> Partner Payables</h6>
                        <div class="row text-center">
                            @foreach($order->accountsPayable as $ap)
                            <div class="col-6">
                                <div class="card {{ $ap->status === 'paid' ? 'bg-light' : ($ap->status === 'partial' ? 'border-warning' : 'border-danger') }}">
                                    <div class="card-body">
                                        @if($ap->vendor_type === 'printing')
                                            <i class="bi bi-printer-fill fs-3 text-primary"></i>
                                            <div class="mt-2"><small class="text-muted">Print & Press Partner</small></div>
                                        @else
                                            <i class="bi bi-layers-fill fs-3 text-warning"></i>
                                            <div class="mt-2"><small class="text-muted">Press Partner</small></div>
                                        @endif
                                        <div class="fw-bold text-primary fs-5 mt-2">₱{{ number_format($ap->total_amount, 2) }}</div>
                                        <div class="small">
                                            <span class="text-success">Paid: ₱{{ number_format($ap->paid_amount, 2) }}</span><br>
                                            <span class="text-danger">Balance: ₱{{ number_format($ap->balance, 2) }}</span>
                                        </div>
                                        <div class="mt-2">
                                            @if($ap->status === 'paid')
                                                <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Paid</span>
                                            @elseif($ap->status === 'partial')
                                                <span class="badge bg-warning"><i class="bi bi-clock-fill"></i> Partial</span>
                                            @else
                                                <span class="badge bg-danger"><i class="bi bi-exclamation-triangle-fill"></i> Pending</span>
                                            @endif
                                        </div>
                                        @if($ap->due_date)
                                        <div class="small text-muted mt-1">Due: {{ $ap->due_date->format('M d, Y') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="alert alert-secondary mt-3 mb-0">
                            <div class="row text-center">
                                <div class="col-6">
                                    <strong>Total Payable:</strong> ₱{{ number_format($order->accountsPayable->sum('total_amount'), 2) }}
                                </div>
                                <div class="col-6">
                                    <strong>Total Outstanding:</strong> <span class="text-danger">₱{{ number_format($order->accountsPayable->sum('balance'), 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

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
                    @if(!$order->progress && $order->status === 'ongoing')
                    <form action="{{ route('orders.generate-link', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-link-45deg"></i> Generate Partner Link
                        </button>
                    </form>
                    @elseif($order->progress)
                    <button type="button" class="btn btn-info" onclick="copyProgressLink{{ $order->id }}()">
                        <i class="bi bi-clipboard"></i> Copy Partner Link
                    </button>
                    <script>
                    function copyProgressLink{{ $order->id }}() {
                        const link = "{{ url('/progress/' . ($order->progress->unique_link ?? '')) }}";
                        navigator.clipboard.writeText(link).then(() => {
                            alert('Link copied to clipboard!\n\n' + link);
                        });
                    }
                    </script>
                    @endif
                    
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
                                <option value="ready_for_delivery" {{ $order->status === 'ready_for_delivery' ? 'selected' : '' }}>Ready for Delivery</option>
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
