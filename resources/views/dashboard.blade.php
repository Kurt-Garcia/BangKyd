@extends('layouts.navbar')

@section('title', 'Dashboard')

@push('styles')
<style>
    body {
        background: #f8f9fa;
    }
    
    .stat-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
        background: white;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #fa709a 0%, #fee140 100%);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }
    
    .stat-card:hover::before {
        transform: scaleX(1);
    }
    
    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 50px rgba(0,0,0,0.15);
    }
    
    .stat-card .card-body {
        padding: 1.75rem;
    }
    
    .stat-card .icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover .icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }
    
    .stat-value {
        font-size: 2.25rem;
        font-weight: 700;
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.25rem;
    }
    
    .gradient-primary {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
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
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    }
    
    .activity-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        background: white;
        border-left: 4px solid transparent;
    }
    
    .activity-card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        transform: translateX(5px);
        border-left-color: #fa709a;
    }
    
    .progress-thin {
        height: 10px;
        border-radius: 10px;
        background: #e9ecef;
        overflow: hidden;
    }
    
    .progress-bar {
        border-radius: 10px;
        background: linear-gradient(90deg, #fa709a 0%, #fee140 100%);
        transition: width 0.6s ease;
    }
    
    .welcome-banner {
        background: url('{{ asset('img/BG.jpg') }}') no-repeat center center;
        background-size: cover;
        border-radius: 25px;
        color: white;
        padding: 3rem;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 50px rgba(0,0,0,0.2);
    }
    
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.35);
        z-index: 0;
    }
    
    .welcome-banner > * {
        position: relative;
        z-index: 1;
    }
    
    .welcome-banner h2 {
        font-size: 2.5rem;
        font-weight: 700;
        text-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .welcome-icon {
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        animation: pulse 2s ease-in-out infinite;
        margin-left: auto;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .section-title {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #2d3748;
        position: relative;
        padding-left: 15px;
    }
    
    .section-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 5px;
        height: 70%;
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        border-radius: 10px;
    }
    
    .quick-stat-card {
        border-radius: 18px;
        border: none;
        box-shadow: 0 6px 25px rgba(0,0,0,0.08);
        background: white;
        transition: all 0.3s ease;
    }
    
    .quick-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 35px rgba(0,0,0,0.12);
    }
    
    .quick-stat-card .card-body {
        padding: 1.75rem;
    }
    
    .quick-stat-card h6 {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 1.25rem;
    }
    
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        background: white;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 1rem;
    }
    
    .empty-state p {
        color: #718096;
        font-size: 1.1rem;
    }
</style>
@endpush

@section('content')

<!-- Welcome Banner -->
<div class="welcome-banner">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="mb-3">Welcome back, {{ auth()->user()->username }}! 👋</h2>
            <p class="mb-0 fs-5 opacity-90">Here's what's happening with your business today.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="welcome-icon">
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
                    <div class="flex-grow-1">
                        <p class="text-muted mb-2 text-uppercase" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Total Sales Orders</p>
                        <h2 class="stat-value">{{ $totalSalesOrders }}</h2>
                        <small class="text-muted"><i class="bi bi-hourglass-split me-1"></i>{{ $pendingSalesOrders }} pending</small>
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
                    <div class="flex-grow-1">
                        <p class="text-muted mb-2 text-uppercase" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">In Production</p>
                        <h2 class="stat-value">{{ $ongoingOrders }}</h2>
                        <small class="text-success"><i class="bi bi-check-circle me-1"></i>{{ $readyForDelivery }} ready</small>
                    </div>
                    <div class="icon-wrapper gradient-info text-white">
                        <i class="bi bi-gear-wide-connected"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <p class="text-muted mb-2 text-uppercase" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Pending Payments</p>
                        <h2 class="stat-value">₱{{ number_format($totalOutstanding, 0) }}</h2>
                        <small class="text-warning"><i class="bi bi-exclamation-circle me-1"></i>{{ $pendingPayments + $partialPayments }} accounts</small>
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
                    <div class="flex-grow-1">
                        <p class="text-muted mb-2 text-uppercase" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Total Collected</p>
                        <h2 class="stat-value">₱{{ number_format($totalReceived, 0) }}</h2>
                        <small class="text-success"><i class="bi bi-wallet2 me-1"></i>All time</small>
                    </div>
                    <div class="icon-wrapper gradient-success text-white">
                        <i class="bi bi-graph-up-arrow"></i>
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
        <div class="card quick-stat-card">
            <div class="card-body">
                <h6><i class="bi bi-inbox me-2"></i>Receiving Report</h6>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted">Unconfirmed Orders</span>
                    <span class="badge bg-danger">{{ $unconfirmedSubmissions }}</span>
                </div>
                <div class="progress progress-thin">
                    <div class="progress-bar" style="width: {{ $totalSubmissions > 0 ? ($unconfirmedSubmissions / $totalSubmissions * 100) : 0 }}%; background: linear-gradient(90deg, #ff6b6b 0%, #ee5a6f 100%);"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card quick-stat-card">
            <div class="card-body">
                <h6><i class="bi bi-credit-card me-2"></i>Payment Status</h6>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="fs-3 fw-bold text-danger mb-1">{{ $pendingPayments }}</div>
                        <small class="text-muted d-block">Pending</small>
                    </div>
                    <div class="col-4">
                        <div class="fs-3 fw-bold text-warning mb-1">{{ $partialPayments }}</div>
                        <small class="text-muted d-block">Partial</small>
                    </div>
                    <div class="col-4">
                        <div class="fs-3 fw-bold text-success mb-1">{{ $totalAR - $pendingPayments - $partialPayments }}</div>
                        <small class="text-muted d-block">Paid</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card quick-stat-card">
            <div class="card-body">
                <h6><i class="bi bi-box-seam me-2"></i>Order Status</h6>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="fs-3 fw-bold" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $ongoingOrders }}</div>
                        <small class="text-muted d-block">Ongoing</small>
                    </div>
                    <div class="col-4">
                        <div class="fs-3 fw-bold text-warning mb-1">{{ $readyForDelivery }}</div>
                        <small class="text-muted d-block">Ready</small>
                    </div>
                    <div class="col-4">
                        <div class="fs-3 fw-bold text-success mb-1">{{ $completedOrders }}</div>
                        <small class="text-muted d-block">Done</small>
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
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="mb-1 fw-bold">{{ $order->order_number }}</h6>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">{{ $order->accountReceivable->submission->salesOrder->so_name }}</p>
                    </div>
                    @if($order->progress)
                        @if($order->progress->current_stage === 'print_press')
                            <span class="badge" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <i class="bi bi-printer me-1"></i>Print & Press
                            </span>
                        @elseif($order->progress->current_stage === 'tailoring')
                            <span class="badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <i class="bi bi-scissors me-1"></i>Tailoring
                            </span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-gear me-1"></i>Processing</span>
                        @endif
                    @else
                        <span class="badge bg-secondary">Ongoing</span>
                    @endif
                </div>
                @if($order->progress)
                <div class="progress progress-thin mb-2">
                    <div class="progress-bar" style="width: {{ $order->progress->getProgressPercentage() }}%;">
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-percent me-1"></i>{{ $order->progress->getProgressPercentage() }}% Complete
                    </small>
                    <small class="text-muted">
                        <i class="bi bi-box me-1"></i>{{ $order->accountReceivable->submission->total_quantity }} jerseys
                    </small>
                </div>
                @else
                <small class="text-muted"><i class="bi bi-box me-1"></i>{{ $order->accountReceivable->submission->total_quantity }} jerseys</small>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="bi bi-gear-wide-connected"></i>
            <p>No orders in production</p>
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
                        <h6 class="mb-1 fw-bold">{{ $submission->salesOrder->so_number }}</h6>
                        <p class="text-muted mb-2" style="font-size: 0.9rem;">{{ $submission->salesOrder->so_name }}</p>
                        <div class="mt-2">
                            <span class="badge" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                                <i class="bi bi-box me-1"></i>{{ $submission->total_quantity }} jerseys
                            </span>
                            <span class="badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                                <i class="bi bi-cash me-1"></i>₱{{ number_format($submission->total_amount, 0) }}
                            </span>
                        </div>
                    </div>
                    <div class="text-end">
                        @if($submission->accountReceivable)
                            <span class="badge bg-success mb-2"><i class="bi bi-check-circle me-1"></i>Confirmed</span>
                        @else
                            <span class="badge bg-warning mb-2"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                        @endif
                        <div class="text-muted" style="font-size: 0.8rem;">
                            <i class="bi bi-clock me-1"></i>{{ $submission->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p>No recent orders</p>
        </div>
        @endforelse
    </div>
</div>

@endsection
