<?php

namespace App\Notifications\Discord\Event;

use App\Actions\Discord\SendDiscordNotificationAction;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use App\Notifications\Discord\DiscordNotification;
use App\Notifications\Discord\Strategies\CreateMessage;
use Illuminate\Support\Facades\Auth;

class UpdateEventNotification extends DiscordNotification
{
    public function buildMessage(): self
    {
        $eventAttributes = $this->discordNotificationData->extra;

        $this->message = [
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
                            'value' => $this->discordNotificationData->day->date->format('d/m/Y'),
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

        return $this;
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
