<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Features;

uses(RefreshDatabase::class);

test('user accounts can be deleted', function () {
    if (!Features::hasAccountDeletionFeatures()) {
        $this->markTestSkipped('Account deletion is not enabled.');
    }

    $user = User::factory()->create();

    $this->actingAs($user);

    $this->delete('/user', [
        'password' => 'password',
    ]);

    $this->assertNull($user->fresh());
});

test('correct password must be provided before account can be deleted', function () {
    if (!Features::hasAccountDeletionFeatures()) {
        $this->markTestSkipped('Account deletion is not enabled.');
    }

    $user = User::factory()->create();

    $this->actingAs($user);

    $this->delete('/user', [
        'password' => 'wrong-password',
    ]);

    $this->assertNotNull($user->fresh());
});
