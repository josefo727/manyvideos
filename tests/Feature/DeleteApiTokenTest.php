<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

uses(RefreshDatabase::class);

test('api tokens can be deleted', function () {
    if (!Features::hasApiFeatures()) {
        $this->markTestSkipped('API support is not enabled.');
    }

    $user = User::factory()->withPersonalTeam()->create();

    $this->actingAs($user);

    $token = $user->tokens()->create([
        'name' => 'Test Token',
        'token' => Str::random(40),
        'abilities' => ['create', 'read'],
    ]);

    $this->delete('/user/api-tokens/'.$token->id);

    $this->assertCount(0, $user->fresh()->tokens);
});
