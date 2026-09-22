<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>KSP Desa Mlokomanis Wetan</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />


        <!-- Styles & Scripts bawaan Laravel -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Livewire Styles (Opsional jika Anda ingin menambahkan komponen livewire di landing page) -->
        @livewireStyles
    </head>
    <body class="antialiased font-sans bg-slate-50 text-slate-800 selection:bg-primary selection:text-white flex flex-col min-h-screen relative z-0">
        <!-- Background Shapes Pattern -->
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none opacity-10">
            <!-- Lingkaran (Circle) -->
            <svg class="absolute top-[-5%] left-[-5%] w-64 h-64 text-primary" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50" /></svg>
            <!-- Segitiga (Triangle) -->
            <svg class="absolute top-[20%] right-[-5%] w-48 h-48 text-primary rotate-12" fill="currentColor" viewBox="0 0 100 100"><polygon points="50,10 90,90 10,90" /></svg>
            <!-- Persegi (Square) -->
            <svg class="absolute bottom-[5%] left-[10%] w-40 h-40 text-primary -rotate-12" fill="currentColor" viewBox="0 0 100 100"><rect width="80" height="80" x="10" y="10" rx="8" /></svg>
        </div>
        
        <!-- Navbar -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo / Brand -->
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            K
                        </div>
                        <span class="font-bold text-xl text-slate-800 tracking-tight">KSP Mlokomanis</span>
                    </div>

                    <!-- Navigation Links (Desktop) -->
                    <div class="hidden md:flex space-x-8">
                        <a href="#beranda" class="text-slate-600 hover:text-primary font-medium transition duration-150">Beranda</a>
                        <a href="#layanan" class="text-slate-600 hover:text-primary font-medium transition duration-150">Layanan</a>
                        <a href="#keunggulan" class="text-slate-600 hover:text-primary font-medium transition duration-150">Keunggulan</a>
                    </div>

                    <!-- Auth Links -->
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-lg bg-primary text-white font-semibold hover:bg-primaryHover transition duration-300 shadow-md hover:shadow-lg">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-slate-600 hover:text-primary font-semibold transition duration-150">
                                    Masuk
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-lg bg-primary text-white font-semibold hover:bg-primaryHover transition duration-300 shadow-md hover:shadow-lg">
                                        Daftar
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section id="beranda" class="relative bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 pt-20 px-4 sm:px-6 lg:px-8">
                    <main class="mt-10 mx-auto max-w-7xl sm:mt-12 md:mt-16 lg:mt-20 xl:mt-28">
                        <div class="sm:text-center lg:text-left">
                            <h1 class="text-4xl tracking-tight font-extrabold text-slate-900 sm:text-5xl md:text-6xl">
                                <span class="block xl:inline">Koperasi Maju Bersama</span>
                                <span class="block text-primary mt-1">Desa Mlokomanis Wetan</span>
                            </h1>
                            <p class="mt-3 text-base text-slate-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0 leading-relaxed">
                                Mari wujudkan kesejahteraan ekonomi bersama! Kami hadir untuk memberikan kemudahan layanan simpan pinjam yang aman, transparan, dan saling menguntungkan bagi seluruh warga desa.
                            </p>
                            <div class="mt-8 sm:mt-12 sm:flex sm:justify-center lg:justify-start gap-4">
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-primary hover:bg-primaryHover md:py-4 md:text-lg md:px-10 shadow-lg hover:shadow-xl transition-all duration-300">
                                        Bergabung Sekarang
                                    </a>
                                @endif
                                <a href="#layanan" class="mt-3 w-full flex items-center justify-center px-8 py-3 border border-slate-300 text-base font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 md:py-4 md:text-lg md:px-10 transition-all duration-300 sm:mt-0 shadow-sm">
                                    Pelajari Lebih Lanjut
                                </a>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
            <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-slate-100 flex items-center justify-center">
                <!-- Ilustrasi / Gambar (Bisa diganti dengan foto asli desa/koperasi nantinya) -->
                <svg class="w-full h-64 sm:h-72 md:h-96 lg:w-full lg:h-full text-secondary opacity-20" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </section>

        <!-- Layanan Section -->
        <section id="layanan" class="py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-base font-semibold text-primary tracking-wide uppercase">Layanan Kami</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                        Solusi Finansial Untuk Anggota
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-slate-500 mx-auto">
                        Berbagai pilihan layanan simpanan dan pinjaman yang disesuaikan dengan kebutuhan pengembangan ekonomi Anda.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Card 1 -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 hover:shadow-xl hover:border-primary/30 transition duration-300 group">
                        <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Simpanan Pokok & Wajib</h3>
                        <p class="text-slate-500 leading-relaxed">
                            Fondasi keanggotaan koperasi dengan pembagian Sisa Hasil Usaha (SHU) yang adil dan transparan setiap tahunnya.
                        </p>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 hover:shadow-xl hover:border-primary/30 transition duration-300 group">
                        <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Simpanan Sukarela</h3>
                        <p class="text-slate-500 leading-relaxed">
                            Simpanan dana fleksibel yang dapat disetor dan ditarik kapan saja. Alternatif menabung yang aman dan menguntungkan.
                        </p>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 hover:shadow-xl hover:border-primary/30 transition duration-300 group">
                        <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Pinjaman Usaha</h3>
                        <p class="text-slate-500 leading-relaxed">
                            Dukungan permodalan dengan bunga ringan dan syarat mudah untuk memajukan usaha mikro, kecil, dan menengah (UMKM) warga.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA / Register Banner -->
        <section class="bg-primary relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20 text-center">
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                    <span class="block">Siap untuk memajukan ekonomi desa?</span>
                </h2>
                <p class="mt-4 text-lg leading-6 text-emerald-100 max-w-2xl mx-auto">
                    Bergabunglah bersama ratusan anggota lainnya dan nikmati berbagai manfaat menjadi anggota Koperasi Mlokomanis Wetan.
                </p>
                <div class="mt-8 flex justify-center gap-4">
                    @if (Route::has('register'))
                        @guest
                            <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-primary font-bold rounded-lg hover:bg-slate-50 transition duration-300 shadow-lg">
                                Daftar Sekarang
                            </a>
                        @endguest
                    @endif
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-slate-900 mt-auto">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                        K
                    </div>
                    <span class="text-white font-semibold text-lg">KSP Mlokomanis Wetan</span>
                </div>
                
                <div class="text-slate-400 text-sm text-center md:text-left">
                    <p>Jl. Balai Desa Mlokomanis Wetan, Wonogiri, Jawa Tengah</p>
                    <p class="mt-1">&copy; {{ date('Y') }} Koperasi Simpan Pinjam. Hak Cipta Dilindungi.</p>
                </div>

                <div class="text-slate-500 text-xs">
                    Sistem dibangun dengan Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </div>
            </div>
        </footer>

        <!-- Livewire Scripts -->
        @livewireScripts
    </body>
</html>