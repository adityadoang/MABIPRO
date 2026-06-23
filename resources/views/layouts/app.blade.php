<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="MABIPRO - Sistem Manajemen Penjualan Properti.">
    <title>@hasSection('title')@yield('title') — @endif MABIPRO</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/mabipro-logo.jpg') }}">

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-surface text-on-surface flex h-screen overflow-hidden font-body-md" x-data="{ sidebarOpen: false }">


    {{-- ══════════════════════════════════════════
         SIDEBAR (Desktop) — matches admin layout
    ══════════════════════════════════════════ --}}
    <nav class="bg-surface-container-low dark:bg-primary-container text-secondary dark:text-secondary-fixed border-r border-outline-variant dark:border-outline fixed left-0 top-0 h-[100dvh] flex flex-col p-4 space-y-2 z-50 w-72 max-w-[85vw] md:flex"
         x-bind:class="sidebarOpen ? 'flex' : 'hidden'"
         id="sidebar" role="navigation" aria-label="Marketing navigation">

        {{-- Logo / User Area --}}
        <div class="mb-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 flex-shrink-0 rounded-full bg-white overflow-hidden flex items-center justify-center shadow-sm">
                    <img src="{{ asset('images/mabipro-logo.jpg') }}" alt="MABIPRO Logo" class="w-full h-full object-contain">
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

        {{-- Logout --}}
        <div class="mt-auto space-y-2 pt-4 border-t border-outline-variant shrink-0">
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
    </nav>

    {{-- ══════════════════════════════════════════
         MAIN CONTENT
    ══════════════════════════════════════════ --}}
    <main class="flex-1 ml-0 md:ml-72 flex flex-col h-[100dvh] overflow-y-auto bg-surface-bright">

        {{-- Mobile Header --}}
        <header class="md:hidden bg-surface border-b border-outline-variant p-4 flex justify-between items-center sticky top-0 z-40">
            <h1 class="font-headline-md text-headline-md font-bold text-primary">MABIPRO</h1>
            <button id="sidebar-mobile-btn" @click="sidebarOpen = !sidebarOpen" class="text-on-surface-variant p-2 rounded-full hover:bg-surface-container" aria-label="Toggle menu">
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

    {{-- Sidebar Overlay (Mobile) --}}
    <div id="sidebar-overlay" x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black/40 z-40 md:hidden" style="display: none;"></div>

    @livewireScripts
</body>
</html>