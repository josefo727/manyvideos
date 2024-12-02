<?php

use App\Jobs\ProcessVideoMetadata;
use App\Models\Tag;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('shows a list of the user\'s videos', function () {
    Event::fake();

    $user = User::factory()->create();
    $videos = Video::factory()->count(3)->for($user)->create();

    $response = $this->actingAs($user)->get('/admin/videos');

    $response->assertStatus(200)
        ->assertInertia(fn ($page) =>
        $page->component('Admin/Videos/Index')
            ->has('videos.data', 3)
            ->where('videos.data.0.name', $videos->first()->name)
        );
});

it('allows a user to upload a video', function () {
    Event::fake();

    $user = User::factory()->create();
    $tags = Tag::factory()->count(4)->create();
    $tagsId = $tags->pluck('id')->random(2)->toArray();

    $realFilePath = base_path('tests/files/sample.mp4');

    $videoFile = new UploadedFile(
        $realFilePath,
        'sample.mp4',
        'video/mp4',
        null,
        true
    );

    $response = $this->actingAs($user)->post('/admin/videos', [
        'name' => 'Test Video',
        'video' => $videoFile,
        'tags' => $tagsId,
    ], [
        'Content-Type' => 'multipart/form-data',
    ]);

    $response->assertRedirect('/admin/videos');

    Storage::disk('public')->assertExists('videos/' . $videoFile->hashName());

    $this->assertDatabaseHas('videos', [
        'name' => 'Test Video',
        'user_id' => $user->id,
    ]);

    $video = Video::query()->where('name', 'Test Video')->first();
    $this->assertEquals($tagsId, $video->tags()->pluck('tags.id')->toArray());

    Storage::disk('public')->delete('videos/sample.mp4');
});

it('allows a user to view the edit page for their video', function () {
    $user = User::factory()->create();
    $video = Video::factory()->for($user)->create();

    $response = $this->actingAs($user)->get("/admin/videos/{$video->id}/edit");

    $response->assertStatus(200)
        ->assertInertia(fn ($page) =>
        $page->component('Admin/Videos/Edit')
            ->where('video.id', $video->id)
            ->where('video.name', $video->name)
        );
});

it('allows a user to edit their video', function () {
    Event::fake();

    $user = User::factory()->create();
    $tags = Tag::factory()->count(4)->create();
    $tagsId = $tags->pluck('id')->random(2)->toArray();
    $video = Video::factory()->for($user)->create();

    $realFilePath = base_path('tests/files/another-sample.mp4');

    $newVideoFile = new UploadedFile(
        $realFilePath,
        'another-sample.mp4',
        'video/mp4',
        null,
        true
    );

    $response = $this->actingAs($user)->put("/admin/videos/{$video->id}", [
        'name' => 'Updated Title',
        'video' => $newVideoFile,
        'tags' => $tagsId,
    ], [
        'Content-Type' => 'multipart/form-data',
    ]);

    $response->assertRedirect('/admin/videos');

    Storage::disk('public')->assertExists('videos/' . $newVideoFile->hashName());
    Storage::disk('public')->assertMissing('videos/' . $video->path);

    $this->assertDatabaseHas('videos', [
        'id' => $video->id,
        'name' => 'Updated Title',
        'path' => 'videos/' . $newVideoFile->hashName(),
    ]);

    $this->assertEquals($tagsId, $video->fresh()->tags()->pluck('tags.id')->toArray());
});

it('allows a user to delete their video', function () {
    Event::fake();

    $user = User::factory()->create();
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
        'user_id' => $user->id,
        'path' => $path,
    ]);

    $job = new ProcessVideoMetadata($video);
    $job->handle();
    $video->refresh();
    $videoFilePath = $video->path;
    $thumbnailFilePath = $video->thumbnail;

    $response = $this->actingAs($user)->delete("/admin/videos/{$video->id}");

    $response->assertRedirect('/admin/videos');
    $this->assertDatabaseMissing('videos', ['id' => $video->id]);

    Storage::disk('public')->assertMissing($videoFilePath);
    Storage::disk('public')->assertMissing($thumbnailFilePath);
});

it('does not allow a user to edit a video that does not belong to them', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $tags = Tag::factory()->count(4)->create();

    $tagsId = $tags->pluck('id')->random(2)->toArray();

    $video = Video::factory()->for($otherUser)->create();

    $response = $this->actingAs($user)->put("/admin/videos/{$video->id}", [
        'name' => 'Unauthorized Update',
        'tags' => $tagsId,
    ]);

    $response->assertStatus(403);
});

it('does not allow a user to delete a video that does not belong to them', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $video = Video::factory()->for($otherUser)->create([
        'path' => 'videos/sample.mp4',
        'thumbnail' => 'videos/sample.jpg',
    ]);

    Storage::disk('public')->put($video->path, 'Content');
    Storage::disk('public')->put($video->thumbnail, 'Content');

    $response = $this->actingAs($user)->delete("/admin/videos/{$video->id}");

    $response->assertStatus(403);
    $this->assertDatabaseHas('videos', ['id' => $video->id]);

    Storage::disk('public')->assertExists($video->path);
    Storage::disk('public')->assertExists($video->thumbnail);
});
