<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MABIPRO - Sistem Manajemen Penjualan Properti. Pantau dan kelola status unit perumahan secara real-time.">
    <title>MABIPRO - Property Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;0,14..32,900;1,14..32,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            color: #1a1d23;
            display: flex;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ══════════════════════════════
           SIDEBAR
        ══════════════════════════════ */
        .sidebar {
            width: 210px;
            min-height: 100vh;
            background: #ffffff;
            border-right: 1px solid #e8eaed;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 50;
            box-shadow: 2px 0 8px rgba(0,0,0,0.04);
        }

        /* Logo area */
        .sidebar-logo {
            padding: 1.25rem 1rem 1rem;
            border-bottom: 1px solid #f0f2f5;
        }
        .sidebar-logo-inner {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            text-decoration: none;
        }
        .sidebar-logo-icon {
            width: 32px;
            height: 32px;
            background: #1a1d23;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-logo-icon svg { color: #fff; width: 16px; height: 16px; }
        .sidebar-logo-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }
        .sidebar-logo-name {
            font-size: 0.9375rem;
            font-weight: 800;
            color: #1a1d23;
            letter-spacing: -0.3px;
        }
        .sidebar-logo-sub {
            font-size: 0.6rem;
            font-weight: 500;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1px;
        }


        /* Nav sections */
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
            overflow-y: auto;
        }
        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.5625rem 0.75rem;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #64748b;
            text-decoration: none;
            transition: all 0.15s ease;
            cursor: pointer;
        }
        .sidebar-nav-item svg { width: 16px; height: 16px; flex-shrink: 0; }
        .sidebar-nav-item:hover {
            background: #f8fafc;
            color: #1a1d23;
        }
        .sidebar-nav-item.active {
            background: #22c55e;
            color: #ffffff;
            font-weight: 600;
        }
        .sidebar-nav-item.active svg { color: #fff; }
        .sidebar-nav-item.disabled {
            opacity: 0.45;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Sidebar footer */
        .sidebar-footer {
            padding: 0.75rem;
            border-top: 1px solid #f0f2f5;
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }
        .sidebar-footer-item {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.15s ease;
            cursor: pointer;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
        }
        .sidebar-footer-item svg { width: 15px; height: 15px; flex-shrink: 0; }
        .sidebar-footer-item:hover { background: #f8fafc; color: #475569; }
        .sidebar-footer-item.logout:hover { background: #fef2f2; color: #dc2626; }

        /* ══════════════════════════════
           MAIN CONTENT
        ══════════════════════════════ */
        .main-content {
            flex: 1;
            margin-left: 210px;
            min-height: 100vh;
            background: #f0f2f5;
            display: flex;
            flex-direction: column;
        }

        /* ══════════════════════════════
           MOBILE OVERLAY
        ══════════════════════════════ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 40;
        }

        /* Mobile hamburger */
        .mobile-topbar {
            display: none;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 1.25rem;
            background: #fff;
            border-bottom: 1px solid #e8eaed;
            position: sticky;
            top: 0;
            z-index: 30;
        }
        .hamburger-btn {
            display: flex;
            flex-direction: column;
            gap: 4px;
            cursor: pointer;
            background: none;
            border: none;
            padding: 4px;
        }
        .hamburger-btn span {
            display: block;
            width: 20px;
            height: 2px;
            background: #475569;
            border-radius: 2px;
            transition: all 0.2s;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.25s ease;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .sidebar-overlay.open { display: block; }
            .mobile-topbar { display: flex; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar" role="navigation" aria-label="Main navigation">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <a href="{{ route('marketing.dashboard') }}" class="sidebar-logo-inner" id="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div class="sidebar-logo-text">
                    <span class="sidebar-logo-name">MABIPRO</span>
                    <span class="sidebar-logo-sub">Admin</span>
                </div>
            </a>
        </div>


        {{-- Main Navigation --}}
        <nav class="sidebar-nav" role="menubar">
            {{-- Overview --}}
            <a href="#" id="nav-overview" role="menuitem"
               class="sidebar-nav-item disabled">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                </svg>
                Overview
            </a>

            {{-- Marketing --}}
            <a href="{{ route('marketing.dashboard') }}" id="nav-marketing" role="menuitem"
               class="sidebar-nav-item {{ request()->routeIs('marketing.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Marketing
            </a>

            {{-- Laporan Pembayaran --}}
            <a href="{{ route('payment.report') }}" id="nav-payment-report" role="menuitem"
               class="sidebar-nav-item {{ request()->routeIs('payment.report') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan Pembayaran
            </a>

        </nav>

        {{-- Footer --}}
        <div class="sidebar-footer">

            <button id="nav-logout" class="sidebar-footer-item logout" type="button" onclick="alert('Logout segera tersedia!')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </div>
    </aside>

    {{-- Sidebar overlay (mobile) --}}
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    {{-- Main Content --}}
    <div class="main-content">
        {{-- Mobile topbar --}}
        <div class="mobile-topbar">
            <button class="hamburger-btn" id="hamburger-btn" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>
            <span style="font-size: 0.9rem; font-weight: 700; color: #1a1d23;">MABIPRO Admin</span>
        </div>

        {{-- Page Content --}}
        {{ $slot }}
    </div>

    @livewireScripts

    <script>
        // Mobile sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const hamburger = document.getElementById('hamburger-btn');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('open');
        }
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        }

        if (hamburger) hamburger.addEventListener('click', openSidebar);
        if (overlay)   overlay.addEventListener('click', closeSidebar);
    </script>
</body>
</html>