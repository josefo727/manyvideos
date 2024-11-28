<?php

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a video with a user', function () {
    Video::withoutEvents(function () {
        $user = User::factory()->create();

        $video = Video::query()->create([
            'user_id' => $user->id,
            'name' => 'Sample Video',
            'path' => 'videos/sample.mp4',
            'size' => null,
            'duration' => null,
            'resolution' => null,
            'thumbnail' => null,
        ]);

        expect($video->user)->toBeInstanceOf(User::class);
        expect($video->path)->toBe('videos/sample.mp4');
    });
});
