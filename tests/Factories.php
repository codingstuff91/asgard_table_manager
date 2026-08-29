<?php

use App\Models\Association;
use App\Models\Category;
use App\Models\Day;
use App\Models\Event;
use App\Models\Game;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

function createAssociation(): Association
{
    return Association::factory()->create();
}

function createDay(): Day
{
    return Day::factory()->create();
}

function createDayForAssociation(Association $association, ?string $date = null): Day
{
    return Day::factory()->create([
        'date' => $date ?? now()->format('Y-m-d'),
        'association_id' => $association->id,
    ]);
}

function createUsersForAssociation(Association $association, int $usersCount = 1): Collection
{
    $users = User::factory($usersCount)->create();

    $association->users()->attach($users);

    return $users;
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

function createGameWithCategory(?Category $category = null): Game
{
    return Game::factory()
        ->for($category ?? Category::factory()->create())
        ->create();
}

function createTable(
    ?Day $day = null,
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
