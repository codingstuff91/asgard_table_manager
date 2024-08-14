<?php

namespace App\Notifications\Discord;

use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use Illuminate\Support\Facades\Auth;

class CreateTableNotification extends DiscordNotification
{
    public function buildMessage(DiscordNotificationData $discordNotificationData): array
    {
        return [
            'content' => EmbedMessageContent::CREATED->value,
            'embeds' => [
                [
                    'title' => 'Table de : '.$discordNotificationData->game->name,
                    'description' => self::setEmbedDescription($discordNotificationData),
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
                            'value' => $discordNotificationData->table->start_hour,
                            'inline' => true,
                        ],
                    ],
                    'footer' => [
                        'text' => $discordNotificationData->table->description,
                    ],
                ],
            ],
        ];
    }

    private static function setEmbedDescription(DiscordNotificationData $discordNotificationData): string
    {
        return 'Plus d\'informations sur '.config('app.url').'/days/'.$discordNotificationData->day->id;
    }
}
