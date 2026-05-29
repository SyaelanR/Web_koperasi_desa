<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Koperasi (Export Excel)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 border-l-4 border-indigo-500">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Pusat Laporan & Export Data</h3>
                <p class="text-sm text-gray-600">
                    Gunakan halaman ini untuk mengekspor data-data penting Koperasi ke dalam format Microsoft Excel (.xlsx) untuk kebutuhan pencetakan atau *backup* bulanan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Laporan Rekapitulasi Kas --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 flex flex-col">
                    <div class="p-6 flex-1">
                        <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Rekapitulasi Kas</h3>
                        <p class="text-sm text-gray-500 mb-4">Mengekspor seluruh riwayat transaksi Buku Kas, meliputi pemasukan, pengeluaran, serta saldo berjalan hingga saat ini.</p>
                    </div>
                    <div class="bg-gray-50 p-4 border-t border-gray-100">
                        <button wire:click="exportRekapitulasiKas" wire:loading.attr="disabled" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Excel
                        </button>
                    </div>
                </div>

                {{-- Laporan Piutang Warga --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 flex flex-col">
                    <div class="p-6 flex-1">
                        <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-lg mb-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Daftar Piutang Warga</h3>
                        <p class="text-sm text-gray-500 mb-4">Menampilkan daftar pinjaman warga yang masih berjalan, sisa tunggakan pokok, bunga, admin, serta estimasi denda.</p>
                        
                        <div class="mt-auto pt-4 border-t border-gray-100">
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Filter Periode:</label>
                            <select wire:model.live="filter_piutang" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                <option value="semua">Semua Waktu (Semua Piutang)</option>
                                <option value="bulan_ini">Hanya Pinjaman Bulan Ini</option>
                            </select>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 border-t border-gray-100">
                        <button wire:click="exportPiutangWarga" wire:loading.attr="disabled" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Excel
                        </button>
                    </div>
                </div>

                {{-- Laporan Simpanan --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 flex flex-col">
                    <div class="p-6 flex-1">
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Laporan Simpanan</h3>
                        <p class="text-sm text-gray-500 mb-4">Merekapitulasi total Simpanan Pokok dan Simpanan Wajib per masing-masing anggota koperasi.</p>
                        
                        <div class="mt-auto pt-4 border-t border-gray-100">
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Filter Periode:</label>
                            <select wire:model.live="filter_simpanan" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="semua">Semua Waktu (Akumulasi Total)</option>
                                <option value="bulan_ini">Hanya Setoran Bulan Ini</option>
                            </select>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 border-t border-gray-100">
                        <button wire:click="exportLaporanSimpanan" wire:loading.attr="disabled" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Excel
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
