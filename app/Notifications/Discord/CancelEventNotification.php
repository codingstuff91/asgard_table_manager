<?php

namespace App\Notifications\Discord;

use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use Illuminate\Support\Facades\Auth;

class CancelEventNotification extends DiscordNotification
{
    public function buildMessage(DiscordNotificationData $discordNotificationData): array
    {
        return [
            'content' => EmbedMessageContent::EVENT_CANCELLED->value,
            'embeds' => [
                [
                    'title' => self::setMessageTitle($discordNotificationData),
                    'color' => EmbedColor::DELETED->value,
                    'author' => [
                        'name' => 'Annulée par : '.Auth::user()->name,
                    ],
                ],
            ],
        ];
    }

    private static function setMessageTitle(DiscordNotificationData $discordNotificationData): string
    {
        $eventAttributes = $discordNotificationData->extra;

        return 'L\'évènement '.$eventAttributes['name'].' prévue le '.$discordNotificationData->day->date->format('d/m/Y').' à '.$eventAttributes['start_hour'].' est annulé.';
    }
}
