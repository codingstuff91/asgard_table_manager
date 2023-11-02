<?php

use App\Models\Category;
use App\Models\Game;
use App\Models\User;

test('a game must not be created twice', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->post('/games', [
        'name' => 'test game',
        'category_id' => Category::first(),
    ]);

    expect(['name' => Game::first()->name])->toBeInDatabase('games');

    $response = $this->post('/games', [
        'name' => 'test game',
        'category_id' => Category::first(),
    ]);

    $response->assertSessionHasErrors('name');
});
