<?php

namespace App\Jobs;

use App\Models\Video;
use App\Notifications\VideoPublishedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\VideoPublished;

class SendVideoNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Video $video) {}

    public function handle(): void
    {
        VideoPublished::dispatch($this->video);
        $this->video->user->notify(new VideoPublishedNotification($this->video));
    }
}
