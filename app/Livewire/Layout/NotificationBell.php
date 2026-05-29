<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class NotificationBell extends Component
{
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            if (isset($notification->data['url'])) {
                return redirect($notification->data['url']);
            }
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $notifications = auth()->user() ? auth()->user()->unreadNotifications()->take(5)->get() : collect();
        $unreadCount = auth()->user() ? auth()->user()->unreadNotifications()->count() : 0;
        
        return view('livewire.layout.notification-bell', compact('notifications', 'unreadCount'));
    }
}
