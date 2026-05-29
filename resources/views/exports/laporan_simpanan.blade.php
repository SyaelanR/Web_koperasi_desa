<table>
    <tr>
        <td colspan="4"><strong>LAPORAN SIMPANAN WARGA</strong></td>
    </tr>
    <tr>
        <td colspan="4">Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</td>
    </tr>
    <tr>
        <th>Nama Warga</th>
        <th>Total Simpanan Pokok</th>
        <th>Total Simpanan Wajib</th>
        <th>Total Keseluruhan Simpanan</th>
    </tr>
    @foreach($data as $row)
        <tr>
            <td>{{ $row['nama'] }}</td>
            <td>{{ $row['total_pokok'] }}</td>
            <td>{{ $row['total_wajib'] }}</td>
            <td><strong>{{ $row['total_keseluruhan'] }}</strong></td>
        </tr>
    @endforeach
</table>
