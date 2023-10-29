<?php

use App\Actions\Discord\BuildEmbedMessageStructureAction;
use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use App\Models\Day;
use App\Models\Game;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;

beforeEach(function () {
    $this->seed();
    login();
});

it('should return a long embed message structure when creating or updating a table', function (string $notificationType, string $contentMessage) {
    $notificationData = DiscordNotificationData::make(
        Game::first(),
        Table::first(),
        Day::first(),
    );

    $embedMessageStructure = app(BuildEmbedMessageStructureAction::class)::buildEmbedStructure($notificationData, $notificationType);

    expect($embedMessageStructure['content'])->toBe($contentMessage)
        ->and($embedMessageStructure['embeds'][0]['title'])->toBe('Table de : '.$notificationData->game->name)
        ->and($embedMessageStructure['embeds'][0]['description'])->toBe('Plus d\'informations sur '. route('days.show', $notificationData->day->id))
        ->and($embedMessageStructure['embeds'][0]['author']['name'])->toBe('Créateur : '.Auth::user()->name)
        ->and($embedMessageStructure['embeds'][0]['color'])->toBe(EmbedColor::CREATED->value)
        ->and($embedMessageStructure['embeds'][0]['fields'][0]['value'])->toBe($notificationData->day->date->format('d/m/Y'))
        ->and($embedMessageStructure['embeds'][0]['fields'][1]['value'])->toBe($notificationData->table->start_hour);
})->with([
    'Creating table' => ['create', EmbedMessageContent::CREATED->value],
    'Updating table' => ['update', EmbedMessageContent::UPDATED->value],
]);

it('should return a short embed message structure when deleting a table', function () {
    $notificationData = DiscordNotificationData::make(
        Game::first(),
        Table::first(),
        Day::first(),
    );

    $embedMessageStructure = app(BuildEmbedMessageStructureAction::class)::buildEmbedStructure($notificationData, 'delete');

    expect($embedMessageStructure['content'])->toBe(EmbedMessageContent::DELETED->value)
        ->and($embedMessageStructure['embeds'][0]['title'])->toBe('La Table de '.$notificationData->game->name.' prévue le '.$notificationData->day->date->format('d/m/Y').' à '.$notificationData->table->start_hour.' est annulée.')
        ->and($embedMessageStructure['embeds'][0]['author']['name'])->toBe('Annulée par : '.Auth::user()->name)
        ->and($embedMessageStructure['embeds'][0]['color'])->toBe(EmbedColor::DELETED->value);
});

it('should return an embed structure message when subscribing an user to a table', function () {
    $notificationData = DiscordNotificationData::make(
        Game::first(),
        Table::first(),
        Day::first(),
    );

    $embedMessageStructure = app(BuildEmbedMessageStructureAction::class)::buildEmbedStructure($notificationData, 'subscribe');

    expect($embedMessageStructure['content'])->toBe(EmbedMessageContent::SUBSCRIBED->value)
        ->and($embedMessageStructure['embeds'][0]['title'])->toBe(Auth::user()->name.' s\'est inscrit à la table de '.$notificationData->game->name)
        ->and($embedMessageStructure['embeds'][0]['description'])->toBe('Plus d\'informations sur '. route('days.show', $notificationData->day->id))
        ->and($embedMessageStructure['embeds'][0]['color'])->toBe(EmbedColor::CREATED->value);
});

it('should return an embed structure message when unsubscribing a user of a table', function () {
    $notificationData = DiscordNotificationData::make(
        Game::first(),
        Table::first(),
        Day::first(),
    );

    $embedMessageStructure = app(BuildEmbedMessageStructureAction::class)::buildEmbedStructure($notificationData, 'unsubscribe');

    expect($embedMessageStructure['content'])->toBe(EmbedMessageContent::UNSUBSCRIBED->value)
        ->and($embedMessageStructure['embeds'][0]['title'])->toBe(Auth::user()->name.' s\'est désinscrit de la table de '.$notificationData->game->name)
        ->and($embedMessageStructure['embeds'][0]['description'])->toBe('Plus d\'informations sur '. route('days.show', $notificationData->day->id))
        ->and($embedMessageStructure['embeds'][0]['color'])->toBe(EmbedColor::DELETED->value);
});
