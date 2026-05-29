<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Persetujuan Anggota Baru (Approval)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Daftar Menunggu Persetujuan</h2>

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Lengkap</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">No. HP</th>
                    <th scope="col" class="px-6 py-3">Alamat</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">{{ $user->phone }}</td>
                        <td class="px-6 py-4">{{ $user->address }}</td>
                        <td class="px-6 py-4">
                            <button wire:click="openModal({{ $user->id }})" class="font-medium text-blue-600 hover:underline">Setujui</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center">Tidak ada anggota baru yang menunggu persetujuan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none bg-black bg-opacity-50">
        <div class="relative w-full max-w-md mx-auto my-6">
            <div class="relative flex flex-col w-full bg-white border-0 rounded-lg shadow-lg outline-none focus:outline-none">
                <div class="flex items-start justify-between p-5 border-b border-solid rounded-t border-blueGray-200">
                    <h3 class="text-xl font-semibold">
                        Setujui Anggota Baru
                    </h3>
                    <button wire:click="$set('showModal', false)" class="float-right p-1 ml-auto text-3xl font-semibold leading-none text-black bg-transparent border-0 outline-none focus:outline-none">
                        <span class="block w-6 h-6 text-2xl text-black bg-transparent outline-none focus:outline-none">×</span>
                    </button>
                </div>
                <div class="relative flex-auto p-6">
                    <p class="mb-4 text-sm text-gray-600">Anda akan menyetujui pendaftaran <strong>{{ $selectedUser->name }}</strong>.</p>
                    
                    <form wire:submit.prevent="approve">
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-bold text-gray-700" for="simpananPokok">
                                Simpanan Pokok (Rp)
                            </label>
                            <input wire:model="simpananPokok" type="number" id="simpananPokok" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                            @error('simpananPokok') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-end pt-4 border-t border-solid rounded-b border-blueGray-200">
                            <button wire:click="$set('showModal', false)" type="button" class="px-6 py-2 mb-1 mr-1 text-sm font-bold text-red-500 uppercase transition-all duration-150 ease-linear outline-none background-transparent focus:outline-none">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-3 mb-1 mr-1 text-sm font-bold text-white uppercase transition-all duration-150 ease-linear bg-emerald-500 rounded shadow outline-none active:bg-emerald-600 hover:shadow-lg focus:outline-none">
                                Setujui & Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
            </div>
        </div>
    </div>
</div>
