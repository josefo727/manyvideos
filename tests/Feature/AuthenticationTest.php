<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('login screen can be rendered', function () {
    $this->mock(\Laravel\Fortify\Contracts\LoginViewResponse::class)
        ->shouldReceive('toResponse')
        ->andReturn(response('hello world'));

    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSeeText('hello world');
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->withoutExceptionHandling()->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

    $response->assertRedirect('/dashboard');
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
        '_token' => csrf_token(),
    ]);

    $this->assertGuest();
});
