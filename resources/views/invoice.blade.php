<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice - {{ $salesOrder->so_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: url('{{ asset('img/BG.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            min-height: 100vh;
            padding: 1.5rem 0;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            z-index: -1;
        }
        .invoice-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .invoice-box {
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            padding: 1.5rem;
            font-size: 0.9rem;
        }
        .invoice-header {
            border-bottom: 2px solid #fa709a;
            padding-bottom: 0.75rem;
            margin-bottom: 1.25rem;
        }
        .amount-box {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 6px;
            margin: 0.75rem 0;
        }
        .total-amount {
            font-size: 1.25rem;
            font-weight: bold;
            color: #fa709a;
        }
        .down-payment {
            font-size: 1.1rem;
            font-weight: bold;
            color: #dc3545;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container invoice-container">
        <div class="invoice-box">
            <div class="invoice-header">
                <div class="row align-items-center">
                    <div class="col-6">
                        <img src="{{ asset('img/BangKydLogo.png') }}" alt="BangKyd Logo" height="80">
                        <div class="mt-2">
                            <p class="mb-0 small"><strong>{{ \App\Models\SystemSetting::get('business_name', 'BangKyd ERP') }}</strong></p>
                            @if(\App\Models\SystemSetting::get('business_address'))
                                <p class="mb-0 small">{{ \App\Models\SystemSetting::get('business_address') }}</p>
                            @endif
                            @if(\App\Models\SystemSetting::get('business_phone'))
                                <p class="mb-0 small"><i class="bi bi-telephone"></i> {{ \App\Models\SystemSetting::get('business_phone') }}</p>
                            @endif
                            @if(\App\Models\SystemSetting::get('business_email'))
                                <p class="mb-0 small"><i class="bi bi-envelope"></i> {{ \App\Models\SystemSetting::get('business_email') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <h4 class="mb-0">INVOICE</h4>
                        <small class="text-muted">Order #{{ $submission->id }}</small>
                    </div>
                </div>
            </div>

            <div class="alert alert-success py-2 mb-3">
                <small><i class="bi bi-check-circle-fill"></i> <strong>Order Submitted Successfully!</strong></small>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <h6 class="mb-2">Customer Information</h6>
                    <p class="mb-1 small"><strong>Name:</strong> {{ $salesOrder->so_name }}</p>
                    <p class="mb-1 small"><strong>SO:</strong> {{ $salesOrder->so_number }}</p>
                    <p class="mb-0 small"><strong>Date:</strong> {{ $submission->submitted_at->format('M d, Y') }}</p>
                </div>
                <div class="col-6">
                    <h6 class="mb-2">Order Details</h6>
                    <p class="mb-1 small"><strong>Total Jerseys:</strong> {{ $submission->total_quantity }} pcs</p>
                    <p class="mb-1 small"><strong>Price per Jersey:</strong> ₱{{ number_format($salesOrder->product->price ?? 0, 2) }}</p>
                    <p class="mb-0 small"><strong>Submitted:</strong> {{ $submission->submitted_at->format('M d, Y') }}</p>
                </div>
            </div>

            <hr class="my-2">

            <div class="amount-box">
                <div class="row mb-1">
                    <div class="col-7">
                        <strong>Subtotal:</strong>
                    </div>
                    <div class="col-5 text-end">
                        <strong>₱{{ number_format($submission->total_amount, 2) }}</strong>
                    </div>
                </div>
                <hr class="my-2">
                <div class="row mb-1">
                    <div class="col-7">
                        <span class="total-amount">TOTAL AMOUNT:</span>
                    </div>
                    <div class="col-5 text-end">
                        <span class="total-amount">₱{{ number_format($submission->total_amount, 2) }}</span>
                    </div>
                </div>
                <hr class="my-2">
                <div class="row mb-1">
                    <div class="col-7">
                        <span class="down-payment">Down Payment (50%):</span>
                        <br><small class="text-muted">Pay upon order confirmation</small>
                    </div>
                    <div class="col-5 text-end">
                        <span class="down-payment">₱{{ number_format($submission->down_payment, 2) }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-7">
                        <strong class="text-success">Balance:</strong>
                        <br><small class="text-muted">Pay when claiming order</small>
                    </div>
                    <div class="col-5 text-end">
                        <strong class="text-success">₱{{ number_format($submission->balance, 2) }}</strong>
                    </div>
                </div>
            </div>

            <div class="alert alert-info py-2 mt-3">
                <h6 class="mb-2"><i class="bi bi-info-circle"></i> Payment Instructions</h6>
                <div class="row">
                    <div class="col-md-7">
                        <ul class="mb-2 small">
                            <li>Pay <strong>₱{{ number_format($submission->down_payment, 2) }}</strong> as down payment to confirm order</li>
                            <li>Balance of <strong>₱{{ number_format($submission->balance, 2) }}</strong> when claiming jerseys</li>
                            <li>Keep this invoice for your records</li>
                        </ul>
                        <div class="card bg-white border-0 mt-2">
                            <div class="card-body p-2">
                                <p class="mb-1 small"><strong><i class="bi bi-phone"></i> GCash Payment Details:</strong></p>
                                <p class="mb-0 small"><strong>Number:</strong> {{ \App\Models\SystemSetting::get('gcash_number', '09176461305') }}</p>
                                <p class="mb-0 small"><strong>Name:</strong> {{ \App\Models\SystemSetting::get('gcash_name', 'Kurt Gwapo') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 text-center">
                        <p class="small mb-1"><strong>Scan to Pay</strong></p>
                        @php
                            $qrPath = \App\Models\SystemSetting::get('gcash_qr_image', 'img/Sample QR.svg');
                            $qrUrl = str_starts_with($qrPath, 'img/') ? asset($qrPath) : asset('storage/' . $qrPath);
                        @endphp
                        <img src="{{ $qrUrl }}" alt="GCash QR Code" class="img-fluid" style="max-width: 150px; border: 2px solid #ddd; border-radius: 8px; padding: 5px; background: white;">
                    </div>
                </div>
            </div>

            <div class="text-center mt-3 no-print">
                <button class="btn text-white me-2" style="background: url('{{ asset('img/BG.jpg') }}') center center; background-size: cover; position: relative; overflow: hidden;" onclick="window.print()">
                    <span style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4);"></span>
                    <span style="position: relative; z-index: 1;"><i class="bi bi-printer"></i> Print</span>
                </button>
                <button class="btn btn-secondary" onclick="if(window.opener){window.close()}else{window.history.back()}">
                    <i class="bi bi-x-circle"></i> Close
                </button>
            </div>

            <div class="text-center mt-3 pt-3 border-top">
                <p class="text-muted mb-0"><small>Thank you for your business!</small></p>
                <p class="text-muted mb-0"><small>For inquiries, please contact {{ \App\Models\SystemSetting::get('business_name', 'BangKyd ERP') }}</small></p>
                @if(\App\Models\SystemSetting::get('business_phone') || \App\Models\SystemSetting::get('business_email'))
                    <p class="text-muted mb-0"><small>
                        @if(\App\Models\SystemSetting::get('business_phone'))
                            {{ \App\Models\SystemSetting::get('business_phone') }}
                        @endif
                        @if(\App\Models\SystemSetting::get('business_phone') && \App\Models\SystemSetting::get('business_email'))
                            |
                        @endif
                        @if(\App\Models\SystemSetting::get('business_email'))
                            {{ \App\Models\SystemSetting::get('business_email') }}
                        @endif
                    </small></p>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
