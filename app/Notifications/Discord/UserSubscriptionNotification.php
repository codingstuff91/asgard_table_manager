<?php

namespace App\Notifications\Discord;

use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use Illuminate\Support\Facades\Auth;

class UserSubscriptionNotification extends DiscordNotification
{
    public function buildMessage(DiscordNotificationData $discordNotificationData): array
    {
        return [
            'content' => EmbedMessageContent::SUBSCRIBED->value,
            'embeds' => [
                [
                    'title' => Auth::user()->name.' s\'est inscrit à la table de '.$discordNotificationData->game->name,
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
                ],
            ],
        ];
    }
}
