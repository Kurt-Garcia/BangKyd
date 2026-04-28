@extends('layouts.navbar')

@section('title', 'Dashboard')

@push('styles')
<style>
    body {
        background: var(--dash2-bg, #f3f3f3);
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

    :root {
        --dash2-nav-gradient: linear-gradient(135deg, #111111 10%, #000000 40%, #1c1c1c 80%);
        --dash2-nav-primary: #111111;
        --dash2-bg: #f3f3f3;
    }

    body {
        background: var(--dash2-bg);
    }

    .legacy-dashboard {
        display: none;
    }

    .dash2-hero {
        border-radius: 22px;
        background: var(--dash2-nav-gradient);
        color: #fff;
        overflow: hidden;
        box-shadow: 0 14px 45px rgba(0, 0, 0, 0.28);
        position: relative;
    }

    .dash2-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background:
            radial-gradient(800px 200px at 15% 10%, rgba(255,255,255,0.22), rgba(255,255,255,0) 60%),
            radial-gradient(600px 240px at 85% 0%, rgba(255,255,255,0.18), rgba(255,255,255,0) 55%);
        pointer-events: none;
    }

    .dash2-hero-inner {
        position: relative;
        z-index: 1;
        padding: 1.5rem 1.5rem 1.25rem 1.5rem;
    }

    .dash2-chip {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .4rem .75rem;
        border-radius: 999px;
        background: rgba(255,255,255,0.14);
        border: 1px solid rgba(255,255,255,0.22);
        color: rgba(255,255,255,0.92);
        font-size: .85rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .dash2-title {
        font-weight: 800;
        letter-spacing: -0.02em;
        margin: 0;
    }

    .dash2-subtitle {
        opacity: .9;
        margin: .35rem 0 0 0;
    }

    .dash2-card {
        border: 0;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .dash2-card.soft {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(8px);
    }

    .dash2-kpi {
        border-radius: 18px;
        border: 0;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .dash2-kpi:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
    }

    .dash2-kpi-label {
        color: rgba(20, 35, 60, 0.65);
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: .25rem;
    }

    .dash2-kpi-value {
        font-size: 1.65rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin: 0;
        color: #0f172a;
    }

    .dash2-kpi-sub {
        margin: .35rem 0 0 0;
        font-size: .85rem;
        color: rgba(20, 35, 60, 0.7);
    }

    .dash2-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        background: var(--dash2-nav-gradient);
        box-shadow: 0 10px 22px rgba(0, 0, 0, 0.22);
        flex-shrink: 0;
    }

    .dash2-icon.soft {
        color: #111111;
        background: rgba(0, 0, 0, 0.06);
        box-shadow: none;
    }

    .dash2-mini-chart {
        width: 100%;
        height: 160px;
        border-radius: 14px;
        background: rgba(255,255,255,0.14);
        border: 1px solid rgba(255,255,255,0.22);
    }

    .dash2-donut {
        width: 132px;
        height: 132px;
        border-radius: 50%;
        background: conic-gradient(var(--dash2-nav-primary) calc(var(--p) * 1%), rgba(15, 23, 42, 0.08) 0);
        display: grid;
        place-items: center;
        position: relative;
    }

    .dash2-donut::before {
        content: "";
        width: 92px;
        height: 92px;
        border-radius: 50%;
        background: #fff;
        box-shadow: inset 0 0 0 1px rgba(15, 23, 42, 0.06);
    }

    .dash2-donut > span {
        position: absolute;
        font-weight: 800;
        color: #0f172a;
    }

    .dash2-bars {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        align-items: end;
        height: 160px;
        padding: 12px 10px;
        border-radius: 16px;
        background: rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.12);
    }

    .dash2-bar {
        width: 100%;
        border-radius: 14px;
        background: var(--dash2-nav-gradient);
        opacity: .95;
        min-height: 10px;
    }

    .dash2-bar.soft {
        background: rgba(0, 0, 0, 0.22);
    }

    .dash2-bar-meta {
        margin-top: .6rem;
        font-size: .85rem;
        color: rgba(20, 35, 60, 0.75);
        display: flex;
        justify-content: space-between;
        gap: 8px;
    }

    .dash2-row {
        display: flex;
        align-items: start;
        justify-content: space-between;
        gap: 14px;
        padding: .9rem 1rem;
        border-radius: 16px;
        border: 1px solid rgba(15, 23, 42, 0.06);
        background: rgba(255, 255, 255, 0.9);
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .dash2-row:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.10);
    }

    .dash2-row-title {
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        font-size: .95rem;
    }

    .dash2-row-sub {
        margin: .1rem 0 0 0;
        color: rgba(20, 35, 60, 0.7);
        font-size: .85rem;
    }

    .dash2-pill {
        border-radius: 999px;
        padding: .3rem .6rem;
        font-size: .78rem;
        font-weight: 700;
        color: #fff;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .dash2-cal {
        width: 100%;
        border-collapse: separate;
        border-spacing: 6px;
        font-size: .85rem;
    }

    .dash2-cal th {
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: rgba(20, 35, 60, 0.55);
        font-weight: 800;
        padding: 4px 0;
        text-align: center;
    }

    .dash2-cal td {
        text-align: center;
        padding: 8px 0;
        border-radius: 10px;
        background: rgba(0, 0, 0, 0.04);
        color: rgba(20, 35, 60, 0.85);
    }

    .dash2-cal td.muted {
        opacity: .35;
        background: rgba(15, 23, 42, 0.04);
    }

    .dash2-cal td.today {
        background: var(--dash2-nav-gradient);
        color: #fff;
        font-weight: 800;
    }
</style>
@endpush

@section('content')

@php
    $paidAmount = (float) $totalReceived;
    $outstandingAmount = (float) $totalOutstanding;
    $collectionTotal = $paidAmount + $outstandingAmount;
    $collectionPercent = $collectionTotal > 0 ? ($paidAmount / $collectionTotal) * 100 : 0;

    $orderMax = max([$ongoingOrders, $readyForDelivery, $completedOrders, 1]);

    $revValues = $trendRevenue ?? [];
    $revCount = count($revValues);
    $revMax = $revCount > 0 ? max($revValues) : 0;
    $svgW = 640;
    $svgH = 160;
    $padX = 16;
    $padY = 16;
    $plotW = $svgW - ($padX * 2);
    $plotH = $svgH - ($padY * 2);
    $pts = [];
    for ($i = 0; $i < $revCount; $i++) {
        $x = $padX + ($revCount > 1 ? ($plotW * ($i / ($revCount - 1))) : 0);
        $y = $padY + ($revMax > 0 ? ($plotH * (1 - ($revValues[$i] / $revMax))) : $plotH);
        $pts[] = [$x, $y];
    }
    $linePath = '';
    $fillPath = '';
    if ($revCount > 0) {
        $linePath = 'M ' . $pts[0][0] . ' ' . $pts[0][1];
        for ($i = 1; $i < $revCount; $i++) {
            $linePath .= ' L ' . $pts[$i][0] . ' ' . $pts[$i][1];
        }
        $fillPath = $linePath . ' L ' . $pts[$revCount - 1][0] . ' ' . ($padY + $plotH) . ' L ' . $pts[0][0] . ' ' . ($padY + $plotH) . ' Z';
    }

    $now = \Carbon\Carbon::now();
    $first = $now->copy()->startOfMonth();
    $daysInMonth = $now->daysInMonth;
    $startDow = $first->dayOfWeekIso;
    $cell = 1 - ($startDow - 1);

    $delta = (float) ($revenueDelta ?? 0);
    $deltaUp = $delta >= 0;
@endphp

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="d-flex flex-column gap-4">
            <div class="dash2-hero">
                <div class="dash2-hero-inner">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                        <div>
                            <span class="dash2-chip mb-2">
                                <i class="bi bi-activity"></i>
                                <span>Last 14 days</span>
                            </span>
                            <h2 class="dash2-title">Revenue analytics</h2>
                            <p class="dash2-subtitle">Sales order submissions total value and trend</p>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-bold">₱{{ number_format($currRevenue ?? 0, 0) }}</div>
                            <div class="small" style="opacity: .9;">
                                <span class="me-1">
                                    <i class="bi {{ $deltaUp ? 'bi-arrow-up-right' : 'bi-arrow-down-right' }}"></i>
                                </span>
                                <span>{{ number_format(abs($delta), 1) }}%</span>
                                <span class="opacity-75 ms-1">vs previous period</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 dash2-mini-chart">
                        <svg viewBox="0 0 {{ $svgW }} {{ $svgH }}" width="100%" height="160" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="dash2RevFill" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="rgba(255,255,255,0.38)"/>
                                    <stop offset="100%" stop-color="rgba(255,255,255,0)"/>
                                </linearGradient>
                            </defs>
                            @if($fillPath)
                                <path d="{{ $fillPath }}" fill="url(#dash2RevFill)"></path>
                            @endif
                            @if($linePath)
                                <path d="{{ $linePath }}" fill="none" stroke="rgba(255,255,255,0.95)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                            @endif
                        </svg>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <span class="dash2-chip"><i class="bi bi-cart-check"></i><span>{{ $totalSalesOrders }} sales orders</span></span>
                        <span class="dash2-chip"><i class="bi bi-bag-check"></i><span>{{ $totalOrders }} production orders</span></span>
                        <span class="dash2-chip"><i class="bi bi-exclamation-circle"></i><span>{{ $unconfirmedSubmissions }} unconfirmed</span></span>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-3">
                    <div class="dash2-kpi p-4 h-100">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="dash2-kpi-label">Sales Orders</div>
                                <p class="dash2-kpi-value">{{ $totalSalesOrders }}</p>
                                <p class="dash2-kpi-sub">{{ $pendingSalesOrders }} pending submissions</p>
                            </div>
                            <div class="dash2-icon"><i class="bi bi-cart-check"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dash2-kpi p-4 h-100">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="dash2-kpi-label">In Production</div>
                                <p class="dash2-kpi-value">{{ $ongoingOrders }}</p>
                                <p class="dash2-kpi-sub">{{ $readyForDelivery }} ready for delivery</p>
                            </div>
                            <div class="dash2-icon"><i class="bi bi-gear-wide-connected"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dash2-kpi p-4 h-100">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="dash2-kpi-label">AR Outstanding</div>
                                <p class="dash2-kpi-value">₱{{ number_format($totalOutstanding, 0) }}</p>
                                <p class="dash2-kpi-sub">{{ $pendingPayments + $partialPayments }} accounts</p>
                            </div>
                            <div class="dash2-icon"><i class="bi bi-cash-coin"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dash2-kpi p-4 h-100">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="dash2-kpi-label">Net Profit Potential</div>
                                <p class="dash2-kpi-value">₱{{ number_format($totalReceived - $totalAPPaid, 0) }}</p>
                                <p class="dash2-kpi-sub">After partner costs</p>
                            </div>
                            <div class="dash2-icon"><i class="bi bi-currency-dollar"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="dash2-card p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="dash2-kpi-label">Orders Status</div>
                                <div class="dash2-kpi-sub">Distribution across production</div>
                            </div>
                            <div class="dash2-icon soft"><i class="bi bi-bar-chart"></i></div>
                        </div>

                        <div class="dash2-bars mb-2">
                            <div class="dash2-bar" style="height: {{ ($ongoingOrders / $orderMax) * 100 }}%"></div>
                            <div class="dash2-bar soft" style="height: {{ ($readyForDelivery / $orderMax) * 100 }}%"></div>
                            <div class="dash2-bar" style="height: {{ ($completedOrders / $orderMax) * 100 }}%"></div>
                        </div>

                        <div class="dash2-bar-meta">
                            <span>Ongoing</span><strong>{{ $ongoingOrders }}</strong>
                        </div>
                        <div class="dash2-bar-meta">
                            <span>Ready</span><strong>{{ $readyForDelivery }}</strong>
                        </div>
                        <div class="dash2-bar-meta">
                            <span>Completed</span><strong>{{ $completedOrders }}</strong>
                        </div>

                        <div class="mt-4">
                            <div class="dash2-kpi-label mb-2">Payables</div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small text-muted">Outstanding</span>
                                <span class="fw-semibold">₱{{ number_format($totalAPOutstanding, 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted">Paid</span>
                                <span class="fw-semibold">₱{{ number_format($totalAPPaid, 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="dash2-card p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="dash2-kpi-label">Orders in Production</div>
                                <div class="dash2-kpi-sub">Most recent ongoing jobs</div>
                            </div>
                            <div class="dash2-icon soft"><i class="bi bi-clock-history"></i></div>
                        </div>

                        <div class="d-grid gap-3">
                            @forelse($ordersInProduction as $order)
                                <div class="dash2-row">
                                    <div>
                                        <p class="dash2-row-title">{{ $order->order_number }}</p>
                                        <p class="dash2-row-sub">{{ $order->accountReceivable->submission->salesOrder->so_name }}</p>
                                        <div class="small text-muted mt-1">
                                            <i class="bi bi-box me-1"></i>{{ $order->accountReceivable->submission->total_quantity }} jerseys
                                        </div>
                                    </div>
                                    <span class="dash2-pill" style="background-image: var(--dash2-nav-gradient);">Ongoing</span>
                                </div>
                            @empty
                                <div class="text-muted">No orders in production.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="dash2-card p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="dash2-kpi-label">Recent Submissions</div>
                                <div class="dash2-kpi-sub">Latest customer submissions</div>
                            </div>
                            <div class="dash2-icon soft"><i class="bi bi-inboxes"></i></div>
                        </div>

                        <div class="d-grid gap-3">
                            @forelse($recentSubmissions->take(5) as $submission)
                                <div class="dash2-row">
                                    <div>
                                        <p class="dash2-row-title">{{ $submission->salesOrder->so_number }}</p>
                                        <p class="dash2-row-sub">{{ $submission->salesOrder->so_name }}</p>
                                        <div class="small text-muted mt-1 d-flex flex-wrap gap-2">
                                            <span><i class="bi bi-box me-1"></i>{{ $submission->total_quantity }} jerseys</span>
                                            <span><i class="bi bi-cash me-1"></i>₱{{ number_format($submission->total_amount, 0) }}</span>
                                        </div>
                                    </div>
                                    <span class="dash2-pill" style="background: {{ $submission->accountReceivable ? '#111111' : 'rgba(17,17,17,0.60)' }};">
                                        {{ $submission->accountReceivable ? 'Confirmed' : 'Pending' }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-muted">No recent submissions.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="dash2-card p-4 h-100">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="dash2-kpi-label">Collection Rate</div>
                    <div class="dash2-kpi-value">{{ number_format($collectionPercent, 0) }}%</div>
                    <div class="dash2-kpi-sub">Paid vs outstanding (AR)</div>
                </div>
                <div class="dash2-icon soft">
                    <i class="bi bi-pie-chart"></i>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between gap-3 mt-4">
                <div class="dash2-donut" style="--p: {{ $collectionPercent }};">
                    <span>{{ number_format($collectionPercent, 0) }}%</span>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small text-muted">Collected</span>
                        <span class="fw-semibold">₱{{ number_format($paidAmount, 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small text-muted">Outstanding</span>
                        <span class="fw-semibold">₱{{ number_format($outstandingAmount, 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-muted">Accounts</span>
                        <span class="fw-semibold">{{ $pendingPayments + $partialPayments }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <div class="dash2-kpi-label mb-2">This Month</div>
                <div class="dash2-card soft p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold text-dark">{{ $now->format('F Y') }}</div>
                        <span class="dash2-chip" style="background: rgba(0,0,0,0.06); border-color: rgba(0,0,0,0.10); color: #111;">
                            <i class="bi bi-calendar3"></i>{{ $now->format('M') }}
                        </span>
                    </div>
                    <table class="dash2-cal">
                        <thead>
                            <tr>
                                <th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($r = 0; $r < 6; $r++)
                                <tr>
                                    @for($c = 0; $c < 7; $c++)
                                        @php
                                            $d = $cell;
                                            $isMuted = $d < 1 || $d > $daysInMonth;
                                            $isToday = !$isMuted && $d === (int) $now->format('j');
                                            $cell++;
                                        @endphp
                                        <td class="{{ $isMuted ? 'muted' : '' }} {{ $isToday ? 'today' : '' }}">
                                            {{ $isMuted ? '' : $d }}
                                        </td>
                                    @endfor
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="legacy-dashboard">

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
                    @if($order->status === 'completed')
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>Completed
                        </span>
                    @elseif($order->status === 'claimed')
                        <span class="badge bg-secondary">
                            <i class="bi bi-archive me-1"></i>Claimed
                        </span>
                    @elseif($order->status === 'ready_for_delivery')
                        <span class="badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="bi bi-box me-1"></i>Ready
                        </span>
                    @elseif($order->status === 'ongoing')
                        <span class="badge" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="bi bi-gear me-1"></i>Ongoing
                        </span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                    @endif
                </div>
                @php
                    $progressWidth = 0;
                    if ($order->status === 'ongoing') {
                        $progressWidth = 25;
                    } elseif ($order->status === 'ready_for_delivery') {
                        $progressWidth = 75;
                    } elseif ($order->status === 'completed' || $order->status === 'claimed') {
                        $progressWidth = 100;
                    }
                @endphp
                <div class="progress progress-thin mb-2">
                    <div class="progress-bar" style="width: {{ $progressWidth }}%;">
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-percent me-1"></i>{{ $progressWidth }}% Complete
                    </small>
                    <small class="text-muted">
                        <i class="bi bi-box me-1"></i>{{ $order->accountReceivable->submission->total_quantity }} jerseys
                    </small>
                </div>
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

</div>

@endsection
