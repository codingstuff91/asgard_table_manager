<?php

namespace App\Notifications\Discord\Day;

use App\Actions\Discord\SendDiscordNotificationAction;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use App\Notifications\Discord\DiscordNotification;
use App\Notifications\Discord\Strategies\CreateMessage;
use Illuminate\Support\Facades\Auth;

class CancelDayNotification extends DiscordNotification
{
    public function buildMessage(): self
    {
        $this->message = [
            'content' => EmbedMessageContent::CANCELLED->value,
            'embeds' => [
                [
                    'title' => 'La session du '.$this->discordNotificationData->day->date->format('d/m/Y').' doit être annulée.',
                    'description' => $this->discordNotificationData->extra['explanation'],
                    'color' => EmbedColor::DELETED,
                    'author' => [
                        'name' => 'Annulée par : '.Auth::user()->name,
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
            $this->channelId,
            $this->message,
            null
        );
    }
}
