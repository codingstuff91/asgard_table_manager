<?php

namespace App\Notifications\Discord;

use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use Illuminate\Support\Facades\Auth;

class CancelDayNotification extends DiscordNotification
{
    public function buildMessage(DiscordNotificationData $discordNotificationData): array
    {
        return [
            'content' => EmbedMessageContent::CANCELLED->value,
            'embeds' => [
                [
                    'title' => 'La session du '.$discordNotificationData->day->date->format('d/m/Y').' doit être annulée.',
                    'description' => $discordNotificationData->extra['explanation'],
                    'color' => EmbedColor::DELETED,
                    'author' => [
                        'name' => 'Annulée par : '.Auth::user()->name,
                    ],
                ],
            ],
        ];
    }
}
