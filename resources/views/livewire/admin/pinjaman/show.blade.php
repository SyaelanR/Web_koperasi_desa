<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pinjaman & Cicilan: ') }} {{ $pinjaman->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Detail Pinjaman (Kiri) --}}
                <div class="col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Informasi Pinjaman</h3>
                        
                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-gray-500">Tanggal Pengajuan</p>
                                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($pinjaman->tanggal_pengajuan)->format('d M Y') }}</p>
                            </div>
                            @if($pinjaman->tanggal_cair)
                            <div>
                                <p class="text-gray-500">Tanggal Pencairan</p>
                                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($pinjaman->tanggal_cair)->format('d M Y') }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-gray-500">Pokok Pinjaman</p>
                                <p class="font-medium text-gray-900">Rp {{ number_format($pinjaman->nominal_pinjam, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Tenor</p>
                                <p class="font-medium text-gray-900">{{ $pinjaman->tenor }} Bulan</p>
                            </div>
                            <div class="pt-2 border-t border-gray-100">
                                <p class="text-gray-500">Total Bunga (Flat)</p>
                                <p class="font-medium text-gray-900">Rp {{ number_format($pinjaman->bunga_nominal, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Total Biaya Admin</p>
                                <p class="font-medium text-gray-900">Rp {{ number_format($pinjaman->biaya_admin, 0, ',', '.') }}</p>
                            </div>
                            <div class="pt-2 border-t border-gray-100">
                                <p class="text-gray-500">Status Pinjaman</p>
                                @if($pinjaman->status === 'paid_off')
                                    <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Lunas</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Berjalan</span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('admin.pinjaman.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                &larr; Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Tabel Cicilan (Kanan) --}}
                <div class="col-span-1 md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Jadwal & Pembayaran Angsuran</h3>
                        
                        @if (session()->has('message'))
                            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
                                {{ session('message') }}
                            </div>
                        @endif

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan Ke</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tagihan Pokok+Bunga</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="relative py-3 pl-3 pr-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach($angsurans as $angsuran)
                                        <tr class="hover:bg-gray-50">
                                            <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $angsuran->angsuran_ke }}</td>
                                            <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($angsuran->jatuh_tempo)->format('d M Y') }}</td>
                                            <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-900 font-medium">
                                                Rp {{ number_format($angsuran->pokok + $angsuran->bunga + $angsuran->biaya_admin, 0, ',', '.') }}
                                                @if($angsuran->denda > 0)
                                                    <br><span class="text-xs text-red-600">+ Denda Rp {{ number_format($angsuran->denda, 0, ',', '.') }}</span>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-3 text-sm">
                                                @if($angsuran->status === 'paid')
                                                    <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700">Sudah Dibayar ({{ \Carbon\Carbon::parse($angsuran->tanggal_bayar)->format('d/m') }})</span>
                                                @else
                                                    @if(now()->gt(\Carbon\Carbon::parse($angsuran->jatuh_tempo)))
                                                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700">Terlambat</span>
                                                    @else
                                                        <span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800">Belum Dibayar</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="relative whitespace-nowrap py-3 pl-3 pr-4 text-center text-sm font-medium">
                                                @if($angsuran->status === 'unpaid')
                                                    <button wire:click="openPayModal({{ $angsuran->id }})" class="text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 rounded text-xs font-semibold shadow-sm transition duration-150">
                                                        Bayar
                                                    </button>
                                                @else
                                                    <span class="text-gray-400 text-xs">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Pembayaran --}}
    @if($showPayModal && $selectedAngsuran)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none bg-black bg-opacity-40 backdrop-blur-sm transition-opacity">
        <div class="relative w-full max-w-md mx-auto my-6">
            <div class="relative flex flex-col w-full bg-white border-0 rounded-xl shadow-2xl outline-none focus:outline-none">
                <div class="flex items-start justify-between p-5 border-b border-solid rounded-t border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">
                        Proses Pembayaran Cicilan
                    </h3>
                    <button wire:click="$set('showPayModal', false)" class="p-1 ml-auto text-gray-400 hover:text-gray-600 transition duration-150 outline-none focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="relative flex-auto p-6">
                    <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-gray-600">Cicilan Pokok:</span>
                            <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($selectedAngsuran->pokok, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-gray-600">Bunga:</span>
                            <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($selectedAngsuran->bunga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between mb-2 pb-2 border-b border-gray-300">
                            <span class="text-sm text-gray-600">Biaya Admin:</span>
                            <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($selectedAngsuran->biaya_admin, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between mt-2">
                            <span class="text-sm font-bold text-gray-800">Total Tagihan Normal:</span>
                            <span class="text-sm font-bold text-indigo-600">Rp {{ number_format($selectedAngsuran->pokok + $selectedAngsuran->bunga + $selectedAngsuran->biaya_admin, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <form wire:submit.prevent="pay">
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Denda Keterlambatan</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input wire:model="denda_nominal" type="number" class="w-full pl-10 px-4 py-2 leading-tight text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                            </div>
                            <p class="text-xs text-red-500 mt-1">Sistem menyarankan denda otomatis jika telat (5% per bulan keterlambatan). Anda bebas mengedit angka ini.</p>
                            @error('denda_nominal') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-end pt-4 mt-4 border-t border-solid border-gray-200">
                            <button wire:click="$set('showPayModal', false)" type="button" class="px-5 py-2.5 mr-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 transition duration-150">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 transition duration-150 shadow-sm">
                                Proses Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
