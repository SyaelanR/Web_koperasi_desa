<?php

namespace App\Livewire\Member\Pinjaman;

use App\Models\Pinjaman;
use Livewire\Component;

class Create extends Component
{
    public $nominal_pinjam;
    public $tenor;

    public function mount()
    {
        if (auth()->user()->role !== 'member') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if (auth()->user()->status !== 'active') {
            session()->flash('message', 'Status keanggotaan Anda saat ini belum aktif. Anda tidak dapat mengajukan pinjaman.');
            return redirect()->route('member.pinjaman.progress');
        }

        // Cek apakah ada pinjaman pending atau sedang berjalan (belum lunas)
        $activePinjaman = Pinjaman::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'approved'])
            ->with('angsurans')
            ->get()
            ->filter(function($pinjaman) {
                if ($pinjaman->status === 'pending') return true;
                $angsuran_masuk = $pinjaman->angsurans->where('status', 'paid')->sum('pokok');
                return ($pinjaman->nominal_pinjam - $angsuran_masuk) > 0;
            })
            ->first();

        if ($activePinjaman) {
            session()->flash('message', 'Anda masih memiliki pinjaman yang sedang berjalan atau menunggu persetujuan. Tidak dapat mengajukan pinjaman baru.');
            return redirect()->route('member.pinjaman.progress');
        }
    }

    public function submit()
    {
        $this->validate([
            'nominal_pinjam' => 'required|numeric|min:10000',
            'tenor' => 'required|integer|min:1|max:36',
        ], [
            'nominal_pinjam.min' => 'Minimal pengajuan pinjaman adalah Rp 10.000',
            'tenor.min' => 'Tenor minimal 1 bulan',
            'tenor.max' => 'Tenor maksimal 36 bulan',
        ]);

        $pinjaman = Pinjaman::create([
            'user_id' => auth()->id(),
            'tanggal_pengajuan' => now()->format('Y-m-d'),
            'nominal_pinjam' => $this->nominal_pinjam,
            'tenor' => $this->tenor,
            'status' => 'pending',
        ]);
        
        // Notifikasi ke member
        auth()->user()->notify(new \App\Notifications\PinjamanDiprosesNotification($pinjaman));
        
        // Notifikasi ke semua admin
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\NewPinjamanNotification($pinjaman, auth()->user()->name));
        }

        session()->flash('message', 'Pengajuan pinjaman berhasil dibuat dan sedang diproses.');
        return redirect()->route('member.pinjaman.progress');
    }

    public function render()
    {
        return view('livewire.member.pinjaman.create')
            ->layout('layouts.app');
    }
}
