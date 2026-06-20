<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - MABIPRO Admin</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Roboto+Mono:wght@400;500;600&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/production.css'])
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'industrial': {
                            50:  '#EFEBE9',
                            100: '#D7CCC8',
                            200: '#BCAAA4',
                            300: '#A1887F',
                            400: '#8D6E63',
                            500: '#795548',
                            600: '#6D4C41',
                            700: '#5D4037',
                            800: '#4E342E',
                            900: '#3E2723',
                        },
                        'rust': {
                            DEFAULT: '#BF360C',
                            light: '#E64A19',
                            dark: '#8F2809',
                        }
                    },
                    fontFamily: {
                        'display': ['Oswald', 'sans-serif'],
                        'mono': ['Roboto Mono', 'monospace'],
                        'body': ['Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="header-stripe text-industrial-50 border-b-4 border-rust">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex gap-1">
                    <span class="rivet"></span>
                    <span class="rivet"></span>
                </div>
                <div>
                    <h1 class="font-display text-2xl font-bold tracking-wider">MABIPRO</h1>
                    <p class="font-mono text-xs text-industrial-200 tracking-widest">ADMIN PANEL</p>
                </div>
            </div>
            <nav class="flex gap-2">
                <a href="{{ route('admin.blocks.index') }}" 
                   class="label-tag {{ request()->routeIs('admin.blocks.*') ? 'bg-rust' : '' }}">
                    BLOK
                </a>
                <a href="{{ route('admin.units.index') }}" 
                   class="label-tag {{ request()->routeIs('admin.units.*') ? 'bg-rust' : '' }}">
                    UNIT
                </a>
                <a href="{{ route('production.index') }}" 
                   class="label-tag">
                    PRODUCTION
                </a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-rust text-industrial-50 px-4 py-3 mb-6 industrial-border-sm font-mono text-sm">
                <span class="font-bold">[OK]</span> {{ session('success') }}
            </div>
        @endif
        
        @yield('content')
    </main>

    <footer class="border-t-4 border-rust bg-industrial-900 text-industrial-100 mt-12">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center font-mono text-xs">
            <span>// MABIPRO ADMIN PANEL v1.0</span>
            <span class="flex items-center gap-2">
                <span class="rivet"></span>
                STATUS: ACTIVE
                <span class="rivet"></span>
            </span>
        </div>
    </footer>
</body>
</html>