<?php

namespace App\Notifications\Discord;

use App\Actions\Discord\SendDiscordNotificationAction;
use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use App\Notifications\Discord\Strategies\CreateMessage;
use Illuminate\Support\Facades\Auth;

class CancelEventNotification extends DiscordNotification
{
    public function buildMessage(): self
    {
        $this->message = [
            'content' => EmbedMessageContent::EVENT_CANCELLED->value,
            'embeds' => [
                [
                    'title' => self::setMessageTitle($this->discordNotificationData),
                    'color' => EmbedColor::DELETED->value,
                    'author' => [
                        'name' => 'Annulée par : '.Auth::user()->name,
                    ],
                ],
            ],
        ];

        return $this;
    }

    private static function setMessageTitle(DiscordNotificationData $discordNotificationData): string
    {
        $eventAttributes = $discordNotificationData->extra;

        return 'L\'évènement '.$eventAttributes['name'].' prévue le '.$discordNotificationData->day->date->format('d/m/Y').' à '.$eventAttributes['start_hour'].' est annulé.';
    }

    public function send(): void
    {
        $messageCreationStrategy = app(CreateMessage::class);

        app(SendDiscordNotificationAction::class)(
            $messageCreationStrategy,
            config('discord.event_channel_test'),
            $this->message,
            null,
        );
    }
}
