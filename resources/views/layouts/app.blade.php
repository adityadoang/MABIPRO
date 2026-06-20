<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MABIPRO - Sistem Manajemen Penjualan Properti.">
    <title>@yield('title', 'MABIPRO Property Management')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Vite assets (CSS & JS dikompilasi) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire styles --}}
    @livewireStyles

    {{-- Tailwind CDN sebagai fallback saat npm run dev belum dijalankan --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .sidebar-active { background-color: #6ee7b7; color: #064e3b; font-weight: 600; }
        .sidebar-item { color: #475569; transition: all 0.2s; }
        .sidebar-item:hover { background-color: #e2e8f0; }
        .card-shadow { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-gray-800">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between h-full flex-shrink-0 z-20 shadow-sm">
        <div>
            <!-- Logo & Profile -->
            <div class="p-6 flex items-center gap-3 border-b border-gray-100">
                <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-white font-bold">
                    M
                </div>
                <div>
                    <h2 class="font-bold text-gray-900 leading-tight">MABIPRO<br>System</h2>
                    <p class="text-[10px] text-gray-500 leading-tight mt-0.5">Property Management<br>System</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-1">
                <a href="{{ route('marketing.dashboard') }}"
                   class="{{ request()->routeIs('marketing.dashboard') ? 'sidebar-active' : 'sidebar-item' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('payment.report') }}"
                   class="{{ request()->routeIs('payment.report') ? 'sidebar-active' : 'sidebar-item' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Laporan Pembayaran
                </a>
                <a href="{{ route('legalitas.dashboard') }}"
                   class="{{ request()->routeIs('legalitas.*') ? 'sidebar-active' : 'sidebar-item' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Legalitas
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-gray-100">
            <nav class="space-y-1">
                <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-red-600 hover:bg-red-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </a>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-[#f8fafc]">
        @yield('header')

        <!-- Scrollable Content Area -->
        <div class="flex-1 overflow-y-auto p-8">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Support @extends/@yield (Blade biasa) --}}
            @yield('content')

            {{-- Support Livewire component ($slot) --}}
            @isset($slot)
                {{ $slot }}
            @endisset
        </div>

        <!-- Footer -->
        <footer class="bg-gray-200/50 border-t border-gray-300 px-8 py-4 flex justify-between items-center flex-shrink-0 mt-auto">
            <div class="flex items-center gap-6">
                <span class="font-bold text-gray-900 text-xs">MABIPRO</span>
                <span class="text-gray-500 text-xs">&copy; 2026 MABIPRO Property Management. All rights reserved.</span>
            </div>
            <div class="flex items-center gap-4 text-xs text-gray-500 font-medium">
                <a href="#" class="hover:text-gray-900">Privacy Policy</a>
                <a href="#" class="hover:text-gray-900">Terms of Service</a>
            </div>
        </footer>
    </main>

    @livewireScripts

</body>
</html>
