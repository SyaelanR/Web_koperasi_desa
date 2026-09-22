<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-slate-50 relative">
        <!-- Background Shapes Pattern -->
        <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none opacity-10">
            <!-- Lingkaran (Circle) -->
            <svg class="absolute top-[-5%] left-[-5%] w-64 h-64 text-primary" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50" /></svg>
            <!-- Segitiga (Triangle) -->
            <svg class="absolute top-[20%] right-[-5%] w-48 h-48 text-primary rotate-12" fill="currentColor" viewBox="0 0 100 100"><polygon points="50,10 90,90 10,90" /></svg>
            <!-- Persegi (Square) -->
            <svg class="absolute bottom-[5%] left-[10%] w-40 h-40 text-primary -rotate-12" fill="currentColor" viewBox="0 0 100 100"><rect width="80" height="80" x="10" y="10" rx="8" /></svg>
        </div>

        <div class="relative z-10 min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" wire:navigate class="flex items-center gap-3 hover:opacity-90 transition-opacity">
                    <div class="w-14 h-14 bg-primary rounded-full flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                        K
                    </div>
                    <span class="font-bold text-3xl text-slate-800 tracking-tight">KSP Mlokomanis</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-8 py-8 bg-white shadow-xl shadow-slate-200/60 sm:rounded-2xl border border-slate-100/50">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
