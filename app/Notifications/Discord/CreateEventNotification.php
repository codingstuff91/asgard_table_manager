<?php

namespace App\Notifications\Discord;

use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use Illuminate\Support\Facades\Auth;

class CreateEventNotification extends DiscordNotification
{
    public function buildMessage(DiscordNotificationData $discordNotificationData): array
    {
        $eventAttributes = $discordNotificationData->extra;

        return [
            'content' => EmbedMessageContent::EVENT_CREATED->value,
            'embeds' => [
                [
                    'title' => $eventAttributes['name'],
                    'description' => self::setMessageDescription($discordNotificationData->day->id),
                    'author' => [
                        'name' => 'Créateur : '.Auth::user()->name,
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

    private static function setMessageDescription(int $dayId): string
    {
        return 'Plus d\'informations sur '.config('app.url').'/days/'.$dayId;
    }
}
