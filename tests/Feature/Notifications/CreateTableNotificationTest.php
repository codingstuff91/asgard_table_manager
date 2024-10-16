<?php

use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Tests\RequestFactories\TableRequestFactory;

use function Pest\Laravel\post;

test('The discord thread id is stored into new created table', function () {
    login();
    mockCreateMessageAndThreadStrategy();

    $day = createDay();
    $game = createGameWithCategory();

    $tableAttributes = TableRequestFactory::new()
        ->withOrganizer(Auth::user())
        ->withDay($day)
        ->withGame($game)
        ->withCategory($game->category)
        ->withPlayersNumber(5)
        ->withStartHour('21:00')
        ->create();

    post(route('table.store', $day), $tableAttributes);

    $createdTable = Table::first();

    expect($createdTable->discord_thread_id)->toBe(123456789);
});

test('The discord message id is stored into table informations', function () {
    login();
    mockCreateMessageAndThreadStrategy();

    $day = createDay();
    $game = createGameWithCategory();

    $tableAttributes = TableRequestFactory::new()
        ->withOrganizer(Auth::user())
        ->withDay($day)
        ->withGame($game)
        ->withCategory($game->category)
        ->withPlayersNumber(5)
        ->withStartHour('21:00')
        ->create();

    post(route('table.store', $day), $tableAttributes);

    $createdTable = Table::first();

    expect($createdTable->discord_message_id)->toBe(23456789);
});
