<table>
    <tr>
        <td colspan="6"><strong>DAFTAR PIUTANG WARGA (PINJAMAN BELUM LUNAS)</strong></td>
    </tr>
    <tr>
        <td colspan="6">Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</td>
    </tr>
    <tr>
        <th>Nama Warga</th>
        <th>Nominal Pinjaman Awal</th>
        <th>Sisa Pokok Belum Dibayar</th>
        <th>Sisa Bunga & Admin Belum Dibayar</th>
        <th>Estimasi Denda Keterlambatan</th>
        <th>Total Piutang (Tunggakan)</th>
    </tr>
    @foreach($data as $row)
        <tr>
            <td>{{ $row['nama'] }}</td>
            <td>{{ $row['nominal_pinjaman'] }}</td>
            <td>{{ $row['sisa_pokok'] }}</td>
            <td>{{ $row['sisa_bunga_admin'] }}</td>
            <td>{{ $row['estimasi_denda'] }}</td>
            <td><strong>{{ $row['total_piutang'] }}</strong></td>
        </tr>
    @endforeach
</table>
