<?php

use App\Models\Association;
use App\Providers\RouteServiceProvider;

test('registration screen can be rendered if an association slug is provided', function () {
    $association = Association::factory()->create();

    $response = $this->get("/register?association=$association->slug");

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
});

test('Redirection to association choosing page when url parameter is null', function () {
    $response = $this->get('/register');

    $response->assertRedirect(route('association.choose'));
});
