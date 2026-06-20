<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - MABIPRO Production</title>
    
    <!-- Google Fonts: Oswald (Industrial) + Roboto Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Roboto+Mono:wght@400;500;600&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'industrial': {
                            50:  '#EFEBE9',  // Krem terang
                            100: '#D7CCC8',  // Krem
                            200: '#BCAAA4',  // Coklat muda
                            300: '#A1887F',  // Coklat medium
                            400: '#8D6E63',  // Coklat
                            500: '#795548',  // Coklat tua
                            600: '#6D4C41',  // Coklat gelap
                            700: '#5D4037',  // Coklat sangat gelap
                            800: '#4E342E',  // Coklat pekat
                            900: '#3E2723',  // Coklat hitam
                        },
                        'rust': {
                            DEFAULT: '#BF360C',
                            light: '#E64A19',
                            dark: '#8F2809',
                        },
                        'steel': {
                            DEFAULT: '#455A64',
                            light: '#607D8B',
                            dark: '#263238',
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
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #EFEBE9;
            background-image: 
                linear-gradient(45deg, rgba(62, 39, 35, 0.03) 25%, transparent 25%),
                linear-gradient(-45deg, rgba(62, 39, 35, 0.03) 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, rgba(62, 39, 35, 0.03) 75%),
                linear-gradient(-45deg, transparent 75%, rgba(62, 39, 35, 0.03) 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
        }
        
        .industrial-border {
            border: 2px solid #3E2723;
            box-shadow: 4px 4px 0px #3E2723;
        }
        
        .industrial-border-sm {
            border: 1px solid #5D4037;
            box-shadow: 2px 2px 0px #5D4037;
        }
        
        .rivet {
            width: 8px;
            height: 8px;
            background: radial-gradient(circle at 30% 30%, #A1887F, #3E2723);
            border-radius: 50%;
            display: inline-block;
        }
        
        .progress-bar-industrial {
            background: #D7CCC8;
            border: 2px solid #3E2723;
            height: 24px;
            position: relative;
        }
        
        .progress-bar-industrial .fill {
            background: repeating-linear-gradient(
                45deg,
                #BF360C,
                #BF360C 10px,
                #E64A19 10px,
                #E64A19 20px
            );
            height: 100%;
            transition: width 0.5s ease;
        }
        
        .label-tag {
            background: #3E2723;
            color: #EFEBE9;
            font-family: 'Roboto Mono', monospace;
            font-size: 10px;
            letter-spacing: 2px;
            padding: 2px 8px;
            text-transform: uppercase;
            display: inline-block;
        }
        
        .card-industrial {
            background: #EFEBE9;
            border: 2px solid #3E2723;
            box-shadow: 6px 6px 0px #3E2723;
            transition: all 0.2s ease;
        }
        
        .card-industrial:hover {
            box-shadow: 8px 8px 0px #BF360C;
            transform: translate(-2px, -2px);
        }
        
        .btn-industrial {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid #3E2723;
            box-shadow: 4px 4px 0px #3E2723;
            transition: all 0.15s ease;
            font-weight: 600;
        }
        
        .btn-industrial:hover {
            box-shadow: 2px 2px 0px #3E2723;
            transform: translate(2px, 2px);
        }
        
        .btn-industrial:active {
            box-shadow: 0px 0px 0px #3E2723;
            transform: translate(4px, 4px);
        }
        
        .header-stripe {
            background: #3E2723;
            background-image: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 20px,
                rgba(191, 54, 12, 0.1) 20px,
                rgba(191, 54, 12, 0.1) 40px
            );
        }
        
        .timeline-line {
            border-left: 3px dashed #5D4037;
        }
        
        input, select, textarea {
            font-family: 'Roboto Mono', monospace;
        }
        
        .corner-rivet {
            position: absolute;
            width: 12px;
            height: 12px;
            background: radial-gradient(circle at 30% 30%, #A1887F, #3E2723);
            border-radius: 50%;
            border: 1px solid #3E2723;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header Industrial -->
    <header class="header-stripe text-industrial-50 border-b-4 border-rust">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex gap-1">
                    <span class="rivet"></span>
                    <span class="rivet"></span>
                </div>
                <div>
                    <h1 class="font-display text-2xl font-bold tracking-wider">MABIPRO</h1>
                    <p class="font-mono text-xs text-industrial-200 tracking-widest">PRODUCTION TRACKING SYSTEM</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="label-tag">UNIT: {{ $unit->unit_number ?? 'DASHBOARD' }}</span>
                <span class="label-tag bg-rust">{{ now()->format('d.m.Y') }}</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t-4 border-rust bg-industrial-900 text-industrial-100 mt-12">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center font-mono text-xs">
            <span>// MABIPRO PRODUCTION MODULE v1.0</span>
            <span class="flex items-center gap-2">
                <span class="rivet"></span>
                STATUS: ACTIVE
                <span class="rivet"></span>
            </span>
        </div>
    </footer>
</body>
</html>