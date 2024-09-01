<?php

use App\Models\Category;
use App\Repositories\GameRepository;

test('Retrieve games for a choosen category', function () {
    login();

    $category = Category::factory()->create();

    createGameWithCategory($category);
    createGameWithCategory($category);

    $games = app(GameRepository::class)->findByCategory($category->id);
    expect($games->count())->toBe(2);
});
