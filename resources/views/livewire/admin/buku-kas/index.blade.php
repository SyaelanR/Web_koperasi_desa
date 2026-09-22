<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buku Kas Koperasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Pemasukan</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Pengeluaran</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-indigo-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Saldo Akhir</p>
                    <p class="mt-2 text-3xl font-bold text-indigo-700">Rp {{ number_format($saldo_akhir, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Riwayat Transaksi Buku Kas</h2>
                        <p class="text-sm text-gray-600 mt-1">Seluruh arus kas (Simpanan, Pinjaman, dan manual) tercatat di sini.</p>
                    </div>
                    <button wire:click="openModal" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md transition duration-150 shadow-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Catatan Kas
                    </button>
                </div>

                @if (session()->has('message'))
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="overflow-x-auto overflow-y-auto max-h-[40rem] rounded-lg shadow ring-1 ring-black ring-opacity-5">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Keterangan</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Kategori</th>
                                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Pemasukan (Rp)</th>
                                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Pengeluaran (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($buku_kas_records as $record)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($record->tanggal)->format('d/m/Y') }}</td>
                                    <td class="px-3 py-4 text-sm font-medium text-gray-900 max-w-xs truncate" title="{{ $record->keterangan }}">{{ $record->keterangan }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 capitalize">{{ str_replace('_', ' ', $record->kategori) }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-green-600 text-right">
                                        {{ $record->jenis === 'pemasukan' ? number_format($record->nominal, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-red-600 text-right">
                                        {{ $record->jenis === 'pengeluaran' ? number_format($record->nominal, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-500">Belum ada transaksi yang tercatat di Buku Kas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Manual --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none bg-black bg-opacity-40 backdrop-blur-sm transition-opacity">
        <div class="relative w-full max-w-lg mx-auto my-6">
            <div class="relative flex flex-col w-full bg-white border-0 rounded-xl shadow-2xl outline-none focus:outline-none">
                <div class="flex items-start justify-between p-5 border-b border-solid rounded-t border-gray-200 bg-gray-50">
                    <h3 class="text-xl font-bold text-gray-800">
                        Input Kas Manual
                    </h3>
                    <button wire:click="$set('showModal', false)" class="p-1 ml-auto text-gray-400 hover:text-gray-600 transition duration-150 outline-none focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="relative flex-auto p-6">
                    <form wire:submit.prevent="submit">
                        <div class="mb-4 grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">Jenis Arus Kas</label>
                                <select wire:model.live="jenis" class="w-full px-4 py-2 leading-tight text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="pemasukan">Kas Masuk (Pemasukan)</option>
                                    <option value="pengeluaran">Kas Keluar (Pengeluaran)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">Tanggal</label>
                                <input wire:model="tanggal" type="date" class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                @error('tanggal') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Kategori</label>
                            <select wire:model.live="kategori" class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                @if($jenis === 'pemasukan')
                                    <option value="bantuan_pemerintah">Bantuan Pemerintah / Hibah</option>
                                    <option value="bunga_bank">Pendapatan Bunga Bank</option>
                                @else
                                    <option value="biaya_rapat">Biaya Rapat / Konsumsi</option>
                                    <option value="operasional">Biaya Operasional Koperasi</option>
                                    <option value="inventaris">Pembelian Inventaris</option>
                                @endif
                                <option value="lainnya">Lainnya (Ketik sendiri)</option>
                            </select>
                        </div>

                        @if($kategori === 'lainnya')
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nama Kategori Lainnya</label>
                            <input wire:model="kategori_lainnya" type="text" class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Contoh: Sumbangan">
                            @error('kategori_lainnya') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        @endif

                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nominal (Rp)</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input wire:model="nominal" type="number" class="w-full pl-10 px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Contoh: 500000">
                            </div>
                            @error('nominal') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Keterangan / Deskripsi</label>
                            <textarea wire:model="keterangan" rows="3" class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Jelaskan untuk keperluan apa..."></textarea>
                            @error('keterangan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-end pt-4 border-t border-solid border-gray-200">
                            <button wire:click="$set('showModal', false)" type="button" class="px-5 py-2.5 mr-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition shadow-sm">
                                Simpan Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
