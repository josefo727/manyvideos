<?php

use App\Jobs\ProcessVideoMetadata;
use App\Models\Video;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('dispatches job when a video is created', function () {
    Queue::fake();

    Video::factory()->create(['path' => 'videos/sample.mp4']);

    Queue::assertPushedOn('video-metadata', ProcessVideoMetadata::class);
});

it('dispatches job when video path is updated', function () {
    Queue::fake();

    $video = Video::factory()->create();
    $video->update(['path' => 'videos/updated-sample.mp4']);

    Queue::assertPushedOn('video-metadata', ProcessVideoMetadata::class);
});
