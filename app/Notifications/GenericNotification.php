<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GenericNotification extends Notification
{
    use Queueable;

    protected Model $record;
    protected string $subject;
    protected string $message;
    protected string $url;
    protected string $title;

    /**
     * Create a new notification instance.
     */
    public function __construct(Model $record, string $subject, string $message, string $url, string $title)
    {
        $this->record = $record;
        $this->subject = $subject;
        $this->message = $message;
        $this->url = $url;
        $this->title = $title;
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
            ->subject($this->subject)
            ->line($this->message)
            ->action('Lihat Data', $this->url)
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
            'title' => $this->title,
            'record_id' => $this->record->id,
            'url' => $this->url,
            'message' => $this->message,
        ];
    }
}
