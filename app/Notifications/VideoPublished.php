<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class VideoPublished extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $video) {}

    public function via($notifiable)
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'video' => [
                'id' => $this->video->id,
                'title' => $this->video->title,
            ],
        ]);
    }
}
