<?php

namespace App\Notifications\Sales;

use App\Models\Sales\SPKMarketings\SPKMarketing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SpkMarketingNotif extends Notification
{
    use Queueable;
    protected SPKMarketing $record;

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
            ->subject('SPK Marketing Baru Dibuat')
            ->line('Ada data SPK Marketing baru yang berhasil dibuat.')
            ->action('Lihat Data', url('/admin/sales/spk-marketing/' . $this->record->id . '/edit'))
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
            'title' => 'Data SPK Marketing Berhasil Dibuat',
            'record_id' => $this->record->id,
            'url' => url('/admin/sales/spk-marketing/' . $this->record->id . '/edit'),
            'message' => 'Ada data SPK Marketing baru yang berhasil dibuat.',
        ];
    }
}
