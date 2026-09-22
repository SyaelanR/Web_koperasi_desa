<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Cepat Simpanan Sukarela') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session()->has('message'))
                <div class="p-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
                    {{ session('message') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6 flex justify-between items-center border-b pb-4">
                    <h3 class="text-lg font-bold text-gray-900">Form Input Cepat Simpanan Sukarela</h3>
                    <div class="flex items-center space-x-2">
                        <x-input-label for="tanggal" value="Tanggal Setor" class="mb-0" />
                        <x-text-input wire:model="tanggal" id="tanggal" type="date" class="block w-40" />
                        <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                    </div>
                </div>

                <div class="mb-4 text-sm text-gray-600 bg-blue-50 p-4 rounded-md border border-blue-100">
                    <strong>Petunjuk:</strong> 
                    Isi nominal pada anggota yang melakukan setoran. Anggota dengan nominal `0` (nol) <strong>tidak akan</strong> disimpan ke database. Hanya anggota aktif yang ditampilkan di sini.
                </div>

                <form wire:submit="saveAll">
                    <div class="overflow-x-auto border rounded-lg mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Nominal Setoran (Rp)</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($rows as $index => $row)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($row['user_name'])
                                                <div class="text-sm font-medium text-gray-900">{{ $row['user_name'] }}</div>
                                            @else
                                                <select wire:model="rows.{{ $index }}.user_id" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                                    <option value="">Pilih Anggota</option>
                                                    @foreach($this->availableUsers as $u)
                                                        @php
                                                            $isSelectedElsewhere = collect($rows)
                                                                ->filter(function ($item, $k) use ($index) {
                                                                    return $k != $index && !empty($item['user_id']);
                                                                })
                                                                ->pluck('user_id')
                                                                ->contains($u->id);
                                                        @endphp
                                                        @if(!$isSelectedElsewhere)
                                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->get('rows.'.$index.'.user_id')" class="mt-1 text-xs" />
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" wire:model="rows.{{ $index }}.nominal" class="pl-10 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm sm:text-sm" min="0">
                                            </div>
                                            <x-input-error :messages="$errors->get('rows.'.$index.'.nominal')" class="mt-1 text-xs" />
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <button type="button" wire:click="removeRow({{ $index }})" class="text-red-600 hover:text-red-900" title="Hapus Baris">
                                                <svg class="h-5 w-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                @if(empty($rows))
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada anggota. Klik "Tambah Baris" untuk menginput.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <button type="button" wire:click="addRow" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Baris
                        </button>

                        <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Simpan Seluruh Setoran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
