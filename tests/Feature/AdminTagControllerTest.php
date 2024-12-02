<?php

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows a list of tags', function () {
    $user = User::factory()->create();
    $tags = Tag::factory()->count(5)->create();

    $response = $this->actingAs($user)->get('/admin/tags');

    $response->assertStatus(200)
        ->assertInertia(fn ($page) =>
        $page->component('Admin/Tags/Index')
            ->has('tags.data', 5)
        );
});

it('allows a user to create a tag', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/admin/tags', [
        'name' => 'UniqueTagName',
    ]);

    $response->assertRedirect('/admin/tags');
    $this->assertDatabaseHas('tags', ['name' => 'UniqueTagName']);
});

it('allows a user to edit a tag', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->create(['name' => 'OldTagName']);

    $response = $this->actingAs($user)->put("/admin/tags/{$tag->id}", [
        'name' => 'NewUniqueTagName',
    ]);

    $response->assertRedirect('/admin/tags');
    $this->assertDatabaseHas('tags', ['name' => 'NewUniqueTagName']);
});

it('allows a user to delete a tag', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->create(['name' => 'TagToDelete']);

    $response = $this->actingAs($user)->delete("/admin/tags/{$tag->id}");

    $response->assertRedirect('/admin/tags');
    $this->assertDatabaseMissing('tags', ['name' => 'TagToDelete']);
});
