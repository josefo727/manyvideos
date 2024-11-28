<?php

use App\Models\Comment;
use App\Models\Video;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

it('a video can have multiple comments', function () {
    Event::fake();

    $video = Video::factory()->create();
    $comments = Comment::factory()->count(3)->create(['video_id' => $video->id]);

    expect($video->comments)->toHaveCount(3);
    expect($video->comments->pluck('id')->toArray())->toEqual($comments->pluck('id')->toArray());
});

it('a comment belongs to a video and a user', function () {
    Event::fake();

    $user = User::factory()->create();
    $video = Video::factory()->create();
    $comment = Comment::factory()->create([
        'video_id' => $video->id,
        'user_id' => $user->id,
    ]);

    expect($comment->video->id)->toBe($video->id);
    expect($comment->user->id)->toBe($user->id);
});

it('can count comments for a video', function () {
    Event::fake();

    $video = Video::factory()->create();
    Comment::factory()->count(5)->create(['video_id' => $video->id]);

    expect($video->comments_count)->toBe(5);
});
