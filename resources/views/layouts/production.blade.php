<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MABIPRO Produksi')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/mabipro-logo.jpg') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col">
            {{-- Logo --}}
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-[20px]">construction</span>
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-900 text-sm">MABIPRO Produksi</h2>
                        <p class="text-xs text-gray-500">Construction Monitoring</p>
                    </div>
                </div>
            </div>
            
            {{-- Menu --}}
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('production.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 bg-primary text-white rounded-lg font-medium transition-colors">
                    <span class="material-symbols-outlined text-[20px]">handyman</span>
                    Production
                </a>
                
                {{-- Logout Button - Merah --}}
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg font-medium transition-colors">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                        Logout
                    </button>
                </form>
            </nav>

            {{-- User Profile (HANYA 1x, tanpa logout) --}}
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-[20px]">person</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 text-sm">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->role ?? 'Role' }}</p>
                    </div>
                </div>
            </div>
        </aside>
        
        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto bg-gray-50">
            {{-- Top Bar --}}
            <header class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">@yield('page-title', 'Production')</h1>
                        <p class="text-sm text-gray-500">@yield('page-subtitle', 'Monitor construction progress')</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="text-gray-400 hover:text-gray-600">
                            <span class="material-symbols-outlined">notifications</span>
                        </button>
                        <button class="text-gray-400 hover:text-gray-600">
                            <span class="material-symbols-outlined">help</span>
                        </button>
                    </div>
                </div>
            </header>
            
            {{-- Content --}}
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
                        <span class="material-symbols-outlined text-green-600">check_circle</span>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center gap-3">
                        <span class="material-symbols-outlined text-red-600">error</span>
                        <p class="text-red-800 font-medium">{{ session('error') }}</p>
                    </div>
                @endif
                
                @yield('content')
            </div>
            
            {{-- Footer --}}
            <footer class="bg-white border-t border-gray-200 px-6 py-4 mt-8">
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <p>&copy; 2026 MABIPRO Property Management</p>
                    <p>Production Module v1.0</p>
                </div>
            </footer>
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>