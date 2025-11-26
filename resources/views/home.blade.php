<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'BangKyd') }} - Jersey Workflow Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif
    <style>
        :root {
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-success: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --gradient-info: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        
        .hero-section {
            background: var(--gradient-primary);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
            padding: 100px 0;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            opacity: 0.95;
            line-height: 1.8;
            margin-bottom: 2rem;
        }
        
        .btn-hero {
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-hero-primary {
            background: white;
            color: #667eea;
            border: none;
        }
        
        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            color: #667eea;
        }
        
        .btn-hero-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn-hero-outline:hover {
            background: white;
            color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            height: 100%;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: white;
        }
        
        .icon-primary { background: var(--gradient-primary); }
        .icon-success { background: var(--gradient-success); }
        .icon-info { background: var(--gradient-info); }
        
        .feature-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #2d3748;
        }
        
        .feature-description {
            color: #718096;
            line-height: 1.8;
        }
        
        .workflow-step {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            height: 100%;
        }
        
        .workflow-step:hover {
            border-color: #667eea;
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
        }
        
        .workflow-step .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin: 0 auto 1rem;
        }
        
        .workflow-step .step-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .workflow-step .step-title {
            font-weight: 700;
            font-size: 1.1rem;
            color: #2d3748;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: #2d3748;
        }
        
        .section-subtitle {
            font-size: 1.1rem;
            color: #718096;
            margin-bottom: 3rem;
        }
        
        .stats-section {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 80px 0;
        }
        
        .stat-card {
            text-align: center;
            padding: 2rem;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-label {
            color: #718096;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }
        
        .cta-section {
            background: var(--gradient-primary);
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        
        .cta-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }
        
        .footer {
            background: #2d3748;
            color: white;
            padding: 40px 0 20px;
        }
        
        .animate-fade-in {
            animation: fadeIn 1s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
            .hero-content { padding: 60px 0; }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-lightning-charge-fill"></i> BangKyd
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#workflow">Workflow</a></li>
                    @auth
                        <li class="nav-item">
                            <a class="btn btn-primary px-4" href="{{ url('/dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="btn btn-primary px-4" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Log in
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center hero-content">
                <div class="col-lg-6 animate-fade-in">
                    <h1 class="hero-title">Streamline Your Jersey Production Workflow</h1>
                    <p class="hero-subtitle">From order intake to delivery, manage every step of your jersey production process in one powerful platform. Built for efficiency, designed for growth.</p>
                    <div class="d-flex gap-3 flex-wrap">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-hero btn-hero-primary">
                                Get Started <i class="bi bi-arrow-right"></i>
                            </a>
                        @else
                            <a href="{{ url('/dashboard') }}" class="btn btn-hero btn-hero-primary">
                                Go to Dashboard <i class="bi bi-arrow-right"></i>
                            </a>
                        @endguest
                        <a href="#features" class="btn btn-hero btn-hero-outline">
                            Learn More
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="workflow-step">
                                <div class="step-number">1</div>
                                <i class="bi bi-cart-check-fill step-icon"></i>
                                <div class="step-title">Order Intake</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="workflow-step">
                                <div class="step-number">2</div>
                                <i class="bi bi-palette-fill step-icon"></i>
                                <div class="step-title">Design</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="workflow-step">
                                <div class="step-number">3</div>
                                <i class="bi bi-printer-fill step-icon"></i>
                                <div class="step-title">Printing</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="workflow-step">
                                <div class="step-number">4</div>
                                <i class="bi bi-layers-fill step-icon"></i>
                                <div class="step-title">Pressing</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="workflow-step">
                                <div class="step-number">5</div>
                                <i class="bi bi-scissors step-icon"></i>
                                <div class="step-title">Tailoring</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="workflow-step">
                                <div class="step-number">6</div>
                                <i class="bi bi-truck step-icon"></i>
                                <div class="step-title">Delivery</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5" style="padding: 100px 0 !important;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Powerful Features for Your Business</h2>
                <p class="section-subtitle">Everything you need to manage jersey production efficiently</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="feature-icon icon-primary">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h3 class="feature-title">Real-time Tracking</h3>
                        <p class="feature-description">Monitor every order from submission to delivery. Know exactly where each jersey is in the production pipeline with live updates.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="feature-icon icon-success">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <h3 class="feature-title">Financial Management</h3>
                        <p class="feature-description">Track accounts receivable and payable automatically. Manage customer payments and partner costs in one place with detailed reports.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="feature-icon icon-info">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h3 class="feature-title">Partner Collaboration</h3>
                        <p class="feature-description">Generate unique links for partners to update production progress. Keep everyone in sync without constant phone calls or messages.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="feature-icon icon-primary">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <h3 class="feature-title">Automated Invoicing</h3>
                        <p class="feature-description">Generate professional invoices instantly. Customers receive detailed breakdowns with payment instructions and QR codes.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="feature-icon icon-success">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <h3 class="feature-title">Comprehensive Dashboard</h3>
                        <p class="feature-description">Get instant insights with beautiful charts and statistics. See pending orders, revenue, production status, and profit margins at a glance.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="feature-icon icon-info">
                            <i class="bi bi-link-45deg"></i>
                        </div>
                        <h3 class="feature-title">Customer Portal</h3>
                        <p class="feature-description">Customers submit orders through unique links. Upload designs, enter player details, and receive instant invoices—all automated.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-number"><i class="bi bi-infinity"></i></div>
                        <div class="stat-label">Unlimited Orders</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">6</div>
                        <div class="stat-label">Production Stages</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Automated</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Accessible</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Workflow Section -->
    <section id="workflow" class="py-5" style="padding: 100px 0 !important; background: white;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">How It Works</h2>
                <p class="section-subtitle">Six simple steps to manage your entire production</p>
            </div>
            <div class="row g-4">
                <div class="col-md-2 col-6">
                    <div class="text-center">
                        <div class="feature-icon icon-primary mx-auto">
                            <i class="bi bi-1-circle-fill"></i>
                        </div>
                        <h5 class="mt-3">Create Sales Order</h5>
                        <p class="small text-muted">Generate unique link for customer</p>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="text-center">
                        <div class="feature-icon icon-success mx-auto">
                            <i class="bi bi-2-circle-fill"></i>
                        </div>
                        <h5 class="mt-3">Customer Submits</h5>
                        <p class="small text-muted">Upload designs & player details</p>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="text-center">
                        <div class="feature-icon icon-info mx-auto">
                            <i class="bi bi-3-circle-fill"></i>
                        </div>
                        <h5 class="mt-3">Confirm Order</h5>
                        <p class="small text-muted">Review and create AR</p>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="text-center">
                        <div class="feature-icon icon-primary mx-auto">
                            <i class="bi bi-4-circle-fill"></i>
                        </div>
                        <h5 class="mt-3">Generate Partner Link</h5>
                        <p class="small text-muted">AP created automatically</p>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="text-center">
                        <div class="feature-icon icon-success mx-auto">
                            <i class="bi bi-5-circle-fill"></i>
                        </div>
                        <h5 class="mt-3">Track Progress</h5>
                        <p class="small text-muted">Partners update each stage</p>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="text-center">
                        <div class="feature-icon icon-info mx-auto">
                            <i class="bi bi-6-circle-fill"></i>
                        </div>
                        <h5 class="mt-3">Complete & Deliver</h5>
                        <p class="small text-muted">Payment & order completion</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Ready to Transform Your Jersey Business?</h2>
            <p class="lead mb-4">Start managing your production workflow efficiently today</p>
            @guest
                <a href="{{ route('login') }}" class="btn btn-hero btn-hero-primary btn-lg">
                    Get Started Now <i class="bi bi-arrow-right ms-2"></i>
                </a>
            @else
                <a href="{{ url('/dashboard') }}" class="btn btn-hero btn-hero-primary btn-lg">
                    Go to Dashboard <i class="bi bi-speedometer2 ms-2"></i>
                </a>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">
                        <i class="bi bi-lightning-charge-fill"></i> BangKyd ERP
                    </h5>
                    <p class="text-light opacity-75">Professional jersey production workflow management system.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 text-light opacity-75">&copy; {{ date('Y') }} BangKyd. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+kbeLJwWJknhk+aoCA8DmF5asJ5AZt0pOEtpJR/YWZLxE+nobVht5cVbE+1WVP" crossorigin="anonymous"></script>
    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
