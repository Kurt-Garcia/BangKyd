@extends('layouts.navbar')

@section('title', 'Player Checklists')

@push('styles')
<style>
    .bw-page .progress {
        background: rgba(0, 0, 0, 0.08);
        border-radius: 999px;
        overflow: hidden;
    }

    .bw-page .progress-bar {
        background: #111;
    }

    .bw-page .card {
        border: 1px solid var(--bw-border, rgba(0, 0, 0, 0.10));
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .bw-page .table thead th {
        color: rgba(0, 0, 0, 0.75);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom-color: rgba(0, 0, 0, 0.10);
    }
</style>
@endpush

@section('content')
<div class="bw-page">
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <h1 class="h4 mb-0"><i class="bi bi-check2-square"></i> Player Checklists</h1>
    <a href="{{ route('orders.index') }}" class="btn btn-outline-dark">
        <i class="bi bi-bag-check"></i> Orders
    </a>
</div>

<div class="card mb-4 border-0">
    <div class="card-body">
        <form method="GET" action="{{ route('orders.checklists') }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-search"></i> Search</label>
                    <input type="text" class="form-control" name="search" placeholder="Order # / SO # / Customer" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><i class="bi bi-filter"></i> Status</label>
                    <select class="form-select" name="status">
                        <option value="">Ongoing + Ready</option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="ready_for_delivery" {{ request('status') == 'ready_for_delivery' ? 'selected' : '' }}>Ready for Delivery</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-dark"><i class="bi bi-funnel"></i> Filter</button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('orders.checklists') }}" class="btn btn-outline-dark"><i class="bi bi-x-circle"></i> Clear</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @forelse($items as $item)
        @php
            $order = $item['order'];
            $submission = $item['submission'];
            $totalPlayers = (int) $item['total_players'];
            $donePlayers = (int) $item['done_players'];
            $percent = $totalPlayers > 0 ? (int) round(($donePlayers / $totalPlayers) * 100) : 0;
            $soNumber = $submission?->salesOrder?->so_number ?? '-';
            $soName = $submission?->salesOrder?->so_name ?? '-';
        @endphp
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <div>
                            <div class="fw-semibold">{{ $order->order_number }}</div>
                            <div class="text-muted small">{{ $soNumber }}</div>
                        </div>
                        <span class="badge {{ $order->status === 'ready_for_delivery' ? 'bg-light text-dark border border-dark' : 'text-bg-dark' }}">
                            {{ $order->status === 'ready_for_delivery' ? 'Ready' : 'Ongoing' }}
                        </span>
                    </div>

                    <div class="text-muted small mb-3">{{ $soName }}</div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="small text-muted">Editing progress</div>
                        <div class="small fw-semibold">{{ $donePlayers }}/{{ $totalPlayers }} ({{ $percent }}%)</div>
                    </div>
                    <div class="progress mb-3" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <div class="d-grid">
                        <a href="{{ route('orders.player-checklist', $order->id) }}" class="btn btn-outline-dark">
                            <i class="bi bi-list-check"></i> Open Checklist
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-check2-square text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3 mb-0">No orders found.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection
</div>
