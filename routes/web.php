<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/admin/approval', \App\Livewire\Admin\Approval::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.approval');

Route::get('/admin/simpanan-wajib', \App\Livewire\Admin\SimpananWajib\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.simpanan-wajib.index');

Route::get('/admin/simpanan-wajib/{tagihan_id}', \App\Livewire\Admin\SimpananWajib\Show::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.simpanan-wajib.show');

Route::get('/admin/simpanan-wajib/{tagihan_id}/input', \App\Livewire\Admin\SimpananWajib\BatchInput::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.simpanan-wajib.batch-input');

Route::get('/member/pinjaman/create', \App\Livewire\Member\Pinjaman\Create::class)
    ->middleware(['auth', 'verified'])
    ->name('member.pinjaman.create');



Route::get('/member/pinjaman/progress', \App\Livewire\Member\Pinjaman\Progress::class)
    ->middleware(['auth', 'verified'])
    ->name('member.pinjaman.progress');

Route::get('/member/simpanan', \App\Livewire\Member\Simpanan\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('member.simpanan.index');

Route::get('/admin/anggota', \App\Livewire\Admin\Anggota\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.anggota.index');

Route::get('/admin/pinjaman', \App\Livewire\Admin\Pinjaman\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.pinjaman.index');

Route::get('/admin/pinjaman/{id}', \App\Livewire\Admin\Pinjaman\Show::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.pinjaman.show');

Route::get('/admin/simpanan-sukarela/input', \App\Livewire\Admin\SimpananSukarela\BatchInput::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.simpanan-sukarela.input');

Route::get('/admin/buku-kas', \App\Livewire\Admin\BukuKas\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.buku-kas.index');

Route::get('/admin/laporan', \App\Livewire\Admin\Laporan\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.laporan.index');

require __DIR__.'/auth.php';
