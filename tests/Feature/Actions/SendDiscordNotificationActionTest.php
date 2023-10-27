<?php

use App\Actions\Discord\BuildEmbedMessageStructureAction;
use App\Actions\Discord\DefineChannelIdAction;
use App\Actions\Discord\SendDiscordNotificationAction;
use App\DataObjects\DiscordNotificationData;
use App\Models\Day;
use App\Models\Game;
use App\Models\Table;
use App\Models\User;
use GuzzleHttp\Client;

it('should send the discord notification', function () {
    $this->seed();
    $this->actingAs(User::first());

    $notificationData = DiscordNotificationData::make(
        Game::first(),
        Table::first(),
        Day::first(),
    );

    $guzzleMock = Mockery::mock(Client::class);
    $guzzleMock->shouldReceive('post');

    app()->instance(Client::class, $guzzleMock);

    $client = Mockery::mock(SendDiscordNotificationAction::class);
    $client->shouldReceive('__invoke');

    $channelId = app(DefineChannelIdAction::class)($notificationData->day->date);

    $embedMessage = app(BuildEmbedMessageStructureAction::class)::buildEmbedStructure($notificationData, 'create');

    $response = app(SendDiscordNotificationAction::class)($channelId, $embedMessage);

    expect($response)->toBe('Discord notification sent');
});
