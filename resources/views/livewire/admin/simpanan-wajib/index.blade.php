<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Tagihan Bulanan Simpanan Wajib') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Event Tagihan Simpanan Wajib</h2>
                    <button wire:click="$set('showModal', true)" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow hover:bg-blue-700 transition duration-150 ease-in-out">
                        + Buat Tagihan Baru
                    </button>
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
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Periode Tagihan</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nominal Bawaan (Default)</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Aksi</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($tagihans as $tagihan)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                        <a href="{{ route('admin.simpanan-wajib.show', $tagihan->id) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                            {{ $tagihan->periode }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Rp {{ number_format($tagihan->nominal_default, 0, ',', '.') }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        @if($tagihan->status === 'draft')
                                            <span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">Belum Disimpan (Draft)</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Selesai Disimpan</span>
                                        @endif
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <a href="{{ route('admin.simpanan-wajib.batch-input', $tagihan->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md transition duration-150">
                                            Isi & Setor Tagihan &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-500">Belum ada tagihan bulan yang dibuat. Silakan klik tombol "Buat Tagihan Baru".</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none bg-black bg-opacity-40 backdrop-blur-sm transition-opacity">
        <div class="relative w-full max-w-md mx-auto my-6">
            <div class="relative flex flex-col w-full bg-white border-0 rounded-xl shadow-2xl outline-none focus:outline-none">
                <div class="flex items-start justify-between p-5 border-b border-solid rounded-t border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">
                        Buat Tagihan Simpanan Wajib
                    </h3>
                    <button wire:click="$set('showModal', false)" class="p-1 ml-auto text-gray-400 hover:text-gray-600 transition duration-150 outline-none focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="relative flex-auto p-6">
                    <form wire:submit.prevent="createTagihan">
                        <div class="mb-5">
                            <label class="block mb-2 text-sm font-semibold text-gray-700" for="periode">
                                Periode Tagihan (Contoh: "Mei 2026")
                            </label>
                            <input wire:model="periode" type="text" id="periode" class="w-full px-4 py-2.5 leading-tight text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150" placeholder="Masukkan bulan dan tahun">
                            @error('periode') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-5">
                            <label class="block mb-2 text-sm font-semibold text-gray-700" for="nominal_default">
                                Nominal Bawaan (Default per anggota)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input wire:model="nominal_default" type="number" id="nominal_default" class="w-full pl-10 px-4 py-2.5 leading-tight text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            </div>
                            @error('nominal_default') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-2">Nominal ini akan terisi otomatis ke form input cepat, tapi masih bisa diedit nantinya.</p>
                        </div>

                        <div class="flex items-center justify-end pt-4 mt-6 border-t border-solid border-gray-200">
                            <button wire:click="$set('showModal', false)" type="button" class="px-5 py-2.5 mr-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 transition duration-150">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition duration-150 shadow-sm">
                                Buat Event Tagihan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
