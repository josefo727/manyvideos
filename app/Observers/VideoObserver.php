<?php

namespace App\Observers;

use App\Jobs\ProcessVideoMetadata;
use App\Models\Video;
use App\Notifications\VideoPublished;

class VideoObserver
{
    /**
     * Handle the Video "created" event.
     */
    public function created(Video $video): void
    {
        ProcessVideoMetadata::dispatch($video)->onQueue('video-metadata');
    }

    /**
     * Handle the Video "updated" event.
     */
    public function updated(Video $video): void
    {
        if ($video->wasChanged('path')) {
            ProcessVideoMetadata::dispatch($video)->onQueue('video-metadata');
        }
    }
}
