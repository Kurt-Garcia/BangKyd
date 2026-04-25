<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice - {{ $salesOrder->so_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --ink: #111111;
            --muted: rgba(17, 17, 17, 0.65);
            --border: rgba(0, 0, 0, 0.10);
            --surface: rgba(255, 255, 255, 0.92);
            --shadow: 0 18px 55px rgba(0, 0, 0, 0.12);
        }

        body {
            background: url('{{ asset('img/bg.svg') }}') no-repeat center center fixed;
            background-size: cover;
            background-color: #f3f3f3;
            min-height: 100vh;
            padding: 28px 0;
            color: var(--ink);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.60);
            pointer-events: none;
            z-index: 0;
        }

        .invoice-wrap {
            position: relative;
            z-index: 1;
            max-width: 980px;
            margin: 0 auto;
            padding: 0 16px;
        }

        .invoice-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 22px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .invoice-hero {
            padding: 26px 28px 18px 28px;
            background: linear-gradient(180deg, rgba(0,0,0,0.04) 0%, rgba(255,255,255,0) 100%);
            border-bottom: 1px solid var(--border);
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: #fff;
            border: 1px solid var(--border);
            display: grid;
            place-items: center;
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.10);
            overflow: hidden;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .meta-label {
            font-size: 0.72rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted);
            font-weight: 700;
            margin-bottom: 4px;
        }

        .meta-value {
            font-weight: 800;
            color: var(--ink);
        }

        .invoice-title {
            font-weight: 900;
            letter-spacing: -0.02em;
            margin: 0;
        }

        .subtle {
            color: var(--muted);
        }

        .section {
            padding: 18px 28px;
        }

        .rule {
            height: 1px;
            background: var(--border);
        }

        .table thead th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.10em;
            color: var(--muted);
            border-bottom: 1px solid var(--border);
            padding: 14px 14px;
            background: rgba(0,0,0,0.02);
        }

        .table td {
            border-top: 1px solid rgba(0, 0, 0, 0.06);
            padding: 14px 14px;
            vertical-align: top;
        }

        .totals {
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.86);
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }

        .totals-row:last-child {
            border-bottom: 0;
        }

        .totals-row .label {
            color: var(--muted);
            font-weight: 700;
        }

        .totals-row .value {
            font-weight: 800;
            color: var(--ink);
            white-space: nowrap;
        }

        .totals-row.total .label,
        .totals-row.total .value {
            font-size: 1.05rem;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(0,0,0,0.06);
            border: 1px solid rgba(0,0,0,0.10);
            color: var(--ink);
            font-weight: 700;
            font-size: 0.85rem;
        }

        .pay-card {
            border: 1px solid var(--border);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.86);
            padding: 16px;
        }

        .footer-bar {
            padding: 14px 28px;
            border-top: 1px solid var(--border);
            background: rgba(0,0,0,0.02);
            color: var(--muted);
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .invoice-hero,
            .section,
            .footer-bar {
                padding-left: 18px;
                padding-right: 18px;
            }
        }

        @media print {
            @page {
                margin: 10mm;
            }

            body {
                background: #fff;
                padding: 0;
                font-size: 12px;
            }
            .no-print,
            .no-print * {
                display: none !important;
            }
            body::before {
                display: none;
            }
            .invoice-wrap {
                max-width: none;
                padding: 0;
            }
            .invoice-card {
                box-shadow: none;
                border: 0;
                border-radius: 0;
            }
            .invoice-hero {
                background: #fff;
                padding: 14px 16px 10px 16px;
            }
            .section {
                padding: 10px 16px;
            }
            .footer-bar {
                padding: 10px 16px;
            }
            .brand-logo {
                width: 44px;
                height: 44px;
                border-radius: 12px;
                box-shadow: none;
            }
            .invoice-title {
                font-size: 1.35rem;
            }
            .table thead th {
                padding: 8px 10px;
            }
            .table td {
                padding: 8px 10px;
            }
            .pay-card,
            .totals,
            .invoice-hero,
            .footer-bar {
                break-inside: avoid;
                page-break-inside: avoid;
            }
            .table-responsive {
                overflow: visible !important;
            }
        }
    </style>
</head>
<body>
    @php
        $businessName = \App\Models\SystemSetting::get('business_name', 'BangKyd ERP');
        $businessAddress = \App\Models\SystemSetting::get('business_address');
        $businessPhone = \App\Models\SystemSetting::get('business_phone');
        $businessEmail = \App\Models\SystemSetting::get('business_email');

        $invoiceNo = 'INV-' . str_pad((string) $submission->id, 6, '0', STR_PAD_LEFT);

        $players = collect($submission->players ?? []);
        $productIds = $players->pluck('product_id')->filter()->unique()->values();
        $productsById = $productIds->count() > 0
            ? \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id')
            : collect();

        $items = [];
        if ($productIds->count() > 0) {
            foreach ($productIds as $productId) {
                $product = $productsById->get($productId);
                $qty = (int) $players->where('product_id', $productId)->count();
                $rate = (float) ($product?->price ?? 0);
                $items[] = [
                    'name' => $product?->name ?? 'Unknown Product',
                    'qty' => $qty,
                    'rate' => $rate,
                    'amount' => $qty * $rate,
                ];
            }
        } else {
            $rate = (float) ($salesOrder->product->price ?? 0);
            $items[] = [
                'name' => $salesOrder->product->name ?? 'Jerseys',
                'qty' => (int) ($submission->total_quantity ?? 0),
                'rate' => $rate,
                'amount' => (float) ($submission->total_amount ?? 0),
            ];
        }

    @endphp

    <div class="invoice-wrap">
        <div class="invoice-card">
            <div class="invoice-hero">
                <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-3">
                        <div class="brand-logo">
                            <img src="{{ asset('img/BangKydLogo.png') }}" alt="BangKyd Logo">
                        </div>
                        <div>
                            <div class="fw-bold">{{ $businessName }}</div>
                            @if($businessAddress)
                                <div class="small subtle">{{ $businessAddress }}</div>
                            @endif
                            <div class="small subtle">
                                @if($businessEmail)
                                    <span class="me-2"><i class="bi bi-envelope"></i> {{ $businessEmail }}</span>
                                @endif
                                @if($businessPhone)
                                    <span><i class="bi bi-telephone"></i> {{ $businessPhone }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="text-end ms-auto">
                        <div class="chip mb-2"><i class="bi bi-check-circle"></i> Submitted</div>
                        <h2 class="invoice-title">Invoice</h2>
                        <div class="small subtle">Sales Order: {{ $salesOrder->so_number }}</div>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-lg-6">
                        <div class="meta-label">Recipient</div>
                        <div class="meta-value">{{ $salesOrder->so_name }}</div>
                        <div class="small subtle mt-1">
                            Total quantity: {{ $submission->total_quantity }} pcs
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="meta-label">Invoice No.</div>
                                <div class="meta-value">{{ $invoiceNo }}</div>
                            </div>
                            <div class="col-6">
                                <div class="meta-label">Invoice Date</div>
                                <div class="meta-value">{{ $submission->submitted_at->format('F d, Y') }}</div>
                            </div>
                            <div class="col-6">
                                <div class="meta-label">Submission ID</div>
                                <div class="meta-value">#{{ $submission->id }}</div>
                            </div>
                            <div class="col-6">
                                <div class="meta-label">Deadline</div>
                                <div class="meta-value">
                                    {{ $submission->deadline_date ? \Illuminate\Support\Carbon::parse($submission->deadline_date)->format('F d, Y') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th style="width: 110px;" class="text-end">Qty</th>
                                <th style="width: 170px;" class="text-end">Rate</th>
                                <th style="width: 190px;" class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item['name'] }}</td>
                                    <td class="text-end">{{ number_format((int) $item['qty']) }}</td>
                                    <td class="text-end">₱{{ number_format((float) $item['rate'], 2) }}</td>
                                    <td class="text-end fw-bold">₱{{ number_format((float) $item['amount'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rule"></div>

            <div class="section">
                <div class="row g-4 align-items-start">
                    <div class="col-lg-7">
                        <div class="pay-card">
                            <div class="fw-bold mb-2"><i class="bi bi-credit-card me-1"></i> Payment Instructions</div>
                            <div class="small subtle mb-3">
                                For security, payment details are not displayed on this public page. Please use our official channels to confirm where to send your down payment.
                            </div>

                            <div class="small subtle mt-3">
                                Reference your invoice number <span class="fw-semibold">{{ $invoiceNo }}</span> when contacting us.
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="totals">
                            <div class="totals-row">
                                <div class="label">Subtotal</div>
                                <div class="value">₱{{ number_format((float) $submission->total_amount, 2) }}</div>
                            </div>
                            <div class="totals-row">
                                <div class="label">Down Payment (50%)</div>
                                <div class="value">₱{{ number_format((float) $submission->down_payment, 2) }}</div>
                            </div>
                            <div class="totals-row">
                                <div class="label">Balance</div>
                                <div class="value">₱{{ number_format((float) $submission->balance, 2) }}</div>
                            </div>
                            <div class="totals-row total">
                                <div class="label">Total</div>
                                <div class="value">₱{{ number_format((float) $submission->total_amount, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="meta-label">Notes</div>
                    <div class="small subtle">
                        Down payment confirms the order. Remaining balance is payable upon claiming. Please keep this invoice for your records.
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4 no-print">
                    <button type="button" class="btn btn-dark" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Print
                    </button>
                    <button type="button" class="btn btn-outline-dark" onclick="if(window.opener){window.close()}else{window.history.back()}">
                        <i class="bi bi-x-circle me-1"></i> Close
                    </button>
                </div>
            </div>

            <div class="footer-bar">
                <div class="d-flex flex-wrap justify-content-between gap-2">
                    <div>
                        <span class="fw-semibold">{{ $businessName }}</span>
                        <span class="mx-2">•</span>
                        <span>Invoice {{ $invoiceNo }}</span>
                    </div>
                    <div>
                        @if($businessEmail)
                            <span class="me-3"><i class="bi bi-envelope"></i> {{ $businessEmail }}</span>
                        @endif
                        @if($businessPhone)
                            <span><i class="bi bi-telephone"></i> {{ $businessPhone }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
