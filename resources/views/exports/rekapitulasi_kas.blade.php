<table>
    <tr>
        <td colspan="6"><strong>LAPORAN REKAPITULASI BUKU KAS</strong></td>
    </tr>
    <tr>
        <td colspan="6">Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</td>
    </tr>
    <tr>
        <th>Tanggal</th>
        <th>Keterangan</th>
        <th>Kategori</th>
        <th>Kas Masuk (Pemasukan)</th>
        <th>Kas Keluar (Pengeluaran)</th>
        <th>Saldo Berjalan</th>
    </tr>
    @php $saldo = 0; @endphp
    @foreach($buku_kas as $kas)
        @php
            if($kas->jenis === 'pemasukan') {
                $saldo += $kas->nominal;
            } else {
                $saldo -= $kas->nominal;
            }
        @endphp
        <tr>
            <td>{{ \Carbon\Carbon::parse($kas->tanggal)->format('d/m/Y') }}</td>
            <td>{{ $kas->keterangan }}</td>
            <td>{{ ucwords(str_replace('_', ' ', $kas->kategori)) }}</td>
            <td>{{ $kas->jenis === 'pemasukan' ? $kas->nominal : '' }}</td>
            <td>{{ $kas->jenis === 'pengeluaran' ? $kas->nominal : '' }}</td>
            <td>{{ $saldo }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="5"><strong>TOTAL SALDO AKHIR</strong></td>
        <td><strong>{{ $saldo }}</strong></td>
    </tr>
</table>
