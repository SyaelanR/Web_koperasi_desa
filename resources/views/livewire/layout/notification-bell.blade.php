<div>
    <div class="relative ms-3 flex items-center">
        <x-dropdown align="right" width="80">
            <x-slot name="trigger">
                <button class="relative p-2 text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    
                    @if($unreadCount > 0)
                        <span class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="block px-4 py-2 text-xs text-gray-400 font-bold uppercase tracking-wider border-b border-gray-100 flex justify-between">
                    <span>Notifikasi</span>
                    @if($unreadCount > 0)
                        <button wire:click="markAllAsRead" class="text-indigo-600 hover:text-indigo-900 lowercase font-medium normal-case">Tandai semua dibaca</button>
                    @endif
                </div>

                <div class="max-h-60 overflow-y-auto">
                    @forelse($notifications as $notif)
                        <button wire:click="markAsRead('{{ $notif->id }}')" class="w-full text-left block px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition duration-150 ease-in-out">
                            <div class="text-sm font-medium text-gray-900">{{ $notif->data['title'] ?? 'Notifikasi Baru' }}</div>
                            <div class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $notif->data['message'] ?? '' }}</div>
                            <div class="text-[10px] text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</div>
                        </button>
                    @empty
                        <div class="block px-4 py-4 text-sm text-gray-500 text-center">
                            Tidak ada notifikasi baru.
                        </div>
                    @endforelse
                </div>
                <div class="block px-4 py-2 text-center text-xs border-t border-gray-100 text-gray-500 hover:bg-gray-50 transition">
                    Hanya menampilkan 5 terbaru
                </div>
            </x-slot>
        </x-dropdown>
    </div>
</div>
