@extends('layouts.navbar')

@section('title', 'Orders')

@section('content')
<style>
    @keyframes deadlineBlinkRed {
        0%, 100% { opacity: 1; box-shadow: 0 0 0 rgba(0,0,0,0); }
        50% { opacity: 0.35; box-shadow: 0 0 0.75rem rgba(220, 53, 69, 0.55); }
    }
    .deadline-blink {
        animation: deadlineBlinkRed 1.8s ease-in-out infinite;
    }
    @media (prefers-reduced-motion: reduce) {
        .deadline-blink { animation: none; }
    }
</style>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="bi bi-bag-check"></i> Orders</h1>
    <span class="badge bg-primary">{{ $orders->count() }} Total Orders</span>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('orders.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label"><i class="bi bi-search"></i> Search</label>
                    <input type="text" class="form-control" name="search" placeholder="SO Number or Customer" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label"><i class="bi bi-filter"></i> Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_production" {{ request('status') == 'in_production' ? 'selected' : '' }}>In Production</option>
                        <option value="ready_for_delivery" {{ request('status') == 'ready_for_delivery' ? 'selected' : '' }}>Ready</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
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
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                        @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
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
                    @elseif($order->status === 'ready_for_delivery')
                        <span class="badge bg-warning">Ready for Delivery</span>
                    @elseif($order->status === 'ongoing')
                        <span class="badge bg-primary">Ongoing</span>
                    @else
                        <span class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
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
                @php
                    $cardImages = collect($order->accountReceivable->submission->images)->take(3)->values();
                @endphp
                <div id="orderCardCarousel{{ $order->id }}" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2500">
                    <div class="carousel-inner rounded">
                        @foreach($cardImages as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image) }}" class="d-block w-100" alt="Design" style="height: 300px; object-fit: cover; object-position: center;">
                            </div>
                        @endforeach
                    </div>
                    @if($cardImages->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#orderCardCarousel{{ $order->id }}" data-bs-slide="prev" onclick="event.stopPropagation();">
                            <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1) grayscale(1);"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#orderCardCarousel{{ $order->id }}" data-bs-slide="next" onclick="event.stopPropagation();">
                            <span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1) grayscale(1);"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
                @endif
            </div>
            <div class="card-footer bg-light text-muted">
                @php
                    $deadlineDate = $order->accountReceivable->submission->deadline_date ?? null;
                    $deadlineDaysLeft = null;
                    if ($deadlineDate) {
                        $deadlineDaysLeft = now()->startOfDay()->diffInDays($deadlineDate->copy()->startOfDay(), false);
                    }

                    $submissionId = $order->accountReceivable->sales_order_submission_id ?? ($order->accountReceivable->submission->id ?? null);
                    $totalPlayers = is_array($order->accountReceivable->submission->players ?? null) ? count($order->accountReceivable->submission->players) : 0;
                    $donePlayers = ($submissionId && isset(($checklistDoneCounts ?? [])[$submissionId])) ? (int) ($checklistDoneCounts[$submissionId] ?? 0) : 0;
                    $checkPercent = $totalPlayers > 0 ? (int) round(($donePlayers / $totalPlayers) * 100) : 0;
                @endphp
                <div class="d-flex justify-content-between align-items-center">
                    <small><i class="bi bi-clock"></i> Started: {{ $order->started_at->format('M d, Y') }}</small>
                    @if($deadlineDate && $order->status !== 'completed')
                        @php
                            $deadlineBadgeClass = 'bg-info';
                            if ($deadlineDaysLeft !== null && $deadlineDaysLeft < 0) {
                                $deadlineBadgeClass = 'bg-danger';
                            } elseif ($deadlineDaysLeft !== null && $deadlineDaysLeft >= 0 && $deadlineDaysLeft <= 3) {
                                $deadlineBadgeClass = 'bg-danger deadline-blink';
                            }
                        @endphp
                        <span class="badge {{ $deadlineBadgeClass }}"><i class="bi bi-calendar-event"></i> Deadline: {{ $deadlineDate->format('M d, Y') }}</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <small class="text-muted">
                        <i class="bi bi-check2-square"></i> Edited: {{ $donePlayers }}/{{ $totalPlayers }} ({{ $checkPercent }}%)
                    </small>
                    <a href="{{ route('orders.player-checklist', $order->id) }}" class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation();">
                        Checklist
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Detail Modal -->
    <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            @php
                $ar = $order->accountReceivable;
                $isFullyPaid = $ar && ($ar->status === 'paid' || ($ar->paid_amount >= $ar->total_amount));
                $statusLabel = match ($order->status) {
                    'completed' => 'Completed',
                    'claimed' => 'Claimed',
                    'ready_for_delivery' => 'Ready for Delivery',
                    'ongoing' => 'Ongoing',
                    default => ucfirst(str_replace('_', ' ', $order->status)),
                };
                $statusBadgeClass = match ($order->status) {
                    'completed' => 'text-bg-success',
                    'claimed' => 'text-bg-secondary',
                    'ready_for_delivery' => 'text-bg-warning',
                    'ongoing' => 'text-bg-primary',
                    default => 'text-bg-light',
                };
                $headerBgClass = match ($order->status) {
                    'ongoing' => 'bg-primary',
                    'ready_for_delivery' => 'bg-warning',
                    'completed' => 'bg-success',
                    default => 'bg-secondary',
                };
                $arBalance = $ar ? ($ar->balance ?? ($ar->total_amount - $ar->paid_amount)) : 0;
            @endphp
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header {{ $headerBgClass }} bg-gradient text-white border-0 py-4">
                    <div class="d-flex align-items-start justify-content-between w-100 gap-3">
                        <div class="me-auto">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h5 class="modal-title mb-0">{{ $order->order_number }}</h5>
                                <span class="badge rounded-pill {{ $statusBadgeClass }} {{ $statusBadgeClass === 'text-bg-light' ? 'text-dark' : '' }}">{{ $statusLabel }}</span>
                            </div>
                            <div class="small opacity-75 mt-1">
                                {{ $order->accountReceivable->submission->salesOrder->so_number }} • {{ $order->accountReceivable->submission->salesOrder->so_name }}
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body bg-body-tertiary">
                    <div class="row g-4">
                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="fw-semibold"><i class="bi bi-info-circle me-2"></i>Order Info</div>
                                        <span class="badge rounded-pill bg-light text-dark border">{{ $order->started_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="text-muted small">SO Number</div>
                                            <div class="fw-semibold">{{ $order->accountReceivable->submission->salesOrder->so_number }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-muted small">AR Number</div>
                                            <div class="fw-semibold">{{ $ar->ar_number ?? '-' }}</div>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-muted small">Customer</div>
                                            <div class="fw-semibold">{{ $order->accountReceivable->submission->salesOrder->so_name }}</div>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-muted small">Started</div>
                                            <div class="fw-semibold">{{ $order->started_at->format('M d, Y h:i A') }}</div>
                                        </div>
                                        @if($order->completed_at)
                                            <div class="col-12">
                                                <div class="text-muted small">Completed</div>
                                                <div class="fw-semibold">{{ $order->completed_at->format('M d, Y h:i A') }}</div>
                                            </div>
                                        @endif
                                        @if($order->claimed_at)
                                            <div class="col-12">
                                                <div class="text-muted small">Claimed</div>
                                                <div class="fw-semibold">{{ $order->claimed_at->format('M d, Y h:i A') }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-body">
                                    <div class="fw-semibold mb-3"><i class="bi bi-cash-stack me-2"></i>Payment</div>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="text-muted small">Total</div>
                                            <div class="fs-5 fw-bold">₱{{ number_format($ar->total_amount ?? 0, 2) }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-muted small">Paid</div>
                                            <div class="fs-5 fw-bold text-success">₱{{ number_format($ar->paid_amount ?? 0, 2) }}</div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light">
                                                <div>
                                                    <div class="text-muted small">Balance</div>
                                                    <div class="fw-bold text-danger fs-5">₱{{ number_format($arBalance, 2) }}</div>
                                                </div>
                                                <span class="badge rounded-pill {{ $isFullyPaid ? 'bg-success' : 'bg-warning text-dark' }}">{{ $isFullyPaid ? 'Fully Paid' : 'Not Fully Paid' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($order->production_notes)
                                <div class="card border-0 shadow-sm rounded-4">
                                    <div class="card-body">
                                        <div class="fw-semibold mb-2"><i class="bi bi-journal-text me-2"></i>Production Notes</div>
                                        <div class="text-muted">{{ $order->production_notes }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-lg-7">
                            @if($order->accountReceivable->submission->images && count($order->accountReceivable->submission->images) > 0)
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                                    <div class="card-body">
                                        <div class="fw-semibold mb-3"><i class="bi bi-images me-2"></i>Design Preview</div>
                                        @php
                                            $modalImages = collect($order->accountReceivable->submission->images)->take(3)->values();
                                        @endphp
                                        <div id="orderModalCarousel{{ $order->id }}" class="carousel slide" data-bs-ride="carousel">
                                            @if($modalImages->count() > 1)
                                                <div class="carousel-indicators">
                                                    @foreach($modalImages as $index => $image)
                                                        <button type="button" data-bs-target="#orderModalCarousel{{ $order->id }}" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}" onclick="event.stopPropagation();"></button>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <div class="carousel-inner rounded-3">
                                                @foreach($modalImages as $index => $image)
                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                        <a href="{{ asset('storage/' . $image) }}" target="_blank" rel="noopener noreferrer">
                                                            <img src="{{ asset('storage/' . $image) }}" class="d-block w-100" alt="Design" style="height: 420px; max-height: 60vh; object-fit: contain; background-color: #f8f9fa;">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>

                                            @if($modalImages->count() > 1)
                                                <button class="carousel-control-prev" type="button" data-bs-target="#orderModalCarousel{{ $order->id }}" data-bs-slide="prev" onclick="event.stopPropagation();">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1) grayscale(1);"></span>
                                                    <span class="visually-hidden">Previous</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#orderModalCarousel{{ $order->id }}" data-bs-slide="next" onclick="event.stopPropagation();">
                                                    <span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1) grayscale(1);"></span>
                                                    <span class="visually-hidden">Next</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="fw-semibold"><i class="bi bi-people me-2"></i>Jersey Details</div>
                                        <span class="badge rounded-pill bg-light text-dark border">{{ $order->accountReceivable->submission->total_quantity }} pcs</span>
                                    </div>
                                    @php
                                        $playersByProduct = collect($order->accountReceivable->submission->players)->groupBy('product_id');
                                        $productIds = $playersByProduct->keys()->filter()->values();
                                        $productsById = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');
                                    @endphp
                                    <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
                                        @foreach($playersByProduct as $productId => $players)
                                            @php
                                                $productName = optional($productsById->get($productId))->name ?? 'Unknown Product';
                                            @endphp
                                            <div class="d-flex align-items-center justify-content-between mt-3 mb-2">
                                                <div class="fw-semibold text-primary">{{ $productName }}</div>
                                                <span class="badge rounded-pill bg-secondary">{{ count($players) }} pcs</span>
                                            </div>
                                            <table class="table table-hover align-middle mb-4">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width: 60px;">#</th>
                                                        <th>Jersey Name</th>
                                                        <th style="width: 120px;">Number</th>
                                                        <th style="width: 120px;">Size</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($players as $index => $player)
                                                        <tr>
                                                            <td class="text-muted">{{ $index + 1 }}</td>
                                                            <td class="fw-semibold">{{ $player['jersey_name'] }}</td>
                                                            <td><span class="badge bg-light text-dark border">{{ $player['jersey_number'] }}</span></td>
                                                            <td class="text-muted">{{ $player['jersey_size'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white">
                    <a href="{{ route('orders.player-checklist', $order->id) }}" class="btn btn-outline-primary">
                        <i class="bi bi-check2-square"></i> Checklist
                    </a>
                    @if($order->status !== 'completed')
                        @if($isFullyPaid)
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#completeOrderModal{{ $order->id }}">
                                <i class="bi bi-check-circle"></i> Done
                            </button>
                        @else
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#orderPaymentRequiredModal{{ $order->id }}">
                                <i class="bi bi-check-circle"></i> Done
                            </button>
                        @endif
                    @endif
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @if($order->status !== 'completed')
    <div class="modal fade" id="completeOrderModal{{ $order->id }}" tabindex="-1" aria-labelledby="completeOrderModalLabel{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="completeOrderModalLabel{{ $order->id }}">Mark Order as Completed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Mark <strong>{{ $order->order_number }}</strong> as completed?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('orders.complete', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            Yes, Done
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($order->status !== 'completed' && !$isFullyPaid)
    <div class="modal fade" id="orderPaymentRequiredModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderPaymentRequiredModalLabel{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderPaymentRequiredModalLabel{{ $order->id }}">Payment Required</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    This order must be fully paid before you can mark it as completed.
                    @if($ar)
                        <div class="mt-3">
                            <div><strong>AR Status:</strong> {{ ucfirst($ar->status) }}</div>
                            <div><strong>Balance:</strong> ₱{{ number_format($ar->balance ?? ($ar->total_amount - $ar->paid_amount), 2) }}</div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    @endif



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
