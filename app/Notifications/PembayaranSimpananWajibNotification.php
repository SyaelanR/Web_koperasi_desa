<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PembayaranSimpananWajibNotification extends Notification
{
    use Queueable;

    
    public $simpanan;
/**
     * Create a new notification instance.
     */
    public function __construct($simpanan)
    {
        $this->simpanan = $simpanan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return ['title' => 'Setoran Simpanan Wajib Diterima', 'message' => 'Setoran wajib Anda sebesar Rp ' . number_format($this->simpanan->nominal, 0, ',', '.') . ' telah dicatat.', 'url' => route('member.simpanan.index')];
    }
}
