<?php

namespace App\Notifications\Discord\User;

use App\Actions\Discord\SendDiscordNotificationAction;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use App\Notifications\Discord\DiscordNotification;
use App\Notifications\Discord\Strategies\CreateMessageIntoThread;
use Illuminate\Support\Facades\Auth;

class UserUnsubscriptionNotification extends DiscordNotification
{
    public function buildMessage(): self
    {
        $this->message = [
            'content' => EmbedMessageContent::UNSUBSCRIBED->value,
            'embeds' => [
                [
                    'title' => Auth::user()->name.' s\'est désinscrit de la table de '.$this->discordNotificationData->game->name,
                    'color' => EmbedColor::DELETED->value,
                    'fields' => [
                        [
                            'name' => 'Date',
                            'value' => $this->discordNotificationData->getDay(),
                            'inline' => true,
                        ],
                        [
                            'name' => 'Heure',
                            'value' => $this->discordNotificationData->getStartHour(),
                            'inline' => true,
                        ],
                    ],
                ],
            ],
        ];

        return $this;
    }

    public function send(): void
    {
        $messageCreationStrategy = app(CreateMessageIntoThread::class);

        app(SendDiscordNotificationAction::class)(
            $messageCreationStrategy,
            $this->channelId,
            $this->message,
            $this->discordNotificationData->table
        );
    }
}
