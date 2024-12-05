<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VideoPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $video;

    public function __construct($video)
    {
        $this->video = $video;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Video is Live!')
            ->line("Your video titled '{$this->video->name}' is now available.")
            ->action('View Video', url('/videos/' . $this->video->id))
            ->line('Thank you for using our platform!');
    }
}
