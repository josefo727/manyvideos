<?php

namespace App\Jobs;

use App\Models\Video;
use FFMpeg\FFMpeg;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ProcessVideoMetadata implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Video $video
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $filePath = Storage::disk('public')->path($this->video->path);

        if (! file_exists($filePath)) {
            throw new RuntimeException("The file {$filePath} does not exist.");
        }

        $ffmpeg = FFMpeg::create();
        $videoFile = $ffmpeg->open($filePath);

        $duration = $videoFile->getFormat()->get('duration');
        $width = $videoFile->getStreams()->videos()->first()->get('width');
        $height = $videoFile->getStreams()->videos()->first()->get('height');
        $size = Storage::disk('public')->size($this->video->path);

        $this->video->updateQuietly([
            'duration' => $duration,
            'resolution' => "{$width}x{$height}",
            'size' => $size,
        ]);
    }
}
