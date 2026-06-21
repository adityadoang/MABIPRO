<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MABIPRO') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-background text-on-background min-h-screen flex items-center justify-center font-sans antialiased p-4 md:p-8">
        <div class="w-full max-w-[480px]">
            <!-- Brand Logo -->
            <div class="text-center mb-10 flex flex-col items-center">
                <div class="w-24 h-24 flex-shrink-0 rounded-full overflow-hidden bg-white shadow-sm border border-outline-variant mb-4 flex items-center justify-center">
                    <img src="{{ asset('images/mabipro-logo.png') }}" alt="MABIPRO Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="font-display-lg text-[40px] leading-[48px] font-bold text-primary tracking-tighter">MABIPRO</h1>
                <p class="font-body-md text-sm text-on-surface-variant mt-1">Property Management System</p>
            </div>

            <!-- Login Card -->
            <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant p-6 md:p-8 relative overflow-hidden">
                <!-- Minimalist Decorative Accent -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-secondary to-primary"></div>
                
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
