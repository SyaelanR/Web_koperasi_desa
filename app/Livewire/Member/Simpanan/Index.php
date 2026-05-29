<?php

namespace App\Livewire\Member\Simpanan;

use App\Models\Simpanan;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        if (auth()->user()->role !== 'member') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $userId = auth()->id();
        
        $totalPokok = Simpanan::where('user_id', $userId)->where('jenis', 'pokok')->sum('nominal');
        $totalWajib = Simpanan::where('user_id', $userId)->where('jenis', 'wajib')->sum('nominal');
        $totalSukarela = Simpanan::where('user_id', $userId)->where('jenis', 'sukarela')->sum('nominal');
        
        $riwayatSimpanan = Simpanan::where('user_id', $userId)->orderBy('tanggal', 'desc')->get();

        return view('livewire.member.simpanan.index', compact('totalPokok', 'totalWajib', 'totalSukarela', 'riwayatSimpanan'))
            ->layout('layouts.app');
    }
}
