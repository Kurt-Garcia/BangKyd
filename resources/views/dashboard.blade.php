@extends('layouts.navbar')

@section('title', 'Dashboard')

@push('styles')
<style>
    .stat-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .stat-card .icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }
    .gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .gradient-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    .gradient-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .gradient-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .gradient-danger {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    .activity-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }
    .activity-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    }
    .progress-thin {
        height: 8px;
        border-radius: 10px;
    }
    .welcome-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #2d3748;
    }
</style>
@endpush

@section('content')

<!-- Welcome Banner -->
<div class="welcome-banner">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="mb-2">Welcome back, {{ auth()->user()->username ?? auth()->user()->name }}! 👋</h2>
            <p class="mb-0 opacity-75">Here's what's happening with your business today.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="fs-1">
                <i class="bi bi-speedometer2"></i>
            </div>
        </div>
    </div>
</div>

<!-- Key Metrics -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Total Sales Orders</p>
                        <h3 class="mb-0">{{ $totalSalesOrders }}</h3>
                        <small class="text-muted">{{ $pendingSalesOrders }} pending</small>
                    </div>
                    <div class="icon-wrapper gradient-primary text-white">
                        <i class="bi bi-cart-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Orders in Production</p>
                        <h3 class="mb-0">{{ $ongoingOrders }}</h3>
                        <small class="text-primary">{{ $readyForDelivery }} ready</small>
                    </div>
                    <div class="icon-wrapper gradient-info text-white">
                        <i class="bi bi-bag-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Pending Payments</p>
                        <h3 class="mb-0">₱{{ number_format($totalOutstanding, 0) }}</h3>
                        <small class="text-warning">{{ $pendingPayments + $partialPayments }} accounts</small>
                    </div>
                    <div class="icon-wrapper gradient-warning text-white">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Total Collected</p>
                        <h3 class="mb-0">₱{{ number_format($totalReceived, 0) }}</h3>
                        <small class="text-success">All time</small>
                    </div>
                    <div class="icon-wrapper gradient-success text-white">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AP & Additional Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Partner Payables</p>
                        <h3 class="mb-0">₱{{ number_format($totalAPOutstanding, 0) }}</h3>
                        <small class="text-danger">{{ $pendingAP + $partialAP }} outstanding</small>
                    </div>
                    <div class="icon-wrapper gradient-danger text-white">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Partner Payments Made</p>
                        <h3 class="mb-0">₱{{ number_format($totalAPPaid, 0) }}</h3>
                        <small class="text-muted">All time</small>
                    </div>
                    <div class="icon-wrapper gradient-success text-white">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Net Profit Potential</p>
                        <h3 class="mb-0">₱{{ number_format($totalReceived - $totalAPPaid, 0) }}</h3>
                        <small class="text-{{ ($totalReceived - $totalAPPaid) > 0 ? 'success' : 'danger' }}">
                            After partner costs
                        </small>
                    </div>
                    <div class="icon-wrapper gradient-info text-white">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card" style="border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <div class="card-body">
                <h6 class="text-muted mb-3"><i class="bi bi-inbox"></i> Receiving Report</h6>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span>Unconfirmed Orders</span>
                    <span class="badge bg-danger">{{ $unconfirmedSubmissions }}</span>
                </div>
                <div class="progress progress-thin">
                    <div class="progress-bar bg-danger" style="width: {{ $totalSubmissions > 0 ? ($unconfirmedSubmissions / $totalSubmissions * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card" style="border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <div class="card-body">
                <h6 class="text-muted mb-3"><i class="bi bi-graph-up"></i> Payment Status</h6>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="fs-4 text-danger">{{ $pendingPayments }}</div>
                        <small class="text-muted">Pending</small>
                    </div>
                    <div class="col-4">
                        <div class="fs-4 text-warning">{{ $partialPayments }}</div>
                        <small class="text-muted">Partial</small>
                    </div>
                    <div class="col-4">
                        <div class="fs-4 text-success">{{ $totalAR - $pendingPayments - $partialPayments }}</div>
                        <small class="text-muted">Paid</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card" style="border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <div class="card-body">
                <h6 class="text-muted mb-3"><i class="bi bi-box-seam"></i> Order Status</h6>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="fs-4 text-primary">{{ $ongoingOrders }}</div>
                        <small class="text-muted">Ongoing</small>
                    </div>
                    <div class="col-4">
                        <div class="fs-4 text-warning">{{ $readyForDelivery }}</div>
                        <small class="text-muted">Ready</small>
                    </div>
                    <div class="col-4">
                        <div class="fs-4 text-success">{{ $completedOrders }}</div>
                        <small class="text-muted">Done</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row g-4">
    <!-- Recent Orders in Production -->
    <div class="col-lg-6">
        <h5 class="section-title"><i class="bi bi-clock-history"></i> Orders in Production</h5>
        @forelse($ordersInProduction as $order)
        <div class="activity-card card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6 class="mb-1">{{ $order->order_number }}</h6>
                        <small class="text-muted">{{ $order->accountReceivable->submission->salesOrder->so_name }}</small>
                    </div>
                    @if($order->progress)
                        @if($order->progress->current_stage === 'printing')
                            <span class="badge bg-primary">Printing</span>
                        @elseif($order->progress->current_stage === 'press')
                            <span class="badge bg-info">Press</span>
                        @elseif($order->progress->current_stage === 'tailoring')
                            <span class="badge bg-warning">Tailoring</span>
                        @else
                            <span class="badge bg-secondary">Processing</span>
                        @endif
                    @else
                        <span class="badge bg-secondary">Ongoing</span>
                    @endif
                </div>
                @if($order->progress)
                <div class="progress progress-thin mb-2">
                    <div class="progress-bar" style="width: {{ $order->progress->getProgressPercentage() }}%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);">
                    </div>
                </div>
                <small class="text-muted">{{ $order->progress->getProgressPercentage() }}% Complete · {{ $order->accountReceivable->submission->total_quantity }} jerseys</small>
                @else
                <small class="text-muted">{{ $order->accountReceivable->submission->total_quantity }} jerseys</small>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-4 text-muted">
            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
            <p class="mt-2">No orders in production</p>
        </div>
        @endforelse
    </div>

    <!-- Recent Customer Orders -->
    <div class="col-lg-6">
        <h5 class="section-title"><i class="bi bi-people"></i> Recent Customer Orders</h5>
        @forelse($recentSubmissions->take(5) as $submission)
        <div class="activity-card card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1">{{ $submission->salesOrder->so_number }}</h6>
                        <small class="text-muted">{{ $submission->salesOrder->so_name }}</small>
                        <div class="mt-1">
                            <span class="badge bg-light text-dark">{{ $submission->total_quantity }} jerseys</span>
                            <span class="badge bg-light text-dark">₱{{ number_format($submission->total_amount, 0) }}</span>
                        </div>
                    </div>
                    <div class="text-end">
                        @if($submission->accountReceivable)
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Confirmed</span>
                        @else
                            <span class="badge bg-warning"><i class="bi bi-hourglass-split"></i> Pending</span>
                        @endif
                        <div class="text-muted small mt-1">{{ $submission->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-4 text-muted">
            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
            <p class="mt-2">No recent orders</p>
        </div>
        @endforelse
    </div>
</div>

@endsection
