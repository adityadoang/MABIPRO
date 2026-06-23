<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="MABIPRO - Sistem Manajemen Penjualan Properti.">
    <title>@hasSection('title')@yield('title') — @endif MABIPRO</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-surface text-on-surface flex h-screen overflow-hidden font-body-md">

    {{-- ══════════════════════════════════════════
         SIDEBAR (Desktop) — matches admin layout
    ══════════════════════════════════════════ --}}
    <nav class="bg-surface-container-low dark:bg-primary-container text-secondary dark:text-secondary-fixed border-r border-outline-variant dark:border-outline fixed left-0 top-0 h-screen flex flex-col p-4 space-y-2 z-50 w-72 md:flex hidden"
         id="sidebar" role="navigation" aria-label="Main navigation">

        {{-- Logo / User Area --}}
        <div class="mb-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 flex-shrink-0 rounded-full bg-white overflow-hidden flex items-center justify-center shadow-sm">
                    <img src="{{ asset('images/mabipro-logo.png') }}" alt="MABIPRO Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h2 class="font-headline-md text-headline-md font-bold text-primary dark:text-primary-fixed">MABIPRO {{ Auth::user()->role ?? 'Marketing' }}</h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Property Management</p>
                </div>
            </div>
        </div>

        {{-- Navigation Items --}}
        <div class="flex-1 space-y-2 overflow-y-auto">

            @if(Auth::user()->isAdmin())
            <a class="{{ request()->routeIs('admin.dashboard') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary' : 'text-on-surface-variant dark:text-on-primary-container hover:bg-surface-container-high' }} font-bold rounded-lg flex items-center gap-3 px-4 py-3 transition-all" href="{{ route('admin.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-label-md text-label-md">Overview</span>
            </a>
            @endif

            @if(Auth::user()->isAdmin() || Auth::user()->isMarketing())
            <a class="{{ request()->routeIs('marketing.dashboard') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary' : 'text-on-surface-variant dark:text-on-primary-container hover:bg-surface-container-high' }} font-bold rounded-lg flex items-center gap-3 px-4 py-3 transition-all"
               href="{{ route('marketing.dashboard') }}"
               id="nav-marketing">
                <span class="material-symbols-outlined">trending_up</span>
                <span class="font-label-md text-label-md">Marketing</span>
            </a>

            <a class="{{ request()->routeIs('marketing.payment.report') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary' : 'text-on-surface-variant dark:text-on-primary-container hover:bg-surface-container-high' }} font-bold rounded-lg flex items-center gap-3 px-4 py-3 transition-all"
               href="{{ route('marketing.payment.report') }}"
               id="nav-payment-report">
                <span class="material-symbols-outlined">receipt_long</span>
                <span class="font-label-md text-label-md">Laporan Pembayaran</span>
            </a>

            @if(Auth::user()->isMarketing() && !Auth::user()->isAdmin())
            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                @csrf
                <button type="submit"
                        class="w-full text-left text-error hover:bg-error-container hover:text-on-error-container transition-all flex items-center gap-3 px-4 py-3 rounded-lg font-bold"
                        id="nav-logout-marketing">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-label-md text-label-md">Logout</span>
                </button>
            </form>
            @endif
            @endif

            @if(Auth::user()->isAdmin() || Auth::user()->isProduksi())
            <a class="{{ request()->routeIs('production.*') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary' : 'text-on-surface-variant dark:text-on-primary-container hover:bg-surface-container-high' }} font-bold rounded-lg flex items-center gap-3 px-4 py-3 transition-all" href="{{ route('production.dashboard') }}">
                <span class="material-symbols-outlined">construction</span>
                <span class="font-label-md text-label-md">Production</span>
            </a>
            @endif

            @if(Auth::user()->isAdmin() || Auth::user()->isLegalitas())
            <a class="{{ request()->routeIs('legalitas.*') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary' : 'text-on-surface-variant dark:text-on-primary-container hover:bg-surface-container-high' }} font-bold rounded-lg flex items-center gap-3 px-4 py-3 transition-all" href="{{ route('legalitas.dashboard') }}">
                <span class="material-symbols-outlined">description</span>
                <span class="font-label-md text-label-md">Legality</span>
            </a>
            @if(Auth::user()->isLegalitas() && !Auth::user()->isAdmin())
            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                @csrf
                <button type="submit"
                        class="w-full text-left text-error hover:bg-error-container hover:text-on-error-container transition-all flex items-center gap-3 px-4 py-3 rounded-lg font-bold"
                        id="nav-logout-legalitas">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-label-md text-label-md">Logout</span>
                </button>
            </form>
            @endif
            @endif

            @if(Auth::user()->isAdmin())
            <a class="{{ request()->routeIs('admin.blocks.*') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary' : 'text-on-surface-variant dark:text-on-primary-container hover:bg-surface-container-high' }} font-bold rounded-lg flex items-center gap-3 px-4 py-3 transition-all" href="{{ route('admin.blocks.index') }}">
                <span class="material-symbols-outlined">domain</span>
                <span class="font-label-md text-label-md">Blok</span>
            </a>

            <a class="{{ request()->routeIs('admin.units.*') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary' : 'text-on-surface-variant dark:text-on-primary-container hover:bg-surface-container-high' }} font-bold rounded-lg flex items-center gap-3 px-4 py-3 transition-all" href="{{ route('admin.units.index') }}">
                <span class="material-symbols-outlined">home</span>
                <span class="font-label-md text-label-md">Unit</span>
            </a>

            <a class="{{ request()->routeIs('admin.users.*') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary' : 'text-on-surface-variant dark:text-on-primary-container hover:bg-surface-container-high' }} font-bold rounded-lg flex items-center gap-3 px-4 py-3 transition-all" href="{{ route('admin.users.index') }}">
                <span class="material-symbols-outlined">group</span>
                <span class="font-label-md text-label-md">User Management</span>
            </a>
            @endif

        </div>

        {{-- Logout (Admin & Produksi only — Marketing & Legalitas have their own inline logout) --}}
        @if(Auth::user()->isAdmin() || Auth::user()->isProduksi())
        <div class="mt-auto space-y-2 pt-4 border-t border-outline-variant">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left text-on-surface-variant dark:text-on-primary-container hover:bg-surface-container-high dark:hover:bg-on-primary-fixed-variant transition-all flex items-center gap-3 px-4 py-3 rounded-lg"
                        id="nav-logout">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-label-md text-label-md">Logout</span>
                </button>
            </form>
        </div>
        @endif
    </nav>

    {{-- ══════════════════════════════════════════
         MAIN CONTENT
    ══════════════════════════════════════════ --}}
    <main class="flex-1 ml-0 md:ml-72 flex flex-col h-screen overflow-y-auto bg-surface-bright pb-24 md:pb-0">

        {{-- Mobile Header --}}
        <header class="md:hidden bg-surface border-b border-outline-variant p-4 flex justify-between items-center sticky top-0 z-40">
            <h1 class="font-headline-md text-headline-md font-bold text-primary">MABIPRO</h1>
            <button id="sidebar-mobile-btn" class="text-on-surface-variant p-2 rounded-full hover:bg-surface-container" aria-label="Toggle menu">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </header>

        <div class="p-margin-mobile md:p-margin-desktop flex-1 max-w-container-max mx-auto w-full space-y-lg">
            @if (session('success'))
                <div class="bg-secondary-container text-on-secondary-container p-4 rounded-lg mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-error-container text-on-error-container p-4 rounded-lg mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined">error</span>
                    {{ session('error') }}
                </div>
            @endif

            @hasSection('header')
                @yield('header')
            @endif
            
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </div>

        {{-- Footer --}}
        <footer class="bg-surface-dim dark:bg-inverse-surface text-on-surface dark:text-inverse-on-surface border-t border-outline-variant w-full py-6 px-margin-desktop mt-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="font-label-md text-label-md font-bold text-primary dark:text-primary-fixed">MABIPRO</div>
            <div class="font-body-sm text-body-sm">© {{ date('Y') }} MABIPRO Property Management. All rights reserved.</div>
        </footer>
    </main>

    {{-- ══════════════════════════════════════════
         MOBILE BOTTOM NAV
    ══════════════════════════════════════════ --}}
    <nav class="md:hidden fixed bottom-0 left-0 w-full bg-surface border-t border-outline-variant flex justify-around p-2 z-50">

        {{-- Admin: Overview --}}
        @if(Auth::user()->isAdmin())
        <a class="flex flex-col items-center p-2 {{ request()->routeIs('admin.dashboard') ? 'text-primary' : 'text-on-surface-variant hover:text-primary' }}"
           href="{{ route('admin.dashboard') }}">
            <span class="material-symbols-outlined" style="{{ request()->routeIs('admin.dashboard') ? "font-variation-settings: 'FILL' 1;" : '' }}">dashboard</span>
            <span class="text-[10px] mt-1 {{ request()->routeIs('admin.dashboard') ? 'font-bold' : '' }}">Overview</span>
        </a>
        @endif

        {{-- Admin / Marketing: Marketing & Laporan --}}
        @if(Auth::user()->isAdmin() || Auth::user()->isMarketing())
        <a class="flex flex-col items-center p-2 {{ request()->routeIs('marketing.dashboard') ? 'text-primary' : 'text-on-surface-variant hover:text-primary' }}"
           href="{{ route('marketing.dashboard') }}">
            <span class="material-symbols-outlined" style="{{ request()->routeIs('marketing.dashboard') ? "font-variation-settings: 'FILL' 1;" : '' }}">trending_up</span>
            <span class="text-[10px] mt-1 {{ request()->routeIs('marketing.dashboard') ? 'font-bold' : '' }}">Marketing</span>
        </a>
        <a class="flex flex-col items-center p-2 {{ request()->routeIs('marketing.payment.report') ? 'text-primary' : 'text-on-surface-variant hover:text-primary' }}"
           href="{{ route('marketing.payment.report') }}">
            <span class="material-symbols-outlined" style="{{ request()->routeIs('marketing.payment.report') ? "font-variation-settings: 'FILL' 1;" : '' }}">receipt_long</span>
            <span class="text-[10px] mt-1 {{ request()->routeIs('marketing.payment.report') ? 'font-bold' : '' }}">Laporan</span>
        </a>
        @endif

        {{-- Admin / Produksi: Production --}}
        @if(Auth::user()->isAdmin() || Auth::user()->isProduksi())
        <a class="flex flex-col items-center p-2 {{ request()->routeIs('production.*') ? 'text-primary' : 'text-on-surface-variant hover:text-primary' }}"
           href="{{ route('production.dashboard') }}">
            <span class="material-symbols-outlined" style="{{ request()->routeIs('production.*') ? "font-variation-settings: 'FILL' 1;" : '' }}">construction</span>
            <span class="text-[10px] mt-1 {{ request()->routeIs('production.*') ? 'font-bold' : '' }}">Production</span>
        </a>
        @endif

        {{-- Admin / Legalitas: Legality --}}
        @if(Auth::user()->isAdmin() || Auth::user()->isLegalitas())
        <a class="flex flex-col items-center p-2 {{ request()->routeIs('legalitas.*') ? 'text-primary' : 'text-on-surface-variant hover:text-primary' }}"
           href="{{ route('legalitas.dashboard') }}">
            <span class="material-symbols-outlined" style="{{ request()->routeIs('legalitas.*') ? "font-variation-settings: 'FILL' 1;" : '' }}">description</span>
            <span class="text-[10px] mt-1 {{ request()->routeIs('legalitas.*') ? 'font-bold' : '' }}">Legality</span>
        </a>
        @endif

        {{-- Logout always visible --}}
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();" class="flex flex-col items-center p-2 text-on-surface-variant hover:text-error">
            <span class="material-symbols-outlined">logout</span>
            <span class="text-[10px] mt-1">Logout</span>
        </a>
        <form id="logout-form-mobile" method="POST" action="{{ route('logout') }}" class="hidden" style="display: none;">
            @csrf
        </form>
    </nav>

    {{-- Sidebar Overlay (Mobile) --}}
    <div id="sidebar-overlay" class="hidden fixed inset-0 bg-black/40 z-40 md:hidden"></div>

    @livewireScripts

    <script>
        // Mobile sidebar toggle
        const sidebarEl  = document.getElementById('sidebar');
        const overlayEl  = document.getElementById('sidebar-overlay');
        const mobileBtn  = document.getElementById('sidebar-mobile-btn');

        function openSidebar() {
            sidebarEl?.classList.remove('hidden');
            sidebarEl?.classList.add('flex');
            overlayEl?.classList.remove('hidden');
        }
        function closeSidebar() {
            sidebarEl?.classList.add('hidden');
            sidebarEl?.classList.remove('flex');
            overlayEl?.classList.add('hidden');
        }

        mobileBtn?.addEventListener('click', openSidebar);
        overlayEl?.addEventListener('click', closeSidebar);
    </script>
</body>
</html>