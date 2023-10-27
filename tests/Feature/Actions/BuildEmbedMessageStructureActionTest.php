<?php

use App\Actions\Discord\BuildEmbedMessageStructureAction;
use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageTitle;
use App\Models\Day;
use App\Models\Game;
use App\Models\Table;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

it('should return a long embed message structure when creating or updating a table', function (string $notificationType,  string $contentMessage) {
    $this->seed();
    $this->actingAs(User::first());

    $notificationData = DiscordNotificationData::make(
        Game::first(),
        Table::first(),
        Day::first(),
    );

    $embedMessageStructure = app(BuildEmbedMessageStructureAction::class)::buildEmbedStructure($notificationData, $notificationType);

    expect($embedMessageStructure['content'])->toBe($contentMessage)
        ->and($embedMessageStructure['embeds'][0]['title'])->toBe('Table de : ' . $notificationData->game->name)
        ->and($embedMessageStructure['embeds'][0]['author']['name'])->toBe('Créateur : ' . Auth::user()->name)
        ->and($embedMessageStructure['embeds'][0]['color'])->toBe(EmbedColor::CREATED_OR_UPDATED)
        ->and($embedMessageStructure['embeds'][0]['fields'][0]['value'])->toBe($notificationData->day->date->format('d/m/Y'))
        ->and($embedMessageStructure['embeds'][0]['fields'][1]['value'])->toBe($notificationData->table->start_hour);
})->with([
    'Creating table' => ['create', EmbedMessageTitle::CREATED->value],
    'Saturday' => ['update', EmbedMessageTitle::UPDATED->value],
]);;

it('should return a short embed message structure when deleting a table', function () {
    $this->seed();
    $this->actingAs(User::first());

    $notificationData = DiscordNotificationData::make(
        Game::first(),
        Table::first(),
        Day::first(),
    );

    $embedMessageStructure = app(BuildEmbedMessageStructureAction::class)::buildEmbedStructure($notificationData, 'delete');

//    dd($embedMessageStructure);

    expect($embedMessageStructure['content'])->toBe(EmbedMessageTitle::DELETED->value)
        ->and($embedMessageStructure['embeds'][0]['title'])->toBe('La Table de ' . $notificationData->game->name . ' prévue le ' . $notificationData->day->date->format('d/m/Y') . ' à ' . $notificationData->table->start_hour . ' est annulée.')
        ->and($embedMessageStructure['embeds'][0]['author']['name'])->toBe('Annulée par : ' . Auth::user()->name)
        ->and($embedMessageStructure['embeds'][0]['color'])->toBe(EmbedColor::DELETED);
});
