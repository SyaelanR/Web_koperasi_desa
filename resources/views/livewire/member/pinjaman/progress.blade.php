<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Progres Cicilan Pinjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session()->has('message'))
                <div class="p-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 border border-yellow-200">
                    {{ session('message') }}
                </div>
            @endif

            @if(!$pinjaman)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pinjaman aktif</h3>
                    <p class="mt-1 text-sm text-gray-500">Anda tidak memiliki pinjaman yang sedang berjalan atau menunggu persetujuan.</p>
                    <div class="mt-6">
                        <a href="{{ route('member.pinjaman.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Ajukan Pinjaman Baru
                        </a>
                    </div>
                </div>
            @elseif($pinjaman->status === 'pending')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 mb-4">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Pinjaman Sedang Ditinjau</h3>
                    <p class="text-sm text-gray-600">Pengajuan pinjaman Anda sebesar <strong>Rp {{ number_format($pinjaman->nominal_pinjam, 0, ',', '.') }}</strong> pada tanggal {{ \Carbon\Carbon::parse($pinjaman->tanggal_pengajuan)->format('d M Y') }} sedang menunggu persetujuan dari Admin.</p>
                    <p class="text-sm text-gray-500 mt-2">Silakan periksa halaman ini kembali secara berkala.</p>
                </div>
            @else
                {{-- Status Approved --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-blue-500 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Ringkasan Pinjaman ({{ $pinjaman->tenor }} Bulan)</h3>
                        <p class="text-sm text-gray-600 mt-1">Cair pada: {{ \Carbon\Carbon::parse($pinjaman->tanggal_cair)->format('d M Y') }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Sisa Pokok Hutang</div>
                        <div class="text-2xl font-black text-blue-700">Rp {{ number_format($sisa_pokok, 0, ',', '.') }}</div>
                        <div class="text-xs font-semibold mt-1 px-2 py-1 inline-block rounded-full {{ $status_pelunasan === 'Lunas' ? 'bg-green-100 text-green-800' : ($denda > 0 ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ $status_pelunasan }}
                        </div>
                    </div>
                </div>

                @if($denda > 0)
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">
                                Anda memiliki keterlambatan cicilan. Estimasi total denda saat ini adalah <strong>Rp {{ number_format($denda, 0, ',', '.') }}</strong>. Harap segera melunasi tunggakan Anda.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800">Jadwal Angsuran</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan Ke</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rincian Tagihan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($angsurans as $angsuran)
                                    @php
                                        $isOverdue = $angsuran->status === 'unpaid' && now()->gt(\Carbon\Carbon::parse($angsuran->jatuh_tempo));
                                        $angsuranDenda = $angsuran->denda ?? 0;
                                        if ($isOverdue) {
                                            $diffMonths = now()->diffInMonths(\Carbon\Carbon::parse($angsuran->jatuh_tempo));
                                            if ($diffMonths < 1) $diffMonths = 1;
                                            $totalTagihan = $angsuran->pokok + $angsuran->bunga + $angsuran->biaya_admin;
                                            $angsuranDenda += ($totalTagihan * 0.05) * $diffMonths;
                                        }
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition {{ $isOverdue ? 'bg-red-50/50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium text-center">
                                            {{ $angsuran->bulan_ke }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $isOverdue ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                                            {{ \Carbon\Carbon::parse($angsuran->jatuh_tempo)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>Pokok: Rp {{ number_format($angsuran->pokok, 0, ',', '.') }}</div>
                                            <div>Bunga: Rp {{ number_format($angsuran->bunga, 0, ',', '.') }}</div>
                                            <div>Admin: Rp {{ number_format($angsuran->biaya_admin, 0, ',', '.') }}</div>
                                            @if($angsuranDenda > 0)
                                                <div class="text-red-600">Denda: Rp {{ number_format($angsuranDenda, 0, ',', '.') }}</div>
                                            @endif
                                            <div class="font-bold text-gray-900 mt-1 border-t pt-1">
                                                Total: Rp {{ number_format($angsuran->pokok + $angsuran->bunga + $angsuran->biaya_admin + $angsuranDenda, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($angsuran->status === 'paid')
                                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                    Lunas ({{ \Carbon\Carbon::parse($angsuran->tanggal_bayar)->format('d/m') }})
                                                </span>
                                            @elseif($isOverdue)
                                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                                    Menunggak
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                                    Belum Jatuh Tempo
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($status_pelunasan === 'Lunas')
                    <div class="mt-6 text-center">
                        <a href="{{ route('member.pinjaman.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                            Ajukan Pinjaman Baru
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
