<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'BangKyd') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="#">BangKyd ERP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-2">
                    @auth
                        <li class="nav-item"><a class="btn btn-outline-secondary" href="{{ url('/dashboard') }}">Dashboard</a></li>
                    @else
                        <li class="nav-item"><a class="btn btn-primary" href="{{ route('login') }}">Log in</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <h1 class="display-5 fw-semibold">Run your jersey workflow with ease</h1>
                    <p class="text-secondary">Orders to design, printing, pressing, tailoring, and delivery – all tracked in one place. Simple, fast, and built for small teams.</p>
                    <div class="d-flex gap-2">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary">Log in</a>
                        @endguest
                        <a href="#features" class="btn btn-outline-secondary">Explore features</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="card h-100"><div class="card-body"><div class="text-muted small">Step</div><div class="fw-medium mt-1">Order Intake</div></div></div>
                        </div>
                        <div class="col-6">
                            <div class="card h-100"><div class="card-body"><div class="text-muted small">Step</div><div class="fw-medium mt-1">Design</div></div></div>
                        </div>
                        <div class="col-6">
                            <div class="card h-100"><div class="card-body"><div class="text-muted small">Step</div><div class="fw-medium mt-1">Printing</div></div></div>
                        </div>
                        <div class="col-6">
                            <div class="card h-100"><div class="card-body"><div class="text-muted small">Step</div><div class="fw-medium mt-1">Pressing</div></div></div>
                        </div>
                        <div class="col-6">
                            <div class="card h-100"><div class="card-body"><div class="text-muted small">Step</div><div class="fw-medium mt-1">Tailoring</div></div></div>
                        </div>
                        <div class="col-6">
                            <div class="card h-100"><div class="card-body"><div class="text-muted small">Step</div><div class="fw-medium mt-1">Delivery</div></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+kbeLJwWJknhk+aoCA8DmF5asJ5AZt0pOEtpJR/YWZLxE+nobVht5cVbE+1WVP" crossorigin="anonymous"></script>
</body>
</html>
