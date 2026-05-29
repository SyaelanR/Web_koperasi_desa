<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pinjaman Anggota') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Pengajuan Pinjaman</h2>
                </div>

                @if (session()->has('message'))
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="overflow-x-auto rounded-lg shadow ring-1 ring-black ring-opacity-5">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Tanggal Pengajuan</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nama Anggota</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nominal Pinjaman</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tenor</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($pinjamans as $pinjaman)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($pinjaman->tanggal_pengajuan)->format('d M Y') }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">{{ $pinjaman->user->name }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Rp {{ number_format($pinjaman->nominal_pinjam, 0, ',', '.') }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $pinjaman->tenor }} Bulan</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        @if($pinjaman->status === 'pending')
                                            <span class="inline-flex items-center rounded-full bg-yellow-50 px-2.5 py-0.5 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">Menunggu Approval</span>
                                        @elseif($pinjaman->status === 'approved')
                                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Disetujui / Berjalan</span>
                                        @elseif($pinjaman->status === 'paid_off')
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Lunas</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-center text-sm font-medium sm:pr-6">
                                        @if($pinjaman->status === 'pending')
                                            <button wire:click="openApprovalModal({{ $pinjaman->id }})" class="text-white bg-indigo-600 hover:bg-indigo-700 font-semibold px-3 py-1.5 rounded-md transition duration-150 mx-1">
                                                Review
                                            </button>
                                            <button wire:click="reject({{ $pinjaman->id }})" class="text-white bg-red-600 hover:bg-red-700 font-semibold px-3 py-1.5 rounded-md transition duration-150 mx-1">
                                                Tolak
                                            </button>
                                        @elseif($pinjaman->status === 'approved' || $pinjaman->status === 'paid_off')
                                            <a href="{{ route('admin.pinjaman.show', $pinjaman->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md transition duration-150">
                                                Detail & Cicilan &rarr;
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-500">Belum ada pengajuan pinjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Approval Modal --}}
    @if($showApprovalModal && $selectedPinjaman)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none bg-black bg-opacity-40 backdrop-blur-sm transition-opacity">
        <div class="relative w-full max-w-lg mx-auto my-6">
            <div class="relative flex flex-col w-full bg-white border-0 rounded-xl shadow-2xl outline-none focus:outline-none">
                <div class="flex items-start justify-between p-5 border-b border-solid rounded-t border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">
                        Approval Pinjaman Warga
                    </h3>
                    <button wire:click="$set('showApprovalModal', false)" class="p-1 ml-auto text-gray-400 hover:text-gray-600 transition duration-150 outline-none focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="relative flex-auto p-6">
                    <div class="mb-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-700"><strong>Peminjam:</strong> {{ $selectedPinjaman->user->name }}</p>
                        <p class="text-sm text-gray-700"><strong>Nominal:</strong> Rp {{ number_format($selectedPinjaman->nominal_pinjam, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-700"><strong>Tenor:</strong> {{ $selectedPinjaman->tenor }} Bulan</p>
                    </div>

                    <form wire:submit.prevent="approve">
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nominal Bunga Flat (Keseluruhan Pinjaman)</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input wire:model.live="bunga_nominal" type="number" class="w-full pl-10 px-4 py-2 leading-tight text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Sistem menyarankan 2% dari pokok pinjaman secara default.</p>
                            @error('bunga_nominal') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nominal Biaya Administrasi</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input wire:model.live="biaya_admin" type="number" class="w-full pl-10 px-4 py-2 leading-tight text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Sistem menyarankan 1.5% dari pokok pinjaman secara default.</p>
                            @error('biaya_admin') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Opsi Potongan Bunga & Admin</label>
                            <select wire:model.live="metode_potongan" class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="potong_cair">Dipotong dari Pencairan Pinjaman (Uang cair lebih sedikit)</option>
                                <option value="masuk_cicilan">Masuk ke Tagihan Cicilan Bulanan (Cicilan lebih besar)</option>
                            </select>
                        </div>

                        <div class="mb-2 p-3 bg-indigo-50 rounded text-indigo-800 text-sm border border-indigo-100">
                            <strong>Estimasi Pencairan Bersih:</strong> 
                            @if($metode_potongan === 'potong_cair')
                                Rp {{ number_format($selectedPinjaman->nominal_pinjam - (int)$bunga_nominal - (int)$biaya_admin, 0, ',', '.') }}
                            @else
                                Rp {{ number_format($selectedPinjaman->nominal_pinjam, 0, ',', '.') }}
                            @endif
                            <br>
                            <strong>Estimasi Cicilan per Bulan:</strong> 
                            @if($metode_potongan === 'potong_cair')
                                Rp {{ number_format($selectedPinjaman->nominal_pinjam / $selectedPinjaman->tenor, 0, ',', '.') }}
                            @else
                                Rp {{ number_format(($selectedPinjaman->nominal_pinjam + (int)$bunga_nominal + (int)$biaya_admin) / $selectedPinjaman->tenor, 0, ',', '.') }}
                            @endif
                        </div>

                        <div class="flex items-center justify-end pt-4 mt-4 border-t border-solid border-gray-200">
                            <button wire:click="$set('showApprovalModal', false)" type="button" class="px-5 py-2.5 mr-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 transition duration-150">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 transition duration-150 shadow-sm">
                                Setujui & Cairkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
