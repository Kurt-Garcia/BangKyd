<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Submitted</title>
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
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 0;
            color: var(--ink);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f3f3;
        }
        
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.60);
            pointer-events: none;
            z-index: 0;
        }

        .wrap {
            position: relative;
            z-index: 1;
            max-width: 980px;
            width: 100%;
            padding: 0 16px;
        }

        .card-shell {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 22px;
            box-shadow: var(--shadow);
            overflow: hidden;
            width: 100%;
            max-width: 720px;
            margin: 0 auto;
        }

        .hero {
            padding: 22px 22px 18px 22px;
            background: linear-gradient(180deg, rgba(0,0,0,0.04) 0%, rgba(255,255,255,0) 100%);
            border-bottom: 1px solid var(--border);
        }

        .brand-logo {
            width: 58px;
            height: 58px;
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

        .body {
            padding: 18px 22px 22px 22px;
            text-align: center;
        }

        .title {
            font-weight: 900;
            letter-spacing: -0.02em;
            margin: 8px 0 6px 0;
        }

        .subtle {
            color: var(--muted);
        }

        .footer-note {
            border-top: 1px solid var(--border);
            padding: 14px 22px;
            background: rgba(0,0,0,0.02);
            color: var(--muted);
            font-size: 0.85rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card-shell">
            <div class="hero">
                <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-3">
                        <div class="brand-logo">
                            <img src="{{ asset('img/BangKydLogo.png') }}" alt="BangKyd Logo">
                        </div>
                        <div>
                            <div class="fw-bold">{{ \App\Models\SystemSetting::get('business_name', 'BangKyd ERP') }}</div>
                            <div class="small subtle">Order submission status</div>
                        </div>
                    </div>
                    <div class="text-end ms-auto">
                        <span class="chip"><i class="bi bi-check-circle"></i> Submitted</span>
                    </div>
                </div>
            </div>

            <div class="body">
                <h2 class="title">This order has already been submitted.</h2>
                <p class="subtle mb-0">Thank you for your submission. We will process your order shortly.</p>

                @if(isset($submission))
                    <div class="d-flex justify-content-center gap-2 flex-wrap mt-4">
                        <a href="{{ route('invoice.show', $submission->id) }}" class="btn btn-dark btn-lg">
                            <i class="bi bi-file-earmark-text me-1"></i> View Invoice
                        </a>
                        <button type="button" class="btn btn-outline-dark btn-lg" onclick="if(window.opener){window.close()}else{window.history.back()}">
                            <i class="bi bi-x-circle me-1"></i> Close
                        </button>
                    </div>
                @else
                    <div class="d-flex justify-content-center gap-2 flex-wrap mt-4">
                        <button type="button" class="btn btn-outline-dark btn-lg" onclick="if(window.opener){window.close()}else{window.history.back()}">
                            <i class="bi bi-x-circle me-1"></i> Close
                        </button>
                    </div>
                @endif
            </div>

            <div class="footer-note">
                You can now close this page.
            </div>
        </div>
    </div>
</body>
</html>
