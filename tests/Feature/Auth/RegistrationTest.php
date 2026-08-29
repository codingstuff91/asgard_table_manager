<?php

use App\Models\Association;
use App\Models\User;
use App\Providers\RouteServiceProvider;

test('registration screen can be rendered if an association slug is provided', function () {
    $association = Association::factory()->create();

    $response = $this->get("/register?association=$association->slug");

    $response->assertStatus(200);
});

test('New user is registered and linked to an association', function () {
    $association = Association::factory()->create();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'association_id' => $association->id,
    ]);

    $userAssociation = User::first()->associations()->first();

    expect($userAssociation)
        ->toBeInstanceOf(Association::class)
        ->and($userAssociation->name)->toBe($association->name);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
});

test('Redirection to association choosing page when url parameter is null', function () {
    $response = $this->get('/register');

    $response->assertRedirect(route('association.choose'));
});

test('Redirection to association choosing page when try to register to a non existing association', function () {
    $response = $this->get('/register?association=test');

    $response->assertRedirect(route('association.choose'));
});

test('A user can not choose an existing surname', function () {
    $association = Association::factory()->create();

    $user = User::factory()->create(['name' => 'johndoe']);

    $response = $this->post('/register', [
        'name' => $user->name,
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'association_id' => $association->id,
    ]);

    expect($response)->toHaveInvalid('name');
});
