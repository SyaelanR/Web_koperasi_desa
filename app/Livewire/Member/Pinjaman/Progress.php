<?php

namespace App\Livewire\Member\Pinjaman;

use App\Models\Pinjaman;
use Livewire\Component;

class Progress extends Component
{
    public $pinjaman;
    public $angsurans = [];
    public $sisa_pokok = 0;
    public $denda = 0;
    public $status_pelunasan = '';

    public function mount()
    {
        if (auth()->user()->role !== 'member') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $this->pinjaman = Pinjaman::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'approved'])
            ->with('angsurans')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($this->pinjaman && $this->pinjaman->status === 'approved') {
            $this->angsurans = $this->pinjaman->angsurans;
            
            $angsuran_masuk = $this->angsurans->where('status', 'paid')->sum('pokok');
            $this->sisa_pokok = $this->pinjaman->nominal_pinjam - $angsuran_masuk;

            foreach ($this->angsurans->where('status', 'unpaid') as $angsuran) {
                if (now()->gt(\Carbon\Carbon::parse($angsuran->jatuh_tempo))) {
                    $diffMonths = now()->diffInMonths(\Carbon\Carbon::parse($angsuran->jatuh_tempo));
                    if ($diffMonths < 1) $diffMonths = 1;
                    $totalTagihan = $angsuran->pokok + $angsuran->bunga + $angsuran->biaya_admin;
                    $this->denda += ($totalTagihan * 0.05) * $diffMonths;
                }
            }

            if ($this->sisa_pokok <= 0) {
                $this->status_pelunasan = 'Lunas';
            } else {
                $this->status_pelunasan = $this->denda > 0 ? 'Menunggak (Ada Denda)' : 'Aktif (Berjalan)';
            }
        }
    }

    public function render()
    {
        return view('livewire.member.pinjaman.progress')
            ->layout('layouts.app');
    }
}
