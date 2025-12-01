<?php

use App\Models\Association;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Storages\AssociationStorage;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('Store user association into session after user login', function () {
    $user = User::factory()
        ->has(Association::factory())
        ->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    expect(AssociationStorage::exists())->toBeTrue()
        ->and(AssociationStorage::current())->toBeInstanceOf(Association::class)
        ->and(AssociationStorage::current()->id)->toBe($user->associations->first()->id);
});
