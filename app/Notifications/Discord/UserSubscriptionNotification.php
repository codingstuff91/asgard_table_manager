<?php

namespace App\Notifications\Discord;

use App\Actions\Discord\SendDiscordNotificationAction;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use App\Notifications\Discord\Strategies\CreateMessageIntoThread;
use Illuminate\Support\Facades\Auth;

class UserSubscriptionNotification extends DiscordNotification
{
    public function buildMessage(): self
    {
        $this->message = [
            'content' => EmbedMessageContent::SUBSCRIBED->value,
            'embeds' => [
                [
                    'title' => Auth::user()->name.' s\'est inscrit à la table de '.$this->discordNotificationData->game->name,
                    'color' => EmbedColor::CREATED->value,
                    'fields' => [
                        [
                            'name' => 'Date',
                            'value' => $this->discordNotificationData->day->date->format('d/m/Y'),
                            'inline' => true,
                        ],
                        [
                            'name' => 'Heure',
                            'value' => $this->discordNotificationData->table->start_hour,
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
