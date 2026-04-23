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
        ::after,
        ::before {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
            background-color: var(--background-color, #F8F9FA);
        }

        a {
            text-decoration: none;
        }

        li {
            list-style: none;
        }

        .wrapper {
            display: flex;
            width: 100%;
        }

        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 80px;
            min-width: 70px;
            z-index: 1050;
            transition: all .25s ease-in-out;
            background: linear-gradient(135deg,
            var(--primary-color, #335DA6)  10%,
            var(--secondary-color, #1E3C72) 40%,
            var(--accent-color, #33336F) 80%
            );
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        #sidebar.expand {
            width: 260px;
            min-width: 260px;
        }

        .toggle-btn {
            background-color: transparent;
            cursor: pointer;
            border: 0;
            padding: 1rem 1.5rem;
        }

        .toggle-btn i {
            font-size: 1.5rem;
            color: #FFF;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            margin: auto 0;
        }

        #sidebar:not(.expand) .sidebar-logo {
            display: none;
        }

        .sidebar-nav {
            padding: 2rem 0;
            flex: 1 1 auto;
            overflow: hidden;
        }

        a.sidebar-link {
            color: #FFF;
            border-left: 5px solid transparent;
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            white-space: nowrap;
            padding: 0.5rem 1.625rem;
            gap: .75rem;
        }

        a.sidebar-link i {
            font-size: 1.1rem;
        }

        .link-text {
            display: inline-block;
        }

        #sidebar:not(.expand) .link-text {
            display: none;
        }

        a.sidebar-link:hover {
            background-color: var(--background-color, #eff1f8);
            color: var(--accent-color, #0D6EFD);
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            clip-path: polygon(100% 100%, 98% 90%, 50% 90%, 5% 92%, 0 92%, 0 8%, 5% 8%, 50% 10%, 98% 10%, 100% 0%);
        }

        a.sidebar-link.active {
            background-color: var(--background-color, #eff1f8);
            color: var(--accent-color, #0D6EFD);
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        #sidebar .dropdown-btn {
            white-space: nowrap;
        }

        #sidebar .sub-menu {
            display: grid;
            grid-template-rows: 0fr;
            transition: 300ms ease-in-out;
        }

        #sidebar .sub-menu div {
            overflow: hidden;
        }

        #sidebar .sub-menu.showdropdown {
            grid-template-rows: 1fr;
        }

        #sidebar:not(.expand) .sub-menu {
            grid-template-rows: 0fr !important;
        }

        .rotate span:last-child {
            rotate: 180deg;
        }

        .dropdown-btn {
            background-color: transparent;
            border: none;
            color: #eff1f8;
            padding: 0.5rem 1.625rem;
            width: 100%;
            border-left: 3px solid transparent;
            border-top: 3px solid transparent;
            border-bottom: 3px solid transparent;
        }

        .dropdown-btn:hover {
            background-color: var(--background-color, #eff1f8);
            border-left: 3px solid var(--accent-color, #33336F);
            border-top: 3px solid var(--accent-color, #33336F);
            border-bottom: 3px solid var(--accent-color, #33336F);
            color: var(--text-color, #4f688f);
        }

        .dropdown-btn.active {
            background-color: var(--background-color, #eff1f8);
            border-left: 3px solid var(--accent-color, #33336F);
            border-top: 3px solid var(--accent-color, #33336F);
            border-bottom: 3px solid var(--accent-color, #33336F);
            color: var(--text-color, #4f688f);
        }

        #sidebar .dropdown-btn span:first-child {
            font-size: 14px;
            font-weight: 500;
        }

        #sidebar .dropdown-btn span:nth-child(2) {
            padding-left: 10px;
            font-size: 0.85rem;
            font-weight: 400;
        }

        #sidebar:not(.expand) .dropdown-btn span:nth-child(2),
        #sidebar:not(.expand) .dropdown-btn span:last-child {
            display: none;
        }

        .sub-menu .sidebar-link {
            padding: .325rem 1.625rem;
        }

        .main {
            margin-left: 80px;
            width: calc(100% - 80px);
            min-height: 100vh;
            transition: all 0.35s ease-in-out;
            overflow-x: hidden;
        }

        @media (max-width: 768px) {
            .main {
                margin-left: 80px;
                width: calc(100% - 80px);
                padding: 0 10px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="wrapper w-100">
        <aside id="sidebar">
            <div class="d-flex mt-2 btn-toggle">
                <button class="toggle-btn btn-toggle" type="button" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2">
                        <img src="{{ asset('img/BangKydLogo.png') }}" alt="BangKyd Logo" style="height: 40px;">
                        <span class="text-white fw-semibold">BangKyd ERP</span>
                    </a>
                </div>
            </div>
            <ul class="sidebar-nav p-0 m-0">
                <li>
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span class="link-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sales-orders.index') }}" class="sidebar-link {{ request()->routeIs('sales-orders.*') ? 'active' : '' }}">
                        <i class="bi bi-cart-check"></i>
                        <span class="link-text">Sales Order</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('receiving-report') }}" class="sidebar-link {{ request()->routeIs('receiving-report') ? 'active' : '' }}">
                        <i class="bi bi-inbox"></i>
                        <span class="link-text">Receiving Report</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('account-receivables.index') }}" class="sidebar-link {{ request()->routeIs('account-receivables.*') ? 'active' : '' }}">
                        <i class="bi bi-cash-coin"></i>
                        <span class="link-text">Accounts Receivable</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('accounts-payable.index') }}" class="sidebar-link {{ request()->routeIs('accounts-payable.*') ? 'active' : '' }}">
                        <i class="bi bi-wallet2"></i>
                        <span class="link-text">Accounts Payable</span>
                    </a>
                </li>
                <li>
                    <button class="dropdown-btn d-flex align-items-center {{ request()->routeIs('orders.*') ? 'active' : '' }}" onclick="toggleSubMenu(this)" type="button">
                        <span class="bi bi-bag-check"></span>
                        <span>Orders</span>
                        <span class="bi bi-chevron-down ms-auto"></span>
                    </button>
                    <ul class="sub-menu {{ request()->routeIs('orders.*') ? 'showdropdown' : '' }}">
                        <div>
                            <li>
                                <a href="{{ route('orders.index') }}" class="sidebar-link {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                                    <i class="bi bi-grid"></i>
                                    <span class="link-text">Orders List</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('orders.checklists') }}" class="sidebar-link {{ request()->routeIs('orders.checklists') || request()->routeIs('orders.player-checklist') ? 'active' : '' }}">
                                    <i class="bi bi-check2-square"></i>
                                    <span class="link-text">Player Checklists</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>
                <li class="px-4">
                    <hr class="text-white opacity-50 my-3">
                </li>
                <li>
                    @php
                        $maintenanceActive = request()->routeIs('products.*')
                            || request()->routeIs('users.*')
                            || request()->routeIs('change-password')
                            || request()->routeIs('activity-logs')
                            || request()->routeIs('system-settings.*');
                    @endphp
                    <button class="dropdown-btn d-flex align-items-center {{ $maintenanceActive ? 'active' : '' }}" onclick="toggleSubMenu(this)" type="button">
                        <span class="bi bi-tools"></span>
                        <span>Maintenance</span>
                        <span class="bi bi-chevron-down ms-auto"></span>
                    </button>
                    <ul class="sub-menu {{ $maintenanceActive ? 'showdropdown' : '' }}">
                        <div>
                            <li>
                                <a href="{{ route('products.index') }}" class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                                    <i class="bi bi-box-seam"></i>
                                    <span class="link-text">Product Management</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                    <i class="bi bi-people"></i>
                                    <span class="link-text">User Management</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('change-password') }}" class="sidebar-link {{ request()->routeIs('change-password') ? 'active' : '' }}">
                                    <i class="bi bi-key"></i>
                                    <span class="link-text">Change Password</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('activity-logs') }}" class="sidebar-link {{ request()->routeIs('activity-logs') ? 'active' : '' }}">
                                    <i class="bi bi-clock-history"></i>
                                    <span class="link-text">Activity Logs</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('system-settings.index') }}" class="sidebar-link {{ request()->routeIs('system-settings.*') ? 'active' : '' }}">
                                    <i class="bi bi-gear"></i>
                                    <span class="link-text">System Settings</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </aside>

        <div class="main">
            <nav class="navbar navbar-expand bg-white border-bottom shadow-sm sticky-top">
                <div class="container-fluid">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-semibold text-secondary">@yield('title', 'Dashboard')</span>
                    </div>
                    <ul class="navbar-nav ms-auto">
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
            </nav>

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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script>
        function toggleSubMenu(button) {
            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;

            if (!sidebar.classList.contains('expand')) {
                sidebar.classList.add('expand');
            }

            const subMenu = button.nextElementSibling;
            if (!subMenu) return;

            subMenu.classList.toggle('showdropdown');
            button.classList.toggle('rotate');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const toggleArea = document.querySelector('.btn-toggle');

            if (sidebar && toggleArea) {
                toggleArea.addEventListener('click', function () {
                    sidebar.classList.toggle('expand');

                    if (!sidebar.classList.contains('expand')) {
                        document.querySelectorAll('#sidebar .sub-menu.showdropdown').forEach(function (ul) {
                            ul.classList.remove('showdropdown');
                            const btn = ul.previousElementSibling;
                            if (btn) btn.classList.remove('rotate');
                        });
                    }
                });
            }

            document.querySelectorAll('#sidebar .sub-menu.showdropdown').forEach(function (ul) {
                const btn = ul.previousElementSibling;
                if (btn) btn.classList.add('rotate');
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
