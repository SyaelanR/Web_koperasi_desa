<?php

namespace App\Livewire\Admin\BukuKas;

use App\Models\BukuKas;
use Livewire\Component;

class Index extends Component
{
    public $buku_kas_records;
    public $total_pemasukan;
    public $total_pengeluaran;
    public $saldo_akhir;

    public $showModal = false;
    
    // Form fields
    public $tanggal;
    public $jenis = 'pemasukan';
    public $kategori = 'lainnya';
    public $kategori_lainnya = '';
    public $nominal;
    public $keterangan;

    public function mount()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        $this->tanggal = now()->format('Y-m-d');
        $this->loadData();
    }

    public function loadData()
    {
        $this->buku_kas_records = BukuKas::orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();
        $this->total_pemasukan = BukuKas::where('jenis', 'pemasukan')->sum('nominal');
        $this->total_pengeluaran = BukuKas::where('jenis', 'pengeluaran')->sum('nominal');
        $this->saldo_akhir = $this->total_pemasukan - $this->total_pengeluaran;
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->tanggal = now()->format('Y-m-d');
        $this->jenis = 'pemasukan';
        $this->kategori = 'lainnya';
        $this->kategori_lainnya = '';
        $this->nominal = null;
        $this->keterangan = '';
    }

    public function submit()
    {
        $this->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'kategori' => 'required|string',
            'kategori_lainnya' => 'required_if:kategori,lainnya|string|max:255',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'required|string|max:255',
        ]);

        $finalKategori = $this->kategori === 'lainnya' ? $this->kategori_lainnya : $this->kategori;

        BukuKas::create([
            'tanggal' => $this->tanggal,
            'jenis' => $this->jenis,
            'kategori' => $finalKategori,
            'nominal' => $this->nominal,
            'keterangan' => $this->keterangan,
        ]);

        session()->flash('message', 'Catatan kas berhasil ditambahkan.');
        $this->showModal = false;
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.buku-kas.index')
            ->layout('layouts.app');
    }
}
