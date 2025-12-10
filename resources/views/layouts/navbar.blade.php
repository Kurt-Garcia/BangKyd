<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - BangKyd ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif
    <style>
        body {
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            padding: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            width: 250px;
            background: white;
            transition: width 0.3s ease;
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        
        .sidebar.collapsed .nav-link span {
            display: none;
        }
        
        .sidebar.collapsed .nav-link {
            text-align: center;
            padding: 0.75rem 0;
        }
        
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 18px;
        }
        
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 70px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            font-weight: 500;
            color: #333;
            padding: 0.75rem 1rem;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }
        
        .sidebar .nav-link:hover {
            color: #fa709a;
            background-color: #fff5f7;
        }
        
        .sidebar .nav-link.active {
            color: #fa709a;
            background: linear-gradient(90deg, #fff5f7 0%, #fffef5 100%);
            border-left-color: #fa709a;
        }
        
        .sidebar .nav-link i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-divider {
            margin: 0 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .sidebar.collapsed .sidebar-divider {
            margin: 0 0.5rem;
        }

        /* Sidebar Dropdown styles */
        .sidebar .nav-item.dropdown {
            position: relative;
        }

        .sidebar .nav-link.dropdown-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar .nav-link.dropdown-toggle::after {
            transition: transform 0.3s ease;
            border: none;
            content: '\F282';
            font-family: 'bootstrap-icons';
            font-size: 0.8rem;
        }

        .sidebar .nav-link.dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(90deg);
        }

        .sidebar .dropdown-menu {
            position: static !important;
            transform: none !important;
            float: none;
            border: none;
            background: #f8f9fa;
            box-shadow: none;
            padding: 0;
            margin: 0;
            border-radius: 0;
            display: block;
            width: 100%;
        }

        .sidebar .dropdown-item {
            padding: 0.5rem 1rem 0.5rem 3rem;
            color: #555;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            text-decoration: none;
            white-space: nowrap;
        }

        .sidebar .dropdown-item:hover {
            background-color: #fff5f7;
            color: #fa709a;
        }

        .sidebar .dropdown-item.active {
            background: linear-gradient(90deg, #fff5f7 0%, #fffef5 100%);
            color: #fa709a;
            border-left-color: #fa709a;
        }

        .sidebar.collapsed .dropdown-menu {
            display: none !important;
        }

        .sidebar.collapsed .nav-link.dropdown-toggle::after {
            display: none;
        }

        .sidebar.collapsed .nav-item.dropdown .nav-link span {
            display: none;
        }

        /* Top navbar dropdown styles */
        .navbar .dropdown-menu {
            position: absolute !important;
        }
        
        main {
            margin-left: 250px;
            padding-top: 56px;
            transition: margin-left 0.3s ease;
        }
        
        main.expanded {
            margin-left: 70px;
        }
        
        .navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
        }
        
        .sidebar-header {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            background: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            height: 64px;
        }
        
        .sidebar-toggle {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        
        .sidebar-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(250, 112, 154, 0.3);
        }
        
        .sidebar-logo {
            margin-left: 3rem;
            height: 100px;
            transition: opacity 0.3s ease;
        }
        
        .sidebar.collapsed .sidebar-logo {
            opacity: 0;
            width: 0;
            margin-left: 0;
        }
        
        .sidebar.collapsed .sidebar-header {
            justify-content: center;
            padding: 0.75rem;
        }
        
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1035;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-250px);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            main {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                left: 10px;
            }
            
            .sidebar-toggle.shifted {
                left: 10px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand py-0" href="{{ route('dashboard') }}">
                <img src="{{ asset('img/BangKydLogo.png') }}" alt="BangKyd Logo" style="height: 82px; margin-top: -16px; margin-bottom: -19px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->username }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button class="dropdown-item" type="submit">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
                <i class="bi bi-list" style="font-size: 1.25rem;"></i>
            </button>
            <img src="{{ asset('img/BangKydLogo.png') }}" alt="BangKyd Logo" class="sidebar-logo">
        </div>
        
        <div class="sidebar-sticky">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('sales-orders.*') ? 'active' : '' }}" href="{{ route('sales-orders.index') }}">
                        <i class="bi bi-cart-check"></i> <span>Sales Order</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('receiving-report') ? 'active' : '' }}" href="{{ route('receiving-report') }}">
                        <i class="bi bi-inbox"></i> <span>Receiving Report</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('account-receivables.*') ? 'active' : '' }}" href="{{ route('account-receivables.index') }}">
                        <i class="bi bi-cash-coin"></i> <span>Accounts Receivable</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('accounts-payable.*') ? 'active' : '' }}" href="{{ route('accounts-payable.index') }}">
                        <i class="bi bi-wallet2"></i> <span>Accounts Payable</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                        <i class="bi bi-bag-check"></i> <span>Orders</span>
                    </a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider my-3">

                <!-- Maintenance Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('users.*') || request()->routeIs('change-password') || request()->routeIs('activity-logs') || request()->routeIs('system-settings.*') || request()->routeIs('products.*') ? 'active' : '' }}" 
                       href="#" 
                       data-bs-toggle="collapse" 
                       data-bs-target="#maintenanceSubmenu" 
                       aria-expanded="{{ request()->routeIs('users.*') || request()->routeIs('change-password') || request()->routeIs('activity-logs') || request()->routeIs('system-settings.*') || request()->routeIs('products.*') ? 'true' : 'false' }}">
                        <i class="bi bi-tools"></i> <span>Maintenance</span>
                    </a>
                    <div class="collapse {{ request()->routeIs('users.*') || request()->routeIs('change-password') || request()->routeIs('activity-logs') || request()->routeIs('system-settings.*') || request()->routeIs('products.*') ? 'show' : '' }}" id="maintenanceSubmenu">
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                                    <i class="bi bi-box-seam me-2"></i>Product Management
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                    <i class="bi bi-people me-2"></i>User Management
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('change-password') ? 'active' : '' }}" href="{{ route('change-password') }}">
                                    <i class="bi bi-key me-2"></i>Change Password
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('activity-logs') ? 'active' : '' }}" href="{{ route('activity-logs') }}">
                                    <i class="bi bi-clock-history me-2"></i>Activity Logs
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('system-settings.*') ? 'active' : '' }}" href="{{ route('system-settings.index') }}">
                                    <i class="bi bi-gear me-2"></i>System Settings
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <main id="mainContent">
        <div class="container-fluid py-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const toggleIcon = sidebarToggle.querySelector('i');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            }
            
            // Toggle icon
            if (sidebar.classList.contains('collapsed') || !sidebar.classList.contains('show')) {
                toggleIcon.classList.remove('bi-x-lg');
                toggleIcon.classList.add('bi-list');
            } else {
                toggleIcon.classList.remove('bi-list');
                toggleIcon.classList.add('bi-x-lg');
            }
        }
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth > 768) {
                sidebarOverlay.classList.remove('show');
            }
        });
        
        // Store sidebar state in localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarState = localStorage.getItem('sidebarCollapsed');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const toggleIcon = sidebarToggle.querySelector('i');
            
            if (sidebarState === 'true' && window.innerWidth > 768) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                toggleIcon.classList.remove('bi-x-lg');
                toggleIcon.classList.add('bi-list');
            }
        });
        
        // Save state when toggling
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth > 768) {
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
