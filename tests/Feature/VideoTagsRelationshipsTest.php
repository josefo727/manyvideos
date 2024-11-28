<?php

use App\Models\Tag;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);


it('a video can have multiple tags', function () {
    Event::fake();

    $video = Video::factory()->create();
    $tags = Tag::factory()->count(3)->create();

    $video->tags()->attach($tags);

    expect($video->tags)->toHaveCount(3);
    expect($video->tags->pluck('id')->toArray())->toEqual($tags->pluck('id')->toArray());
});

it('a tag can belong to multiple videos', function () {
    Event::fake();

    $tag = Tag::factory()->create();
    $videos = Video::factory()->count(2)->create();

    $tag->videos()->attach($videos);

    expect($tag->videos)->toHaveCount(2);
    expect($tag->videos->pluck('id')->toArray())->toEqual($videos->pluck('id')->toArray());
});

