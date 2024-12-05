<?php

use App\Http\Controllers\HomeController;
use App\Models\Tag;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Inertia\Testing\AssertableInertia;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

it('retrieves home page data with expected structure using Inertia', function () {
    Event::fake();

    $tag = Tag::factory()->create();
    $video = Video::factory()->create();
    $video->tags()->attach($tag->id);

    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertInertia(fn($page) =>
            $page->component('Home')
                ->has('videos')
                ->has('tags', 1)
                ->has('filters')
        );
});
