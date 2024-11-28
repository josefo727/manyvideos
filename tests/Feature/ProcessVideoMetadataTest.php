<?php

use App\Jobs\ProcessVideoMetadata;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('updates video metadata correctly', function () {
    Video::withoutEvents(function () {
        $realFilePath = base_path('tests/files/sample.mp4');
        $uploadedFile = new UploadedFile(
            $realFilePath,
            'sample.mp4',
            'video/mp4',
            null,
            true
        );

        $path = Storage::disk('public')->putFileAs('videos', $uploadedFile, 'sample.mp4');

        $video = Video::factory()->create([
            'path' => $path,
        ]);

        $job = new ProcessVideoMetadata($video);

        $job->handle();

        $video->refresh();
        expect($video->size)->toBeGreaterThan(0);
        expect($video->resolution)->not->toBeNull();
        expect($video->duration)->not->toBeNull();
        Storage::disk('public')->delete($path);
    });
});
