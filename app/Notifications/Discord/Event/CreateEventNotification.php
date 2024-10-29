<?php

namespace App\Notifications\Discord\Event;

use App\Actions\Discord\SendDiscordNotificationAction;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use App\Notifications\Discord\DiscordNotification;
use App\Notifications\Discord\Strategies\CreateMessage;
use Illuminate\Support\Facades\Auth;

class CreateEventNotification extends DiscordNotification
{
    public function buildMessage(): self
    {
        $eventAttributes = $this->discordNotificationData->extra;

        $this->message = [
            'content' => EmbedMessageContent::EVENT_CREATED->value,
            'embeds' => [
                [
                    'title' => $eventAttributes['name'],
                    'description' => self::setMessageDescription($this->discordNotificationData->day->id),
                    'author' => [
                        'name' => 'Créateur : '.Auth::user()->name,
                    ],
                    'color' => EmbedColor::CREATED->value,
                    'fields' => [
                        [
                            'name' => 'Date',
                            'value' => $this->discordNotificationData->getDay(),
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

    private static function setMessageDescription(int $dayId): string
    {
        return 'Plus d\'informations sur '.config('app.url').'/days/'.$dayId;
    }

    public function send(): void
    {
        $messageCreationStrategy = app(CreateMessage::class);

        app(SendDiscordNotificationAction::class)(
            $messageCreationStrategy,
            config('discord.event_channel'),
            $this->message,
            null,
        );
    }
}
