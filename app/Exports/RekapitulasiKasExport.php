<?php

namespace App\Exports;

use App\Models\BukuKas;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapitulasiKasExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        $records = BukuKas::orderBy('tanggal', 'asc')->get();
        
        $data = [];
        $grouped = $records->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->tanggal)->format('M Y');
        });

        $no = 1;
        foreach ($grouped as $month => $items) {
            $angsuran_pokok = $items->where('jenis', 'pemasukan')->where('kategori', 'angsuran_pokok')->sum('nominal');
            $bunga = $items->where('jenis', 'pemasukan')->where('kategori', 'bunga_pinjaman')->sum('nominal');
            $denda = $items->where('jenis', 'pemasukan')->where('kategori', 'denda_pinjaman')->sum('nominal');
            $simp_wajib = $items->where('jenis', 'pemasukan')->where('kategori', 'simpanan_wajib')->sum('nominal');
            $admst = $items->where('jenis', 'pemasukan')->where('kategori', 'biaya_admin')->sum('nominal');
            
            $pemasukan_lain_items = $items->where('jenis', 'pemasukan')->whereNotIn('kategori', ['angsuran_pokok', 'bunga_pinjaman', 'denda_pinjaman', 'simpanan_wajib', 'biaya_admin']);
            $pemasukan_lain = $pemasukan_lain_items->sum('nominal');

            $jumlah_masuk = $angsuran_pokok + $bunga + $denda + $simp_wajib + $admst + $pemasukan_lain;

            $beredar = $items->where('jenis', 'pengeluaran')->where('kategori', 'pencairan_pinjaman')->sum('nominal');
            
            $pengeluaran_lain_items = $items->where('jenis', 'pengeluaran')->where('kategori', '!=', 'pencairan_pinjaman');
            $peng_lain2 = $pengeluaran_lain_items->sum('nominal');

            $sisa_kas = $jumlah_masuk - $beredar - $peng_lain2;

            $data[] = [
                $no++,
                $month,
                $angsuran_pokok,
                $bunga,
                $denda,
                $simp_wajib,
                $admst,
                $pemasukan_lain,
                $jumlah_masuk,
                $beredar,
                $peng_lain2,
                $sisa_kas
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal / Bulan',
            'Angsuran Pokok',
            'Bunga',
            'Denda',
            'Simp. Wajib',
            'Admst',
            'Pemasukan Lain',
            'JUMLAH (Uang Masuk)',
            'Beredar (Pinjam)',
            'Peng. Lain2',
            'SISA KAS Bersih'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
