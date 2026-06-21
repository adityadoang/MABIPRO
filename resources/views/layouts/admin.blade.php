<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — MABIPRO</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Styles / Scripts --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
            body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; background-color: #f1f5f9; color: #1e293b; min-height: 100vh; }
            a { color: inherit; text-decoration: none; }
            button { cursor: pointer; }

            /* Layout */
            .admin-wrapper { display: flex; min-height: 100vh; }

            /* Sidebar */
            .sidebar {
                width: 260px;
                min-height: 100vh;
                background: linear-gradient(180deg, #1e3a5f 0%, #0f2340 100%);
                display: flex;
                flex-direction: column;
                flex-shrink: 0;
                position: fixed;
                top: 0; left: 0; bottom: 0;
                z-index: 40;
                box-shadow: 4px 0 20px rgba(0,0,0,0.15);
            }
            .sidebar-brand {
                padding: 24px 20px;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            .sidebar-brand-title {
                font-size: 20px;
                font-weight: 700;
                color: #ffffff;
                letter-spacing: 0.05em;
            }
            .sidebar-brand-sub {
                font-size: 11px;
                color: rgba(255,255,255,0.5);
                margin-top: 2px;
                text-transform: uppercase;
                letter-spacing: 0.08em;
            }
            .sidebar-nav { padding: 16px 12px; flex: 1; }
            .sidebar-section-label {
                font-size: 10px;
                font-weight: 600;
                color: rgba(255,255,255,0.35);
                text-transform: uppercase;
                letter-spacing: 0.1em;
                padding: 8px 8px 6px;
                margin-top: 8px;
            }
            .sidebar-link {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px 12px;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 500;
                color: rgba(255,255,255,0.7);
                transition: all 0.15s ease;
                margin-bottom: 2px;
            }
            .sidebar-link:hover {
                background: rgba(255,255,255,0.1);
                color: #ffffff;
            }
            .sidebar-link.active {
                background: rgba(255,255,255,0.15);
                color: #ffffff;
                box-shadow: inset 3px 0 0 #60a5fa;
            }
            .sidebar-link svg { width: 16px; height: 16px; flex-shrink: 0; }
            .sidebar-footer {
                padding: 16px 12px;
                border-top: 1px solid rgba(255,255,255,0.1);
            }
            .sidebar-user {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px 12px;
                border-radius: 8px;
                margin-bottom: 6px;
            }
            .sidebar-user-avatar {
                width: 34px; height: 34px;
                border-radius: 50%;
                background: linear-gradient(135deg, #60a5fa, #a78bfa);
                display: flex; align-items: center; justify-content: center;
                font-size: 13px; font-weight: 700; color: #fff;
                flex-shrink: 0;
            }
            .sidebar-user-name { font-size: 13px; font-weight: 600; color: #fff; }
            .sidebar-user-role { font-size: 11px; color: rgba(255,255,255,0.45); }
            .sidebar-logout {
                display: flex; align-items: center; gap: 8px;
                padding: 9px 12px; border-radius: 8px; width: 100%;
                font-size: 13px; font-weight: 500; color: rgba(255,255,255,0.6);
                background: transparent; border: none;
                transition: all 0.15s ease;
            }
            .sidebar-logout:hover { background: rgba(239,68,68,0.15); color: #fca5a5; }
            .sidebar-logout svg { width: 15px; height: 15px; }

            /* Main content */
            .main-content { margin-left: 260px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

            /* Topbar */
            .topbar {
                background: #ffffff;
                border-bottom: 1px solid #e2e8f0;
                padding: 0 28px;
                height: 60px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                position: sticky; top: 0; z-index: 30;
                box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            }
            .topbar-title { font-size: 16px; font-weight: 600; color: #1e293b; }
            .topbar-breadcrumb { font-size: 13px; color: #94a3b8; margin-top: 2px; }

            /* Page body */
            .page-body { padding: 28px; flex: 1; }

            /* Alerts */
            .alert {
                padding: 12px 16px; border-radius: 8px; font-size: 14px;
                margin-bottom: 20px; display: flex; align-items: flex-start; gap: 10px;
            }
            .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
            .alert-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
            .alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }

            /* Cards */
            .card { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; }
            .card-header { padding: 18px 20px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
            .card-title { font-size: 15px; font-weight: 600; color: #1e293b; }
            .card-body { padding: 20px; }

            /* Stat cards */
            .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; margin-bottom: 28px; }
            .stat-card {
                background: #fff; border-radius: 12px; border: 1px solid #e2e8f0;
                padding: 20px; display: flex; flex-direction: column; gap: 8px;
            }
            .stat-card-icon {
                width: 40px; height: 40px; border-radius: 10px;
                display: flex; align-items: center; justify-content: center;
            }
            .stat-card-icon svg { width: 20px; height: 20px; }
            .stat-card-value { font-size: 28px; font-weight: 700; color: #1e293b; line-height: 1; }
            .stat-card-label { font-size: 13px; color: #64748b; font-weight: 500; }
            .stat-card-badge {
                font-size: 11px; font-weight: 600; padding: 2px 8px;
                border-radius: 99px; align-self: flex-start;
            }
            .badge-pending { background: #fef3c7; color: #92400e; }
            .badge-ok { background: #dcfce7; color: #166534; }

            /* Table */
            .table-wrap { overflow-x: auto; }
            table { width: 100%; border-collapse: collapse; font-size: 14px; }
            thead th { padding: 11px 16px; text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #64748b; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
            tbody td { padding: 13px 16px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
            tbody tr:last-child td { border-bottom: none; }
            tbody tr:hover td { background: #f8fafc; }

            /* Badges */
            .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 99px; font-size: 12px; font-weight: 600; }
            .badge-admin     { background: #eff6ff; color: #1d4ed8; }
            .badge-marketing { background: #fef3c7; color: #92400e; }
            .badge-produksi  { background: #f0fdf4; color: #166534; }
            .badge-legalitas { background: #fdf4ff; color: #7e22ce; }

            /* Buttons */
            .btn {
                display: inline-flex; align-items: center; gap: 6px;
                padding: 9px 16px; border-radius: 8px; font-size: 14px; font-weight: 500;
                border: none; transition: all 0.15s ease; cursor: pointer; text-decoration: none;
            }
            .btn svg { width: 15px; height: 15px; }
            .btn-primary { background: #1e3a5f; color: #fff; }
            .btn-primary:hover { background: #162d4a; }
            .btn-secondary { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
            .btn-secondary:hover { background: #e2e8f0; }
            .btn-danger { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
            .btn-danger:hover { background: #fee2e2; }
            .btn-sm { padding: 6px 12px; font-size: 13px; }

            /* Forms */
            .form-group { margin-bottom: 20px; }
            .form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
            .form-label span { color: #ef4444; }
            .form-control {
                width: 100%; padding: 10px 13px; border-radius: 8px;
                border: 1px solid #d1d5db; font-size: 14px; color: #111827;
                outline: none; transition: border-color 0.15s, box-shadow 0.15s;
                background: #fff;
            }
            .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
            .form-control.is-invalid { border-color: #ef4444; }
            .form-error { font-size: 12px; color: #dc2626; margin-top: 5px; }
            .form-hint { font-size: 12px; color: #6b7280; margin-top: 5px; }

            /* Grid helpers */
            .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
            .form-actions { display: flex; align-items: center; gap: 10px; padding-top: 8px; border-top: 1px solid #f1f5f9; margin-top: 4px; }

            /* Pagination */
            .pagination { display: flex; gap: 4px; align-items: center; margin-top: 16px; }
            .pagination a, .pagination span {
                display: inline-flex; align-items: center; justify-content: center;
                min-width: 34px; height: 34px; padding: 0 8px; border-radius: 8px;
                font-size: 13px; font-weight: 500; color: #475569;
                border: 1px solid #e2e8f0; transition: all 0.15s;
            }
            .pagination a:hover { background: #f1f5f9; border-color: #cbd5e1; }
            .pagination .active span { background: #1e3a5f; color: #fff; border-color: #1e3a5f; }

            /* Empty state */
            .empty-state { text-align: center; padding: 48px 20px; color: #94a3b8; }
            .empty-state svg { width: 48px; height: 48px; margin: 0 auto 12px; opacity: 0.4; }
            .empty-state p { font-size: 14px; }

            /* Divider */
            .divider { height: 1px; background: #f1f5f9; margin: 20px 0; }

            /* Delete form inline */
            .delete-form { display: inline; }
        </style>
    @endif
</head>
<body>
<div class="admin-wrapper">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-title">MABIPRO</div>
            <div class="sidebar-brand-sub">Admin Panel</div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-label">Utama</div>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <div class="sidebar-section-label">Manajemen</div>

            <a href="{{ route('admin.users.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                </svg>
                Kelola Pengguna
            </a>

            {{-- Nav Produksi (dari feature/modul-produksi) --}}
            @if(Auth::check() && (Auth::user()->isProduksi() || Auth::user()->isAdmin()))
            <div class="sidebar-section-label">Produksi</div>

            <a href="{{ route('admin.blocks.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.blocks.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Blok
            </a>

            <a href="{{ route('admin.units.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Unit
            </a>

            <a href="{{ route('production.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('production.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Progres Produksi
            </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                    <div class="sidebar-user-role">{{ Auth::user()->role }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="main-content">

        {{-- Topbar --}}
        <header class="topbar">
            <div>
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                <div class="topbar-breadcrumb">@yield('breadcrumb', 'Admin / Dashboard')</div>
            </div>
            <div>
                {{-- slot untuk aksi topbar --}}
                @yield('topbar-action')
            </div>
        </header>

        {{-- Page body --}}
        <main class="page-body">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error" role="alert">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
