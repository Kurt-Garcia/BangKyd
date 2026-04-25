@extends('layouts.navbar')

@section('title', 'Business Settings')

@section('content')
@push('styles')
<style>
    .bw-page .card {
        border: 1px solid var(--bw-border, rgba(0, 0, 0, 0.10));
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .bw-page .card-header {
        background: #111 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.14);
    }

    .bw-page .form-control,
    .bw-page .form-select,
    .bw-page textarea.form-control {
        border-color: rgba(0, 0, 0, 0.14);
        border-radius: 12px;
    }

    .bw-page .form-control:focus,
    .bw-page .form-select:focus,
    .bw-page textarea.form-control:focus {
        border-color: rgba(0, 0, 0, 0.65);
        box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.08);
    }
</style>
@endpush

<div class="bw-page">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="bi bi-building"></i> Business Settings</h1>
</div>

<form action="{{ route('system-settings.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- Business Information -->
        <div class="col-12 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0 text-white"><i class="bi bi-building"></i> Business Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Business Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="business_name" value="{{ ($settings['business'] ?? collect())->where('key', 'business_name')->first()->value ?? '' }}" required>
                        @error('business_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Business Address</label>
                        <textarea class="form-control" name="business_address" rows="3">{{ ($settings['business'] ?? collect())->where('key', 'business_address')->first()->value ?? '' }}</textarea>
                        @error('business_address')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Phone Number</label>
                        <input type="text" class="form-control" name="business_phone" value="{{ ($settings['business'] ?? collect())->where('key', 'business_phone')->first()->value ?? '' }}">
                        @error('business_phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" class="form-control" name="business_email" value="{{ ($settings['business'] ?? collect())->where('key', 'business_email')->first()->value ?? '' }}">
                        @error('business_email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-grid gap-2 mb-4">
        <button type="submit" class="btn btn-dark btn-lg">
            <i class="bi bi-check-circle"></i> Save Settings
        </button>
    </div>
</form>
@endsection
</div>
