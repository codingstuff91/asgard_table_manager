<?php

use App\Notifications\Discord\Table\UpdateTableNotification;
use Illuminate\Support\Facades\Auth;
use Tests\RequestFactories\TableRequestFactory;

use function Pest\Laravel\patch;

test('UpdateTableNotification updates the main discord message and send a update notification message', function () {
    login();
    mockHttpClient();

    $client = Mockery::mock(UpdateTableNotification::class);
    $client->shouldReceive('defineChannelId');
    $client->shouldReceive('buildMessage');
    $client->shouldReceive('send');
    $client->shouldReceive('createUpdateMessage');

    $day = createDay();
    $table = createTable();

    $game = createGameWithCategory();

    $tableAttributes = TableRequestFactory::new()
        ->withOrganizer(Auth::user())
        ->withDay($day)
        ->withGame($game)
        ->withCategory($game->category)
        ->withPlayersNumber(5)
        ->withStartHour('21:00')
        ->create();

    $response = patch(route('table.update', $table), $tableAttributes);

    expect($response->status())->toBe(302);
});
