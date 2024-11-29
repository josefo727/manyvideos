<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows a user to access the global video notifications channel', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/broadcasting/auth', [
        'channel_name' => 'global-video-notifications',
        'socket_id' => '12345.67890',
    ], ['X-CSRF-TOKEN' => csrf_token()]);

    $response->assertStatus(200);
});

it('allows a guest to access the global channel for video notifications', function () {
    $response = $this->post('/broadcasting/auth', [
        'channel_name' => 'global-video-notifications',
        'socket_id' => '12345.67890',
    ], ['X-CSRF-TOKEN' => csrf_token()]);

    $response->assertStatus(200);
});
