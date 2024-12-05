<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

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
            ->line(new HtmlString($this->getHtmlImage()))
            ->action('View Video', url('/videos/' . $this->video->id))
            ->line('Thank you for using our platform!');
    }

    /**
     * @return string
     */
    public function getHtmlImage(): string
    {
        $url = url('/videos/' . $this->video->id);
        $src = url($this->video->thumbnail_path);
        return '<a href="'. $url. '"><img src="'. $src. '" alt="'. $this->video->name. '" /></a>';
    }
}
