<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 transition-opacity sm:hidden" @click="sidebarOpen = false" x-transition.opacity></div>

    <!-- Sidebar -->
    <nav :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 transform transition-transform duration-300 ease-in-out sm:relative sm:translate-x-0 flex flex-col h-full">
        
        <!-- Sidebar Header / Logo -->
        <div class="flex items-center justify-between h-20 px-6 border-b border-slate-100 shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3" wire:navigate>
                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                    K
                </div>
                <span class="font-bold text-xl text-slate-800 tracking-tight">KSP Mlokomanis</span>
            </a>
            <!-- Close button for mobile -->
            <button @click="sidebarOpen = false" class="sm:hidden text-slate-400 hover:text-slate-500 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1 scrollbar-thin scrollbar-thumb-slate-200">
            <x-nav-link-sidebar :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate icon="dashboard">
                {{ __('Dashboard') }}
            </x-nav-link-sidebar>

            @if(Auth::user()->role === 'admin')
                <div class="pt-4 pb-2">
                    <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Admin Menu</p>
                </div>
                <x-nav-link-sidebar :href="route('admin.approval')" :active="request()->routeIs('admin.approval')" wire:navigate icon="users">
                    {{ __('Persetujuan Akun') }}
                </x-nav-link-sidebar>
                <x-nav-link-sidebar :href="route('admin.anggota.index')" :active="request()->routeIs('admin.anggota.index')" wire:navigate icon="user-group">
                    {{ __('Daftar Anggota') }}
                </x-nav-link-sidebar>
                <x-nav-link-sidebar :href="route('admin.simpanan-wajib.index')" :active="request()->routeIs('admin.simpanan-wajib.*')" wire:navigate icon="cash">
                    {{ __('Simpanan Wajib') }}
                </x-nav-link-sidebar>
                <x-nav-link-sidebar :href="route('admin.simpanan-sukarela.input')" :active="request()->routeIs('admin.simpanan-sukarela.*')" wire:navigate icon="currency-dollar">
                    {{ __('Simpanan Sukarela') }}
                </x-nav-link-sidebar>
                <x-nav-link-sidebar :href="route('admin.pinjaman.index')" :active="request()->routeIs('admin.pinjaman.*')" wire:navigate icon="credit-card">
                    {{ __('Pinjaman') }}
                </x-nav-link-sidebar>
                <x-nav-link-sidebar :href="route('admin.buku-kas.index')" :active="request()->routeIs('admin.buku-kas.index')" wire:navigate icon="book-open">
                    {{ __('Buku Kas') }}
                </x-nav-link-sidebar>
                <x-nav-link-sidebar :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.index')" wire:navigate icon="document-report">
                    {{ __('Laporan') }}
                </x-nav-link-sidebar>
            @elseif(Auth::user()->role === 'member')
                <div class="pt-4 pb-2">
                    <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Menu Anggota</p>
                </div>
                <x-nav-link-sidebar :href="route('member.simpanan.index')" :active="request()->routeIs('member.simpanan.index')" wire:navigate icon="cash">
                    {{ __('Riwayat Simpanan') }}
                </x-nav-link-sidebar>
                <x-nav-link-sidebar :href="route('member.pinjaman.progress')" :active="request()->routeIs('member.pinjaman.*')" wire:navigate icon="credit-card">
                    {{ __('Pengajuan & Cicilan') }}
                </x-nav-link-sidebar>
            @endif
        </div>

        <!-- Sidebar Footer (Profile & Logout) -->
        <div class="border-t border-slate-200 p-4 shrink-0 bg-slate-50/50">
            <div class="flex items-center pb-4">
                <div class="h-9 w-9 rounded-full bg-emerald-100 flex items-center justify-center text-primary font-bold shadow-sm">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="ml-3 flex-1 overflow-hidden">
                    <p class="text-sm font-medium text-slate-900 truncate" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></p>
                    <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('profile') }}" class="flex-1 text-center py-2 px-3 border border-slate-200 rounded-lg text-sm font-medium text-slate-600 hover:bg-white hover:text-primary transition-colors shadow-sm" wire:navigate>
                    Profil
                </a>
                <button wire:click="logout" class="flex-1 py-2 px-3 border border-red-100 bg-red-50 rounded-lg text-sm font-medium text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors shadow-sm">
                    Keluar
                </button>
            </div>
        </div>
    </nav>
</div>
