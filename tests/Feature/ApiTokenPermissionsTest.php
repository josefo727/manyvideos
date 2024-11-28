<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

uses(RefreshDatabase::class);

test('api token permissions can be updated', function () {
    if (!Features::hasApiFeatures()) {
        $this->markTestSkipped('API support is not enabled.');
    }

    $user = User::factory()->withPersonalTeam()->create();

    $token = $user->tokens()->create([
        'name' => 'Test Token',
        'token' => Str::random(40),
        'abilities' => ['create', 'read'],
    ]);

    $this->put('/user/api-tokens/'.$token->id, [
        'name' => $token->name,
        'permissions' => [
            'delete',
            'missing-permission',
        ],
    ]);

    $this->assertTrue($user->fresh()->tokens->first()->can('delete'));
    $this->assertFalse($user->fresh()->tokens->first()->can('read'));
    $this->assertFalse($user->fresh()->tokens->first()->can('missing-permission'));
});
