<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Cepat Simpanan Wajib: ') }} {{ $tagihan->periode }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Form Input Cepat</h2>
                        <p class="text-sm text-gray-600 mt-1">Silakan sesuaikan nominal yang dibayar oleh anggota, atau hapus baris jika anggota tersebut tidak membayar.</p>
                    </div>
                    <a href="{{ route('admin.simpanan-wajib.index') }}" class="text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-md transition duration-150">
                        &larr; Kembali
                    </a>
                </div>

                <form wire:submit.prevent="saveAll">
                    <div class="overflow-x-auto rounded-lg shadow ring-1 ring-black ring-opacity-5 mb-6">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 w-1/2">Nama Anggota</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-1/3">Nominal Bayar (Rp)</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($rows as $index => $row)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            @if($row['user_name'] !== '')
                                                <div class="font-semibold text-gray-800">{{ $row['user_name'] }}</div>
                                                <input type="hidden" wire:model="rows.{{ $index }}.user_id">
                                            @else
                                                <select wire:model="rows.{{ $index }}.user_id" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <option value="">-- Pilih Anggota --</option>
                                                    @foreach($this->availableUsersForDropdown as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('rows.'.$index.'.user_id') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-500">
                                            <div class="relative rounded-md shadow-sm">
                                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" wire:model="rows.{{ $index }}.nominal" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="0">
                                            </div>
                                            @error('rows.'.$index.'.nominal') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                        </td>
                                        <td class="relative whitespace-nowrap py-3 pl-3 pr-4 text-center text-sm font-medium sm:pr-6">
                                            <button type="button" wire:click="removeRow({{ $index }})" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-full transition duration-150" title="Hapus Baris">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between items-center mt-6">
                        <button type="button" wire:click="addRow" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Baris
                        </button>

                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-emerald-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Semua Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
