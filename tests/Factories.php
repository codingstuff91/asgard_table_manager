<?php

use App\Models\Category;
use App\Models\Day;
use App\Models\Event;
use App\Models\Game;

function createDay(): Day
{
    return Day::factory()->create();
}

function createPastDay(): Day
{
    return Day::factory()->create([
        'date' => now()->sub('day', 1),
    ]);
}

function createEvent(): Event
{
    return Event::factory()
        ->create([
            'day_id' => createDay()->id,
        ]);
}

function createGameWithCategory()
{
    $category = Category::factory()->create();

    return Game::factory()
        ->for($category)
        ->create();
}
