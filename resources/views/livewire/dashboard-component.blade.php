<x-slot name="header">
    <h2 class="font-semibold text-xl text-slate-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(Auth::user()->role === 'admin')
            <!-- Admin Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-primary mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Anggota</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $totalAnggota }}</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-primary mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Simpanan</p>
                        <p class="text-xl font-bold text-slate-800">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-primary mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Pinjaman Aktif</p>
                        <p class="text-xl font-bold text-slate-800">Rp {{ number_format($pinjamanAktif, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center">
                    <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Pengajuan Pinjaman</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $pengajuanPinjaman }}</p>
                    </div>
                </div>
            </div>

            <!-- Admin Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Aksi Cepat</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('admin.approval') }}" class="px-4 py-2 bg-primary text-white rounded-lg shadow-md hover:bg-primaryHover transition-colors text-sm font-medium inline-flex items-center" wire:navigate>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Persetujuan Akun
                    </a>
                    <a href="{{ route('admin.simpanan-wajib.index') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg shadow-sm hover:bg-slate-50 transition-colors text-sm font-medium inline-flex items-center" wire:navigate>
                        <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Input Simpanan Wajib
                    </a>
                    <a href="{{ route('admin.simpanan-sukarela.input') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg shadow-sm hover:bg-slate-50 transition-colors text-sm font-medium inline-flex items-center" wire:navigate>
                        <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Input Simpanan Sukarela
                    </a>
                </div>
            </div>

        @else
            <!-- Member Dashboard -->
            <div class="bg-primary rounded-3xl p-8 mb-8 text-white relative overflow-hidden shadow-lg">
                <div class="relative z-10">
                    <h3 class="text-lg font-medium text-emerald-100 mb-1">Selamat datang kembali,</h3>
                    <h2 class="text-3xl font-bold mb-6">{{ Auth::user()->name }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-emerald-100 text-sm mb-1">Total Simpanan Anda</p>
                            <p class="text-4xl font-extrabold tracking-tight">Rp {{ number_format($simpananSaya, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <!-- Background Pattern -->
                <svg class="absolute right-0 bottom-0 opacity-10 w-64 h-64 transform translate-x-1/4 translate-y-1/4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Pinjaman Info -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-primary mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500">Pinjaman Aktif</p>
                            <p class="text-xl font-bold text-slate-800">Rp {{ number_format($pinjamanAktif, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @if($pinjamanPending > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            {{ $pinjamanPending }} Pengajuan
                        </span>
                    @endif
                </div>
            </div>

            <!-- Member Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Aksi Cepat</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('member.pinjaman.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg shadow-md hover:bg-primaryHover transition-colors text-sm font-medium inline-flex items-center" wire:navigate>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Ajukan Pinjaman
                    </a>
                    <a href="{{ route('member.simpanan.index') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg shadow-sm hover:bg-slate-50 transition-colors text-sm font-medium inline-flex items-center" wire:navigate>
                        <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Lihat Riwayat Simpanan
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
