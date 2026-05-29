<?php

namespace App\Exports;

use App\Models\Pinjaman;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PiutangWargaExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $filter;

    public function __construct($filter = 'semua')
    {
        $this->filter = $filter;
    }

    public function array(): array
    {
        $query = Pinjaman::with(['user', 'angsurans'])->where('status', 'approved');
        
        if ($this->filter === 'bulan_ini') {
            $query->whereMonth('tanggal_cair', now()->month)
                  ->whereYear('tanggal_cair', now()->year);
        }

        $pinjamans = $query->get();
        
        $data = [];
        $no = 1;
        foreach ($pinjamans as $pinjaman) {
            $angsuran_masuk = $pinjaman->angsurans->where('status', 'paid')->sum('pokok');
            $sisa_pokok = $pinjaman->nominal_pinjam - $angsuran_masuk;
            
            $denda = $pinjaman->angsurans->sum('denda');
            $hasOverdue = false;
            foreach ($pinjaman->angsurans->where('status', 'unpaid') as $angsuran) {
                if (now()->gt(\Carbon\Carbon::parse($angsuran->jatuh_tempo))) {
                    $hasOverdue = true;
                    $diffMonths = now()->diffInMonths(\Carbon\Carbon::parse($angsuran->jatuh_tempo));
                    if ($diffMonths < 1) $diffMonths = 1;
                    $totalTagihan = $angsuran->pokok + $angsuran->bunga + $angsuran->biaya_admin;
                    $denda += ($totalTagihan * 0.05) * $diffMonths;
                }
            }

            if ($sisa_pokok <= 0) {
                $status = 'Lunas';
            } elseif ($hasOverdue) {
                $status = 'Menunggak';
            } else {
                $status = 'Aktif';
            }
            
            if ($sisa_pokok > 0 || $denda > 0) {
                $data[] = [
                    $no++,
                    $pinjaman->user->name,
                    \Carbon\Carbon::parse($pinjaman->tanggal_cair ?? $pinjaman->tanggal_pengajuan)->format('d M Y'),
                    $pinjaman->tenor,
                    $pinjaman->nominal_pinjam,
                    $angsuran_masuk,
                    $sisa_pokok,
                    $denda,
                    $status
                ];
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Anggota',
            'Tgl Cair',
            'Tenor',
            'Nominal Pinjaman',
            'Angsuran Masuk (Pokok)',
            'Sisa Hutang (Kekurangan Pokok)',
            'Akumulasi Denda',
            'Status'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
