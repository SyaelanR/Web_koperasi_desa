<?php

namespace App\Livewire\Admin\Pinjaman;

use App\Models\Pinjaman;
use App\Models\Angsuran;
use App\Models\BukuKas;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Show extends Component
{
    public $pinjaman;
    public $angsurans;

    public $showPayModal = false;
    public $selectedAngsuran;
    public $denda_nominal = 0;

    public function mount($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        $this->pinjaman = Pinjaman::with('user')->findOrFail($id);
        $this->loadAngsurans();
    }

    public function loadAngsurans()
    {
        $this->angsurans = Angsuran::where('pinjaman_id', $this->pinjaman->id)->orderBy('angsuran_ke', 'asc')->get();
    }

    public function openPayModal($id)
    {
        $this->selectedAngsuran = Angsuran::find($id);

        // Hitung denda: 5% per bulan keterlambatan dari total tagihan bulan ini
        $jatuh_tempo = Carbon::parse($this->selectedAngsuran->jatuh_tempo);
        $sekarang = now();
        $this->denda_nominal = 0;

        if ($sekarang->gt($jatuh_tempo)) {
            $bulan_telat = $sekarang->diffInMonths($jatuh_tempo);
            if ($bulan_telat < 1) {
                $bulan_telat = 1; // Telat beberapa hari dihitung 1 bulan
            }
            $total_tagihan = $this->selectedAngsuran->pokok + $this->selectedAngsuran->bunga + $this->selectedAngsuran->biaya_admin;
            $this->denda_nominal = $total_tagihan * 0.05 * $bulan_telat;
        }

        $this->showPayModal = true;
    }

    public function pay()
    {
        $this->validate([
            'denda_nominal' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () {
            // Update Angsuran
            $this->selectedAngsuran->update([
                'status' => 'paid',
                'tanggal_bayar' => now(),
                'denda' => $this->denda_nominal,
            ]);

            // Catat Pemasukan Angsuran Pokok
            BukuKas::create([
                'tanggal' => now(),
                'keterangan' => 'Angsuran Pokok ke-'.$this->selectedAngsuran->angsuran_ke.': ' . $this->pinjaman->user->name,
                'jenis' => 'pemasukan',
                'kategori' => 'angsuran_pokok',
                'nominal' => $this->selectedAngsuran->pokok,
            ]);

            // Jika metode potongan masuk cicilan, catat bunga dan admin
            if ($this->pinjaman->metode_potongan === 'masuk_cicilan') {
                if ($this->selectedAngsuran->bunga > 0) {
                    BukuKas::create([
                        'tanggal' => now(),
                        'keterangan' => 'Angsuran Bunga ke-'.$this->selectedAngsuran->angsuran_ke.': ' . $this->pinjaman->user->name,
                        'jenis' => 'pemasukan',
                        'kategori' => 'bunga',
                        'nominal' => $this->selectedAngsuran->bunga,
                    ]);
                }
                if ($this->selectedAngsuran->biaya_admin > 0) {
                    BukuKas::create([
                        'tanggal' => now(),
                        'keterangan' => 'Angsuran Admin ke-'.$this->selectedAngsuran->angsuran_ke.': ' . $this->pinjaman->user->name,
                        'jenis' => 'pemasukan',
                        'kategori' => 'biaya_admin',
                        'nominal' => $this->selectedAngsuran->biaya_admin,
                    ]);
                }
            }

            // Catat Denda jika ada
            if ($this->denda_nominal > 0) {
                BukuKas::create([
                    'tanggal' => now(),
                    'keterangan' => 'Denda Keterlambatan ke-'.$this->selectedAngsuran->angsuran_ke.': ' . $this->pinjaman->user->name,
                    'jenis' => 'pemasukan',
                    'kategori' => 'denda',
                    'nominal' => $this->denda_nominal,
                ]);
            }

            // Cek apakah pinjaman lunas
            $unpaidCount = Angsuran::where('pinjaman_id', $this->pinjaman->id)->where('status', 'unpaid')->count();
            if ($unpaidCount === 0) {
                $this->pinjaman->update(['status' => 'paid_off']);
                
                // Notify member
                $this->pinjaman->user->notify(new \App\Notifications\PinjamanLunasNotification($this->pinjaman));
            }
            
            // Notify member about the payment
            $this->pinjaman->user->notify(new \App\Notifications\PembayaranCicilanNotification($this->selectedAngsuran));
        });

        session()->flash('message', 'Pembayaran angsuran berhasil dicatat.');
        $this->showPayModal = false;
        $this->loadAngsurans();
    }

    public function render()
    {
        return view('livewire.admin.pinjaman.show')
            ->layout('layouts.app');
    }
}
