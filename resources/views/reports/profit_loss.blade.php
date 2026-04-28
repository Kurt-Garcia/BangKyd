@extends('layouts.navbar')

@section('title', 'Profit & Loss')

@push('styles')
<style>
    .bw-page .card,
    .bw-page .modal-content {
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

    .bw-page .table td {
        border-top-color: rgba(0, 0, 0, 0.08);
    }

    .bw-page .form-label {
        font-weight: 700;
        color: rgba(0, 0, 0, 0.75);
    }

    .bw-page .form-control,
    .bw-page .form-select {
        border-color: rgba(0, 0, 0, 0.14);
        border-radius: 12px;
    }

    .bw-page .form-control:focus,
    .bw-page .form-select:focus {
        border-color: rgba(0, 0, 0, 0.65);
        box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.08);
    }
</style>
@endpush

@section('content')
<div class="bw-page">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="bi bi-graph-up"></i> Profit & Loss</h1>
            <div class="text-muted small">
                {{ $dateFrom->toDateString() }} to {{ $dateTo->toDateString() }} · {{ strtoupper($basis) }} basis
            </div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-dark" href="{{ route('profit-loss.index', ['basis' => $basis]) }}">
                <i class="bi bi-arrow-clockwise"></i> Reset
            </a>
        </div>
    </div>

    <div class="card mb-4 border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('profit-loss.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label"><i class="bi bi-calendar-event"></i> Date From</label>
                        <input type="date" class="form-control" name="date_from" value="{{ request('date_from', $dateFrom->toDateString()) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="bi bi-calendar-event"></i> Date To</label>
                        <input type="date" class="form-control" name="date_to" value="{{ request('date_to', $dateTo->toDateString()) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="bi bi-sliders"></i> Basis</label>
                        <select class="form-select" name="basis">
                            <option value="cash" {{ $basis === 'cash' ? 'selected' : '' }}>Cash (Payments)</option>
                            <option value="accrual" {{ $basis === 'accrual' ? 'selected' : '' }}>Accrual (AR Confirmed / AP Created)</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-funnel"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Revenue</div>
                    <div class="h3 mb-0">₱{{ number_format($revenueTotal, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Expenses</div>
                    <div class="h3 mb-0">₱{{ number_format($expenseTotal, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Net Profit</div>
                    <div class="h3 mb-0 {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                        ₱{{ number_format($netProfit, 2) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Margin</div>
                    <div class="h3 mb-0">{{ number_format($marginPercent, 2) }}%</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="bi bi-people"></i> Revenue by Customer</h5>
                        <span class="badge text-bg-dark">Top {{ $revenueByCustomer->count() }}</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($revenueByCustomer as $row)
                                    <tr>
                                        <td>{{ $row->customer }}</td>
                                        <td class="text-end fw-semibold">₱{{ number_format((float) $row->total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">No revenue in this range.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Expenses by Vendor Type</h5>
                        <span class="badge text-bg-dark">{{ $expenseByVendor->count() }}</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Vendor Type</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenseByVendor as $row)
                                    <tr>
                                        <td class="text-capitalize">{{ $row->vendor_type }}</td>
                                        <td class="text-end fw-semibold">₱{{ number_format((float) $row->total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">No expenses in this range.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="bi bi-cash-coin"></i> Revenue Details</h5>
                        <span class="badge text-bg-dark">{{ $revenueLines->count() }} rows</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($revenueLines as $line)
                                    <tr>
                                        <td class="text-nowrap">
                                            @if($basis === 'cash')
                                                {{ optional($line->paid_at)->format('Y-m-d') }}
                                            @else
                                                {{ optional($line->confirmed_at)->format('Y-m-d') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($basis === 'cash')
                                                {{ $line->accountReceivable?->submission?->salesOrder?->so_name ?? 'Unknown' }}
                                            @else
                                                {{ $line->submission?->salesOrder?->so_name ?? 'Unknown' }}
                                            @endif
                                        </td>
                                        <td class="text-end fw-semibold">
                                            @if($basis === 'cash')
                                                ₱{{ number_format((float) $line->amount, 2) }}
                                            @else
                                                ₱{{ number_format((float) $line->total_amount, 2) }}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">No rows.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="bi bi-wallet2"></i> Expense Details</h5>
                        <span class="badge text-bg-dark">{{ $expenseLines->count() }} rows</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Vendor</th>
                                    <th>Customer</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenseLines as $line)
                                    <tr>
                                        <td class="text-nowrap">
                                            @if($basis === 'cash')
                                                {{ optional($line->paid_at)->format('Y-m-d') }}
                                            @else
                                                {{ optional($line->created_at)->format('Y-m-d') }}
                                            @endif
                                        </td>
                                        <td class="text-capitalize">
                                            @if($basis === 'cash')
                                                {{ $line->accountPayable?->vendor_type ?? 'Unknown' }}
                                            @else
                                                {{ $line->vendor_type ?? 'Unknown' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($basis === 'cash')
                                                {{ $line->accountPayable?->order?->accountReceivable?->submission?->salesOrder?->so_name ?? 'Unknown' }}
                                            @else
                                                {{ $line->order?->accountReceivable?->submission?->salesOrder?->so_name ?? 'Unknown' }}
                                            @endif
                                        </td>
                                        <td class="text-end fw-semibold">
                                            @if($basis === 'cash')
                                                ₱{{ number_format((float) $line->amount, 2) }}
                                            @else
                                                ₱{{ number_format((float) $line->total_amount, 2) }}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No rows.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">AR Outstanding (as of {{ $dateTo->toDateString() }})</div>
                            <div class="h4 mb-0">₱{{ number_format($arOutstanding, 2) }}</div>
                        </div>
                        <i class="bi bi-cash-stack fs-2 text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">AP Outstanding (as of {{ $dateTo->toDateString() }})</div>
                            <div class="h4 mb-0">₱{{ number_format($apOutstanding, 2) }}</div>
                        </div>
                        <i class="bi bi-wallet fs-2 text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

