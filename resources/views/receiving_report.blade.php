@extends('layouts.navbar')

@section('title', 'Receiving Report')

@push('styles')
<style>
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    .modal-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 24px 32px;
        background: #fff;
        border-radius: 16px 16px 0 0;
    }
    .modal-body {
        padding: 32px;
    }
    .modal-footer {
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 20px 32px;
        background: #f8f9fa;
        border-radius: 0 0 16px 16px;
    }
    .design-image-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .design-image-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    }
    .info-group {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        height: 100%;
    }
    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #8898aa;
        margin-bottom: 4px;
        font-weight: 600;
    }
    .info-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #32325d;
        margin-bottom: 0;
    }
    .payment-summary-card {
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 16px;
        padding: 24px;
    }
    .status-pill {
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .status-pill.paid {
        background-color: #d1e7dd;
        color: #0f5132;
    }
    .status-pill.pending {
        background-color: #fff3cd;
        color: #664d03;
    }
    .table-modern {
        margin: 0;
    }
    .table-modern th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #8898aa;
        border-bottom: 1px solid #e9ecef;
        padding: 12px 16px;
        font-weight: 600;
    }
    .table-modern td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f6f9fc;
        color: #525f7f;
        font-size: 0.95rem;
    }
    .table-modern tr:last-child td {
        border-bottom: none;
    }
    .avatar-circle {
        width: 32px;
        height: 32px;
        background-color: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
        color: #525f7f;
    }
    .submission-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
        cursor: pointer;
        position: relative;
    }
    .submission-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .submission-card .card-header-custom {
        padding: 20px 24px;
        background: white;
        border-bottom: 1px solid rgba(0,0,0,0.03);
    }
    .submission-card .card-body-custom {
        padding: 24px;
    }
    .submission-card .card-footer-custom {
        padding: 16px 24px;
        background: #f8f9fa;
        border-top: 1px solid rgba(0,0,0,0.03);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-top: 16px;
        height: 100px;
    }
    .preview-img-item {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
        background: #f1f3f5;
    }
    .preview-placeholder {
        background: #f8f9fa;
        border-radius: 12px;
        height: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        margin-top: 16px;
    }
    .so-badge {
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
        padding: 4px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="bi bi-inbox"></i> Receiving Report</h1>
    <span class="badge bg-primary">{{ $submissions->count() }} Total Submissions</span>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('receiving-report') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label"><i class="bi bi-search"></i> Search</label>
                    <input type="text" class="form-control" name="search" placeholder="SO Number or Customer Name" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><i class="bi bi-calendar"></i> From Date</label>
                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><i class="bi bi-calendar"></i> To Date</label>
                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                        @if(request()->hasAny(['search', 'date_from', 'date_to']))
                            <a href="{{ route('receiving-report') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @forelse($submissions as $submission)
    <div class="col-md-4 mb-4">
        <div class="submission-card" data-bs-toggle="modal" data-bs-target="#submissionModal{{ $submission->id }}">
            <div class="card-header-custom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="so-badge">{{ $submission->salesOrder->so_number }}</span>
                    @if($submission->is_paid)
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Paid</span>
                    @else
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3">Pending</span>
                    @endif
                </div>
                <h6 class="fw-bold text-dark mb-0 text-truncate" title="{{ $submission->salesOrder->so_name }}">{{ $submission->salesOrder->so_name }}</h6>
            </div>
            
            <div class="card-body-custom pt-0">
                <!-- Design Images Preview -->
                @if($submission->images && count($submission->images) > 0)
                @php
                    $rrCardImages = collect($submission->images)->take(3)->values();
                @endphp
                <div id="rrCardCarousel{{ $submission->id }}" class="carousel slide carousel-dark mt-3" data-bs-ride="carousel" data-bs-interval="2500">
                    <div class="carousel-inner rounded">
                        @foreach($rrCardImages as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image) }}" class="d-block w-100" alt="Design" style="height: 300px; object-fit: cover; object-position: center;">
                            </div>
                        @endforeach
                    </div>
                    @if($rrCardImages->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#rrCardCarousel{{ $submission->id }}" data-bs-slide="prev" onclick="event.stopPropagation();">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#rrCardCarousel{{ $submission->id }}" data-bs-slide="next" onclick="event.stopPropagation();">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
                @else
                <div class="preview-placeholder">
                    <i class="bi bi-image mb-2" style="font-size: 1.5rem;"></i>
                    <span class="small">No designs uploaded</span>
                </div>
                @endif
                
                <div class="mt-3 d-flex justify-content-between text-muted small fw-medium">
                    <span><i class="bi bi-people me-1"></i> {{ count($submission->players) }} Players</span>
                    <span><i class="bi bi-layers me-1"></i> {{ $submission->total_quantity }} pcs</span>
                </div>
            </div>
            
            <div class="card-footer-custom">
                <div class="text-muted small">
                    <i class="bi bi-clock me-1"></i> {{ $submission->submitted_at->format('M d, Y') }}
                </div>
                <div class="text-primary fw-bold">
                    ₱{{ number_format($submission->total_amount, 2) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Full Details -->
    <div class="modal fade" id="submissionModal{{ $submission->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold text-dark">{{ $submission->salesOrder->so_number }}</h5>
                        <p class="text-muted small mb-0">{{ $submission->salesOrder->so_name }} &bull; {{ $submission->submitted_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Left Column: Images & Payment -->
                        <div class="col-lg-5">
                            <!-- Design Images -->
                            @if($submission->images && count($submission->images) > 0)
                            <div class="mb-4">
                                <h6 class="text-uppercase text-muted small fw-bold mb-3"><i class="bi bi-images me-2"></i>Design Preview</h6>
                                <div class="d-flex justify-content-center flex-wrap gap-3">
                                    @foreach($submission->images as $index => $image)
                                        @if($index < 3)
                                        <a href="{{ asset('storage/' . $image) }}" target="_blank" class="design-image-card d-block position-relative">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Design" style="width: 140px; height: 140px; object-fit: cover;">
                                        </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Payment Summary -->
                            <div class="payment-summary-card">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="text-uppercase text-muted small fw-bold mb-0"><i class="bi bi-credit-card me-2"></i>Payment Details</h6>
                                    @if($submission->is_paid)
                                        <span class="status-pill paid"><i class="bi bi-check-circle-fill"></i> Paid</span>
                                    @else
                                        <span class="status-pill pending"><i class="bi bi-clock-fill"></i> Pending</span>
                                    @endif
                                </div>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-6">
                                        <div class="info-label">Total Qty</div>
                                        <div class="info-value">{{ $submission->total_quantity }} pcs</div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light mb-2">
                                    <span class="text-muted">Total Amount</span>
                                    <span class="fw-bold text-dark">₱{{ number_format($submission->total_amount, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light mb-2">
                                    <span class="text-danger">Down Payment (50%)</span>
                                    <span class="fw-bold text-danger">- ₱{{ number_format($submission->down_payment, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-2">
                                    <span class="text-success fw-bold">Balance Due</span>
                                    <span class="fs-4 fw-bold text-success">₱{{ number_format($submission->balance, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Players List -->
                        <div class="col-lg-7">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="text-uppercase text-muted small fw-bold mb-0"><i class="bi bi-people me-2"></i>Roster Details</h6>
                                        <span class="badge bg-light text-dark rounded-pill">{{ count($submission->players) }} Players</span>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                        @php
                                            $playersByProduct = collect($submission->players)->groupBy('product_id');
                                            $productIds = $playersByProduct->keys()->filter()->values();
                                            $productsById = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');
                                            $sortedProductIds = $playersByProduct->keys()
                                                ->sortBy(function ($productId) use ($productsById) {
                                                    $name = optional($productsById->get($productId))->name;
                                                    return strtolower((string) ($name ?? 'zzzzzz'));
                                                })
                                                ->values();
                                        @endphp

                                        @foreach($sortedProductIds as $productId)
                                            @php
                                                $product = $productsById->get($productId);
                                                $productName = $product ? $product->name : 'Unknown Product';
                                                $players = $playersByProduct->get($productId, collect());
                                            @endphp

                                            <div class="px-4 pt-3 pb-2 {{ $loop->first ? '' : 'border-top' }}">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="fw-bold text-primary"><i class="bi bi-box me-2"></i>{{ $productName }}</div>
                                                    <span class="badge bg-secondary">{{ count($players) }} pcs</span>
                                                </div>
                                            </div>

                                            <table class="table table-modern table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th style="padding-left: 24px;">#</th>
                                                        <th>Jersey Name</th>
                                                        <th>Number</th>
                                                        <th>Size</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($players as $index => $player)
                                                    <tr>
                                                        <td style="padding-left: 24px;">
                                                            <span class="text-muted">{{ $index + 1 }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="fw-bold text-dark">{{ $player['jersey_name'] }}</div>
                                                        </td>
                                                        <td>
                                                            <div class="avatar-circle">{{ $player['jersey_number'] }}</div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-light text-dark border">{{ $player['jersey_size'] }}</span>
                                                        </td>
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
                <div class="modal-footer bg-white border-top-0 pt-0 pb-4 px-4">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-warning rounded-pill px-4" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#allowResubmissionModal{{ $submission->id }}">
                                <i class="bi bi-arrow-clockwise me-2"></i>Allow Resubmission
                            </button>
                            <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#confirmPaymentModal{{ $submission->id }}">
                                <i class="bi bi-check-circle me-2"></i>Confirm Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Allow Resubmission Modal -->
    <div class="modal fade" id="allowResubmissionModal{{ $submission->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Allow Customer to Resubmit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('receiving-report.allow-resubmission', $submission->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <strong>This will allow the customer to resubmit their order.</strong>
                        </div>
                        <p><strong>SO Number:</strong> {{ $submission->salesOrder->so_number }}</p>
                        <p><strong>Customer:</strong> {{ $submission->salesOrder->so_name }}</p>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label"><strong>Reason for resubmission (optional):</strong></label>
                            <textarea class="form-control" name="reason" rows="3" placeholder="e.g., Missing player information, incorrect jersey sizes, etc."></textarea>
                        </div>
                        <p class="text-muted small"><i class="bi bi-info-circle"></i> This will unlock the order form link and delete the current submission. The customer can then submit a corrected version.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-arrow-clockwise"></i> Allow Resubmission
                        </button>
                    </div>
                </form>
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
