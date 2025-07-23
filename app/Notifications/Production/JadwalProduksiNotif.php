<?php

namespace App\Notifications\Production;

use App\Models\Production\Jadwal\JadwalProduksi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JadwalProduksiNotif extends Notification
{
    use Queueable;
    protected JadwalProduksi $record;

    /**
     * Create a new notification instance.
     */
    public function __construct($record)
    {
        $this->record = $record;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Jadwal Produksi Baru Dibuat')
            ->line('Ada data jadwal produksi baru yang berhasil dibuat.')
            ->action('Lihat Data', url('/admin/produksi/jadwal-produksi/' . $this->record->id . '/edit'))
            ->line('Terima kasih.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
            'title' => 'Jadwal Produksi Baru Dibuat',
            'record_id' => $this->record->id,
            'url' => url('/admin/produksi/jadwal-produksi/' . $this->record->id . '/edit'),
            'message' => 'Ada data jadwal prouksi baru yang berhasil dibuat.',
        ];
    }
}
