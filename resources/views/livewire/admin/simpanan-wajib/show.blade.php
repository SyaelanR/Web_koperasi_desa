<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Histori Pembayaran: ') }} {{ $tagihan->periode }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Daftar Anggota yang Sudah Membayar</h2>
                        <p class="text-sm text-gray-600 mt-1">Anda dapat mengedit nominal atau menghapus riwayat pembayaran jika terjadi kesalahan.</p>
                    </div>
                    <a href="{{ route('admin.simpanan-wajib.index') }}" class="text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-md transition duration-150">
                        &larr; Kembali
                    </a>
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
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Tanggal Bayar</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nama Anggota</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nominal (Rp)</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($simpanans as $simpanan)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-6">{{ \Carbon\Carbon::parse($simpanan->tanggal)->format('d M Y') }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">{{ $simpanan->user->name ?? 'Anonim' }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Rp {{ number_format($simpanan->nominal, 0, ',', '.') }}</td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-center text-sm font-medium sm:pr-6">
                                        <button wire:click="openEditModal({{ $simpanan->id }}, {{ $simpanan->nominal }})" class="text-indigo-600 hover:text-indigo-900 mx-2" title="Edit">
                                            Edit
                                        </button>
                                        <button wire:click="openDeleteModal({{ $simpanan->id }})" class="text-red-600 hover:text-red-900 mx-2" title="Hapus">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-500">Belum ada riwayat pembayaran untuk tagihan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    @if($editModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none bg-black bg-opacity-40 backdrop-blur-sm transition-opacity">
        <div class="relative w-full max-w-md mx-auto my-6">
            <div class="relative flex flex-col w-full bg-white border-0 rounded-xl shadow-2xl outline-none focus:outline-none">
                <div class="flex items-start justify-between p-5 border-b border-solid rounded-t border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">Edit Nominal Pembayaran</h3>
                </div>
                <div class="relative flex-auto p-6">
                    <form wire:submit.prevent="updateNominal">
                        <div class="mb-5">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nominal Baru (Rp)</label>
                            <input wire:model="editNominal" type="number" class="w-full px-4 py-2.5 leading-tight text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('editNominal') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-end pt-4 border-t border-gray-200">
                            <button wire:click="$set('editModal', false)" type="button" class="px-4 py-2 mr-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                            <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Delete Modal --}}
    @if($deleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none bg-black bg-opacity-40 backdrop-blur-sm transition-opacity">
        <div class="relative w-full max-w-sm mx-auto my-6">
            <div class="relative flex flex-col w-full bg-white border-0 rounded-xl shadow-2xl outline-none focus:outline-none">
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500">Yakin ingin menghapus riwayat pembayaran ini?</h3>
                    <button wire:click="deleteSimpanan" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                        Ya, Hapus
                    </button>
                    <button wire:click="$set('deleteModal', false)" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
