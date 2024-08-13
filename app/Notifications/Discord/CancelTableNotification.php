<?php

namespace App\Notifications\Discord;

use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use Illuminate\Support\Facades\Auth;

class CancelTableNotification extends DiscordNotification
{
    public function buildMessage(DiscordNotificationData $discordNotificationData): array
    {
        return [
            'content' => EmbedMessageContent::DELETED->value,
            'embeds' => [
                [
                    'title' => self::setMessageTitle($discordNotificationData),
                    'color' => EmbedColor::DELETED->value,
                    'author' => [
                        'name' => 'Annulée par : ' . Auth::user()->name,
                    ],
                ],
            ],
        ];
    }

    private static function setMessageTitle(DiscordNotificationData $discordNotificationData): string
    {
        return 'La Table de ' . $discordNotificationData->game->name . ' prévue le ' . $discordNotificationData->day->date->format('d/m/Y') . ' à ' . $discordNotificationData->table->start_hour . ' est annulée.';
    }
}
