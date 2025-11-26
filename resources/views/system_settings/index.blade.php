@extends('layouts.navbar')

@section('title', 'System Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="bi bi-gear"></i> System Settings</h1>
</div>

<form action="{{ route('system-settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- Business Information -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <h5 class="mb-0 text-white"><i class="bi bi-building"></i> Business Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Business Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="business_name" value="{{ $settings['business']->where('key', 'business_name')->first()->value ?? '' }}" required>
                        @error('business_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Business Address</label>
                        <textarea class="form-control" name="business_address" rows="3">{{ $settings['business']->where('key', 'business_address')->first()->value ?? '' }}</textarea>
                        @error('business_address')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Phone Number</label>
                        <input type="text" class="form-control" name="business_phone" value="{{ $settings['business']->where('key', 'business_phone')->first()->value ?? '' }}">
                        @error('business_phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" class="form-control" name="business_email" value="{{ $settings['business']->where('key', 'business_email')->first()->value ?? '' }}">
                        @error('business_email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Settings -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <h5 class="mb-0 text-white"><i class="bi bi-wallet2"></i> Payment Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">GCash Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="gcash_number" value="{{ $settings['payment']->where('key', 'gcash_number')->first()->value ?? '' }}" required>
                        @error('gcash_number')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">GCash Account Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="gcash_name" value="{{ $settings['payment']->where('key', 'gcash_name')->first()->value ?? '' }}" required>
                        @error('gcash_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Down Payment Percentage <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="down_payment_percentage" value="{{ $settings['payment']->where('key', 'down_payment_percentage')->first()->value ?? '50' }}" min="0" max="100" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted">Default: 50% of total amount</small>
                        @error('down_payment_percentage')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">GCash QR Code</label>
                        @php
                            $qrPath = $settings['payment']->where('key', 'gcash_qr_image')->first()->value ?? 'img/Sample QR.svg';
                        @endphp
                        
                        <div class="mb-2">
                            <img src="{{ asset($qrPath) }}" alt="Current QR Code" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                        </div>
                        
                        <input type="file" class="form-control" name="gcash_qr_image_file" accept="image/*">
                        <small class="text-muted">Upload new QR code image (optional)</small>
                        @error('gcash_qr_image_file')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-grid gap-2 mb-4">
        <button type="submit" class="btn btn-lg text-white" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <i class="bi bi-check-circle"></i> Save Settings
        </button>
    </div>
</form>
@endsection
