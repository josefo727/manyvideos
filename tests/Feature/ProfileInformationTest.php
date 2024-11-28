<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->put('/user/profile-information', [
        'name' => 'Test Name',
        'email' => 'test@example.com',
    ]);

    expect($user->fresh()->name)->toBe('Test Name');
    expect($user->fresh()->email)->toBe('test@example.com');
});
