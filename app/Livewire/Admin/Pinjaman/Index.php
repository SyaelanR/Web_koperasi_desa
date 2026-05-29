<?php

namespace App\Livewire\Admin\Pinjaman;

use App\Models\Pinjaman;
use App\Models\Angsuran;
use App\Models\BukuKas;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $pinjamans;
    public $showApprovalModal = false;
    
    public $selectedPinjaman;
    public $bunga_nominal;
    public $biaya_admin;
    public $metode_potongan = 'potong_cair';

    public function mount()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        $this->loadPinjamans();
    }

    public function loadPinjamans()
    {
        $this->pinjamans = Pinjaman::with('user')->orderBy('created_at', 'desc')->get();
    }

    public function openApprovalModal($id)
    {
        $this->selectedPinjaman = Pinjaman::find($id);
        
        // Calculate defaults
        // Bunga: 2%
        $this->bunga_nominal = $this->selectedPinjaman->nominal_pinjam * 0.02;
        // Admin: 1.5%
        $this->biaya_admin = $this->selectedPinjaman->nominal_pinjam * 0.015;
        $this->metode_potongan = 'potong_cair';

        $this->showApprovalModal = true;
    }

    public function approve()
    {
        $this->validate([
            'bunga_nominal' => 'required|numeric|min:0',
            'biaya_admin' => 'required|numeric|min:0',
            'metode_potongan' => 'required|in:potong_cair,masuk_cicilan',
        ]);

        DB::transaction(function () {
            // Calculate nominal cair bersih
            $nominal_cair = $this->selectedPinjaman->nominal_pinjam;
            
            if ($this->metode_potongan === 'potong_cair') {
                $nominal_cair = $this->selectedPinjaman->nominal_pinjam - $this->bunga_nominal - $this->biaya_admin;
            }

            $this->selectedPinjaman->update([
                'status' => 'approved',
                'tanggal_cair' => now(),
                'bunga_nominal' => $this->bunga_nominal,
                'biaya_admin' => $this->biaya_admin,
                'metode_potongan' => $this->metode_potongan,
                'nominal_cair_bersih' => $nominal_cair,
            ]);

            // Generate Angsurans (Cicilan)
            $pokok_per_bulan = $this->selectedPinjaman->nominal_pinjam / $this->selectedPinjaman->tenor;
            $bunga_per_bulan = ($this->metode_potongan === 'masuk_cicilan') ? ($this->bunga_nominal / $this->selectedPinjaman->tenor) : 0;
            $admin_per_bulan = ($this->metode_potongan === 'masuk_cicilan') ? ($this->biaya_admin / $this->selectedPinjaman->tenor) : 0;

            for ($i = 1; $i <= $this->selectedPinjaman->tenor; $i++) {
                Angsuran::create([
                    'pinjaman_id' => $this->selectedPinjaman->id,
                    'angsuran_ke' => $i,
                    'jatuh_tempo' => now()->addMonths($i),
                    'pokok' => $pokok_per_bulan,
                    'bunga' => $bunga_per_bulan,
                    'biaya_admin' => $admin_per_bulan,
                    'denda' => 0,
                    'status' => 'unpaid',
                ]);
            }

            // Catat ke Buku Kas (Pengeluaran pinjaman)
            BukuKas::create([
                'tanggal' => now(),
                'keterangan' => 'Pencairan Pinjaman: ' . $this->selectedPinjaman->user->name,
                'jenis' => 'pengeluaran',
                'kategori' => 'pinjaman_beredar',
                'nominal' => $nominal_cair,
            ]);
            
            // Jika potong cair, berarti koperasi mencatat pemasukan bunga dan admin di awal
            if ($this->metode_potongan === 'potong_cair') {
                if ($this->bunga_nominal > 0) {
                    BukuKas::create([
                        'tanggal' => now(),
                        'keterangan' => 'Potongan Bunga Pinjaman: ' . $this->selectedPinjaman->user->name,
                        'jenis' => 'pemasukan',
                        'kategori' => 'bunga',
                        'nominal' => $this->bunga_nominal,
                    ]);
                }
                if ($this->biaya_admin > 0) {
                    BukuKas::create([
                        'tanggal' => now(),
                        'keterangan' => 'Potongan Biaya Admin Pinjaman: ' . $this->selectedPinjaman->user->name,
                        'jenis' => 'pemasukan',
                        'kategori' => 'biaya_admin',
                        'nominal' => $this->biaya_admin,
                    ]);
                }
            }
        });
        
        // Kirim Notifikasi
        $this->selectedPinjaman->user->notify(new \App\Notifications\PinjamanDiterimaNotification($this->selectedPinjaman));

        session()->flash('message', 'Pinjaman berhasil disetujui dan jadwal angsuran telah dibuat.');
        $this->showApprovalModal = false;
        $this->loadPinjamans();
    }

    public function reject($id)
    {
        $pinjaman = Pinjaman::find($id);
        $pinjaman->update(['status' => 'rejected']);
        session()->flash('message', 'Pinjaman ditolak.');
        $this->loadPinjamans();
    }

    public function render()
    {
        return view('livewire.admin.pinjaman.index')
            ->layout('layouts.app');
    }
}
