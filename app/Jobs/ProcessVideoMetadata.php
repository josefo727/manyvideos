<?php

namespace App\Jobs;

use App\Models\Video;
use FFMpeg\Coordinate\TimeCode;
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
     * @return void
     */
    public function handle(): void
    {
        $this->pipeline();
    }

    /**
     * @return void
     */
    protected function pipeline(): void
    {
        $this->ensureVideoFileExists()
            ->processMetadata()
            ->generateThumbnail()
            ->sendNotification();
    }

    /**
     * @return ProcessVideoMetadata
     */
    private function ensureVideoFileExists(): ProcessVideoMetadata
    {
        $filePath = Storage::disk('public')->path($this->video->path);

        if (! file_exists($filePath)) {
            throw new RuntimeException("The file {$filePath} does not exist.");
        }

        return $this;
    }

    /**
     * @return ProcessVideoMetadata
     */
    private function processMetadata(): ProcessVideoMetadata
    {
        $ffmpeg = FFMpeg::create();
        $videoFile = $ffmpeg->open(Storage::disk('public')->path($this->video->path));

        $duration = $videoFile->getFormat()->get('duration');
        $width = $videoFile->getStreams()->videos()->first()->get('width');
        $height = $videoFile->getStreams()->videos()->first()->get('height');
        $size = Storage::disk('public')->size($this->video->path);

        $this->video->updateQuietly([
            'duration' => $duration,
            'resolution' => "{$width}x{$height}",
            'size' => $size,
        ]);

        return $this;
    }

    /**
     * @return ProcessVideoMetadata
     */
    private function generateThumbnail(): ProcessVideoMetadata
    {
        $thumbnailDirectory = Storage::disk('public')->path('thumbnails');
        if (!file_exists($thumbnailDirectory)) {
            mkdir($thumbnailDirectory, 0775, true);
        }

        $thumbnailPath = "thumbnails/{$this->video->id}.jpg";
        $videoFile = FFMpeg::create()->open(Storage::disk('public')->path($this->video->path));
        $thumbnailFullPath = Storage::disk('public')->path($thumbnailPath);

        $videoFile->frame(TimeCode::fromSeconds(2))->save($thumbnailFullPath);

        if (!file_exists($thumbnailFullPath)) {
            throw new RuntimeException("Thumbnail generation failed for video {$thumbnailFullPath}");
        }

        $this->video->updateQuietly(['thumbnail' => $thumbnailPath]);

        return $this;
    }

    /**
     * @return void
     */
    public function sendNotification(): void
    {
        SendVideoNotification::dispatch($this->video);
    }
}
