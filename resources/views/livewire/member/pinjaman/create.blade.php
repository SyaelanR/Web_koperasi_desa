<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengajuan Pinjaman Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Form Pengajuan Pinjaman</h2>
                    <p class="text-gray-600 mt-2">Silakan lengkapi form di bawah ini untuk mengajukan pinjaman. Pinjaman Anda akan diproses oleh Admin/Pengurus Koperasi.</p>
                </div>

                <form wire:submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="nominal_pinjam" class="block text-sm font-semibold text-gray-700">Nominal Pinjaman yang Diajukan</label>
                        <div class="mt-2 relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-gray-500 font-medium">Rp</span>
                            </div>
                            <input wire:model.live="nominal_pinjam" type="number" id="nominal_pinjam" class="block w-full rounded-lg border-gray-300 py-3 pl-12 pr-4 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-lg sm:leading-6 font-semibold" placeholder="Contoh: 1000000">
                        </div>
                        @error('nominal_pinjam') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tenor" class="block text-sm font-semibold text-gray-700">Tenor (Lama Cicilan)</label>
                        <div class="mt-2 relative rounded-md shadow-sm">
                            <input wire:model.live="tenor" type="number" id="tenor" class="block w-full rounded-lg border-gray-300 py-3 pl-4 pr-16 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-lg sm:leading-6 font-semibold" placeholder="Contoh: 12">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                <span class="text-gray-500 font-medium">Bulan</span>
                            </div>
                        </div>
                        @error('tenor') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="rounded-lg bg-indigo-50 p-5 mt-8 border border-indigo-100">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1 md:flex md:justify-between">
                                <p class="text-sm text-indigo-800">
                                    <strong>Estimasi Cicilan Pokok:</strong><br>
                                    @if($nominal_pinjam > 0 && $tenor > 0)
                                        Rp {{ number_format($nominal_pinjam / $tenor, 0, ',', '.') }} / bulan
                                        <br><span class="text-xs opacity-75">(Belum termasuk bunga pinjaman jika ada)</span>
                                    @else
                                        Rp 0 / bulan
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5 border-t border-gray-200 flex items-center justify-end">
                        <a href="{{ route('dashboard') }}" class="text-sm font-semibold leading-6 text-gray-900 mr-6">Batal</a>
                        <button type="submit" class="rounded-md bg-indigo-600 px-8 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition duration-150">
                            Ajukan Pinjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
