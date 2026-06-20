<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MABIPRO - Sistem Manajemen Penjualan Properti. Pantau dan kelola status unit perumahan secara real-time.">
    <title>MABIPRO - Dashboard Marketing</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* ── Navbar ── */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226,232,240,0.8);
            box-shadow: 0 1px 20px rgba(0,0,0,0.06);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }
        .navbar-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }

        /* Logo */
        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            text-decoration: none;
            flex-shrink: 0;
        }
        .navbar-logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(37,99,235,0.35);
        }
        .navbar-logo-icon svg { color: #fff; }
        .navbar-logo-text {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        .navbar-logo-text span { color: #2563eb; }

        /* Desktop Nav Links */
        .navbar-links {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }
        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.875rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .nav-link svg { width: 16px; height: 16px; flex-shrink: 0; }
        .nav-link:hover {
            background: #f1f5f9;
            color: #1e293b;
        }
        .nav-link.active {
            background: #eff6ff;
            color: #2563eb;
        }
        .nav-link.active svg { color: #2563eb; }

        /* Right side */
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .navbar-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.25rem 0.625rem;
            border-radius: 999px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .navbar-badge-dot {
            width: 6px;
            height: 6px;
            background: #22c55e;
            border-radius: 50%;
            animation: pulse-dot 2s infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.6; transform: scale(0.8); }
        }

        /* Hamburger button */
        .hamburger-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        .hamburger-btn:hover { background: #f1f5f9; border-color: #cbd5e1; }
        .hamburger-icon { display: flex; flex-direction: column; gap: 4.5px; }
        .hamburger-icon span {
            display: block;
            width: 18px;
            height: 2px;
            background: #475569;
            border-radius: 2px;
            transition: all 0.3s ease;
            transform-origin: center;
        }
        .hamburger-btn.open .hamburger-icon span:nth-child(1) { transform: translateY(6.5px) rotate(45deg); }
        .hamburger-btn.open .hamburger-icon span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .hamburger-btn.open .hamburger-icon span:nth-child(3) { transform: translateY(-6.5px) rotate(-45deg); }

        /* Mobile drawer */
        .mobile-menu {
            display: none;
            position: absolute;
            top: 65px;
            left: 0;
            right: 0;
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226,232,240,0.9);
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            padding: 1rem 1.25rem;
            flex-direction: column;
            gap: 0.375rem;
            animation: slideDown 0.2s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .mobile-menu.open { display: flex; }
        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .mobile-nav-link svg { width: 18px; height: 18px; flex-shrink: 0; }
        .mobile-nav-link:hover  { background: #f1f5f9; color: #1e293b; }
        .mobile-nav-link.active { background: #eff6ff; color: #2563eb; }
        .mobile-menu-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 0.25rem 0;
        }
        .mobile-menu-footer {
            padding: 0.5rem 1rem 0.25rem;
            font-size: 0.7rem;
            color: #94a3b8;
            font-weight: 500;
        }

        /* ── Main content ── */
        .main-content {
            min-height: calc(100vh - 64px);
            background: #f8fafc;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .navbar-links { display: none; }
            .hamburger-btn { display: flex; }
            .navbar-badge { display: none; }
        }
    </style>
</head>
<body class="bg-slate-50 text-gray-900 antialiased">

    <!-- Navbar -->
    <nav class="navbar" id="main-navbar">
        <div class="navbar-inner">

            <!-- Logo -->
            <a href="{{ route('marketing.dashboard') }}" class="navbar-logo" id="nav-logo">
                <div class="navbar-logo-icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span class="navbar-logo-text">MABIPRO<span>.</span></span>
            </a>

            <!-- Desktop Links -->
            <div class="navbar-links" id="desktop-nav">
                <a href="{{ route('marketing.dashboard') }}"
                   id="nav-dashboard"
                   class="nav-link {{ request()->routeIs('marketing.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                    </svg>
                    Dashboard Marketing
                </a>
                <a href="{{ route('payment.report') }}"
                   id="nav-laporan"
                   class="nav-link {{ request()->routeIs('payment.report') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Laporan Pembayaran
                </a>
            </div>

            <!-- Right side -->
            <div class="navbar-right">
                <div class="navbar-badge" id="nav-status-badge">
                    <span class="navbar-badge-dot"></span>
                    Live
                </div>

                <!-- Hamburger -->
                <button class="hamburger-btn" id="hamburger-btn" aria-label="Toggle menu" aria-expanded="false">
                    <div class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div class="mobile-menu" id="mobile-menu" role="navigation" aria-label="Mobile navigation">
            <a href="{{ route('marketing.dashboard') }}"
               id="mobile-nav-dashboard"
               class="mobile-nav-link {{ request()->routeIs('marketing.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                </svg>
                Dashboard Marketing
            </a>
            <a href="{{ route('payment.report') }}"
               id="mobile-nav-laporan"
               class="mobile-nav-link {{ request()->routeIs('payment.report') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan Pembayaran
            </a>
            <div class="mobile-menu-divider"></div>
            <div class="mobile-menu-footer">MABIPRO — Sistem Manajemen Properti</div>
        </div>
    </nav>

    <main class="main-content">
        {{ $slot }}
    </main>

    @livewireScripts

    <script>
        // Hamburger toggle
        const btn = document.getElementById('hamburger-btn');
        const menu = document.getElementById('mobile-menu');
        if (btn && menu) {
            btn.addEventListener('click', () => {
                const isOpen = menu.classList.toggle('open');
                btn.classList.toggle('open', isOpen);
                btn.setAttribute('aria-expanded', isOpen);
            });
            // Close on outside click
            document.addEventListener('click', (e) => {
                if (!btn.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.remove('open');
                    btn.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');
                }
            });
        }
    </script>
</body>
</html>