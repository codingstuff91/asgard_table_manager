<?php

namespace App\Notifications\Discord\Table;

use App\Actions\Discord\SendDiscordNotificationAction;
use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use App\Notifications\Discord\DiscordNotification;
use App\Notifications\Discord\Strategies\CreateMessage;
use Illuminate\Support\Facades\Auth;

class CancelTableNotification extends DiscordNotification
{
    public function buildMessage(): self
    {
        $this->message = [
            'content' => EmbedMessageContent::DELETED->value,
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
        return 'La Table de '.$discordNotificationData->game->name.' prévue le '.$discordNotificationData->day->date->format('d/m/Y').' à '.$discordNotificationData->table->start_hour.' est annulée.';
    }

    public function send(): void
    {
        $messageCreationStrategy = app(CreateMessage::class);

        app(SendDiscordNotificationAction::class)(
            $messageCreationStrategy,
            $this->channelId,
            $this->message,
            $this->discordNotificationData->table
        );
    }
}
