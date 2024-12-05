<?php

use App\Models\Comment;
use App\Models\Video;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

it('retrieves comments for a video', function () {
    Event::fake();
    $video = Video::factory()->create();
    $comments = Comment::factory()->count(3)->create(['video_id' => $video->id]);

    $response = $this->getJson("/api/videos/{$video->id}/comments");

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonFragment(['id' => $comments->first()->id]);
});

it('stores a new comment for a video', function () {
    Event::fake();
    $user = User::factory()->create();
    $video = Video::factory()->create();
    $commentData = ['content' => 'This is a test comment.'];

    $response = $this->actingAs($user, 'sanctum')->postJson("/api/videos/{$video->id}/comments", $commentData);

    $response->assertStatus(201)
        ->assertJsonFragment(['content' => $commentData['content']]);

    $this->assertDatabaseHas('comments', [
        'video_id' => $video->id,
        'user_id' => $user->id,
        'content' => $commentData['content'],
    ]);
});
