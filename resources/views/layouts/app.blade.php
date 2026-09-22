<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KSP Mlokomanis') }}</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50">
        <!-- Background Shapes Pattern -->
        <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none opacity-10">
            <!-- Lingkaran (Circle) -->
            <svg class="absolute top-[-5%] left-[-5%] w-64 h-64 text-primary" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50" /></svg>
            <!-- Segitiga (Triangle) -->
            <svg class="absolute top-[20%] right-[-5%] w-48 h-48 text-primary rotate-12" fill="currentColor" viewBox="0 0 100 100"><polygon points="50,10 90,90 10,90" /></svg>
            <!-- Persegi (Square) -->
            <svg class="absolute bottom-[5%] left-[10%] w-40 h-40 text-primary -rotate-12" fill="currentColor" viewBox="0 0 100 100"><rect width="80" height="80" x="10" y="10" rx="8" /></svg>
        </div>

        <div x-data="{ sidebarOpen: false }" class="relative z-10 min-h-screen flex">
            
            <!-- Sidebar Navigation -->
            <livewire:layout.navigation />

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
                
                <!-- Mobile Header -->
                <div class="sm:hidden bg-white shadow-sm flex items-center justify-between p-4 z-10 relative">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white font-bold text-sm">
                            K
                        </div>
                        <span class="font-bold text-slate-800">KSP Mlokomanis</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <livewire:layout.notification-bell />
                        <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-700 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow-sm border-b border-slate-200 hidden sm:flex sm:justify-between sm:items-center z-10 relative">
                        <div class="py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                        <div class="px-4 sm:px-6 lg:px-8">
                            <livewire:layout.notification-bell />
                        </div>
                    </header>
                    
                    <!-- Mobile page heading -->
                    <header class="bg-slate-50 sm:hidden z-10 relative">
                        <div class="py-4 px-4">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
