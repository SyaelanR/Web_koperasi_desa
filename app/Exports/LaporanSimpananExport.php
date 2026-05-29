<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanSimpananExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $filter;

    public function __construct($filter = 'semua')
    {
        $this->filter = $filter;
    }

    public function array(): array
    {
        $users = User::where('role', 'member')->with(['simpanans' => function($query) {
            if ($this->filter === 'bulan_ini') {
                $query->whereMonth('tanggal', now()->month)
                      ->whereYear('tanggal', now()->year);
            }
        }])->get();
        
        $data = [];
        $no = 1;
        foreach ($users as $user) {
            $totalPokok = $user->simpanans->where('jenis', 'pokok')->sum('nominal');
            $totalWajib = $user->simpanans->where('jenis', 'wajib')->sum('nominal');
            
            if ($totalPokok > 0 || $totalWajib > 0) {
                $data[] = [
                    $no++,
                    $user->name,
                    'Aktif',
                    $totalPokok,
                    $totalWajib,
                    $totalPokok + $totalWajib
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
            'Status Anggota',
            'Simpanan Pokok (Awal Masuk)',
            'Simpanan Wajib (Total Terkumpul)',
            'Total Simpanan (Aset Warga)'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
