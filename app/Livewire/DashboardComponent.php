<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use Illuminate\Support\Facades\Auth;

class DashboardComponent extends Component
{
    public function render()
    {
        $user = Auth::user();
        $data = [];

        if ($user->role === 'admin') {
            $data['totalAnggota'] = User::where('role', 'member')->count();
            $data['totalSimpanan'] = Simpanan::sum('nominal');
            $data['pinjamanAktif'] = Pinjaman::where('status', 'disetujui')->sum('nominal_pinjam');
            $data['pengajuanPinjaman'] = Pinjaman::where('status', 'menunggu')->count();
        } else {
            // member
            $data['simpananSaya'] = Simpanan::where('user_id', $user->id)->sum('nominal');
            $data['pinjamanAktif'] = Pinjaman::where('user_id', $user->id)->where('status', 'disetujui')->sum('nominal_pinjam');
            $data['pinjamanPending'] = Pinjaman::where('user_id', $user->id)->where('status', 'menunggu')->count();
        }

        return view('livewire.dashboard-component', $data)
            ->layout('layouts.app');
    }
}
