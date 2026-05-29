<?php

namespace App\Livewire\Admin\Laporan;

use App\Exports\RekapitulasiKasExport;
use App\Exports\PiutangWargaExport;
use App\Exports\LaporanSimpananExport;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Component;

class Index extends Component
{
    public $filter_piutang = 'semua';
    public $filter_simpanan = 'semua';

    public function mount()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    public function exportRekapitulasiKas()
    {
        return Excel::download(new RekapitulasiKasExport, 'Laporan_Rekapitulasi_Kas_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function exportPiutangWarga()
    {
        return Excel::download(new PiutangWargaExport($this->filter_piutang), 'Laporan_Piutang_Warga_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function exportLaporanSimpanan()
    {
        return Excel::download(new LaporanSimpananExport($this->filter_simpanan), 'Laporan_Simpanan_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function render()
    {
        return view('livewire.admin.laporan.index')
            ->layout('layouts.app');
    }
}
