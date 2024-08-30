<?php

use App\Models\Category;
use App\Models\Day;
use App\Models\Event;
use App\Models\Game;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;

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

function createTable(
    Day $day = null,
    string $start_hour = '14:00',
    int $playersNumber = 2,
): Table {
    return Table::factory()
        ->for(Category::factory())
        ->for(Game::factory())
        ->for($day ?? Day::factory())
        ->create([
            'organizer_id' => Auth::user()->id,
            'start_hour' => $start_hour,
            'players_number' => $playersNumber,
        ]);
}
