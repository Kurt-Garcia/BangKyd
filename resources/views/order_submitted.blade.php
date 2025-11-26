<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Submitted</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: url('{{ asset('img/BG.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
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
        .success-card {
            max-width: 500px;
            text-align: center;
        }
        .success-icon {
            font-size: 5rem;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card success-card mx-auto shadow-lg">
            <div class="card-body p-5">
                <i class="bi bi-check-circle-fill success-icon"></i>
                <h2 class="mt-4 mb-3">This order has already been submitted.</h2>
                <p class="text-muted">Thank you for your submission. We will process your order shortly.</p>
                
                @if(isset($submission))
                <div class="mt-4">
                    <a href="{{ route('invoice.show', $submission->id) }}" class="btn btn-lg text-white" style="background: url('{{ asset('img/BG.jpg') }}') center center; background-size: cover; position: relative; overflow: hidden;">
                        <span style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4);"></span>
                        <span style="position: relative; z-index: 1;"><i class="bi bi-file-earmark-text"></i> View Invoice</span>
                    </a>
                </div>
                @endif
                
                <hr class="my-4">
                <small class="text-muted">You can now close this page.</small>
            </div>
        </div>
    </div>
</body>
</html>
