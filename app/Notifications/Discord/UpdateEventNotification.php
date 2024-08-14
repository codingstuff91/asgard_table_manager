<?php

namespace App\Notifications\Discord;

use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use Illuminate\Support\Facades\Auth;

class UpdateEventNotification extends DiscordNotification
{
    public function buildMessage(DiscordNotificationData $discordNotificationData): array
    {
        $eventAttributes = $discordNotificationData->extra;

        return [
            'content' => EmbedMessageContent::EVENT_UPDATED->value,
            'embeds' => [
                [
                    'title' => $eventAttributes['name'],
                    'author' => [
                        'name' => 'Mis à jour par : '.Auth::user()->name,
                    ],
                    'color' => EmbedColor::CREATED->value,
                    'fields' => [
                        [
                            'name' => 'Date',
                            'value' => $discordNotificationData->day->date->format('d/m/Y'),
                            'inline' => true,
                        ],
                        [
                            'name' => 'Heure',
                            'value' => $eventAttributes['start_hour'],
                            'inline' => true,
                        ],
                    ],
                    'footer' => [
                        'text' => $eventAttributes['description'],
                    ],
                ],
            ],
        ];
    }
}
