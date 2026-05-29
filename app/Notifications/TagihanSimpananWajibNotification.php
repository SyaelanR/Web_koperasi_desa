<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TagihanSimpananWajibNotification extends Notification
{
    use Queueable;

    
    public $tagihan;
/**
     * Create a new notification instance.
     */
    public function __construct($tagihan)
    {
        $this->tagihan = $tagihan;
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
        return ['title' => 'Tagihan Simpanan Wajib', 'message' => 'Tagihan bulan ' . $this->tagihan->periode . ' telah terbit sebesar Rp ' . number_format($this->tagihan->nominal_default, 0, ',', '.'), 'url' => route('member.simpanan.index')];
    }
}
